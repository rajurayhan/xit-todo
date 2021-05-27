<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UerTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = [
            'name'          => 'Raju Rayhan',
            'email'         => 'send2raju.bd@gmail.com',
            'password'      => bcrypt('raju@2020'),
        ];

        User::create($user);
    }
}
