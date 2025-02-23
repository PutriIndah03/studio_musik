<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'nama' => 'Pembina',
                'username' => '198603052024211014',
                'password' => Hash::make('198603052024211014'),
                'role' => 'pembina'
            ],
            [
                'nama' => 'Mahasiswa',
                'username' => '362155401001',
                'password' => Hash::make('362155401001'),
                'role' => 'mahasiswa'
            ],
            [
                'nama' => 'Staf',
                'username' => '362155401002',
                'password' => Hash::make('362155401002'),
                'role' => 'staf'
            ]
        ]);
    }
}
