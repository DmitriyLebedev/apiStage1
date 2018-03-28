<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Balances;

class BalancesController extends Controller
{
    function getPage(){

        $Balances = new Balances();
        $records = $Balances->orderBy('date', 'desc')->get();

        return view('balances')->with('records', $records);
    }
}
