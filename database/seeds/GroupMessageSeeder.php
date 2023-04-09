<?php

namespace Database\Seeders;

use App\Models\Message;
use Illuminate\Database\Seeder;

class GroupMessageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        Message::query()->create([
            'group_id' => 1,
            'from'     => 1,
            'message'  => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit',
        ]);

        Message::query()->create([
            'group_id' => 1,
            'from'     => 2,
            'message'  => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit',
        ]);

        Message::query()->create([
            'group_id' => 1,
            'from'     => 1,
            'message'  => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit',
        ]);

        Message::query()->create([
            'group_id' => 1,
            'from'     => 2,
            'message'  => 'Lorem  ipsum dolor sit amet, consectetur adipiscing elit',
        ]);
    }
}
