<?php

namespace Database\Factories\Modules\Authorization\Models;

use App\Modules\Authorization\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<User>
 */
class UserFactory extends Factory
{
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'id' => $this->faker->uuid,
            'login' => $this->faker->phoneNumber,
            'password' => $this->faker->password(8, 30),
            'verification_code' => $this->faker->numberBetween(100000, 999999)
        ];
    }
}

?>
