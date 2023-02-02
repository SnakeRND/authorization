<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserAddress>
 */
class UserAddressFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'external_id' => $this->faker->unique()->numberBetween(0, 123456),
            'user_id' => 1,
            'kladr' => (string)rand(1000000000,9999999999),
            'region' => $this->faker->city(),
            'city' => $this->faker->city(),
            'zip_code' => rand(100000,999999),
            'street' => $this->faker->streetAddress(),
            'building' => $this->faker->buildingNumber(),
            'korpus' => $this->faker->buildingNumber(),
            'flat' => $this->faker->buildingNumber(),
            'pvz_code' => Str::random(10),
            'latitude' => $this->faker->latitude(),
            'longitude' =>$this->faker->longitude(),
            'is_active' => true,
            'dadata_checked' => true
        ];
    }
}

?>
