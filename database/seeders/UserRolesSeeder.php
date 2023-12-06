<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserRolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $commonData = ['createdBy'=> 1, 'created_at' => now(), 'updated_at' => now() ];
        $recordsToInsert = [
            ['user_id' => 1, 'role_id' => 1]
        ];
        foreach ($recordsToInsert as &$record) {
            $record = array_merge($record, $commonData);
        }
        DB::table('user_roles')->insert($recordsToInsert);
    }
}
