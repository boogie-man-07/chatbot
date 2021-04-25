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

require ("secure/email.php");
$email = new email();

require ("secure/swiftmailer.php");
$swiftmailer = new swiftmailer();

require ('routes/authroute/authroute.php');
$authroute = new authroute();

require ('routes/common/commonmistakeroute.php');
$commonmistakeroute = new commonmistakeroute();

# Return userinfo if authorized, otherwise return null
$user = $access->getUserByChatID($chatID);
$isAuthorized = $user['is_authorized'];
$firstname = $user['firstname'];
$position = $user["position"];
$confirmation_code = $user["confirmation_code"];
$emailAddress = $user["email"];
$fullname = $user["fullname"];
$companyID = $user["company_id"];

if (!$user) {
    switch ($text) {

        case '/start':
            $authroute->triggerActionForNewUserAuthorization($chatID, $username);
            $access->setState($chatID, "waiting for authorization");
            break;

        case 'Авторизация по email':
            $authroute->triggerActionForStartingAuthorization($chatID);
            $access->setState($chatID, "waiting for login");
            break;

        case 'Вернуться в начало':
            $authroute->triggerActionForMoveToStart($chatID, $username);
            break;

        default:
            $stateResult = $access->getState($chatID);
            $state = $stateResult["dialog_state"];

            switch ($state) {

                case 'waiting for login':
                    if ($authroute->checkLogin($text)) {
                        $result = $access->getUserByPersonnelNumber($text);
                        if (result) {
                            if ($authroute->comparse($text, $result['email'])) {
                                $confirmationCode = $email->generateConfirmationCode(10);
                                $access->saveConfirmationCode($confirmationCode, $chatID, $result['email']);
                                $access->setState($chatID, "waiting for confirmation code");
                                $authroute->triggerActionForLoginAcceptance($chatID, $result["fullname"]);
                                break;
                            } else {
                                $commonmistakeroute->triggerActionForCommonErrorIfLoginNotFound($chatID);
                                break;
                            }
                        } else {
                            $commonmistakeroute->triggerActionForCommonErrorIfLoginNotFound($chatID);
                            break;
                        }
                    } else {
                        $commonmistakeroute->triggerActionForCommonErrorIfLoginIncorrect($chatID);
                        break;
                    }

                default:
                    $commonmistakeroute->triggerActionForCommonErrorIfNotAuthorized($chatID, $username);
                    break;
            }
    }
} else {
    if (!$isAuthorized) {
        switch ($text) {

            case '/start':
                $commonmistakeroute->triggerActionForCommonErrorIfAuthorizationNotFinished($chatID, $username);
                break;

            default:
                $commonmistakeroute->triggerActionForCommonErrorIfNotAuthorized($chatID, $username);
                break;
        }
    } else {
        switch ($text) {

            case '/start':
                $authroute->triggerActionForAuthorizedUser($chatID, $firstname);
                break;

            default:
                $commonmistakeroute->triggerActionForCommonMistake($chatID);
                break;
        }
    }
}

switch ($queryData) {

    case 'sendMessage':
        $template = $email->confirmationTemplate($companyID);
        $template = str_replace("{confirmationCode}", $confirmation_code, $template);
        $template = str_replace("{fullname}", $fullname, $template);
        $swiftmailer->sendMailViaSmtp(
            $companyID,
            $emailAddress,
            "Подтверждение регистрации в telegram-боте \"Персональный ассистент работника\"",
            $template
        );
        $authroute->triggerActionWithSendingConfirmationEmail($queryUserID, $username);
        break;
}

function sendMessage($chatID, $text, $keyboard) {
    $url = $GLOBALS[website]."/sendMessage?chat_id=$chatID&parse_mode=HTML&text=".urlencode($text)."&reply_markup=".$keyboard;
    file_get_contents($url);
}

$access->disconnect();

?>