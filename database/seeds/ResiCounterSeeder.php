<?php

use App\Model\ResiCounter;
use Illuminate\Database\Seeder;

class ResiCounterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ResiCounter::updateOrCreate([
            'id' => 1,
        ], [
            'code' => 0,
            'counter' => 0
        ]);
    }
}
