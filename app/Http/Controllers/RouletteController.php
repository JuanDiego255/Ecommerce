<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\RoulettePrize;

class RouletteController extends Controller
{
    public function spin(Request $request)
    {
        $prizes = RoulettePrize::where('active', true)
            ->orderBy('id')
            ->get();

        if ($prizes->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No hay premios configurados en la ruleta.',
            ], 400);
        }

        $totalWeight = $prizes->sum('weight');
        $rand = random_int(1, $totalWeight);

        $current = 0;
        $selectedPrize = null;
        $segmentIndex = 0;

        foreach ($prizes as $index => $prize) {
            $current += $prize->weight;

            if ($rand <= $current) {
                $selectedPrize = $prize;
                $segmentIndex = $index;
                break;
            }
        }

        if (!$selectedPrize) {
            $selectedPrize = $prizes->last();
            $segmentIndex = $prizes->count() - 1;
        }

        $couponCode = null;
        if ($selectedPrize->discount_percent > 0) {
            $couponCode = strtoupper(Str::random(8));
            // Aquí va tu lógica real para guardar el cupón
        }

        return response()->json([
            'success'          => true,
            'label'            => $selectedPrize->label,
            'discount_percent' => $selectedPrize->discount_percent,
            'coupon_code'      => $couponCode,
            'segment_index'    => $segmentIndex,      // lo podemos dejar, pero ya no confiamos ciegamente en él
            'segments_count'   => $prizes->count(),
            'prize_id'         => $selectedPrize->id, // 👈 clave para el frontend
        ]);
    }
    public function index()
    {
        $prizes = \App\Models\RoulettePrize::where('active', true)
            ->orderBy('id')
            ->get();
        return view('frontend.design_ecommerce.roulette', compact('prizes'));
    }
}
