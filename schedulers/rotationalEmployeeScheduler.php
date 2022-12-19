<?Php

$file = parse_ini_file("../../../Testbotdb.ini");

$host = trim($file["dbhost"]);
$user = trim($file["dbuser"]);
$pass = trim($file["dbpass"]);
$name = trim($file["dbname"]);

require ("../secure/access.php");
$access = new access($host, $user, $pass, $name);
$access->connect();

require ("../CalendarInfo.php");
$calendarInfo = new CalendarInfo();

$chatID = '187967374';
$offset = "0";

$user = $access->getUserByChatID($chatID);

echo "currentMonth: ".$currentMonth."<br><br>";

echo "today: ".date('d.m.Y')."<br><br>";

$timestamp = strtotime(date('d.m.Y'));
$totalTimeStamp = strtotime('+ 3 days', $timestamp);
echo "finding day :".date('d.m.Y', $totalTimeStamp)."<br><br>";

$firstDay = date('01.m.Y', $totalTimeStamp);

echo "first day of founded month: ".$firstDay."<br><br>";

$rotationalWorkerInfo = $calendarInfo->getMonthlyData($user['user_id'], $firstDay, $offset);

echo json_encode($rotationalWorkerInfo);

function getCurrentMonth() {
    $firstDay = strtotime('first day of this month', time());
    return date('d.m.Y', $firstDay);
}

function getNextMonth($offset) {
    $firstDay = strtotime("first day of $offset month", time());
    return date('d.m.Y', $firstDay);
}


echo "currentMonth: ".$currentMonth."<br><br>";

echo "today: ".date('d.m.Y')."<br><br>";

$timestamp = strtotime(date('d.m.Y'));
$totalTimeStamp = strtotime('+ 3 days', $timestamp);
echo "finding day :".date('d.m.Y', $totalTimeStamp)."<br><br>";

$firstDay = date('01.m.Y', $totalTimeStamp);

echo "first day of founded month: ".$firstDay."<br><br>";

function getCurrentMonth() {
    $firstDay = strtotime('first day of this month', time());
    return date('d.m.Y', $firstDay);
}

?>