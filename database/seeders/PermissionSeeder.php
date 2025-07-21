<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = config()->get('permissions.permissions');
        foreach ($permissions as $group => $groups) {
            foreach ($groups as $type => $permission) {
                foreach ($permission as $key => $value) {
                    Permission::updateOrCreate(['name' => $key, 'guard_name' => 'web'], ['group_name' => $group, 'sub_group_name' => $type, 'description' => $value]);
                }
            }
        }
    }
}
