<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\User;
use Ramsey\Uuid\Uuid;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([

            'id'                => Uuid::uuid4(),
            'name'              => 'Muhammad Khoiril Wafi',
            'email'             => 'wafienginer@gmail.com',
            'gender'            => 'Laki-laki',
            'email_verified_at' => Carbon::now(),
            'password'          => Hash::make('password'),
            'role'              => 'moderator',
        ]);

        // for ($index = 0; $index < 50; $index++) {

        //     $id        = Uuid::uuid4();
        //     $name      = 'User_' . ($index + 1);
        //     $jenis_kel = ($index % 2 == 0) ? 'Laki-laki' : 'Perempuan';
        //     $email     = 'user_' . ($index + 1) . '@gmail.com';
        //     $password  = Hash::make('password');
        //     $role      = ($index % 2 == 0) ? 'operator' : 'pengguna';

        //     User::create([

        //         'id'                => $id,
        //         'name'              => $name,
        //         'gender'            => $jenis_kel,
        //         'email'             => $email,
        //         'email_verified_at' => Carbon::now(),
        //         'password'          => $password,
        //         'role'              => $role,
        //     ]);
        // }
    }
}
