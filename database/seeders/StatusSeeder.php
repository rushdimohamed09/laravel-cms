<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $commonData = ['user_id'=> 1, 'created_at' => now(), 'updated_at' => now() ];
        $recordsToInsert = [
            ['name' => 'Open'],
            ['name' => 'In Progress'],
            ['name' => 'On Hold'],
            ['name' => 'Completed'],
            ['name' => 'Cancelled'],
            ['name' => 'Overdue'],
        ];
        foreach ($recordsToInsert as &$record) {
            $record = array_merge($record, $commonData);
        }
        DB::table('statuses')->insert($recordsToInsert);
    }
}
