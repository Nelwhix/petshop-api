<?php

use App\Models\JwtToken;
use App\Models\User;
use App\Services\TokenService;
use Database\Seeders\AdminSeeder;

uses(
    Tests\TestCase::class,
    Illuminate\Foundation\Testing\RefreshDatabase::class
)->in('Feature', 'Unit');

function generateTestTokens(): \App\Services\TokenVO
{
    $tokenService = new TokenService();

    $user = User::factory()->create();
    $accessTokenModel = JwtToken::factory()->create([
        'user_id' => $user->id,
        'token_title' => 'access token',
        'expires_at' => now()->addMinutes(5)
    ]);

    $refreshTokenModel = JwtToken::factory()->create([
        'user_id' => $user->id,
        'token_title' => 'refresh token',
        'expires_at' => now()->addDay()
    ]);

    return $tokenService->createToken($user, $accessTokenModel, $refreshTokenModel);
}

function getAdminToken(): string {
    test()->seed(AdminSeeder::class);

    $response = test()->post('/api/v1/admin/login', [
        'email' => 'admin@buckhill.co.uk',
        'password' => 'admin'
    ])->json();

    return $response['data']['access_token'];
}
