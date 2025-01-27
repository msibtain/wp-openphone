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

        error_log("adding contact to open phone api");
        error_log($s);

        $this->createContact( @$userdata['user_email'], @$userdata['first_name'], @$userdata['last_name'] );
    }

    private function createContact( $email = '', $first_name = '', $last_name = '' )
    {
        if (!$email) return;
        
        $curl = curl_init();
        
        curl_setopt_array($curl, array(
          CURLOPT_URL => $this->openphone_api_url . '/v1/contacts',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS =>'{
          "defaultFields": {
            "company": "5Star",
            "emails": [
              {
                "name": "user email",
                "value": "' . $email . '"
              }
            ],
            "firstName": "' . $first_name . '",
            "lastName": "' . $last_name . '"
          },
          "source": "public-api"
        }',
          CURLOPT_HTTPHEADER => array(
            'Authorization: ' . $this->opnephone_api_key,
            'Content-Type: application/json'
          ),
        ));
        
        $response = curl_exec($curl);
        curl_close($curl);

        ob_start();
        echo "<pre>";
        print_r( json_decode($response) );
        echo "</pre>";
        $s = ob_get_clean();

        error_log("open phone api response: ");
        error_log($s);

    }
}

new clsOpenPhone();