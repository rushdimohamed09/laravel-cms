<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $commonData = ['user_id'=> 1, 'created_at' => now(), 'updated_at' => now() ];
        $recordsToInsert = [
            ['name' => 'admin'],

            ['name' => 'user-own:view'],
            ['name' => 'user-own:edit'],
            ['name' => 'user-own:delete'],

            ['name' => 'employee:view'],
            ['name' => 'employee:add'],
            ['name' => 'employee:edit'],
            ['name' => 'employee:delete'],

            ['name' => 'manager:view'],
            ['name' => 'manager:add'],
            ['name' => 'manager:edit'],
            ['name' => 'manager:delete'],

            ['name' => 'roles:view'],
            ['name' => 'roles:add'],
            ['name' => 'roles:edit'],
            ['name' => 'roles:delete'],

            ['name' => 'permissions:view'],
            ['name' => 'permissions:add'],
            ['name' => 'permissions:edit'],
            ['name' => 'permissions:delete'],

            ['name' => 'projects:view'],
            ['name' => 'projects:add'],
            ['name' => 'projects:edit'],
            ['name' => 'projects:delete'],

            ['name' => 'tasks:view'],
            ['name' => 'tasks:add'],
            ['name' => 'tasks:edit'],
            ['name' => 'tasks:delete'],

            ['name' => 'dashboard:view'],

            ['name' => 'inquiries:view'],
            ['name' => 'inquiries:add'],
            ['name' => 'inquiries:edit'],
            ['name' => 'inquiries:delete'],
        ];
        foreach ($recordsToInsert as &$record) {
            $record = array_merge($record, $commonData);
        }
        DB::table('permissions')->insert($recordsToInsert);
    }
}
