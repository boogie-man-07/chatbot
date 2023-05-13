<?php

class ADApiProvider {

    function activate($email) {
        $login = $this->getLogin($email);
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_PORT => "9082",
            CURLOPT_URL => "http://office.diall.ru:9082/unlock.php?name=$login",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_TIMEOUT => 10,
            CURLOPT_CONNECTTIMEOUT = 10,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "cache-control: no-cache",
                "Content-Type: application/json"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
            return array(
                'result' => false,
                'message' => 'ERR_CONNECTION_TIMEOUT'
            );
        } else {
            $decodedResult = json_decode($response, true);
            return array(
                'result' => $decodedResult['result'],
                'message' => $decodedResult['message']
            );
        }
    }

    function getLogin($value) {
        return strtok($value, '@');
    }
}

?>