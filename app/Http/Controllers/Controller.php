<?php

namespace App\Http\Controllers;

use App\Model\ResiCounter;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;


    public function getResiCounter()
    {
        $data = ResiCounter::first();
        $code = $data->code;
        // Angka yang ingin kamu format
        $counter = $data->counter;

        // Format angka menjadi 7 digit dengan nol di depannya
        $formattedNumber = str_pad($counter, 7, '0', STR_PAD_LEFT);

        return $formattedNumber; // Output: 0000001

    }

    public function incrementResiCounter()
    {
        $data = ResiCounter::first();
        $data->counter = $data->counter + 1;
        $data->save();

        return true;
    }
}
