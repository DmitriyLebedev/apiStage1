<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Money;


class MoneyController extends Controller
{
    function __construct(){

    }

    public function getPage()
    {
        $money = new Money();
       
        $types = $money->select('type')->groupBy('type')->get();
        $years  = $money->selectRaw('YEAR(act_date) AS year')->groupBy('year')->get();

        return view('money')->with('types', json_encode([$types, $years]));
    }
    
    public function getRecords(Request $request)
    {
        $records = $this->filter($request);
    
        $parts = 0;
        $gas = 0;
/*        
        foreach($records as $v){
            if( strstr( strtolower($v->parts), 'gas')) $gas += $v->amount;
            else $parts += $v->amount;
        }*/

        $to_return = [
            'data'  => $records,
            'gas'   => $gas,
            'parts' => $parts
        ];
        return json_encode( $to_return );
    }
    
    public function postRecord(Request $request)
    {
        $this->validate($request, [
            'recordId' => 'required'
        ]);
        
        $money = new Money();

        $record_id = $request['recordId'];
        
        if((int)$record_id > 0){
   // Update     

            $record = $money->find($record_id);
            $record->type     = $request['type'];
            $record->amount      = $request['amount'];
            $record->source      = $request['source'];
            $record->comment     = $request['comment'];
            $record->act_date    = $request['act_date'];

            $record->update();

            return response()->json([
                'type' => $record->type, 
                'amount' => $record->amount, 
                'source' => $record->source, 
                'comment' => $record->comment, 
                'act_date' => $record->act_date
                ], 200);
        } else {
    // Insert
            $money->type     = $request->input('type');
            $money->amount      = $request->input('amount');
            $money->source     = strval($request->input('source'));
            $money->comment     = strval($request->input('comment'));
            $money->act_date    = $request->input('act_date');
            
        // Save message    
            $money->save();
            
        // Redirect   
            return response()->json([
                'redirect' => 'reload' 
            ], 200);
        }
    }
    
    public function filter(Request $request)
    {
        $money = (new Money())->newQuery();
        
        if( $request->has('type')) {
             $money->where('type', '=', $request->input('type'));
        }

        if( $request->has('year')) {
             $money->whereRaw('YEAR(act_date) = '. $request->input('year'));
        }

        
        if( $request->has('month')) {
         
            $money->where( function($money) use ($request)
            {     
                $i = 0;       
                    foreach ( explode('|', $request->input('month')) as $mo ){
                        
                        if($i == 0)
                            $money->whereRaw('MONTH(act_date) = '. $mo);
                        else 
                            $money->orWhereRaw('MONTH(act_date) = '. $mo);
                            $i ++;
                    }
            });
        }
        return $money->get();
    }
}













