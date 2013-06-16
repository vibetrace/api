<?php
/*
If you are tracking events from your backend, the best way is to do it in a non-blocking method.
This will let the script continue to execute and still send data to vibetrace servers.

Feel free to modify this code to match your environment
*/

class EventTracker {
    public $token;
    public $host = 'https://api.vibetrace.com/';
    public function __construct($client_token) {
        $this->token = $client_token;
    }
    function track($event, $data=array()) {
        $params = array(
            'event' => $event,
            'data' => $data
            );

        if (!isset($params['data']['token'])){
            $params['data']['token'] = $this->token;
        }
        $url = $this->host . 'track/?data=' . base64_encode(json_encode($params));
        //You should run this as a background process
        //Please see PHP Expect at http://php.net/manual/en/book.expect.php
        exec("curl '" . $url . "' >/dev/null 2>&1 &"); 
    }
}

// Include and instantiate Vibetrace class
$vibetrace = new EventTracker("YOUR_TOKEN");

//track Purchase
$vibetrace->track('purchase', 
                    array('item'=>'candy', 'type'=>'snack'));


//track ViewItem


//track ViewCategory


//track Search

?>