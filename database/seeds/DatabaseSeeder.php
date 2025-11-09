<?php

use App\Model\District;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);

        // $this->call(ResiCounterSeeder::class);

        $this->call(RegenciesSeeder::class);

        $this->call(DistrictSeeder::class);

        // $this->call(DistrictsTableSeeder::class);

    }
}
