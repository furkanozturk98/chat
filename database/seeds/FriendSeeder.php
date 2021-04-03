<?php

namespace Database\Seeders;

use App\Friend;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class FriendSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $room_id = Str::random(5);
        Friend::query()->create([
            'user_id' => 1,
            'friend_id' => 6,
            'status' => 0,
            'room_id' => $room_id,
        ]);

        Friend::query()->create([
            'user_id' => 6,
            'friend_id' => 1,
            'status' => 0,
            'room_id' => $room_id,
        ]);

        $room_id = Str::random(5);
        Friend::query()->create([
            'user_id' => 1,
            'friend_id' => 7,
            'status' => 0,
            'room_id' => $room_id,
        ]);

        Friend::query()->create([
            'user_id' => 7,
            'friend_id' => 1,
            'status' => 0,
            'room_id' => $room_id,
        ]);

        $room_id = Str::random(5);
        Friend::query()->create([
            'user_id' => 1,
            'friend_id' => 8,
            'status' => 0,
            'room_id' => $room_id,
        ]);

        Friend::query()->create([
            'user_id' => 8,
            'friend_id' => 1,
            'status' => 0,
            'room_id' => $room_id,
        ]);

        $room_id = Str::random(5);
        Friend::query()->create([
            'user_id' => 1,
            'friend_id' => 9,
            'status' => 0,
            'room_id' => $room_id,
        ]);

        Friend::query()->create([
            'user_id' => 9,
            'friend_id' => 1,
            'status' => 0,
            'room_id' => $room_id,
        ]);
    }
}
