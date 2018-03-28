<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;

class Balances extends Model
{
    function __construct(){
    	$this->connection = Config::get('second_connection');
    }
}
