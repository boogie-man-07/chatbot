<?Php

class VacationInfo {

    function getVacationInfo($email) {

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_PORT => "80",
            CURLOPT_URL => "http://192.168.1.20/DA_ERP/hs/Staff/StaffData?EMAIL=$email&DATA=bot",
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
            if (json_last_error() === JSON_ERROR_NONE) {
                $result = json_decode($response, true);
                $restVacation = bcadd(fixComma($result['holiday_main']), fixComma($result['holiday_more']), 2);
                return "Количество оставшихся дней отпуска: $restVacation.";
            } else {
                return "Извините, информация по количеству оставшихся дней отпуска недоступна, попробуйте запросить позднее.";
            }
        }
    }

    function fixComma($text) {
        return str_replace(",", ".", $text);
    }
}

?>