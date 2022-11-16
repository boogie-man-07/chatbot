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
            $convertedResult = $this->convertedResponse($result);
            return $convertedResult;
        }
    }

    function convertedResponse($response) {
        $isRotational = $response['Vahta'] == "1";
        $workingData = $response['Graphics'];
        $daysData = array();
        $totalWorkDays = 0;
        $totalWorkNights = 0;
        $totalDayWorkHours = 0.00;
        $totalNightWorkHours = 0.00;
        $dayEmoji = hex2bin("E29880");
        $nightEmoji = hex2bin("F09F8C99");
        foreach($workingData as $key=>$value) {
            if($value['VidVremeni'] == 'Вахта') {
                $totalWorkDays++;
                $totalDayWorkHours += floatval($value['Hours']);
            } else if ($value['VidVremeni'] == 'Ночные часы (вахта)') {
                $totalWorkNights++;
                $totalNightWorkHours += floatval($value['Hours']);
            }

            $separatedDate = substr($value['Date'], 0, 2);
            $dateNumber = $separatedDate[0] == "0" ? (int)$separatedDate[1] : (int)$separatedDate;
            array_push($daysData, array('dateNumber' => $dateNumber);
        }

        "dateNumber": ,
        "dayOfTheWeek": "Пн",
        "dayHours": 4,
        "nightHours": 7

        $returnArray = array(
            'isRotational' => $isRotational,
            'totalWorkDays' => $totalWorkDays,
            'totalWorkNights' => $totalWorkNights,
            'totalDayWorkHours' => $totalDayWorkHours,
            'totalNightWorkHours' => $totalNightWorkHours,
            'getFirstDayOfMonthWeekIndex' => $this->getFirstDayOfMonthsWeekIndex()
            'daysList' => $daysData;
        );

        return $returnArray;
    }

    function getFirstDayOfMonthsWeekIndex() {
        $firstDay = strtotime('first day of this month', time());
        $firstDayWeekDayName = date('D', $firstDay);
        switch ((string)$firstDayWeekDayName) {
            case 'Mon':
                return 0;
            case 'Tue':
                return 1;
            case 'Wed':
                return 2;
            case 'Thu':
                return 3;
            case 'Fri':
                return 4;
            case 'Sat':
                return 5;
            case 'Sun':
                return 6;
        }
    }
}

?>