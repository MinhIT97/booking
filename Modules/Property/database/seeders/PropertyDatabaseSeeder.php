<?php

namespace Modules\Property\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Property\Models\Property;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Str;

class PropertyDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $hostRole = Role::where('name', 'host')->first();
        
        $host = User::firstOrCreate(
            ['email' => 'host@staynest.com'],
            [
                'name' => 'StayNest Host',
                'password' => bcrypt('password'),
                'role_id' => $hostRole->id ?? null,
            ]
        );

        $properties = [
            [
                'title' => 'Minimalist Concrete Villa',
                'description' => 'A stunning minimalist villa with a private pool and panoramic ocean views. Perfect for a quiet retreat.',
                'price_per_night' => 450.00,
                'max_guests' => 4,
                'bedrooms' => 2,
                'beds' => 2,
                'bathrooms' => 2,
                'address' => 'Beachside 123',
                'city' => 'Da Nang',
                'country' => 'Vietnam',
                'status' => 'approved',
                'average_rating' => 4.9,
                'reviews_count' => 128
            ],
            [
                'title' => 'Rustic Pine Cabin',
                'description' => 'Escape to the mountains in this cozy pine cabin. Features a large fireplace and mountain trail access.',
                'price_per_night' => 180.00,
                'max_guests' => 2,
                'bedrooms' => 1,
                'beds' => 1,
                'bathrooms' => 1,
                'address' => 'Mountain Way 45',
                'city' => 'Da Lat',
                'country' => 'Vietnam',
                'status' => 'approved',
                'average_rating' => 4.7,
                'reviews_count' => 85
            ],
            [
                'title' => 'Urban Sky Loft',
                'description' => 'Modern loft in the heart of the city with floor-to-ceiling windows and high-end finishes.',
                'price_per_night' => 220.00,
                'max_guests' => 2,
                'bedrooms' => 1,
                'beds' => 1,
                'bathrooms' => 1,
                'address' => 'City Center 88',
                'city' => 'Ho Chi Minh City',
                'country' => 'Vietnam',
                'status' => 'approved',
                'average_rating' => 4.85,
                'reviews_count' => 210
            ],
            [
                'title' => 'Bamboo Eco-Retreat',
                'description' => 'Experience sustainable living in this beautiful bamboo house located inside a lush garden.',
                'price_per_night' => 120.00,
                'max_guests' => 3,
                'bedrooms' => 1,
                'beds' => 2,
                'bathrooms' => 1,
                'address' => 'Green Valley 12',
                'city' => 'Hoi An',
                'country' => 'Vietnam',
                'status' => 'approved',
                'average_rating' => 4.95,
                'reviews_count' => 42
            ]
        ];

        foreach ($properties as $data) {
            $data['host_id'] = $host->id;
            Property::create($data);
        }
    }
}
