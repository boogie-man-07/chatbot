<?Php

// require ("logs/logs.php");

$curl = curl_init();
curl_setopt_array($curl, array(
    CURLOPT_PORT => "9081",
    CURLOPT_URL => "http://62.105.147.18:9081/get_data.php",
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

        $fp = fopen('file_diall.csv', 'w');

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