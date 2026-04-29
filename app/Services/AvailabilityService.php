<?php

namespace App\Services;

use App\Models\Cita;
use App\Models\Barbero;
use Carbon\Carbon;

class AvailabilityService
{
    /**
     * Returns an array of available start times (12-hour format, e.g. "9:00 AM")
     * for the given barber, date, and required service duration.
     *
     * When the barbero has rows in barbero_horarios those define all work windows
     * for that day. If no horarios exist the service falls back to the legacy
     * work_start / work_end / work_days columns — preserving backward compatibility
     * with existing tenant data.
     *
     * Hard-coded rules removed:
     *  - Lunch break 12:00-13:00 (use barbero_bloques to model this explicitly)
     *  - Saturday 18:00 hard cutoff (honour whatever hora_fin the barber has set)
     */
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

        // ── Manual blocks (barbero_bloques) ───────────────────────────────
        $bloques = $barbero->bloques()
            ->whereDate('date', $dateYmd)
            ->orderBy('start_time')
            ->get(['start_time', 'end_time'])
            ->map(fn($b) => [
                'starts_at' => Carbon::createFromFormat('Y-m-d H:i', $dateYmd . ' ' . substr($b->start_time, 0, 5)),
                'ends_at'   => Carbon::createFromFormat('Y-m-d H:i', $dateYmd . ' ' . substr($b->end_time, 0, 5)),
            ])->all();

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

    // ── Legacy wrapper (kept for any callers that still use availableSlotsOrig) ──
    public function availableSlotsOrig(Barbero $barbero, string $dateYmd, int $durationMinutes): array
    {
        return $this->availableSlots($barbero, $dateYmd, $durationMinutes);
    }

    // ─────────────────────────────────────────────────────────────────────────────
    // Private helpers
    // ─────────────────────────────────────────────────────────────────────────────

    /**
     * Returns an ordered list of [start, end] Carbon pairs for the given day.
     * Uses barbero_horarios when rows exist; falls back to legacy columns.
     */
    private function getWorkWindows(Barbero $barbero, string $dateYmd, int $dayIdx): array
    {
        // Guard: table may not exist yet on tenants that haven't run the migration
        try {
            $horarios = $barbero->horarios()->get();
        } catch (\Throwable $e) {
            $horarios = collect();
        }

        if ($horarios->isNotEmpty()) {
            $windows = [];
            foreach ($horarios as $h) {
                $raw  = is_array($h->dias) ? $h->dias : json_decode($h->dias, true);
                // Cast to int to handle both string ("1") and integer (1) stored values
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
}
