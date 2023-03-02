<?Php

$file = parse_ini_file("../../../Testbotdb.ini");

$host = trim($file["dbhost"]);
$user = trim($file["dbuser"]);
$pass = trim($file["dbpass"]);
$name = trim($file["dbname"]);
$token = trim($file["token"]);
$nc = new NCLNameCaseRu();

require ("secure/access.php");
$access = new access($host, $user, $pass, $name);
$access->connect();

require ("logs/logs.php");
$logs = new logs();

$result = Array();
$dmsEmployeeList = Array();
$userFromDbResult = Array();

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

            $userItem = array(
                'userId' => $user['guid'],
                'email' => $user['email'],
                'company_name' => $user['company_name'],
                'company_id' => $user['company_id'],
                'activity' => $user['activity']
            );
            array_push($dmsEmployeeList, $userItem);
        }
        // Убираем Еремину из процесса (прибито гвоздями)
        $excluded = "Еремина Елена Анатольевна";
        $dmsEmployeeList = array_filter($dmsEmployeeList, function ($value) use ($excluded) {
            return ($value["fullname"] != $excluded);
        });
    } else {
        $logs->dmsLogUpload("File for upload is bad");
        exit;
    }
}

foreach ($dmsEmployeeList as $employeeValue) {

    $userFromDbResult = $access->getDmsUserByUserId($employeeValue['userId']);

    if ($userFromDbResult) {
        if ($employeeValue['activity'] == 1) {

            echo $employeeValue['userId']." - сотрудник есть в таблице DMS, ничего не делаем<br>";
            $logs->dmsLogUpload($employeeValue['userId']." - сотрудник есть в таблице DMS, ничего не делаем.");

        } else if ($employeeValue['activity'] == 0) {

//             $access->removeDmsEmployeeByUserId($employeeValue['userId']);
            echo $employeeValue['userId']." - сотрудник не активен, удаляем из таблицы DMS<br>";
            $logs->dmsLogUpload($employeeValue['userId']." - сотрудник не активен, удаляем из таблицы DMS");
        }
    } else {
        if ($employeeValue['activity'] == 1) {

//             $access->insertDmsEmployee($employeeValue['userId']);
            echo $employeeValue['userId']." - активный новый сотрудник, добавляем в таблицу DMS<br>";
            $logs->dmsLogUpload($employeeValue['userId']." - активный новый сотрудник, добавляем в таблицу DMS<br>");

        } else if ($employeeValue['activity'] == 0) {
            echo $employeeValue['userId']." - сотрудник не активен, ничего не делаем<br>";
            $logs->dmsLogUpload($employeeValue['email']." сотрудник не активен, ничего не делаем");
        }
    }
}






?>