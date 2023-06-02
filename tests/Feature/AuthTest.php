<?php

namespace Tests\Feature;

use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class AuthTest extends TestCase
{
    public function test_auth_register()
    {
        $response = $this->postJson(route('auth.register'), [
            'last_name' => 'last name',
            'first_name' => 'first name',
            'email' => 'test@mail.com',
            'password' => '12345678'
        ]);

        $response->assertStatus(Response::HTTP_CREATED);
    }

    public function test_auth_register_unprocessable()
    {
        $response = $this->postJson(route('auth.register'), [
            'last_name' => 'last name',
            'first_name' => 'first name',
            'email' => 'test@mail.com',
            'password' => '1234'
        ]);

        $response->assertStatus(422);
    }

    public function test_auth_login()
    {
        $email = 'test@mail.com';
        $password = '12345678';

        User::factory()->create([
            'email' => $email,
            'password' => $password
        ]);

        $response = $this->postJson(route('auth.login'), [
            'login' => $email,
            'password' => $password,
        ]);

        $response->assertStatus(Response::HTTP_OK);
    }

    public function test_auth_login_unauthorized()
    {
        User::factory()->create();

        $response = $this->postJson(route('auth.login'), [
            'login' => 'invalid@mail.com',
            'password' => 'invalid password',
        ]);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    public function test_auth_logout()
    {
        Sanctum::actingAs(User::factory()->create());

        $response = $this->postJson(route('auth.logout'));

        $response->assertStatus(Response::HTTP_OK);
    }
}
