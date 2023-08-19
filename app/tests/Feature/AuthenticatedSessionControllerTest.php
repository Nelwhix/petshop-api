<?php declare(strict_types=1);

use App\Models\User;
use Database\Seeders\AdminSeeder;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

test('regular user cannot pass', function () {
    $user = User::factory()->createOne([
        'password' => Hash::make('test')
    ]);

    $this->post('/api/v1/admin/login', [
        'email' => $user->email,
        'password' => 'test'
    ])->assertStatus(Response::HTTP_BAD_REQUEST);
});

test('user with bad credentials cannot login', function () {
    $this->seed(AdminSeeder::class);

    $this->post('/api/v1/admin/login', [
        'email' => 'admin@buckhill.co.uk',
        'password' => 'test'
    ])->assertStatus(Response::HTTP_BAD_REQUEST);
});

test('admin account can login', function () {
    $this->seed(AdminSeeder::class);

    $this->post('/api/v1/admin/login', [
        'email' => 'admin@buckhill.co.uk',
        'password' => 'admin'
    ])->assertStatus(Response::HTTP_OK);
    $this->assertDatabaseCount('jwt_tokens', 2);
})->only();
