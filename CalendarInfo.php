<?Php

class CalendarInfo {

    function getMonthlyData() {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_PORT => "11180",
            CURLOPT_URL => "http://office.diall.ru:11180/DA_ERP/hs/Staff/Grafic/?GUID=37e79227-62e3-11eb-a20a-00155d93a613&Month=01.11.2022",
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
            return "Извините, но что-то пошло не так, попробуйте повторить позднее.";
        } else {
            $result = json_decode($response, true);
            $convertedResult = convertedResponse($result);
            return $convertedResult;
        }
    }

    function convertedResponse($response) {
        $isRotational = $response['Vahta'] == "1";
        $workingData = $response['Graphincs'];
        $totalWorkDays = 0;
        $totalWorkNights = 0;
        $totalDayWorkHours = 0.00;
        $totalNightWorkHours = 0.00;
        $dayEmoji = hex2bin("E29880");
        $nightEmoji = hex2bin("F09F8C99");
        foreach($workingData as $key=>$value) {
            if($value['VidVremeni'] == 'Вахта') {
                $totalWorkDays++;
                $totalDayWorkHours += (float) $value['Hours'];
            } else if ($value['VidVremeni'] == 'Ночные часы (вахта)') {
                $totalWorkNights++;
                $totalNightWorkHours += (float) $value['Hours'];
            }
        }

        $returnArray = array(
            'isRotational' => $isRotational,
            'totalWorkDays' => $totalWorkDays,
            'totalWorkNights' => $totalWorkNights,
            'totalDayWorkHours' => $totalDayWorkHours,
            'totalNightWorkHours' => $totalNightWorkHours
        );

        return $returnArray;
    }
}

?>