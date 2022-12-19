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





echo "today: ".date('d.m.Y')."<br><br>";

$timestamp = strtotime(date('d.m.Y'));
$totalTimeStamp = strtotime('+ 3 days', $timestamp);
$findingDate = date('d.m.Y', $totalTimeStamp);
echo "finding day :".$findingDate."<br><br>";

$firstDayDate = date('01.m.Y', $totalTimeStamp);

echo "first day of founded month: ".$firstDayDate."<br><br>";

$rotationalWorkerInfo = $calendarInfo->getMonthlyData($user['user_id'], $firstDayDate, $offset);

echo json_encode($rotationalWorkerInfo)."<br><br>";

$dateNumber = substr($findingDate, 0, 1) == "0" ? substr(substr($findingDate, 0, 2), 1) : substr($findingDate, 0, 2);
echo $dateNumber."<br><br>";

foreach ($rotationalWorkerInfo['daysList'] as $value) {
    if ($dateNumber > 1) {
        if ($value['dateNumber'] === $dateNumber) {
            echo $value;
        }
    }
}

?>