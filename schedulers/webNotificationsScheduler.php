<?Php


$file = parse_ini_file("../../../Testbotdb.ini");

$host = trim($file["dbhost"]);
$user = trim($file["dbuser"]);
$pass = trim($file["dbpass"]);
$name = trim($file["dbname"]);
$token = trim($file["token"]);

$website = "https://api.telegram.org/bot".$token;

require ("../CalendarInfo.php");
$calendarInfo = new CalendarInfo();

require ("../secure/access.php");
$access = new access($host, $user, $pass, $name);
$access->connect();

$pendingNotifications = $access->getPendingNotifications();
if

$sendingListFromDb = $access->getSendingListFromDb();
$access->disconnect();

/*foreach ($sendingListFromDb as $user) {
    $message = $rotationalWorkerFromDb['firstname'].", дата Вашего следующего заезда на вахту - ".(string)$desiredDateText." г.";
    $rotationalWorkerInfo = $calendarInfo->getMonthlyDataMock($rotationalWorkerFromDb['user_id'], $desiredDateFirstDayOfMonth, $offset);
    $dateNumber = substr($desiredDate, 0, 1) == "0" ? substr(substr($desiredDate, 0, 2), 1) : substr($desiredDate, 0, 2);

    foreach ($rotationalWorkerInfo['daysList'] as $key=>$value) {
        if ($dateNumber > 1) {
            if ($value['dateNumber'] === $dateNumber) {
                $targetDay = $value['isWorkingDay'] ? true : false;
                $previousDay = $rotationalWorkerInfo['daysList'][$key - 1]['isWorkingDay'] ? true : false;
                if ($targetDay && !$previousDay) {
                    echo "Для работника ".$rotationalWorkerFromDb['fullname']." день ".$desiredDate." является днем начала ближайшей вахты, необходимо отправить уведомление для tg_chat_id: ".$rotationalWorkerFromDb['tg_chat_id']."<br><br>";
                    sendMessage($rotationalWorkerFromDb['tg_chat_id'], $message, null);
                    sendMessage('5389293300', "Для пользователя ".$rotationalWorkerFromDb['fullname']." ушло следующее напоминание: <br>".$message, null);
                    sleep(5);
                } else {
                    echo "Для работника ".$rotationalWorkerFromDb['fullname']." день ".$desiredDate." не является днем начала ближайшей вахты, отправлять уведомление для telegramId: ".$rotationalWorkerFromDb['tg_chat_id']." не нужно.<br><br>";
                }
            }
        }
    }
}*/

function sendMessage($chatID, $text, $keyboard) {
  $url = $GLOBALS[website]."/sendMessage?chat_id=$chatID&parse_mode=HTML&text=".urlencode($text)."&reply_markup=".$keyboard;
  file_get_contents($url);
}

function sendDocument($chatID, $documentUrl, $keyboard) {
  $url = $GLOBALS[website]."/sendDocument?chat_id=$chatID&parse_mode=HTML&document=".$documentUrl."&reply_markup=".$keyboard;
  file_get_contents($url);
}

?>