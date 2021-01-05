<?php
class Post {
    public $sl_token;
    public $page;
  
    function __construct($sl_token, $page) {
        $this->sl_token = $sl_token;
        $this->page = $page;
    }

    function get_posts ($url){
        $params = "?";
        $params .= "sl_token=".$this->sl_token;
        $params .= "&page=".$this->page;

        $result = file_get_contents($url.$params);
        $response = json_decode($result, true);
        
        return $response["data"]["posts"];
    }
}
?>