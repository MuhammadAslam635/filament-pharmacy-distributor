<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Order;
use App\Models\Transaction;
use App\Models\User;

class TransactionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Transaction::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'order_id' => Order::factory(),
            'status' => $this->faker->randomElement(["pending","paid","partial"]),
            'method' => $this->faker->randomElement(["cod","online","cash"]),
            'total' => $this->faker->randomFloat(2, 0, 9999999999.99),
            'paid' => $this->faker->randomFloat(2, 0, 9999999999.99),
            'remaining' => $this->faker->randomFloat(2, 0, 9999999999.99),
            'next_payment_date' => $this->faker->date(),
            'user_id' => User::factory(),
        ];
    }
}
