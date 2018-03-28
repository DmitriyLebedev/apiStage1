<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;

class Transport extends Model
{
    function __construct(){
    	$this->connection = Config::get('second_connection');
    }
}
