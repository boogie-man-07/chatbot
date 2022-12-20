<?Php

class HrLinkApiProvider {

    /*private var $bearerToken = null;
    private var $masterToken = null;

    function getApplicationTypes() {
        $bearerToken = generateBearerToken();
        $masterToken = generateMasterKey($bearerToken);

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

    function uploadFile() {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://hrlink.diall.ru/api/v1/files',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_POSTFIELDS => array('file'=> new CURLFILE('/Users/booogie-man-07/Downloads/star.png')),
            CURLOPT_HTTPHEADER => array(
                'User-Api-Token: 2799ee80-c1a6-40e0-a930-64ba4346c548',
                'Content-Type: multipart/form-data'
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


    function generateBearerToken() {
        $pk = file_get_contents('/var/www/sigmabot.ddns.net/pk.key');
        $pubk = file_get_contents('/var/www/sigmabot.ddns.net/pubk.pub');

        $privateKey = <<<EOD
        $pk
        EOD;

        $publicKey = <<<EOD
        $pubk
        EOD;

        $payload = [
            'iss' => 'DiallAlianceT',
            'sub' => '108da251-6c21-4105-80f2-99386f97a313',
            'aud' => 'esa.hr-link.ru',
            'exp' => time() + (60 * 5),
            'nbf' => time(),
            'iat' => time()
        ];

        $jwt = JWT::encode($payload, $privateKey, 'RS256');
        return $jwt;
    }

    function generateMasterKey($bearerToken) {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://esa.hr-link.ru/api/v1/masterTokens',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>'{
                "tenantHost": "hrlink.diall.ru"
            }',
            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer $bearerToken",
                "Content-Type: application/json"
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        if ($err) {
//             echo "cURL Error #: ".$err;
            $returnArray = array(
                'result' => false,
                'message' => 'Извините, но что-то пошло не так, попробуйте повторить позднее.'
            );
            return $returnArray;
        } else {
            $result = json_decode($response, true);
            $returnArray = array(
                'result' => $result['result'],
                'message' => $result['masterToken']
            );
            return $returnArray;
        }
    }*/
}

?>