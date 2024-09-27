<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Buyer;
use App\Models\Order;

class OrderFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Order::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'buyer_id' => Buyer::factory(),
            'total' => $this->faker->randomFloat(2, 0, 9999999999.99),
            'subtotal' => $this->faker->randomFloat(2, 0, 9999999999.99),
            'tax' => $this->faker->randomFloat(2, 0, 9999999999.99),
            'discount' => $this->faker->randomFloat(2, 0, 9999999999.99),
            'status' => $this->faker->randomElement(["pending","dispatch","processing","devlivered","cancelled"]),
            'delivery_date' => $this->faker->date(),
            'cancel_date' => $this->faker->date(),
        ];
    }
}
