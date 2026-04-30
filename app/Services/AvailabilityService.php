<?php

namespace App\Services;

use App\Models\Cita;
use App\Models\Barbero;
use Carbon\Carbon;

class AvailabilityService
{
    public function availableSlots(Barbero $barbero, string $dateYmd, int $requiredMinutes): array
    {
        $slot   = max(5, (int)($barbero->slot_minutes ?? 30));
        $buffer = (int)($barbero->buffer_minutes ?? 0);
        $dayIdx = (int)Carbon::parse($dateYmd)->dayOfWeek; // 0=Dom … 6=Sáb

        // ── Exception / holiday check ──────────────────────────────────────
        $hasException = $barbero->excepciones()
            ->whereDate('date', '<=', $dateYmd)
            ->whereDate('date_to', '>=', $dateYmd)
            ->exists();

        if ($hasException) return [];

        // ── Work windows (horarios or legacy) ─────────────────────────────
        $windows = $this->getWorkWindows($barbero, $dateYmd, $dayIdx);
        if (empty($windows)) return [];

        // ── Booked appointments ───────────────────────────────────────────
        $citas = Cita::where('barbero_id', $barbero->id)
            ->whereIn('status', ['pending', 'confirmed', 'completed'])
            ->whereDate('starts_at', $dateYmd)
            ->orderBy('starts_at')
            ->get(['starts_at', 'ends_at']);

        // ── Manual blocks (barbero_bloques — fecha específica) ───────────
        $bloques = $barbero->bloques()
            ->whereDate('date', $dateYmd)
            ->orderBy('start_time')
            ->get(['start_time', 'end_time'])
            ->map(fn($b) => [
                'starts_at' => Carbon::createFromFormat('Y-m-d H:i', $dateYmd . ' ' . substr($b->start_time, 0, 5)),
                'ends_at'   => Carbon::createFromFormat('Y-m-d H:i', $dateYmd . ' ' . substr($b->end_time, 0, 5)),
            ])->all();

        // ── Recurring breaks (barbero_descansos — por día de la semana) ──
        foreach ($this->getRecurringBlocks($barbero, $dateYmd, $dayIdx) as $rb) {
            $bloques[] = $rb;
        }

        $now     = Carbon::now();
        $isToday = $dateYmd === $now->toDateString();

        $overlaps = fn($aS, $aE, $bS, $bE) => ($aS < $bE) && ($aE > $bS);

        $available = [];

        foreach ($windows as ['start' => $winStart, 'end' => $winEnd]) {
            for ($t = $winStart->copy(); $t->lt($winEnd); $t->addMinutes($slot)) {
                if ($isToday && $t->lt($now)) continue;

                $cStart = $t->copy();
                $cEnd   = $t->copy()->addMinutes($requiredMinutes + $buffer);

                if ($cEnd->gt($winEnd)) break;

                $busy = false;

                foreach ($citas as $c) {
                    $cs = $c->starts_at instanceof \Carbon\Carbon ? $c->starts_at : Carbon::parse($c->starts_at);
                    $ce = $c->ends_at   instanceof \Carbon\Carbon ? $c->ends_at   : Carbon::parse($c->ends_at);
                    if ($overlaps($cStart, $cEnd, $cs, $ce)) {
                        $busy = true;
                        break;
                    }
                }
                if ($busy) continue;

                foreach ($bloques as $b) {
                    if ($overlaps($cStart, $cEnd, $b['starts_at'], $b['ends_at'])) {
                        $busy = true;
                        break;
                    }
                }
                if ($busy) continue;

                $available[] = $t->format('g:i A');
            }
        }

        return $available;
    }

    // ── Legacy wrapper ────────────────────────────────────────────────────
    public function availableSlotsOrig(Barbero $barbero, string $dateYmd, int $durationMinutes): array
    {
        return $this->availableSlots($barbero, $dateYmd, $durationMinutes);
    }

    // ─────────────────────────────────────────────────────────────────────
    // Private helpers
    // ─────────────────────────────────────────────────────────────────────

    private function getWorkWindows(Barbero $barbero, string $dateYmd, int $dayIdx): array
    {
        try {
            $horarios = $barbero->horarios()->get();
        } catch (\Throwable $e) {
            $horarios = collect();
        }

        if ($horarios->isNotEmpty()) {
            $windows = [];
            foreach ($horarios as $h) {
                $raw  = is_array($h->dias) ? $h->dias : json_decode($h->dias, true);
                $dias = array_map('intval', $raw ?? []);
                if (!in_array($dayIdx, $dias)) continue;

                $windows[] = [
                    'start' => Carbon::createFromFormat('Y-m-d H:i', $dateYmd . ' ' . substr($h->hora_inicio, 0, 5)),
                    'end'   => Carbon::createFromFormat('Y-m-d H:i', $dateYmd . ' ' . substr($h->hora_fin, 0, 5)),
                ];
            }
            usort($windows, fn($a, $b) => $a['start']->timestamp - $b['start']->timestamp);
            return $windows;
        }

        // ── Legacy fallback ──────────────────────────────────────────────
        $raw      = $barbero->work_days ? json_decode($barbero->work_days, true) : [1, 2, 3, 4, 5];
        $workDays = array_map('intval', $raw ?? []);
        if (!in_array($dayIdx, $workDays)) return [];

        $workStart = substr($barbero->work_start ?? '09:00', 0, 5);
        $workEnd   = substr($barbero->work_end   ?? '18:00', 0, 5);

        return [[
            'start' => Carbon::createFromFormat('Y-m-d H:i', $dateYmd . ' ' . $workStart),
            'end'   => Carbon::createFromFormat('Y-m-d H:i', $dateYmd . ' ' . $workEnd),
        ]];
    }

    /**
     * Returns recurring blocked intervals for the given day from barbero_descansos.
     * Safe to call even if the table hasn't been migrated yet.
     */
    private function getRecurringBlocks(Barbero $barbero, string $dateYmd, int $dayIdx): array
    {
        try {
            $descansos = $barbero->descansos()->get();
        } catch (\Throwable $e) {
            return [];
        }

        $blocks = [];
        foreach ($descansos as $d) {
            $raw  = is_array($d->dias) ? $d->dias : json_decode($d->dias, true);
            $dias = array_map('intval', $raw ?? []);
            if (!in_array($dayIdx, $dias)) continue;

            $blocks[] = [
                'starts_at' => Carbon::createFromFormat('Y-m-d H:i', $dateYmd . ' ' . substr($d->hora_inicio, 0, 5)),
                'ends_at'   => Carbon::createFromFormat('Y-m-d H:i', $dateYmd . ' ' . substr($d->hora_fin, 0, 5)),
            ];
        }
        return $blocks;
    }
}
