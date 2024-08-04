<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->insert(
            [
                [
                    'name' => 'Admin',
                    'username' => 'admin',
                    'email' => 'admin@gmail.com',
                    'password' => Hash::make('123'),
                    'role' => 'admin',
                    'status' => '1',
                ],
                [
                    'name' => 'Instructor',
                    'username' => 'instructor',
                    'email' => 'instructor@gmail.com',
                    'password' => Hash::make('123'),
                    'role' => 'instructor',
                    'status' => '1',
                ],
                [
                    'name' => 'User',
                    'username' => 'user',
                    'email' => 'user@gmail.com',
                    'password' => Hash::make('123'),
                    'role' => 'user',
                    'status' => '1',
                ],
            ]
        );
    }
}
