<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('users')->insert([
            'nome' => 'jorge',
            'email' => 'jorge@gmail.com',
            'password' => Hash::make('jorge@gmail.com'), // Bcrypt automático
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
