<?php

namespace Database\Seeders;

use App\Models\GroupMember;
use Illuminate\Database\Seeder;

class GroupMemberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        GroupMember::query()->create([
            'group_id' => 1,
            'member_id' => 1,
            'type' => 2
        ]);

        GroupMember::query()->create([
            'group_id' => 1,
            'member_id' => 2,
            'type' => 0
        ]);

        GroupMember::query()->create([
            'group_id' => 2,
            'member_id' => 2,
            'type' => 2
        ]);

        GroupMember::query()->create([
            'group_id' => 2,
            'member_id' => 1,
            'type' => 0,
        ]);

        GroupMember::query()->create([
            'group_id' => 3,
            'member_id' => 3,
            'type' => 2,
        ]);

        GroupMember::query()->create([
            'group_id' => 3,
            'member_id' => 1,
            'type' => 1,
        ]);

        GroupMember::query()->create([
            'group_id' => 4,
            'member_id' => 4,
            'type' => 2,
        ]);

        GroupMember::query()->create([
            'group_id' => 5,
            'member_id' => 5,
            'type' => 2,
        ]);
    }
}
