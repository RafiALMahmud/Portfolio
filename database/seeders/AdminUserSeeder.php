<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminUserSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 */
	public function run(): void
	{
		User::updateOrCreate(
			['email' => 'rafi.almahmud.007@gmail.com'],
			[
				'name' => 'Admin',
				'phone' => null,
				'present_address' => null,
				'role' => 'admin',
				'password' => Hash::make('Rafi0008'),
				'email_verified_at' => now(),
			]
		);
	}
}
