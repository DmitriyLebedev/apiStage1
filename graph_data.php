<?php

$months = array (1=>'January','February','March','April','May','June','July','August','September','October','November','December','January');
$report_type_reports_names = array('history'=>'2016 Billed', 'monthly'=>'By Month 2016');

$DataManager = require_once 'lib/class.DataManager.php';

echo $DataManager->getMonthlyJson(); 
