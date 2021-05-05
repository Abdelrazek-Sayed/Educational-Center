<?php

namespace Tests\Feature;

use Tests\TestCase;

class AuthTest extends TestCase
{
    private $email;
    public function testLoginWithAdminAccount()
    {

        $data = [
            'email' => 'admin@gmail.com',
            'password' => '12345678',
        ];

        $user = $this->json('POST', '/api/auth/login', $data);
        $user->assertStatus(200)->assertJson(['data' => ['role' => 'Admin',]]);
        $this->email = $user['data']['email'];

        // $user->assertSee('admin');
    }

    // public function testCredentials()
    // {
    //     $data = [
    //         'email' => 'aa@gmail.com',
    //         'password' => '12345678',
    //     ];

    //     $user = $this->json('post', '/api/auth/login', $data);
    //     $user->assertStatus(200)->assertJson(['data' => ['role' => 'Admin']]);

    // }
}
