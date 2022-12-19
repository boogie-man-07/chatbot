<?Php

// Инициализируем переменные необходимых классов и подключение к БД
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

// Берем из БД всех сотрудников, которые работают вахтовым методом + зарегистрированы в боте
$rotationalWorkersListFromDb = $access->getRotationalWorkersListFromDb();
// $chatID = '187967374';
// $user = $access->getUserByChatID($chatID);
$offset = "0";

$currentTimestamp = strtotime(date('d.m.Y'));
$desiredTimestamp = strtotime('+ 3 days', $currentTimestamp);
$desiredDate = date('d.m.Y', $desiredTimestamp);
echo "finding day :".$findingDate."<br><br>";
// $desiredDateFirstDayOfMonth = date('01.m.Y', $desiredTimestamp);
// echo "first day of founded month: ".$firstDayDate."<br><br>";

//************

foreach ($rotationalWorkersListFromDb as $rotationalWorkerFromDb) {
    $rotationalWorkerInfo = $calendarInfo->getMonthlyData($rotationalWorkerFromDb['user_id'], $desiredDateFirstDayOfMonth, $offset);
    $dateNumber = substr($desiredDate, 0, 1) == "0" ? substr(substr($desiredDate, 0, 2), 1) : substr($desiredDate, 0, 2);

    foreach ($rotationalWorkerInfo['daysList'] as $key=>$value) {
        if ($dateNumber > 1) {
            if ($value['dateNumber'] === $dateNumber) {
                $targetDay = $value['isWorkingDay'] ? true : false;
                $previousDay = $rotationalWorkerInfo['daysList'][$key - 1]['isWorkingDay'] ? true : false;
                if ($targetDay && !$previousDay) {
                    echo "Для работника ".$rotationalWorkerFromDb['fullname']." день ".$desiredDate." является днем начала ближайшей вахты, необходимо отправить уведомление для telegramId: ".$rotationalWorkerFromDb['tg_chat_id']."<br><br>";
                } else {
                    echo "Для работника ".$rotationalWorkerFromDb['fullname']." день ".$desiredDate." не является днем начала ближайшей вахты, отправлять уведомление для telegramId: ".$rotationalWorkerFromDb['tg_chat_id']." не нужно.<br><br>";
                }
            }
        }
    }
}

//************



// $rotationalWorkerInfo = $calendarInfo->getMonthlyData($user['user_id'], $firstDayDate, $offset);
//
// echo json_encode($rotationalWorkerInfo)."<br><br>";
//
// $dateNumber = substr($findingDate, 0, 1) == "0" ? substr(substr($findingDate, 0, 2), 1) : substr($findingDate, 0, 2);
// echo $dateNumber."<br><br>";
//
// foreach ($rotationalWorkerInfo['daysList'] as $key=>$value) {
//     if ($dateNumber > 1) {
//         if ($value['dateNumber'] === $dateNumber) {
//             echo $key;
//             echo "<br><br>";
//             echo json_encode($rotationalWorkerInfo['daysList'][$key - 1]);
//             echo "<br><br>";
//             echo json_encode($value)."<br><br>";
//             echo "Рабочий день: <br><br>";
//             $previous = $rotationalWorkerInfo['daysList'][$key - 1]['isWorkingDay'] ? true : false;
//             $finding = $value['isWorkingDay'] ? true : false;
//
//             $shouldSendNotification = (!$previous && $finding);
//             echo "Предыдущий: ".$previous."<br><br>";
//             echo "Искомый: ".$finding."<br><br>";
//             echo "Рабочий ли день?: ".$shouldSendNotification."<br><br>";
//
//             if ($finding && !$previous) {
//                 echo "Это первый рабочий день, нужно отправить уведомление.<br><br>";
//             } else {
//                 echo "Это не первый рабочий день, не нужно отправлять уведомление.<br><br>";
//             }
//         }
//     }
// }

?>