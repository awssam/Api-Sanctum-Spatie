<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            [
                'email'=>'awssam@awssam.com',
                'name'=>'awssam',
                'password'=>bcrypt(env('ADMIN_FIRST_PASSWORD'))
            ]
        ];

        foreach ($users as $user){
            $u = User::updateOrCreate(['email' => $user['email']], $user);
            $u->assignRole('super-admin');
        }


    }
}
