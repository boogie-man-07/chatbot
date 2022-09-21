<?Php

$curl = curl_init();
curl_setopt_array($curl, array(
    CURLOPT_PORT => "11180",
    CURLOPT_URL => "http://office.diall.ru:11180/DA_ERP/hs/Staff/StaffData?EMAIL=ivanovds@diall.ru&DATA=bot",
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
    //echo "cURL Error #: ".$err;
    return "Извините, но что-то пошло не так, попробуйте повторить позднее.";
} else {
    $result = json_decode($response, true);
    $main = $this->fixComma($result['holiday_main']);
    $additional = $this->fixComma($result['holiday_more']);
    $restVacation = bcadd($main, $additional);
    echo $restVacation;
    return $restVacation;
}

?>