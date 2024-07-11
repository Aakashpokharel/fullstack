<?php

namespace Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'superadmin',
            'email' => 'curiousbrowser0@gmail.com',
            'password' => bcrypt('password'),
            // 'first_time_login' => Carbon::now(),
            'api_token' => Str::random(64),
        ]);
    }
}
