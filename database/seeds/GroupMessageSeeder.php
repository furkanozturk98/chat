<?php

namespace Database\Seeders;

use App\Models\GroupMessage;
use Illuminate\Database\Seeder;

class GroupMessageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        GroupMessage::query()->create([
            'group_id' => 1,
            'sender' => 1,
            'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit'
        ]);

        GroupMessage::query()->create([
            'group_id' => 1,
            'sender' => 2,
            'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit'
        ]);

        GroupMessage::query()->create([
            'group_id' => 1,
            'sender' => 1,
            'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit'
        ]);

        GroupMessage::query()->create([
            'group_id' => 1,
            'sender' => 2,
            'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit'
        ]);

    }
}
