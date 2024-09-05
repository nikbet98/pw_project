<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'firstname' => 'Admin',
            'lastname' => 'User',
            'email' => 'admin@admin.com',
            'password' => bcrypt('password'),
            'role' => 'admin', 
        ]);

        User::create([
            'firstname' => 'Nicola',
            'lastname' => 'Bettinzoli',
            'email' => 'nikbet98@gmail.com',
            'password' => bcrypt('password'),
            'role' => 'user', 
        ]);
        
    }
}
