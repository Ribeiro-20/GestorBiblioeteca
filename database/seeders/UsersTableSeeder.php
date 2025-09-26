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
            'nome' => 'José',
            'email' => 'jose@gmail.com',
            'password' => Hash::make('senha123'), // Bcrypt automático
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
