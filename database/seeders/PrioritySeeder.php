<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PrioritySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $commonData = ['user_id'=> 1, 'created_at' => now(), 'updated_at' => now() ];
        $recordsToInsert = [
            ['name' => 'Low'],
            ['name' => 'Normal'],
            ['name' => 'High'],
            ['name' => 'Urgent'],
            ['name' => 'Critical'],
        ];
        foreach ($recordsToInsert as &$record) {
            $record = array_merge($record, $commonData);
        }
        DB::table('priorities')->insert($recordsToInsert);
    }
}
