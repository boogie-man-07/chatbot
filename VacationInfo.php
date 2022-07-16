<?Php

class VacationInfo {

    function getVacationInfo($email) {

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_PORT => "80",
            CURLOPT_URL => "http://192.168.1.20/DA_ERP/hs/Staff/StaffData?EMAIL=kirilovoi@diall.ru&DATA=bot",
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
            return "Извините, информация по количеству оставшегося отпуска недоступна, попробуйте запросить позднее";
        } else {

            $result = json_decode($response, true);
            //echo $response;
            return (string) ((int) $response['holiday_main'] + (int) $response['holiday_more']);

        }
    }
}

?>