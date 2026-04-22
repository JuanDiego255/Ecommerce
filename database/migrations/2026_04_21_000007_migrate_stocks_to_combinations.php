<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        $stocks = DB::table('stocks')
            ->join('attributes', 'stocks.attr_id', '=', 'attributes.id')
            ->where('attributes.name', '!=', 'Stock')
            ->whereNotNull('stocks.attr_id')
            ->select('stocks.id', 'stocks.clothing_id', 'stocks.attr_id', 'stocks.value_attr', 'stocks.price', 'stocks.stock')
            ->get();

        foreach ($stocks as $s) {
            // Skip if this exact single-value combination already exists
            $exists = DB::table('variant_combinations as vc')
                ->join('variant_combination_values as vcv', 'vcv.combination_id', '=', 'vc.id')
                ->where('vc.clothing_id', $s->clothing_id)
                ->where('vcv.value_attr', $s->value_attr)
                ->whereRaw('(SELECT COUNT(*) FROM variant_combination_values x WHERE x.combination_id = vc.id) = 1')
                ->exists();

            if ($exists) continue;

            $clothing    = DB::table('clothing')->where('id', $s->clothing_id)->first();
            $manageStock = $clothing ? (int) $clothing->manage_stock : 1;

            $comboId = DB::table('variant_combinations')->insertGetId([
                'clothing_id'  => $s->clothing_id,
                'price'        => $s->price ?? 0,
                'stock'        => $manageStock === 1 ? ($s->stock ?? 0) : -1,
                'manage_stock' => $manageStock,
                'created_at'   => now(),
                'updated_at'   => now(),
            ]);

            DB::table('variant_combination_values')->insert([
                'combination_id' => $comboId,
                'attr_id'        => $s->attr_id,
                'value_attr'     => $s->value_attr,
                'created_at'     => now(),
                'updated_at'     => now(),
            ]);
        }
    }

    public function down()
    {
        // Not reversible — data migration only
    }
};
