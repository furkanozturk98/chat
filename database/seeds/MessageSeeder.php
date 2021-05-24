<?php

namespace Database\Seeders;

use App\Friend;
use App\MessageStatuses;
use App\Models\Message;
use Illuminate\Database\Seeder;

class MessageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Message::query()->create([
            'from' => 1,
            'to' => 6,
            'room_id' => Friend::query()->where('user_id', 1)->where('friend_id', 6)->first()->room_id,
            'message' => 'hello',
            'status' => MessageStatuses::UNREAD
        ]);

        Message::query()->create([
            'from' => 6,
            'to' => 1,
            'room_id' => Friend::query()->where('user_id', 1)->where('friend_id', 6)->first()->room_id,
            'message' => 'hello',
            'status' => MessageStatuses::UNREAD
        ]);

        Message::query()->create([
            'from' => 1,
            'to' => 6,
            'room_id' => Friend::query()->where('user_id', 1)->where('friend_id', 6)->first()->room_id,
            'message' => 'deneme',
            'status' => MessageStatuses::UNREAD
        ]);

        Message::query()->create([
            'from' => 1,
            'to' => 6,
            'room_id' => Friend::query()->where('user_id', 1)->where('friend_id', 6)->first()->room_id,
            'message' => 'deneme',
            'status' => MessageStatuses::UNREAD
        ]);
    }
}
