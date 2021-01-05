<?php
class Register {
    public $client_id;
    public $name;
    public $email;
  
    function __construct($client_id, $email, $name) {
        $this->client_id = $client_id;
        $this->email = $email;
        $this->name = $name;
    }

    function register_user($url) {
        $data = array(
            'client_id' => $this->client_id,
            'email' => $this->email,
            'name' => $this->name
        );

        // use key 'http' even if you send the request to https://...
        $options = array(
            'http' => array(
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'method'  => 'POST',
                'content' => http_build_query($data)
            )
        );
        $context  = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        $response = json_decode($result, true);
        return $response;
    }

    function get_sl_token($response_data){
        return $response_data["data"]["sl_token"];
    }
}
  
?>