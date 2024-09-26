<?php

namespace App\Http\Traits;

trait SmsHandel {

    function sendSms($number, $msg) {
        $user    = env('SMS_USER');
        $pass    = env('SMS_PASS');
        $masking = "Info%20SMS";
        $url     = "http://services.smsnet24.com/sendSms?";
        $param   = "user_id=$user&user_password=$pass&route_id=1&sms_type_id=1&sms_sender=$masking&sms_receiver=88$number&sms_text=" . urlencode($msg) . "&sms_category_name=Promotion";
        $crl     = curl_init();
        curl_setopt($crl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($crl, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($crl, CURLOPT_URL, $url);
        curl_setopt($crl, CURLOPT_HEADER, 0);
        curl_setopt($crl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($crl, CURLOPT_POST, 1);
        curl_setopt($crl, CURLOPT_POSTFIELDS, $param);
        $response = curl_exec($crl);
        curl_close($crl);
        return $response;
    }

}
