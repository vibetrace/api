<?php
/*
If you are tracking events from your backend, the best way is to do it in a non-blocking method.
This will let the script continue to execute and still send data to vibetrace servers.

Feel free to modify this code to match your environment
*/


// Include and instantiate Vibetrace class
require_once('VibetraceClass.php');
$appId = "YOUR-APP-ID"; 
$apiKey = "YOUR-API-KEY";
$apiSecret = "YOUR-API-SECRET";
$vibetrace = new EventTracker($appId, $apiKey, $apiSecret);

//track events
$vibetrace->track('compare', 
                    array(
                        'policybegin'=>'1371772800',  //timestamp
                        'policyend'=>'1390176000', //timestamp
                        'name'=>'Ion Popescu', //string
                        'email'=>'email-example@yahoo.com', //string
                        'gender'=>'M', //string
                        'county'=>'Chicago', //string
                        'locality'=>'Chicago', //string
                        'birthdate'=>'555724800', //timestamp
                        'vin'=>'1HGCM82633A004352', //string
                        'manufacturer'=>'Honda', //string
                        'model'=>'CRX' //string
                    ));
//track events
$vibetrace->track('fc3', 
                    array(
                        'email'=>'email-example@yahoo.com', //string
                        'vin'=>'1HGCM82633A004352' //string
                    ));
//track events
$vibetrace->track('purchase', 
                    array(
                        'policybegin'=>'1371772800',  //timestamp
                        'policyend'=>'1390176000', //timestamp
                        'name'=>'Ion Popescu', //string
                        'email'=>'email-example@yahoo.com', //string
                        'gender'=>'M', //string
                        'county'=>'Chicago', //string
                        'locality'=>'Chicago', //string
                        'birthdate'=>'555724800', //timestamp
                        'vin'=>'1HGCM82633A004352', //string
                        'manufacturer'=>'Honda', //string
                        'model'=>'CRX', //string
                        'boughtonline'=>true, //boolean
                        'insurer'=>'ASIROM', //string
                        'price'=>1232.20, //float
                    ));



?>