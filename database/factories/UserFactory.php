<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class UserFactory extends Factory
{
    /**
     * O nome do modelo que esta fábrica irá criar.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Defina o estado padrão do modelo.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name, // Nome aleatório
            'email' => $this->faker->unique()->safeEmail, // E-mail único
            'email_verified_at' => now(), // E-mail verificado no momento da criação
            'password' => Hash::make('password'), // Senha criptografada
            'remember_token' => Str::random(10), // Token para lembrar o login do usuário
        ];
    }
}
