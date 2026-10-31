<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Find the categor's id
        $headphone = Category::where('name', 'Headphone')->firstOrFail();
        $mouse = Category::where('name', 'Mouse')->firstOrFail();
        $smartwatch = Category::where('name', 'Smartwatch')->firstOrFail();

        // Find the seller's id
        $seller = User::where('role', 'seller')->first() ?? User::first();

        if (!$seller && !$headphone) {
            $this->command->warn('Make sure there are seller and categorys data are ready');
            return;
        }

        // create first fixed product
        $products = [
            [
                'name' => 'Sony WH-1000XM5 Wireless Noise Canceling Headphones',
                'user_id' => $seller->id,
                'category_id' => $headphone->id,
                'stock' => 15,
                'price' => 499900.00,
                'image' => null,
                'rating' => 4.8,
                'review_count' => 13,
                'description' => 'Headphone nirkabel premium dengan peredam bising terdepan di industri (Industry-leading Noise Cancellation) berkat dua prosesor dan delapan mikrofon. Dilengkapi driver 30mm yang presisi, daya tahan baterai hingga 30 jam, serta desain ultra-nyaman dan ringan untuk pemakaian seharian penuh.'
            ],
            [
                'name' => 'Logitech G PRO X SUPERLIGHT 2 Wireless Gaming Mouse',
                'user_id' => $seller->id,
                'category_id' => $mouse->id,
                'stock' => 7,
                'price' => 320570.00,
                'image' => null,
                'rating' => 4.25,
                'review_count' => 10,
                'description' => 'Evolusi mouse gaming esport paling dominan di kancah turnamen profesional. Menghadirkan switch hybrid optik-mekanikal LIGHTFORCE untuk kecepatan kilat dan ketahanan klik mekanik yang memuaskan. Menggunakan sensor HERO 2 dengan akurasi sub-mikron dan port modern USB-C.'
            ],
            [
                'name' => 'Huawei Watch GT 4 46mm Smartwatch',
                'user_id' => $seller->id,
                'category_id' => $smartwatch->id,
                'stock' => 17,
                'price' => 3250000.00,
                'image' => null,
                'rating' => 4.75,
                'review_count' => 9,
                'description' => 'Menggabungkan estetika jam tangan klasik berbentuk segi delapan dengan teknologi kesehatan terdepan. Daya tahan baterai legendaris hingga 14 hari pemakaian. Kompatibel dengan perangkat Android maupun iOS, dilengkapi manajemen kalori dan pelacakan tidur TruSleep 3.0.'
            ],
        ];

        foreach($products as $product) {
            Product::firstOrCreate(
                ['name' => $product['name']],
                $product
            );
        }
    }
}
