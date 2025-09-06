<?php

// app/Support/IcsBuilder.php
namespace App\Support;

class IcsBuilder
{
    public static function appointment(string $uid, string $summary, string $description, string $startLocal, string $endLocal, string $tz = 'America/Costa_Rica'): string
    {
        $L = [
            'BEGIN:VCALENDAR',
            'VERSION:2.0',
            'PRODID:-//Proyecto Barbería//ES',
            'CALSCALE:GREGORIAN',
            'METHOD:PUBLISH',
            'BEGIN:VEVENT',
            "UID:$uid",
            'DTSTAMP:' . gmdate('Ymd\THis\Z'),
            "DTSTART;TZID=$tz:" . date('Ymd\THis', strtotime($startLocal)),
            "DTEND;TZID=$tz:" . date('Ymd\THis', strtotime($endLocal)),
            'SUMMARY:' . self::esc($summary),
            'DESCRIPTION:' . self::esc($description),
            'END:VEVENT',
            'END:VCALENDAR'
        ];
        return implode("\r\n", $L);
    }
    private static function esc(string $t): string
    {
        return str_replace(["\\", ",", ";", "\n"], ["\\\\", "\,", "\;", "\\n"], $t);
    }
}
