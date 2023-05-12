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
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 300,
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
            return json_encode(array(
                'result' => false,
                'message' => 'Ошибка разблокировки учетной записи.'
            ));
        } else {
            return $response;
        }
    }

    function getLogin($value) {
        return strtok($value, '@');
    }
}

?>