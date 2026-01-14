<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $password = Hash::make('12345678');

        $users = [
            ['name' => 'Rémy', 'email' => 'r.rouyer@laposte.net'],
            ['name' => 'Cali', 'email' => 'cbun@live.fr'],
            ['name' => 'Marion', 'email' => 'marion.weller77@gmail.com'],
            ['name' => 'Dulce', 'email' => 'dbrochard@briecomterobert.fr'],
            ['name' => 'Isabelle', 'email' => 'idermigny@gmail.com'],
            ['name' => 'sandra', 'email' => 'sandra.bbaillon@laposte.net'],
            ['name' => 'Muriel', 'email' => 'muriel.tsr@gmail.com'],
        ];

        foreach ($users as $user) {
            User::updateOrCreate(
                ['email' => $user['email']],
                [
                    'name' => $user['name'],
                    'password' => $password,
                    'role' => 'admin',
                ]
            );
        }
    }
}
