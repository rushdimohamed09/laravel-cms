<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $commonData = ['user_id'=> 1, 'created_at' => now(), 'updated_at' => now() ];
        $recordsToInsert = [
            ['name' => 'admin', 'is_user' => true],
            ['name' => 'user-own', 'is_user' => true],
            ['name' => 'employee', 'is_user' => true],
            ['name' => 'manager', 'is_user' => true],
            ['name' => 'roles', 'is_user' => false],
            ['name' => 'permissions', 'is_user' => false],
            ['name' => 'projects', 'is_user' => false],
            ['name' => 'tasks', 'is_user' => false],
        ];
        foreach ($recordsToInsert as &$record) {
            $record = array_merge($record, $commonData);
        }
        DB::table('roles')->insert($recordsToInsert);
    }
}
