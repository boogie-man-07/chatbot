<?Php

class CalendarInfo {

    function getMonthlyDataForEmployee() {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_PORT => "11180",
            CURLOPT_URL => "http://dev1/da_erp_copy3/hs/Staff/Grafic?GUID=37e79227-62e3-11eb-a20a-00155d93a613&Month=01.11.2022",
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
            return $result;
        }
    }

    function getMonthlyDataForOffice() {
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_PORT => "11180",
                CURLOPT_URL => "http://dev1/da_erp_copy3/hs/Staff/Grafic?GUID=a16f8694-e7f9-11eb-a1b1-d4f5ef044d5e&Month=01.11.2022",
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
                return $result;
            }
        }
}

?>