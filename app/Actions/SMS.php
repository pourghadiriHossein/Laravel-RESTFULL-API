<?php

namespace App\Actions;

use Exception;
use Illuminate\Support\Facades\Log;

class SMS {

    protected const API_KEY = 'd7306d0e5e6cd9b89e11f7bfc6965811e606db1bde623ca0a6e60968577357b7';
    protected const TEMPLATE_NAME = 'poulstar';

    public static function sendSMS($receptor, $param1, $param2)
    {
        $curl = curl_init();

        curl_setopt_array($curl,
            array(
                CURLOPT_URL => "https://api.ghasedak.me/v2/verification/send/simple",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_FOLLOWLOCATION => 1,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTPAUTH => CURLAUTH_ANY,
                CURLOPT_SSL_VERIFYHOST => false,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => "receptor=".$receptor."&template=".self::TEMPLATE_NAME."&type=1&param1=".$param1."&param2=".$param2."",
                CURLOPT_HTTPHEADER => array(
                    "apikey: ".self::API_KEY,
                    "cache-control: no-cache",
                    "content-type: application/x-www-form-urlencoded",
                )
            )
        );
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            $response = json_decode($response, true);
            try {
                if($response['result']['code'] === 200) {
                    return true;
                }
            } catch(Exception $e) {
                Log::error(json_encode([$response, self::API_KEY]));
                return $response;
            }
        }
        return false;
    }
}
