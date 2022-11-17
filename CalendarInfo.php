<?Php

class CalendarInfo {

    function getMonthlyData($userId, $currentMonth) {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_PORT => "11180",
            CURLOPT_URL => "http://office.diall.ru:11180/DA_ERP/hs/Staff/Grafic/?GUID=$userId&Month=$currentMonth",
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
            return $this->convertedResponse($result);
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
        foreach($workingData as $key=>$value) {
            if($value['VidVremeni'] == 'Вахта') {
                $totalWorkDays++;
                $totalDayWorkHours += floatval($value['Hours']);
            } else if ($value['VidVremeni'] == 'Ночные часы (вахта)') {
                $totalWorkNights++;
                $totalNightWorkHours += floatval($value['Hours']);
            }

            $countedValue = $value['Date'];
            $dateNumber = substr($countedValue, 0, 1) == "0" ? substr(substr($countedValue, 0, 2), 1) : substr($countedValue, 0, 2);
            $isWorkingDay = $value['VidVremeni'] == 'Выходные дни' ? false : true;
            $hasWorkingNight = array_count_values(array_column($workingData, 'Date'))[$countedValue] > 1 ? true : false;
            $buttonText = $isWorkingDay ? ($hasWorkingNight ? hex2bin("F09F8C99") : hex2bin("E29880")) : $dateNumber;

            array_push($daysData, array(
                'dateNumber' => $dateNumber,
                'isWorkingDay' => $isWorkingDay,
                'hasWorkingNight' => $hasWorkingNight,
                'buttonText' => $buttonText
            ));
        }

        $uniqueDaysData = array_unique($daysData,SORT_REGULAR);

        $resultDaysData = array();
        for ($m = 0; $m <= count($uniqueDaysData); $m++) {
            array_push($resultDaysData, $uniqueDaysData[$m]);
        }

        $returnArray = array(
            'isRotational' => $isRotational,
            'totalWorkDays' => $totalWorkDays,
            'totalWorkNights' => $totalWorkNights,
            'totalDayWorkHours' => $totalDayWorkHours,
            'totalNightWorkHours' => $totalNightWorkHours,
            'firstDayOfMonthWeekIndex' => $this->getFirstDayOfMonthsWeekIndex(),
            'currentMonth' => $this->getMonthByIndex(),
            'daysList' => $resultDaysData
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

    function getMonthByIndex() {
        $firstDay = strtotime('first day of this month', time());
        $monthIndex = date('m', $firstDay);
        $yearIndex = date('Y', $firstDay);
        switch ((string)$monthIndex) {
            case '01':
                return "Январь $yearIndex";
            case '02':
                return "Февраль $yearIndex";
            case '03':
                return "Март $yearIndex";
            case '04':
                return "Апрель $yearIndex";
            case '05':
                return "Май $yearIndex";
            case '06':
                return "Июнь $yearIndex";
            case '07':
                return "Июль $yearIndex";
            case '08':
                return "Август $yearIndex";
            case '09':
                return "Сентябрь $yearIndex";
            case '10':
                return "Октябрь $yearIndex";
            case '11':
                return "Ноябрь $yearIndex";
            case '12':
                return "Декабрь $yearIndex";
        }
    }
}

?>