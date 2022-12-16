<?Php

include ('vendor/autoload.php');

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

generateBearerToken();


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
    echo $jwt;
    // $decoded = JWT::decode($jwt, new Key($publicKey, 'RS256'));

    /*
     NOTE: This will now be an object instead of an associative array. To get
     an associative array, you will need to cast it as such:
    */

    // $decoded_array = (array) $decoded;
    // echo "Decode:\n" . print_r($decoded_array, true) . "\n";
}



?>