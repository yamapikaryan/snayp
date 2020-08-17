<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\User;
use App\Role;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::truncate();
        DB::table('role_user')->truncate();

        $adminRole = Role::where('name', 'admin')->first();
        $managerRole = Role::where('name', 'manager')->first();
        $accountantRole = Role::where('name', 'accountant')->first();

        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@test.com',
            'password' => Hash::make('password')
        ]);

        $manager = User::create([
            'name' => 'Manager User',
            'email' => 'manager@test.com',
            'password' => Hash::make('password')
        ]);

        $accountant = User::create([
            'name' => 'Accountant User',
            'email' => 'accountant@test.com',
            'password' => Hash::make('password')
        ]);

        $admin->roles()->attach($adminRole);
        $manager->roles()->attach($managerRole);
        $accountant->roles()->attach($accountantRole);


    }
}
