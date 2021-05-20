<?php

namespace Database\Seeders;

use App\Models\GroupInvite;
use Illuminate\Database\Seeder;

class GroupInviteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        GroupInvite::query()->create([
            'from' => 1,
            'to' => 3,
            'group_id' => 1,
            'status' => 0
        ]);

        GroupInvite::query()->create([
            'from' => 1,
            'to' => 6,
            'group_id' => 1,
            'status' => 0
        ]);

        GroupInvite::query()->create([
            'from' => 1,
            'to' => 7,
            'group_id' => 1,
            'status' => 0
        ]);

        GroupInvite::query()->create([
            'from' => 1,
            'to' => 8,
            'group_id' => 3,
            'status' => 0
        ]);

        GroupInvite::query()->create([
            'from' => 4,
            'to' => 1,
            'group_id' => 4,
            'status' => 0
        ]);

        GroupInvite::query()->create([
            'from' => 5,
            'to' => 1,
            'group_id' => 5,
            'status' => 0
        ]);
    }
}
