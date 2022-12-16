<?Php

include ('vendor/autoload.php');

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

$bearerToken = generateBearerToken();
generateMasterKey($bearerToken);

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
//     echo "Encode:\n" . print_r($jwt, true) . "\n";
    return $jwt;
    // $decoded = JWT::decode($jwt, new Key($publicKey, 'RS256'));

    /*
     NOTE: This will now be an object instead of an associative array. To get
     an associative array, you will need to cast it as such:
    */

    // $decoded_array = (array) $decoded;
    // echo "Decode:\n" . print_r($decoded_array, true) . "\n";
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
        echo "cURL Error #: ".$err;
//         return "Извините, но что-то пошло не так, попробуйте повторить позднее.";
    } else {
        echo $response;
    }
}



?>