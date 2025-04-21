<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'aldmic',
            'email' => 'aldmic',
            'password' => Hash::make('123abc123'),
        ]);
    }
}