<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Transport;


class TransportController extends Controller
{
    protected $db;
    
    function __construct(){
    
        $this->middleware('auth');
        
        $this->db['name'] = 'test';
        $this->db['user'] = 'admin';
        $this->db['pass'] = '123123';
    }

    public function getPage()
    {
        $transport = new Transport($this->db);
       
        $vehicles = $transport->select('vehicle')->groupBy('vehicle')->get();
        $years  = $transport->selectRaw('YEAR(act_date) AS year')->groupBy('year')->get();

        return view('transport')->with('vehicles', json_encode([$vehicles, $years]));
    }
    
    public function getRecords(Request $request)
    {
        $records = $this->filter($request);
    
        $parts = 0;
        $gas = 0;
        
        foreach($records as $v){
            if( strstr( strtolower($v->parts), 'gas')) $gas += $v->amount;
            else $parts += $v->amount;
        }
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
        
        $transport = new Transport($this->db);

        $record_id = $request['recordId'];
        
        if((int)$record_id > 0){
   // Update     

            $record = $transport->find($record_id);
            $record->vehicle     = $request['vehicle'];
            $record->parts       = $request['parts'];
            $record->amount      = $request['amount'];
            $record->comment     = $request['comment'];
            $record->act_date    = $request['act_date'];

            $record->update();

            return response()->json([
                'vehicle' => $record->vehicle, 
                'parts' => $record->parts, 
                'amount' => $record->amount, 
                'comment' => $record->comment, 
                'act_date' => $record->act_date
                ], 200);
        } else {
    // Insert
            $transport->vehicle     = $request->input('vehicle');
            $transport->parts       = $request->input('parts');
            $transport->amount      = $request->input('amount');
            $transport->comment     = strval($request->input('comment'));
            $transport->act_date    = $request->input('act_date');
            
        // Save message    
            $transport->save();
            
        // Redirect   
            return response()->json([
                'redirect' => 'reload' 
            ], 200);
        }
    }
    
    public function filter(Request $request)
    {
        $transport = (new Transport($this->db))->newQuery();
        
        if( $request->has('vehicle')) {
             $transport->where('vehicle', '=', $request->input('vehicle'));
        }

        if( $request->has('year')) {
             $transport->whereRaw('YEAR(act_date) = '. $request->input('year'));
        }

        
        if( $request->has('month')) {
         
            $transport->where( function($transport) use ($request)
            {     
                $i = 0;       
                    foreach ( explode('|', $request->input('month')) as $mo ){
                        
                        if($i == 0)
                            $transport->whereRaw('MONTH(act_date) = '. $mo);
                        else 
                            $transport->orWhereRaw('MONTH(act_date) = '. $mo);
                            $i ++;
                    }
            });
        }
        return $transport->get();
    }
}













