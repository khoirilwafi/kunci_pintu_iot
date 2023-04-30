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
        // insert moderator
        DB::table('users')->insert([
            'id'                => Uuid::uuid4(),
            'name'              => 'Muhammad Khoiril Wafi',
            'email'             => 'wafienginer@gmail.com',
            'phone'             => '083116291606',
            'gender'            => 'laki-laki',
            'password'          => Hash::make('password'),
            'role'              => 'moderator',
            'created_at'        => Carbon::now(),
            'updated_at'        => Carbon::now(),
        ]);
    }
}
