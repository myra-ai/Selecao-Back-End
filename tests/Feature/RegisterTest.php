<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase; // Usado para garantir que o banco de dados de teste seja limpo entre os testes

    /** @test */
    public function it_can_register_a_new_user()
    {
        // Dados do usuário para registro
        $data = [
            'name' => 'Test User',
            'email' => 'testuser@example.com',
            'password' => 'password',
            'password_confirmation' => 'password', // A confirmação da senha
        ];

        // Realiza a requisição POST para o endpoint de registro
        $response = $this->postJson('/api/register', $data);

        // Verifica se a resposta é um sucesso com status 201 (Criado)
        $response->assertStatus(201)
                 ->assertJson([
                     'message' => 'User registered successfully.',
                 ]);

        // Verifica se o usuário foi realmente criado no banco de dados
        $this->assertDatabaseHas('users', [
            'email' => 'testuser@example.com',
        ]);
    }

    /** @test */
    public function it_cannot_register_with_an_existing_email()
    {
        // Cria um usuário existente com o e-mail usado no teste
        User::factory()->create([
            'email' => 'existinguser@example.com',
        ]);

        // Dados de registro com um e-mail já existente
        $data = [
            'name' => 'New User',
            'email' => 'existinguser@example.com', // O e-mail já existe
            'password' => 'password',
            'password_confirmation' => 'password',
        ];

        // Realiza a requisição POST para o endpoint de registro
        $response = $this->postJson('/api/register', $data);

        // Verifica se a resposta foi 409 (Conflito), que indica que o e-mail já está em uso
        $response->assertStatus(409)
                 ->assertJson([
                     'message' => 'Email already exists.',
                 ]);
    }

    /** @test */
    public function it_requires_a_valid_email()
    {
        // Dados de registro com um e-mail inválido
        $data = [
            'name' => 'Test User',
            'email' => 'invalid-email', // E-mail inválido
            'password' => 'password',
            'password_confirmation' => 'password',
        ];

        // Realiza a requisição POST para o endpoint de registro
        $response = $this->postJson('/api/register', $data);

        // Verifica se a validação falhou e retornou erro para o e-mail
        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['email']);
    }

    /** @test */
    public function it_requires_a_strong_password()
    {
        // Dados de registro com uma senha muito curta (menor que 8 caracteres)
        $data = [
            'name' => 'Test User',
            'email' => 'testuser@example.com',
            'password' => 'short', // Senha fraca
            'password_confirmation' => 'short',
        ];

        // Realiza a requisição POST para o endpoint de registro
        $response = $this->postJson('/api/register', $data);

        // Verifica se a validação falhou para a senha
        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['password']);
    }
}
