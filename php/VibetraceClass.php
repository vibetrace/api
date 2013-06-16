<?php
/*
If you are tracking events from your backend, the best way is to do it in a non-blocking method.
This will let the script continue to execute and still send data to vibetrace servers.

Feel free to modify this code to match your environment
*/

class EventTracker {
    public $token;
    public $baseHost = 'https://app.vibetrace.com/api/v3/apps/';
    public function __construct($clientToken, $apiKey, $apiToken) {
        $this->token = $clientToken;
        $this->apiKey = $apiKey;
        $this->apiToken = $apiToken;
        $this->host = $this->baseHost . $this->token . '/events/';
    }
    function track($event, $data=array()) {
        $auth = base64_encode($this->apiKey . ':' . $this->apiToken);
        $headers = array(
            'Authorization: Basic '.$auth,
            'Accept: application/json',
            'Content-Type: application/json'
        );

        //create the url for the event
        $url = $this->host . $event;
        $fieldsString = json_encode($data);

        //curl
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch,CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch,CURLOPT_POST, count($data));
        curl_setopt($ch,CURLOPT_POSTFIELDS, $fieldsString);

        //execute post
        $result = curl_exec($ch);

        //close connection
        curl_close($ch);
    }
}

?>