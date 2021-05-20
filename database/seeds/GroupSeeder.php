<?php

namespace Database\Seeders;

use App\Models\Group;
use Illuminate\Database\Seeder;

class GroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Group::query()->create([
            'name' => 'group1',
            'created_by' => 1,
            'image' => 'group.png'
        ]);

        Group::query()->create([
            'name' => 'group2',
            'created_by' => 2,
            'image' => 'group.png'
        ]);

        Group::query()->create([
            'name' => 'group3',
            'created_by' => 3,
            'image' => 'group.png'
        ]);

        Group::query()->create([
            'name' => 'group4',
            'created_by' => 4,
            'image' => 'group.png'
        ]);

        Group::query()->create([
            'name' => 'group5',
            'created_by' => 5,
            'image' => 'group.png'
        ]);
    }
}
