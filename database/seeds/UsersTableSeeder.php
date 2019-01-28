<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        for($i = 0; $i < 10; $i++) {
            $num = rand(1, 100);
            DB::table('users')->insert([
                'name' => 'User num ' . $num,
                'email' => 'user' . $num . '@mail.ru',
                'password' => Hash::make(($num * $num + $num - 2 * ($num - 1))),
                'created_at' => Carbon::now()
            ]);
        }
    }
}
