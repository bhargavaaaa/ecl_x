<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\Country;
use App\Models\Role;
use App\Models\State;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (!User::where('id', 1)->exists()) {
            $user = User::create([
                'name' => 'Administrator',
                'email' => 'admin@gmail.com',
                'phone' => '9998887775',
                'password' => bcrypt('123456'),
                'country_id' => Country::where('name', 'india')->value('id'),
                'state_id' => State::where('name', 'gujarat')->value('id'),
                'city_id' => City::where('name', 'rajkot')->value('id'),
                'address' => "Soni Bazar, Rajkot."
            ]);

            $role = Role::create([
                'name' => 'Admin'
            ]);

            $user->syncRoles($role);
        }
    }
}
