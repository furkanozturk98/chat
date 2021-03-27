<?php

namespace Database\Seeders;

use App\Models\FriendRequest;
use Illuminate\Database\Seeder;

class FriendRequestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        FriendRequest::query()->create([
            'from' => 1,
            'to' => 2,
            'status' => 0,
        ]);

        FriendRequest::query()->create([
            'from' => 1,
            'to' => 3,
            'status' => 0,
        ]);

        FriendRequest::query()->create([
            'from' => 4,
            'to' => 1,
            'status' => 0,
        ]);

        FriendRequest::query()->create([
            'from' => 5,
            'to' => 1,
            'status' => 0,
        ]);

    }
}
