<?php

use Illuminate\Database\Seeder;

class AreaTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('areas')->insert([
            [
                'name' => 'area1',
                'kana' => 'えりあ1',
                'group_id' => 1,
            ],
            [
                'name' => 'area2',
                'kana' => 'えりあ2',
                'group_id' => 1,
            ],
            [
                'name' => 'area3',
                'kana' => 'えりあ3',
                'group_id' => 2,
            ],
            
        ]);
    }
}