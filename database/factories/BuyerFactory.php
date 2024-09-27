<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Buyer;

class BuyerFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Buyer::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'shop' => $this->faker->word(),
            'slug' => $this->faker->slug(),
            'address' => $this->faker->text(),
            'orders' => $this->faker->numberBetween(-10000, 10000),
            'total' => $this->faker->randomFloat(2, 0, 9999999999.99),
            'paid' => $this->faker->randomFloat(2, 0, 9999999999.99),
            'status' => $this->faker->randomElement(["approved","pending","block"]),
            'payment_cycle' => $this->faker->numberBetween(-10000, 10000),
        ];
    }
}
