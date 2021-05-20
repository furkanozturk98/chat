<?php

use Database\Seeders\FriendRequestSeeder;
use Database\Seeders\FriendSeeder;
use Database\Seeders\GroupInviteSeeder;
use Database\Seeders\GroupMemberSeeder;
use Database\Seeders\GroupMessageSeeder;
use Database\Seeders\GroupSeeder;
use Database\Seeders\MessageSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
             UserSeeder::class,
             FriendRequestSeeder::class,
             FriendSeeder::class,
             MessageSeeder::class,
             GroupSeeder::class,
             GroupMemberSeeder::class,
             GroupInviteSeeder::class,
             GroupMessageSeeder::class
         ]);
    }
}
