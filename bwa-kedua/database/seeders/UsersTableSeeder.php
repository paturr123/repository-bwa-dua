<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $users = [
            [
                'name'               => 'John Doe',
                'email'              => 'John@mail.com',
                'password'           => Hash::make('Admin@12345'),
                'remember_token'     => NULL,
                'created_at'         => date('Y-m-d h:i:s'),
                'updated_at'         => date('Y-m-d h:i:s'),
            ],
            [
                'name'               => 'Jane Doe',
                'email'              => 'Jane@mail.com',
                'password'           => Hash::make('Admin@12345'),
                'remember_token'     => NULL,
                'created_at'         => date('Y-m-d h:i:s'),
                'updated_at'         => date('Y-m-d h:i:s'),
            ],
        ];

        User::insert($users);
    }
}