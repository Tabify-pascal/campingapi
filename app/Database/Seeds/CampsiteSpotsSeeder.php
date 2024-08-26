<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class CampsiteSpotsSeeder extends Seeder
{
    public function run()
    {
        $types = ['Bungalow', 'Tent', 'Caravan'];
        $data = [];

        for ($i = 1; $i <= 20; $i++) {
            $type = $types[array_rand($types)];
            $number_of_guests = rand(2, 6);
            $price = $this->calculatePrice($type, $number_of_guests);

            $data[] = [
                'price' => $price,
                'number_of_guests' => $number_of_guests,
                'name' => strtoupper(substr($type, 0, 1)) . $i,
                'type' => $type,
            ];
        }

        // Insert the data
        $this->db->table('campsite_spots')->insertBatch($data);
    }

    private function calculatePrice(string $type, int $number_of_guests): float
    {
        // Base prices for each type
        $basePrices = [
            'Tent' => 50,
            'Caravan' => 75,
            'Bungalow' => 100,
        ];

        // Additional cost per guest
        $additionalCostPerGuest = 10;

        // Calculate the total price
        $basePrice = $basePrices[$type];
        $totalPrice = $basePrice + ($additionalCostPerGuest * ($number_of_guests - 2)); // First 2 guests included

        return $totalPrice;
    }
}