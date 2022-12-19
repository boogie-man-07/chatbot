<?Php

$file = parse_ini_file("../../Testbotdb.ini");

$host = trim($file["dbhost"]);
$user = trim($file["dbuser"]);
$pass = trim($file["dbpass"]);
$name = trim($file["dbname"]);

require ("../secure/access.php");
$access = new access($host, $user, $pass, $name);
$access->connect();

require ("CalendarInfo.php");
$calendarInfo = new CalendarInfo();

$chatID = '37e7921d-62e3-11eb-a20a-00155d93a613';
$calendarOffset = "0";

$user = $access->getUserByChatID($chatID);

$rotationalWorkerInfo = $calendarInfo->getMonthlyData($user['user_id'], getCurrentMonth(), $offset);

echo json_encode($rotationalWorkerInfo);

function getCurrentMonth() {
    $firstDay = strtotime('first day of this month', time());
    return date('d.m.Y', $firstDay);
}

function getNextMonth($offset) {
    $firstDay = strtotime("first day of $offset month", time());
    return date('d.m.Y', $firstDay);
}

?>