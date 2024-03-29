﻿<?Php

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

        

        foreach ($result['data'] as $users) {
            

            $user = json_decode($users['data'], true);

            if ($user['Holiday_main'] <= 0) {
                $holiday_main = 0;
            } else {
                $holiday_main = round($user['Holiday_main'], 0);
            }

            if ($user['Holiday_more'] <= 0) {
                $holiday_more = 0;
            } else {
                $holiday_more = round($user['Holiday_more'], 0);
            }

            $userItem = array(
                'firstname' => $user['firstname'],
                'lastname' => $user['lastname'],
                'fullname' => $user['fullname'],
                'position' => $user['position'],
                'email' => $user['email'],
                'office_number' => $user['office_number'],
                'internal_number' => $user['internal_number'],
                'mobile_number' => $user['mobile_number'],
                'company_name' => $user['company_name'],
                'company_id' => $user['company_id'],
                'boss' => $user['boss'],
                'main_holliday_counter' => $holiday_main,
                'additional_holliday_counter' => $holiday_more,
                'activity' => $user['activity']
            );
            array_push($employeeList, $userItem);
        }

    } else {

        // TODO just add to logging
        $logs = new logs();
        $logs->logUpload("File for upoad is bad", null);

    }
}

// $userFromDbResult = $access->getAllUsersFromDb();

$logs = new logs();


foreach ($employeeList as $employeeValue) {

    echo json_encode($employeeValue, true);

    // Проверяем активных сотрудников офиса
    if ($employeeValue['email'] != null && $employeeValue['mobile_number'] != null && $employeeValue['activity'] == 1) {

        $userFromDbResult = $access->getUserByPersonnelNumber($employeeValue['email']);

        if ($userFromDbResult) {

            // Если есть и в выгрузке и в БД - обновляем в БД по email 
            echo $employeeValue['email']." сотрудник офиса существует и в файле, и в БД, обновляем в БД<br>";

            $access->updateEmployeeByEmail(
                $employeeValue['firstname'],
                $employeeValue['lastname'],
                $employeeValue['fullname'],
                $employeeValue['position'],
                $employeeValue['office_number'],
                $employeeValue['internal_number'],
                $employeeValue['mobile_number'],
                $employeeValue['company_name'],
                $employeeValue['company_id'],
                $employeeValue['boss'],
                $employeeValue['main_holliday_counter'],
                $employeeValue['additional_holliday_counter'],
                $employeeValue['email']
            );

            $logs->logUpload($employeeValue['email']." сотрудник офиса существует и в файле, и в БД, обновляем в БД", $employeeValue['email']);

        } else {

            // Если есть в выгрузке, но нет в БД - добавляем в БД по email
            echo $employeeValue['email']." существует в файле, но отсутствует в БД, добавляем в БД<br>";

            $access->insertEmployee(
                $employeeValue['lastname'],
                $employeeValue['firstname'],
                $employeeValue['fullname'],
                $employeeValue['position'],
                $employeeValue['email'],
                $employeeValue['office_number'],
                $employeeValue['internal_number'],
                $employeeValue['mobile_number'],
                $employeeValue['company_id'],
                $employeeValue['company_name'],
                $employeeValue['boss'],
                $employeeValue['main_holliday_counter'],
                $employeeValue['additional_holliday_counter']
            );

            $logs->logUpload($employeeValue['email']." сотрудник существует в файле, но отсутствует в БД, добавляем в БД", $employeeValue['email']);
        }

    // Проверяем активных овощеводов
    } /* else if ($employeeValue['email'] == null && $employeeValue['mobile_number'] != null && $employeeValue['activity'] == 1) {

        $userFromDbResult = $access->getUserByPhoneNumber($employeeValue['mobile_number']);

        if ($userFromDbResult) {

            // Если есть и в выгрузке и в БД - обновляем в БД по мобильному
            echo $employeeValue['mobile_number']." овощевод существует в файле и существует в БД. Обновляем в БД по мобильному.<br>";

            $access->updateEmployeeByMobileNumber(
                $employeeValue['firstname'],
                $employeeValue['lastname'],
                $employeeValue['fullname'],
                $employeeValue['position'],
                $employeeValue['office_number'],
                $employeeValue['internal_number'],
                $employeeValue['company_name'],
                $employeeValue['company_id'],
                $employeeValue['boss'],
                $employeeValue['main_holliday_counter'],
                $employeeValue['additional_holliday_counter'],
                $employeeValue['email']
            );

            $logs->logUpload($employeeValue['mobile_number']." овощевод существует в файле и существует в БД. Обновляем в БД по мобильному.", $employeeValue['mobile_number']);

        } else {
    
            // Если есть и в выгрузке, но нет в БД - добавляем в БД по мобильному
            echo $employeeValue['mobile_number']." овощевод существует в файле, но отсутствует в БД. Добавляем в БД по мобильному.<br>";

            $access->insertEmployee(
                $employeeValue['lastname'],
                $employeeValue['firstname'],
                $employeeValue['fullname'],
                $employeeValue['position'],
                null,
                null,
                null,
                $employeeValue['mobile_number'],
                22,
                $employeeValue['company_name'],
                $employeeValue['boss'],
                $employeeValue['main_holliday_counter'],
                $employeeValue['additional_holliday_counter']
            );

            $logs->logUpload($employeeValue['mobile_number']." овощевод существует в файле, но отсутствует в БД. Добавляем в БД по мобильному.", $employeeValue['mobile_number']);

        }

    // Проверяем неактивных сотрудников офиса 
    }*/ else if ($employeeValue['email'] != null && $employeeValue['mobile_number'] != null && $employeeValue['activity'] == 0) {

        $userFromDbResult = $access->getUserByPersonnelNumber($employeeValue['email']);

        if ($userFromDbResult) {

            // Если есть и в выгрузке и в БД - удаляем из БД по email  
            echo $employeeValue['email']." сотрудник офиса неактиивен, удаляем из БД по email<br>";

            $access->removeEmpoyeeByEmail($employeeValue['email']);

            $logs->logUpload($employeeValue['email']." сотрудник офиса неактивен, удаляем из БД", $employeeValue['email']);

        }

    // Проверяем неактивных овощеводов
    } /*else if ($employeeValue['email'] == null && $employeeValue['mobile_number'] != null && $employeeValue['activity'] == 0) {

        $userFromDbResult = $access->getUserForJobByPhoneNumber($employeeValue['mobile_number']);

        if ($userFromDbResult) {

            // Если есть и в выгрузке и в БД - удаляем из БД по мобильному
            echo $employeeValue['mobile_number']." овощевод неактивен. Удаляем из БД по мобильному.<br>";

            $access->removeEmpoyeeByMobileNumber($employeeValue['mobile_number']);

            $logs->logUpload($employeeValue['mobile_number']." овощевод неактивен. Удаляем из БД по мобильному.",$employeeValue['mobile_number']);

        }

    // Проверяем тех у кого вообще ничего нет
    }*/ else if ($employeeValue['email'] == null && $employeeValue['mobile_number'] == null) {

        // Складываем в отдельный файл для доработки 
        $logs->logEmptyUser(
            $employeeValue['firstname'],
            $employeeValue['lastname'],
            $employeeValue['fullname'],
            $employeeValue['position'],
            $employeeValue['email'],
            $employeeValue['office_number'],
            $employeeValue['internal_number'],
            $employeeValue['mobile_number'],
            $employeeValue['company_name'],
            $employeeValue['company_id'],
            $employeeValue['boss'],
            $employeeValue['main_holliday_counter'],
            $employeeValue['additional_holliday_counter'],
            $employeeValue['activity']
        );
    }
    
}






?>