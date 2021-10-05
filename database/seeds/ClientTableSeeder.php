<?php

use Illuminate\Database\Seeder;

class ClientTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('clients')->insert([
            [
                'company_name' => 'company1',
                'name_kana' => 'かんぱにー1',
                'area_id' => 1,
            ],
            [
                'company_name' => 'company2',
                'name_kana' => 'かんぱにー2',
                'area_id' => 1,
            ],
            [
                'company_name' => 'company3',
                'name_kana' => 'かんぱにー3',
                'area_id' => 2,
            ],
            [
                'company_name' => 'company4',
                'name_kana' => 'かんぱにー4',
                'area_id' => 3,
            ],
            [
                'company_name' => 'company5',
                'name_kana' => 'かんぱにー5',
                'area_id' => 3,
            ],
            
        ]);
    }
}