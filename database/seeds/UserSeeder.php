<?php

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::query()->create([
            'name' => 'Furkan Öztürk',
            'email' => 'admin@admin.com',
            'password' => Hash::make('password'),
            'api_token' => Str::random(60),
        ]);

        User::query()->create([
            'name' => 'Jon Doe1',
            'email' => 'test@test.com',
            'password' => Hash::make('password'),
            'api_token' => Str::random(60),
        ]);

        User::query()->create([
            'name' => 'Jon Doe2',
            'email' => 'test2@test.com',
            'password' => Hash::make('password'),
            'api_token' => Str::random(60),
        ]);

        User::query()->create([
            'name' => 'Jon Doe3',
            'email' => 'test3@test.com',
            'password' => Hash::make('password'),
            'api_token' => Str::random(60),
        ]);
    }
}
