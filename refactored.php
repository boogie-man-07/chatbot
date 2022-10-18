<?Php

define(ROOT_DIR, __DIR__);
require_once (ROOT_DIR."/routes/authroute/authroute.php");
require_once (ROOT_DIR.'/routes/common/commonmistakeroute.php');
require_once (ROOT_DIR.'/routes/phonebookroute/PhonebookRoute.php');
require_once (ROOT_DIR.'/routes/values/ValuesRoute.php');
require_once (ROOT_DIR.'/routes/mainrules/MainRulesRoute.php');
require_once (ROOT_DIR.'/routes/maininformation/MainInformationRoute.php');
require_once (ROOT_DIR.'/routes/salary/SalaryRoute.php');
require_once ('./UnauthorizedUserScenario.php');
require_once ('./NonFinishedAuthorizationUserScenario.php');
require_once ('./AuthorizedUserScenario.php');
//require_once('./Messages.php');

require ("vendor/autoload.php");
require ("keyboards/keyboards.php");
require ("constants/constants.php");
require ("logics/logics.php");
require ("logs/logs.php");
require ("secure/access.php");
require ("secure/email.php");
require ("secure/swiftmailer.php");
require ("Forms.php");
require ("VacationInfo.php");

// DB connection
$file = parse_ini_file("../../Testbotdb.ini");

$host = trim($file["dbhost"]);
$user = trim($file["dbuser"]);
$pass = trim($file["dbpass"]);
$name = trim($file["dbname"]);
$token = trim($file["token"]);

$access = new access($host, $user, $pass, $name);
$access->connect();
//$messages = new Messages();
$logics = new logics();
$logs = new logs();
$constants = new constants();
$keyboards = new keyboards();
$email = new email();
$swiftmailer = new swiftmailer();
$forms = new Forms();
$vacationInfo = new VacationInfo();
$authroute = new authroute($constants, $keyboards, $logics);
$commonmistakeroute = new commonmistakeroute($constants, $keyboards);
$phonebookroute = new PhonebookRoute($constants, $keyboards);
$valuesRoute = new ValuesRoute($constants, $keyboards);
$mainRulesRoute = new MainRulesRoute($constants, $keyboards);
$mainInformationRoute = new MainInformationRoute($constants, $keyboards);
$salaryRoute = new SalaryRoute($constants, $keyboards);

$json = file_get_contents('constants/localization.json');
$data = json_decode($json, true);
$commandList = $data['commands'];
$statesList = $data['states'];
$constantsList = $data['constants'];

$website = "https://api.telegram.org/bot".$token;
$updates = file_get_contents('php://input');
$updates = json_decode($updates, true);
$message = $queryData ? $query["data"] : $updates['message'];
$phoneNumber = $updates['message']['contact']['phone_number'];
$query = $updates["callback_query"];
$queryID = $query["id"];
$queryData = $query["data"];

$chatID = $queryData ? $query["from"]["id"] : $updates['message']['from']['id'];
$text = $queryData ? $queryData : $updates['message']['text'];
$username = $queryData ? $query["from"]["first_name"] : $updates['message']['from']['first_name'];
$isInline = $queryData ? true : false;

$user = $access->getUserByChatID($chatID);
$isAuthorized = $user['is_authorized'];
$stateResult = $access->getState($chatID);
$state = $stateResult["dialog_state"];

// Main logics
if (!$user) {
    $unauthorizedUserScenario = new UnauthorizedUserScenario($chatID, $user, $username, $access, $swiftmailer, $authroute, $commonmistakeroute, $commandList, $statesList, $constantsList, $state, $email, $phoneNumber);
    $unauthorizedUserScenario->run($text);
} else {
    if (!$isAuthorized) {
        $nonFinishedAuthorizationUserScenario = new NonFinishedAuthorizationUserScenario($chatID, $user, $username, $access, $swiftmailer, $authroute, $commonmistakeroute, $commandList, $statesList, $state, $email, $query);
        if ($isInline) {
            $nonFinishedAuthorizationUserScenario->runInline($text);
        } else {
            $nonFinishedAuthorizationUserScenario->run($text);
        }
    } else {
        $authorizedUserScenario = new AuthorizedUserScenario($chatID, $user, $username, $access, $swiftmailer, $authroute, $commonmistakeroute, $phonebookroute, $valuesRoute, $mainRulesRoute, $mainInformationRoute, $salaryRoute, $commandList, $statesList, $state, $logics, $forms, $email, $vacationInfo, $query, $logs, $message);
        if ($isInline) {
            $authorizedUserScenario->runInline($text);
        } else {
            $authorizedUserScenario->run($text);
        }
    }
}

$access->disconnect();

function sendMessage($chatID, $text, $keyboard) {
  $url = $GLOBALS[website]."/sendMessage?chat_id=$chatID&parse_mode=HTML&text=".urlencode($text)."&reply_markup=".$keyboard;
  file_get_contents($url);
}

function sendPhoto($chatID, $imageUrl, $keyboard) {
  $url = $GLOBALS[website]."/sendPhoto?chat_id=$chatID&parse_mode=HTML&photo=".$imageUrl."&reply_markup=".$keyboard;
  file_get_contents($url);
}

function sendSticker($chatID, $sticker) {
  $url = $GLOBALS[website]."/sendSticker?chat_id=$chatID&sticker=$sticker";
  file_get_contents($url);
}

function answerCallbackQuery($callbackQueryId, $text) {
    $url = $GLOBALS[website]."/answerCallbackQuery?callback_query_id=$callbackQueryId&text=$text";
    file_get_contents($url);
}

?>