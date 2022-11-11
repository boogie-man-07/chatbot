<?Php

require 'Library/NCLNameCaseRu.php';

// STEP 1. Build connection
// Secure way to biuld connection
$file = parse_ini_file("../../Testbotdb.ini"); // accessing the file with connection info

// store in php var information from ini var
$host = trim($file["dbhost"]);
$user = trim($file["dbuser"]);
$pass = trim($file["dbpass"]);
$name = trim($file["dbname"]);
$token = trim($file["token"]);
$nc = new NCLNameCaseRu();

// include access.php to call func from access.php file
require ("secure/access.php");
$access = new access($host, $user, $pass, $name);
$access->connect();

require ("logs/logs.php");
$logs = new logs();

$result = Array();
$employeeList = Array();
$userFromDbResult = Array();

$is_sigma_available = 0;
$is_greenhouse_available = 0;
$is_diall_available = 0;

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
    exit;
} else {
    $result = json_decode($response, true);
    if (!$result['error']) {
        foreach ($result['data'] as $users) {

            $user = json_decode($users['data'], true);

            if ($user['holiday_main'] <= 0) {
                $holiday_main = 0;
            } else {
                $holiday_main = round($user['holiday_main'], 0);
            }

            if ($user['holiday_more'] <= 0) {
                $holiday_more = 0;
            } else {
                $holiday_more = round($user['holiday_more'], 0);
            }

            $userItem = array(
                'userId' => $user['guid'],
                'firstname' => $user['firstname'],
                'lastname' => $user['lastname'],
                'fullname' => $user['fullname'],
                'form_fullname' => $nc->q($user['fullname'], NCL::$RODITLN),
                'position' => $user['position'],
                'form_position' => $user['position'],
                'email' => $user['email'],
                'office_number' => $user['office_number'],
                'internal_number' => $user['internal_number'],
                'mobile_number' => $user['mobile_number'],
                'company_name' => $user['company_name'],
                'company_id' => $user['company_id'],
                'boss' => $user['head'],
                'boss_position' => $user['head_position'],
                'main_holliday_counter' => $holiday_main,
                'additional_holliday_counter' => $holiday_more,
                'activity' => $user['activity']
            );
            array_push($employeeList, $userItem);
        }
        // Убираем Еремину из процесса (прибито гвоздями)
        $excluded = "Еремина Елена Анатольевна";
        $employeeList = array_filter($employeeList, function ($value) use ($excluded) {
            return ($value["fullname"] != $excluded);
        });
    } else {
        $logs->logUpload("File for upload is bad", null);
        exit;
    }
}

foreach ($employeeList as $employeeValue) {
    $is_sigma_available = $employeeValue['company_id'] == 1 ? 1 : 0;
    $is_greenhouse_available = $employeeValue['company_id'] == 2 ? 1 : 0;
    $is_diall_available = $employeeValue['company_id'] == 3 ? 1 : 0;


    // проверяем по GUID есть ли сотрудник в БД
    $userFromDbResult = $access->getUserByUserId($employeeValue['userId']);

    if ($userFromDbResult) {
        if ($employeeValue['activity'] == 1) {
            if (preg_match("/diall.ru/", strtolower($employeeValue['email'])) == 1 && $employeeValue['mobile_number'] != "" && $employeeValue['activity'] == 1) {
                echo $employeeValue['userId'].", ".$employeeValue['fullname']." - активный сотрудник офиса, обновляем в БД<br>";
            } else if (preg_match("/diall.ru/", strtolower($employeeValue['email'])) == 0 && $employeeValue['mobile_number'] != "" && $employeeValue['activity'] == 1) {
                echo $employeeValue['userId'].", ".$employeeValue['fullname']." - активный рабочий, обновляем в БД<br>";
            }
        } else if ($employeeValue['activity'] == 0) {
            echo $employeeValue['userId'].", ".$employeeValue['fullname']." - сотрудник/рабочий не активен, удаляем из БД<br>";
        }
    } else {
        if ($employeeValue['activity'] == 1) {
            if (preg_match("/diall.ru/", strtolower($employeeValue['email'])) == 1 && $employeeValue['mobile_number'] != "" && $employeeValue['activity'] == 1) {
                echo $employeeValue['userId'].", ".$employeeValue['fullname']." - активный новый сотрудник офиса, добавляем в БД<br>";
            } else if (preg_match("/diall.ru/", strtolower($employeeValue['email'])) == 0 && $employeeValue['mobile_number'] != "" && $employeeValue['activity'] == 1) {
                echo $employeeValue['userId'].", ".$employeeValue['fullname']." - активный новый рабочий, добавляем в БД<br>";
            } else if ($employeeValue['email'] == "" && $employeeValue['mobile_number'] == "") {
                echo $employeeValue['userId'].", ".$employeeValue['fullname']." - у сотрудника нет ничего, ничего не делаем с БД, просто логируем<br>";
            }
        } else if ($employeeValue['activity'] == 0) {
            echo $employeeValue['userId'].", ".$employeeValue['fullname']." - сотрудник/рабочий не активен, ничего не делаем с БД<br>";
        }
    }
}






?>