<?php

use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'test-user',
            'email' => '111@example.com',
            'password' => bcrypt('test'),
            'sys_admin' => 1,
            'dep_id' => 1,
            'pos_id' => 1,
        ]);
    }
}