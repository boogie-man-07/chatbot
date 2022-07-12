<?Php

require '../Library/NCLNameCaseRu.php';

// STEP 1. Build connection
// Secure way to biuld connection
$file = parse_ini_file("../../../Testbotdb.ini"); // accessing the file with connection info

// store in php var information from ini var
$host = trim($file["dbhost"]);
$user = trim($file["dbuser"]);
$pass = trim($file["dbpass"]);
$name = trim($file["dbname"]);
$token = trim($file["token"]);
$nc = new NCLNameCaseRu();

// include access.php to call func from access.php file
require ("../secure/access.php");
$access = new access($host, $user, $pass, $name);
$access->connect();

require ("../logs/logs.php");

$curl = curl_init();
curl_setopt_array($curl, array(
    CURLOPT_PORT => "80",
    CURLOPT_URL => "http://192.168.1.20/DA_ERP/hs/Staff/StaffData?DATA=bot",
    CURLOPT_USERPWD => "Web1C:67z%Cc#2",
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

    echo "cURL Error #: ".$err;

} else {

    $result = json_decode($response, true);
    echo $result;


}

?>