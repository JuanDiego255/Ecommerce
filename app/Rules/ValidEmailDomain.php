<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ValidEmailDomain implements Rule
{
    private string $reason = 'formato';

    private const TIMEOUT     = 5;
    private const HELO_DOMAIN = 'validador.local';

    // ── Public API ────────────────────────────────────────────────────────────

    public function passes($attribute, $value): bool
    {
        // 1. Syntax
        if (!$this->validSyntax($value)) {
            $this->reason = 'formato';
            return false;
        }

        $atPos  = strrpos($value, '@');
        $local  = substr($value, 0, $atPos);
        $domain = substr($value, $atPos + 1);

        if (!$this->validLocal($local)) {
            $this->reason = 'formato';
            return false;
        }

        // 2. DNS  — MX first, A-record fallback
        $smtpHosts = $this->getMxHosts($domain);

        if (empty($smtpHosts)) {
            $ip = @gethostbyname($domain);
            if ($ip === $domain) {          // gethostbyname() returns the input unchanged when resolution fails
                $this->reason = 'dominio';
                return false;
            }
            $smtpHosts = [$domain];
        }

        // 3. SMTP probe
        $result = $this->verifySMTP($smtpHosts, $value);

        if ($result === 'INVALID') {
            $this->reason = 'dominio';
            return false;
        }

        // VALID or INDETERMINATE — don't block
        return true;
    }

    public function message(): string
    {
        return $this->reason === 'dominio'
            ? 'El dominio del correo no existe o no puede recibir mensajes. Verificá que esté escrito correctamente (ej: usuario@gmail.com).'
            : 'El formato del correo electrónico no es válido.';
    }

    // ── Syntax ────────────────────────────────────────────────────────────────

    private function validSyntax(string $email): bool
    {
        if (str_contains($email, ' ')) return false;
        if (strlen($email) > 254) return false;

        $pattern = '/^[A-Za-z0-9.!#$%&\'*+\/=?^_`{|}~-]+@(?:[A-Za-z0-9](?:[A-Za-z0-9\-]*[A-Za-z0-9])?\.)+[A-Za-z]{2,}$/';
        return (bool) preg_match($pattern, $email);
    }

    private function validLocal(string $local): bool
    {
        if (strlen($local) > 64) return false;
        if (str_starts_with($local, '.') || str_ends_with($local, '.')) return false;
        if (str_contains($local, '..')) return false;
        return true;
    }

    // ── DNS ───────────────────────────────────────────────────────────────────

    private function getMxHosts(string $domain): array
    {
        try {
            $records = @dns_get_record($domain, DNS_MX);
            if (is_array($records) && count($records) > 0) {
                usort($records, fn($a, $b) => ($a['pri'] ?? 0) <=> ($b['pri'] ?? 0));
                return array_column($records, 'target');
            }
        } catch (\Throwable) {}

        // Fallback: both calls may fail on some hosts, treat as empty
        return (bool) @checkdnsrr($domain, 'MX') ? [$domain] : [];
    }

    // ── SMTP ──────────────────────────────────────────────────────────────────

    /** @param  string[] $hosts  MX priority-sorted host list */
    private function verifySMTP(array $hosts, string $email): string
    {
        // Limit probes to avoid long response times: first 2 MX hosts, ports 25 → 587 → 465
        $targets = array_slice($hosts, 0, 2);
        $ports   = [25, 587, 465];

        foreach ($targets as $host) {
            foreach ($ports as $port) {
                $r = $this->smtpProbe($host, $port, $email);
                if ($r !== 'INDETERMINATE') {
                    return $r;
                }
            }
        }

        return 'INDETERMINATE';
    }

    private function smtpProbe(string $host, int $port, string $email): string
    {
        try {
            $ctx = stream_context_create([
                'ssl' => [
                    'verify_peer'       => false,
                    'verify_peer_name'  => false,
                    'allow_self_signed' => true,
                ],
            ]);

            $addr = ($port === 465 ? 'ssl://' : '') . $host . ':' . $port;
            $sock = @stream_socket_client($addr, $errno, $errstr, self::TIMEOUT, STREAM_CLIENT_CONNECT, $ctx);

            if (!$sock) {
                return 'INDETERMINATE';
            }

            stream_set_timeout($sock, self::TIMEOUT);

            // Read banner
            $banner = $this->readLine($sock);
            if (!$this->startsWith2xx($banner)) {
                fclose($sock);
                return 'INDETERMINATE';
            }

            // EHLO (multi-line response)
            fwrite($sock, 'EHLO ' . self::HELO_DOMAIN . "\r\n");
            $ehlo = $this->readMultiLine($sock);
            if (!$this->startsWith2xx($ehlo)) {
                // Some servers only accept HELO
                fwrite($sock, 'HELO ' . self::HELO_DOMAIN . "\r\n");
                $helo = $this->readLine($sock);
                if (!$this->startsWith2xx($helo)) {
                    fclose($sock);
                    return 'INDETERMINATE';
                }
            }

            // MAIL FROM
            fwrite($sock, 'MAIL FROM:<test@' . self::HELO_DOMAIN . ">\r\n");
            $from = $this->readLine($sock);
            if (!$this->startsWith2xx($from)) {
                fclose($sock);
                return 'INDETERMINATE';
            }

            // RCPT TO — the decisive step
            fwrite($sock, "RCPT TO:<{$email}>\r\n");
            $rcpt = $this->readLine($sock);

            @fwrite($sock, "QUIT\r\n");
            fclose($sock);

            $code = (int) substr(trim($rcpt), 0, 3);

            if ($code === 250 || $code === 251) return 'VALID';
            if ($code >= 500 && $code < 600)   return 'INVALID';

            return 'INDETERMINATE';

        } catch (\Throwable) {
            return 'INDETERMINATE';
        }
    }

    private function readLine($sock): string
    {
        if (feof($sock)) return '';
        $line = @fgets($sock, 512);
        $meta = stream_get_meta_data($sock);
        if ($meta['timed_out']) return '';
        return $line ?: '';
    }

    /**
     * Read a potentially multi-line SMTP response (lines ending with "XYZ-").
     * Returns the first three-digit code prefix for status checks.
     */
    private function readMultiLine($sock): string
    {
        $firstCode = '';
        do {
            $line = @fgets($sock, 512);
            if ($line === false) break;
            $meta = stream_get_meta_data($sock);
            if ($meta['timed_out']) break;
            if ($firstCode === '') {
                $firstCode = substr($line, 0, 3);
            }
        } while (strlen($line) >= 4 && $line[3] === '-');

        return $firstCode;
    }

    private function startsWith2xx(string $line): bool
    {
        return strlen($line) >= 1 && $line[0] === '2';
    }
}
