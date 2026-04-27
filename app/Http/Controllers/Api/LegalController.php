<?php

namespace App\Http\Controllers\Api;

use App\Models\TenantInfo;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LegalController
{
    public function privacy(Request $request, string $tenant): JsonResponse
    {
        return $this->respond('privacy', $tenant);
    }

    public function terms(Request $request, string $tenant): JsonResponse
    {
        return $this->respond('terms', $tenant);
    }

    private function respond(string $type, string $tenant): JsonResponse
    {
        $info     = TenantInfo::first();
        $email    = $info->email    ?? 'info@safeworsolutions.com';
        $whatsapp = $info->whatsapp ?? null;
        $date     = Carbon::now()->locale('es')->isoFormat('D [de] MMMM [de] YYYY');

        $path = resource_path("legal/{$type}.json");
        if (!file_exists($path)) {
            return response()->json(['error' => 'Content not found'], 404);
        }

        $sections = json_decode(file_get_contents($path), true);
        $sections = $this->injectValues($sections, $email, $whatsapp);

        return response()->json([
            'company'    => 'Safewor Solutions',
            'email'      => $email,
            'whatsapp'   => $whatsapp,
            'updated_at' => $date,
            'sections'   => $sections,
        ]);
    }

    // Replace __EMAIL__ / __WHATSAPP__ placeholders recursively.
    // Items/paragraphs whose only content is '__WHATSAPP__' are removed when
    // no whatsapp is configured.
    private function injectValues(array $sections, string $email, ?string $whatsapp): array
    {
        foreach ($sections as &$section) {
            $section['paragraphs']  = $this->processStrings($section['paragraphs']  ?? [], $email, $whatsapp);
            $section['items']       = $this->processStrings($section['items']        ?? [], $email, $whatsapp);
            $section['note']        = $this->processString($section['note']          ?? null, $email, $whatsapp);
            if (!empty($section['subsections'])) {
                $section['subsections'] = $this->injectValues($section['subsections'], $email, $whatsapp);
            }
        }
        return $sections;
    }

    private function processStrings(array $items, string $email, ?string $whatsapp): array
    {
        $result = [];
        foreach ($items as $item) {
            if (str_contains($item, '__WHATSAPP__')) {
                if ($whatsapp) {
                    $result[] = str_replace('__WHATSAPP__', $whatsapp, $item);
                }
                // skip if no whatsapp configured
            } else {
                $result[] = str_replace('__EMAIL__', $email, $item);
            }
        }
        return $result;
    }

    private function processString(?string $value, string $email, ?string $whatsapp): ?string
    {
        if ($value === null) return null;
        $value = str_replace('__EMAIL__', $email, $value);
        if ($whatsapp) {
            $value = str_replace('__WHATSAPP__', $whatsapp, $value);
        }
        return $value;
    }
}
