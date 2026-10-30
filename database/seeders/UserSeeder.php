<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $fixedUser = [
            ['name' => 'Nyoman Yogi', 'email' => 'nyomanyogi@gmail.com', 'role' => 'user'],
            ['name' => 'Nyoman Yogi02', 'email' => 'nyomanyogi02@gmail.com', 'role' => 'seller'],
        ];

        foreach($fixedUser as $newUser) {
            User::firstOrCreate(
                ['email' => $newUser['email']],
                [
                    'name' => $newUser['name'],
                    'role' => $newUser['role'],
                    'password' => Hash::make("password")
                ]
            );
        }
    }
}
