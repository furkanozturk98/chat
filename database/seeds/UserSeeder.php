<?php

use App\Models\User;
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
            'name'      => 'Furkan Öztürk',
            'email'     => 'admin@admin.com',
            'password'  => Hash::make('password'),
            'api_token' => Str::random(60),
            'image'     => 'profile.jpg',
        ]);

        User::query()->create([
            'name'      => 'Jon Doe1',
            'email'     => 'test@test.com',
            'password'  => Hash::make('password'),
            'api_token' => Str::random(60),
            'image'     => 'profile.jpg',
        ]);

        User::query()->create([
            'name'      => 'Jon Doe2',
            'email'     => 'test2@test.com',
            'password'  => Hash::make('password'),
            'api_token' => Str::random(60),
            'image'     => 'profile.jpg',
        ]);

        User::query()->create([
            'name'      => 'Jon Doe3',
            'email'     => 'test3@test.com',
            'password'  => Hash::make('password'),
            'api_token' => Str::random(60),
            'image'     => 'profile.jpg',
        ]);

        User::query()->create([
            'name'      => 'Jon Doe4',
            'email'     => 'test4@test.com',
            'password'  => Hash::make('password'),
            'api_token' => Str::random(60),
            'image'     => 'profile.jpg',
        ]);

        User::query()->create([
            'name'      => 'Jon Doe5',
            'email'     => 'test5@test.com',
            'password'  => Hash::make('password'),
            'api_token' => Str::random(60),
            'image'     => 'profile.jpg',
        ]);

        User::query()->create([
            'name'      => 'Jon Doe6',
            'email'     => 'test6@test.com',
            'password'  => Hash::make('password'),
            'api_token' => Str::random(60),
            'image'     => 'profile.jpg',
        ]);

        User::query()->create([
            'name'      => 'Jon Doe7',
            'email'     => 'test7@test.com',
            'password'  => Hash::make('password'),
            'api_token' => Str::random(60),
            'image'     => 'profile.jpg',
        ]);

        User::query()->create([
            'name'      => 'Jon Doe8',
            'email'     => 'test8@test.com',
            'password'  => Hash::make('password'),
            'api_token' => Str::random(60),
            'image'     => 'profile.jpg',
        ]);

        User::query()->create([
            'name'      => 'Jon Doe9',
            'email'     => 'test9@test.com',
            'password'  => Hash::make('password'),
            'api_token' => Str::random(60),
            'image'     => 'profile.jpg',
        ]);

        User::query()->create([
            'name'      => 'Jon Doe10',
            'email'     => 'test10@test.com',
            'password'  => Hash::make('password'),
            'api_token' => Str::random(60),
            'image'     => 'profile.jpg',
        ]);
    }
}
