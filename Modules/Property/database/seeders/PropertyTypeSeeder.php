<?php

namespace Modules\Property\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Property\Models\PropertyType;

class PropertyTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            ['name' => 'Amazing pools', 'slug' => 'pool', 'icon' => '🏊'],
            ['name' => 'Cabins', 'slug' => 'cabin', 'icon' => '🏕️'],
            ['name' => 'Beachfront', 'slug' => 'beachfront', 'icon' => '🏖️'],
            ['name' => 'Luxe', 'slug' => 'luxe', 'icon' => '💎'],
            ['name' => 'Trending', 'slug' => 'trending', 'icon' => '🔥'],
            ['name' => 'Countryside', 'slug' => 'countryside', 'icon' => '🌄'],
            ['name' => 'Apartment', 'slug' => 'apartment', 'icon' => '🏙️'],
            ['name' => 'Villa', 'slug' => 'villa', 'icon' => '🏠'],
            ['name' => 'Studio', 'slug' => 'studio', 'icon' => '🛋️'],
            ['name' => 'House', 'slug' => 'house', 'icon' => '🏡'],
        ];

        foreach ($types as $type) {
            PropertyType::updateOrCreate(['slug' => $type['slug']], $type);
        }
    }
}
