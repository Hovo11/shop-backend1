<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersSeederTable extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'Hovo',
            'surname'=>'Gasparyan' ,
            'age'=>37,
            'type'=>'admin',
            'email' => 'Hovo111196@gmail.com',
            'password' => Hash::make('asdfghjkl'),
        ]);
    }
}
