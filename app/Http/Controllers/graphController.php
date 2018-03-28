<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Http\Requests;
use App\Http\Controllers\Controller;

include ('gChart/gchart/gChart.php' );
include ('gChart/gchart/gLineChart.php' );

class graphController extends Controller
{
public function index(){
	
	 $links = DB::table('balances')->orderBy('id')->get();
	 $i = 1;
	 $yr = '';
     foreach( $links as $key=>$val){

 		$tmp = explode('-', $val->date);

 		if( $tmp[0] < 2015 ){
 			unset($links[$key]);
 			continue;
		}
 		if( $tmp[0] != $yr || $i == sizeof($links)){
		
//                    $months[$i] = $tmp[1] ."/". str_replace('20', '', $tmp[0]);
                    $months[$i] = $tmp[1] ."/". str_replace('20', '', $tmp[0]);

                    $yr = $tmp[0];
        } else $months[$i] = '';

        $balance[$i] = $val->balance;
        $spent[$i] = $val->spent;
        $i ++;
	 }

    $lineChart = new \gchart\gLineChart(1000,300);
    
    $lineChart->addDataSet($balance);
    $lineChart->addDataSet($spent);

    $lineChart->setLegend(array("balance", "spendings"));
    $lineChart->setColors(array("ff3344", "11ff11"));

    $lineChart->setVisibleAxes(array('x','y'));
    $lineChart->setDataRange(0, max($balance));
    $lineChart->addAxisRange(0, 1, sizeof($balance), 1);
    $lineChart->addAxisRange(1, 0, max($balance) );
    
   $lineChart->addAxisLabel(0, $months);
//	$lineChart->addAxisLabel(1, array("Step6", "Step5", "Step4", "Step3", "Step2", "Step1"));

   $lineChart->addLineFill('A','76A4FB',0,0);
   $lineChart->setGridLines( 100 / ( sizeof($balance) -1), 17.3);
    
	return $lineChart->getImgCode();
	 
    $msg = 
'{
  "cols": [
        {"id":"","label":"Topping","pattern":"","type":"string"},
        {"id":"","label":"Slices","pattern":"","type":"number"}
      ],
  "rows": [
        {"c":[{"v":"Mushrooms","f":null},{"v":3,"f":null}]},
        {"c":[{"v":"Onions","f":null},{"v":1,"f":null}]},
        {"c":[{"v":"Olives","f":null},{"v":1,"f":null}]},
        {"c":[{"v":"Zucchini","f":null},{"v":1,"f":null}]},
        {"c":[{"v":"Pepperoni","f":null},{"v":2,"f":null}]}
      ]
}';
        return $msg;
    }
}
