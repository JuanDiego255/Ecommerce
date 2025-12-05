<?php

use Illuminate\Database\Seeder;
use App\Models\RoulettePrize;

class RoulettePrizeSeeder extends Seeder
{
    public function run(): void
    {
        RoulettePrize::insert([
            [
                'label' => '5% de descuento',
                'discount_percent' => 5,
                'weight' => 50,   // más probable
            ],
            [
                'label' => '10% de descuento',
                'discount_percent' => 10,
                'weight' => 30,
            ],
            [
                'label' => '20% de descuento',
                'discount_percent' => 20,
                'weight' => 10,   // menos probable
            ],
            [
                'label' => 'Sigue intentando',
                'discount_percent' => 0,
                'weight' => 10,
            ],
        ]);
    }
}
