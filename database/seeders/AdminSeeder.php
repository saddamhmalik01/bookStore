<?php

namespace Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name'              => 'Admin',
            'email'             => 'admin@admin.com',
            'email_verified_at'  => Carbon::now(),
            'password'          => Hash::make('123456789'),
            'is_admin'          => 1,
            'remember_token'    => 'HHKYKJ',
            'created_at'        => Carbon::now(),
            'updated_at'        => Carbon::now()

        ]);
    }
}
