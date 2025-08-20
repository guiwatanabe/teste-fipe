<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        DB::table('users')->insertOrIgnore([
            'id' => 1,
            'name' => 'API User',
            'email' => 'api-user@test.com',
            'password' => Hash::make('password'),
        ]);
    }
}
