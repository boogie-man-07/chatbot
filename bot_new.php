<?php
/**
 * Created by Murad Adygezalov
 * Date: 28.03.2021
 * Time: 16:59
 */


$file = parse_ini_file("../Botdb.ini"); // accessing the file with connection info

// store in php var information from ini var
$host = trim($file["dbhost"]);
$user = trim($file["dbuser"]);
$pass = trim($file["dbpass"]);
$name = trim($file["dbname"]);
$token = trim($file["token"]);

$confirmationCode = null;
$website = "https://api.telegram.org/bot".$token;

$updates = file_get_contents('php://input');
$updates = json_decode($updates,TRUE);

$text = $updates['message']['text'];
$chatID = $updates['message']['from']['id'];
$phoneNumber = $updates['message']['contact']['phone_number'];
$username = $updates['message']['from']['first_name'];
$query = $updates["callback_query"];
$queryID = $query["id"];
$queryUserID = $query["from"]["id"];
$queryData = $query["data"];
$queryUserName = $query["from"]["first_name"];

require ("vendor/autoload.php");
require ("keyboards/keyboards.php");
require ("constants/constants.php");
// require ("logs/logs.php");

$json = file_get_contents('constants/localization.json');
$data = json_decode($json, true);

// include access.php to call func from access.php file
require ("secure/access.php");
$access = new access($host, $user, $pass, $name);
$access->connect();

require ('routes/authroute/authroute.php');
$authroute = new authroute();

require ('routes/common/commonmistakeroute.php');
$commonmistakeroute = new commonmistakeroute();


$user = $access->getUserByChatID($chatID);

if (!$user) {
    switch ($text) {
        default:
            $commonmistakeroute->triggerActionForCommonErrorIfNotAuthorized();
    }
} else {
    if (!$user["is_authorized"]) {
        $firstname = $user["$firstname"];
        switch ($text) {
            default:
                $commonmistakeroute->triggerActionForCommonErrorIfAuthorizationNotFinished($chatID, $firstname);
        }
    } else {
        switch ($text) {
            case '/start':
                $authroute->triggerActionForNewUserAuthorization($chatID, $username);
                break;
            default:
                $commonmistakeroute->triggerActionForCommonMistake($chatID);
                break;
        }
    }
}



$access->disconnect();

?>