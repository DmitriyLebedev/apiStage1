<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class BalancesInsertController extends Controller
{
/*
	public function insertform(){
		return view('stud_create');
	}

	public function insert(Request $request){

*/
	public function insertform(){
//		$name = $request->input('stud_name');
		$tmp = '';
// var_dump($request);
//		$tmp = file_get_contents('chart.csv');
		foreach(explode("\n", $tmp) as $val){
		
		
				if( strlen( $val ) < 5 ) continue;
				$val = explode(',',  $val);
		
		 		if( $val[1] == '-')$val[1] = '0';
				if( !isset($val[2]) || $val[2] == '-')$val[2] = '0';
				$date = explode( '/', $val[0] );
		
				DB::insert('insert into balances (date, balance, spent) values("'. (strstr( $date[1], '9') ? '19'. $date[1] :'20'. $date[1] ) .'-'.$date[0].'-02", '.$val[1].', '.$val[2].')');
		}

	}
}
