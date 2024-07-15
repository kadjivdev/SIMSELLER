<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class MouvementFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'libelleMvt'=>$this->faker->text(100),
            'dateMvt'=>$this->faker->dateTime(),
            'compteClient_id'=>1,
            'user_id'=>1,
            'montantMvt'=>$this->faker->numberBetween(50000,100000),
            'sens'=>$this->faker->randomElement(['0','1'])
        ];
    }
}
