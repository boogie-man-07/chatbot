<?php

$file = parse_ini_file("../../Testbotdb.ini"); // accessing the file with connection info

$host = trim($file["dbhost"]);
$user = trim($file["dbuser"]);
$pass = trim($file["dbpass"]);
$name = trim($file["dbname"]);
$token = trim($file["token"]);

$website = "https://api.telegram.org/bot".$token;
require ("../secure/access.php");
$access = new access($host, $user, $pass, $name);
$fields = $_POST['message'];
$okMessage = 'Уведомление успешно отправлено авторизованным пользователям. Спасибо за использование!';
$errorMessage = 'Ошибка при отправке сообщения. Пожалуйста, попробуйте позднее!';

/*
 *  LET'S DO THE SENDING
 */

// if you are not debugging and don't need error reporting, turn this off by error_reporting(0);
error_reporting(0);

try {
    $access->connect();
    if(count($_POST) == 0) throw new \Exception('Form is empty');
    $authorizedUsersList = ['284409303'];
    foreach ($authorizedUsersList as $value) {
        sendMessage($value['tg_chat_id'], $fields, null);
        sleep(5);
    }
    $responseArray = array('type' => 'success', 'message' => $okMessage);
} catch (\Exception $e) {
    $responseArray = array('type' => 'danger', 'message' => $errorMessage);
}


// if requested by AJAX request return JSON response
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    $encoded = json_encode($responseArray);
    header('Content-Type: application/json');
    echo $encoded;
} else {
    echo $responseArray['message'];
}

$access->disconnect();

function sendMessage($chatID, $text, $keyboard) {
  $url = $GLOBALS[website]."/sendMessage?chat_id=$chatID&parse_mode=HTML&text=".urlencode($text)."&reply_markup=".$keyboard;
  file_get_contents($url);
}

?>