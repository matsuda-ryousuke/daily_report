<?php

use Illuminate\Database\Seeder;

class Area_groupTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('area_groups')->insert([
            [
                'name' => 'areagroup1',
                'kana' => 'えりあぐるーぷ1',
            ],
            [
                'name' => 'areagroup2',
                'kana' => 'えりあぐるーぷ2',
            ],
        ]);
    }
}