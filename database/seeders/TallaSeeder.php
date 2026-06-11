<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Talla;

class TallaSeeder extends Seeder
{
    public function run(): void
    {
        $tallas = ['S', 'M', 'L', 'XL'];
        foreach ($tallas as $talla) {
            Talla::firstOrCreate(['nombre' => $talla]);
        }
    }
}
