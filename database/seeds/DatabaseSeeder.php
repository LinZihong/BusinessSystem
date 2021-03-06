<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		// $this->call(UsersTableSeeder::class);
        $this->call(UserSeeder::class);
		$this->call(ConfigSeeder::class);
		$this->call(ResourceSeeder::class);
		$this->call(UserRoleSeeder::class);
		$this->call(IntToValSeeder::class);
		$this->call(ZoneSeeder::class);
	}
}
