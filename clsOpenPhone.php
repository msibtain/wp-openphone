<?php
class clsOpenPhone
{
    private $opnephone_api_key;
    private $openphone_api_url = "https://api.openphone.com";

    function __construct()
    {
        $this->opnephone_api_key = get_option("opnephone_api_key");

        add_action( 'user_register', [$this, 'es_user_register'], 10, 2 );
    }

    function es_user_register( $user_id, $userdata )
    {
        ob_start();
        echo "<pre>";
        print_r($userdata);
        echo "</pre>";
        $s = ob_get_clean();

        error_log("user registered open phone");
        error_log($s);
    }
}

new clsOpenPhone();