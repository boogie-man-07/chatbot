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

    } else {
        $logs->logUpload("File for upload is bad", null);
        exit;
    }
}

foreach ($employeeList as $employeeValue) {
    $is_sigma_available = $employeeValue['company_id'] == 1 ? 1 : 0;
    $is_greenhouse_available = $employeeValue['company_id'] == 2 ? 1 : 0;
    $is_diall_available = $employeeValue['company_id'] == 3 ? 1 : 0;

    //echo json_encode($employeeValue, true);

    // Проверяем активных сотрудников офиса
    if (preg_match("/@diall.ru/", $employeeValue['email']) == 1 && $employeeValue['mobile_number'] != "" && $employeeValue['activity'] == 1) {

        $userFromDbResult = $access->getUserByPersonnelNumber($employeeValue['email']);

        if ($userFromDbResult) {

            // Если есть и в выгрузке и в БД - обновляем в БД по email 
            echo $employeeValue['email']." сотрудник офиса существует и в файле, и в БД, обновляем в БД<br>";

            $access->updateEmployeeByEmail(
                $employeeValue['userId'],
                $employeeValue['firstname'],
                $employeeValue['lastname'],
                $employeeValue['fullname'],
                $employeeValue['form_fullname'],
                $employeeValue['position'],
                $employeeValue['form_position'],
                $employeeValue['office_number'],
                $employeeValue['internal_number'],
                $employeeValue['mobile_number'],
                $employeeValue['company_name'],
                $employeeValue['company_id'],
                0,
                $is_sigma_available,
                $is_greenhouse_available,
                $is_diall_available,
                $employeeValue['boss'],
                $employeeValue['boss_position'],
                $employeeValue['main_holliday_counter'],
                $employeeValue['additional_holliday_counter'],
                $employeeValue['email']
            );

            $logs->logUpload($employeeValue['email']." сотрудник офиса существует и в файле, и в БД, обновляем в БД", $employeeValue['email']);

        } else {

            // Если есть в выгрузке, но нет в БД - добавляем в БД по email
            echo $employeeValue['email']." существует в файле, но отсутствует в БД, добавляем в БД<br>";

            $access->insertEmployee(
                $employeeValue['userId'],
                $employeeValue['lastname'],
                $employeeValue['firstname'],
                $employeeValue['fullname'],
                $employeeValue['form_fullname'],
                $employeeValue['position'],
                $employeeValue['form_position'],
                $employeeValue['email'],
                $employeeValue['office_number'],
                $employeeValue['internal_number'],
                $employeeValue['mobile_number'],
                $employeeValue['company_name'],
                $employeeValue['company_id'],
                0,
                $is_sigma_available,
                $is_greenhouse_available,
                $is_diall_available,
                $employeeValue['boss'],
                $employeeValue['boss_position'],
                $employeeValue['main_holliday_counter'],
                $employeeValue['additional_holliday_counter']
            );

            $logs->logUpload($employeeValue['email']." сотрудник существует в файле, но отсутствует в БД, добавляем в БД", $employeeValue['email']);
        }

    // Проверяем активных рабочих
    } else if (preg_match("/@diall.ru/", $employeeValue['email']) == 0 && $employeeValue['mobile_number'] != "" && $employeeValue['activity'] == 1) {

        $userFromDbResult = $access->getUserByPhoneNumber($employeeValue['mobile_number']);

        if ($userFromDbResult) {

            // Если есть и в выгрузке и в БД - обновляем в БД по мобильному
            echo $employeeValue['mobile_number']." рабочий существует в файле и существует в БД. Обновляем в БД по мобильному.<br>";

            $access->updateEmployeeByMobileNumber(
                $employeeValue['userId'],
                $employeeValue['firstname'],
                $employeeValue['lastname'],
                $employeeValue['fullname'],
                $employeeValue['form_fullname'],
                $employeeValue['position'],
                $employeeValue['form_position'],
                $employeeValue['office_number'],
                $employeeValue['internal_number'],
                $employeeValue['mobile_number'],
                $employeeValue['company_name'],
                $employeeValue['company_id'],
                1,
                $is_sigma_available,
                $is_greenhouse_available,
                $is_diall_available,
                $employeeValue['boss'],
                $employeeValue['boss_position'],
                $employeeValue['main_holliday_counter'],
                $employeeValue['additional_holliday_counter'],
                $employeeValue['email']
            );

            $logs->logUpload($employeeValue['mobile_number']." рабочий существует в файле и существует в БД. Обновляем в БД по мобильному.", $employeeValue['mobile_number']);

        } else {
    
            // Если есть и в выгрузке, но нет в БД - добавляем в БД по мобильному
            echo $employeeValue['mobile_number']." рабочий существует в файле, но отсутствует в БД. Добавляем в БД по мобильному.<br>";

            $access->insertEmployee(
                $employeeValue['userId'],
                $employeeValue['lastname'],
                $employeeValue['firstname'],
                $employeeValue['fullname'],
                $employeeValue['form_fullname'],
                $employeeValue['position'],
                $employeeValue['form_position'],
                $employeeValue['email'],
                $employeeValue['office_number'],
                $employeeValue['internal_number'],
                $employeeValue['mobile_number'],
                $employeeValue['company_name'],
                $employeeValue['company_name'],
                1,
                $is_sigma_available,
                $is_greenhouse_available,
                $is_diall_available,
                $employeeValue['boss'],
                $employeeValue['boss_position'],
                $employeeValue['main_holliday_counter'],
                $employeeValue['additional_holliday_counter']
            );

            $logs->logUpload($employeeValue['mobile_number']." рабочий существует в файле, но отсутствует в БД. Добавляем в БД по мобильному.", $employeeValue['mobile_number']);

        }

    // Проверяем неактивных сотрудников офиса 
    } else if (preg_match("/@diall.ru/", $employeeValue['email']) == 1 && $employeeValue['mobile_number'] != "" && $employeeValue['activity'] == 0) {

        $userFromDbResult = $access->getUserByPersonnelNumber($employeeValue['email']);

        if ($userFromDbResult) {

            // Если есть и в выгрузке и в БД - удаляем из БД по email  
            echo $employeeValue['email']." сотрудник офиса неактивен, удаляем из БД по email<br>";

            $access->removeEmpoyeeByEmail($employeeValue['email']);

            $logs->logUpload($employeeValue['email']." сотрудник офиса неактивен, удаляем из БД", $employeeValue['email']);

        }

    // Проверяем неактивных рабочих
    } else if (preg_match("/@diall.ru/", $employeeValue['email']) == 0 && $employeeValue['mobile_number'] != "" && $employeeValue['activity'] == 0) {

        $userFromDbResult = $access->getUserForJobByPhoneNumber($employeeValue['mobile_number']);

        if ($userFromDbResult) {

            // Если есть и в выгрузке и в БД - удаляем из БД по мобильному
            echo $employeeValue['mobile_number']." рабочий неактивен. Удаляем из БД по мобильному.<br>";

            $access->removeEmpoyeeByMobileNumber($employeeValue['mobile_number']);

            $logs->logUpload($employeeValue['mobile_number']." рабочий неактивен. Удаляем из БД по мобильному.",$employeeValue['mobile_number']);

        }

    // Проверяем тех у кого вообще ничего нет
    } else if ($employeeValue['email'] == "" && $employeeValue['mobile_number'] == "") {

        echo $employeeValue['fullname']." не имеет ни email, ни мобильного.<br>";

        // Складываем в отдельный файл для доработки 
        $logs->logEmptyUser(
            $employeeValue['userId'],
            $employeeValue['firstname'],
            $employeeValue['lastname'],
            $employeeValue['fullname'],
            $employeeValue['form_fullname'],
            $employeeValue['position'],
            $employeeValue['email'],
            $employeeValue['office_number'],
            $employeeValue['internal_number'],
            $employeeValue['mobile_number'],
            $employeeValue['company_name'],
            $employeeValue['company_id'],
            $employeeValue['boss'],
            $employeeValue['boss_position'],
            $employeeValue['main_holliday_counter'],
            $employeeValue['additional_holliday_counter'],
            $employeeValue['activity']
        );
    }
    
}






?>