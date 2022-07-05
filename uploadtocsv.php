<?Php

// STEP 1. Build connection
// Secure way to biuld connection

$file = parse_ini_file("../../Testbotdb.ini"); // accessing the file with connection info

// store in php var information from ini var
$host = trim($file["dbhost"]);
$user = trim($file["dbuser"]);
$pass = trim($file["dbpass"]);
$name = trim($file["dbname"]);
$token = trim($file["token"]);

// include access.php to call func from access.php file
require ("secure/access.php");
$access = new access($host, $user, $pass, $name);
$access->connect();

require ("logs/logs.php");

$result = Array();
$employeeList = Array();
$userFromDbResult = Array();

$curl = curl_init();
curl_setopt_array($curl, array(
    CURLOPT_PORT => "9081",
    CURLOPT_URL => "http://teta.gnhs.ru:9081/get_data.php",
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

    if (!$result['error']) {

        $fp = fopen('file.csv', 'w');

        foreach ($result['data'] as $users) {

            $user = json_decode($users['data'], true);
            fputcsv($fp, $user);
        }

        fclose($fp);

    } else {

        // TODO just add to logging
        //$logs = new logs();
        //$logs->logUpload("File for upoad is bad", null);

    }
}

?>