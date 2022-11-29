<?Php

class HrLinkApiProvider {

    function getApplicationTypes() {

        $curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://hrlink.diall.ru/api/v1/applicationTypes',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'GET',
          CURLOPT_HTTPHEADER => array(
            'User-Api-Token: 6255b66b-33c6-424d-8d7f-e85b9e5fc162'
          ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);

        if ($err) {
            //echo "cURL Error #: ".$err;
            return "Извините, но что-то пошло не так, попробуйте повторить позднее.";
        } else {
            $result = json_decode($response, true);
            return $result;
        }
    }
}

?>