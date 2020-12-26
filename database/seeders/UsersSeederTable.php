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
            'surname'=>'Gasparyan',
            'role'=>'admin',
            'type'=>'customer',
            'phone'=>37493373693,
            'address'=>'Abovyan',
            'email' => 'Hovo111196@gmail.com',
            'password' => Hash::make('asdfghjkl'),
        ]);
    }
}
