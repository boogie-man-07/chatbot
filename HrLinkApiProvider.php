<?Php

class HrLinkApiProvider {

    function getApplicationTypes() {

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_PORT => "443",
            CURLOPT_URL => "https://hrlink.diall.ru/api/v1/applicationTypes",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 300,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => {},
            CURLOPT_HTTPHEADER => array(
                "cache-control: no-cache",
                "Content-Type: application/json",
                "User-Api-Token": "6255b66b-33c6-424d-8d7f-e85b9e5fc162"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            //echo "cURL Error #: ".$err;
            return "Извините, но что-то пошло не так, попробуйте повторить позднее.";
        } else {
//             $result = json_decode($response, true);
//             $main = $this->fixComma($result['holiday_main']);
//             $additional = $this->fixComma($result['holiday_more']);
//             $restVacation = bcadd($main, $additional);
            return $response;
        }
    }
}

?>