<?Php

define(ROOT_DIR, __DIR__);
require_once (ROOT_DIR."/routes/authroute/authroute.php");
require_once (ROOT_DIR.'/routes/common/commonmistakeroute.php');
require_once ('./UnauthorizedUserScenario.php');
require_once ('./NonFinishedAuthorizationUserScenario.php');
require_once ('./AuthorizedUserScenario.php');

require ("vendor/autoload.php");
require ("keyboards/keyboards.php");
require ("constants/constants.php");
require ("logics/logics.php");
require ("logs/logs.php");
require ("secure/access.php");
require ("secure/email.php");
require ("secure/swiftmailer.php");

// DB connection
$file = parse_ini_file("../Testbotdb.ini");

$host = trim($file["dbhost"]);
$user = trim($file["dbuser"]);
$pass = trim($file["dbpass"]);
$name = trim($file["dbname"]);
$token = trim($file["token"]);

$access = new access($host, $user, $pass, $name);
$access->connect();
$logics = new logics();
$logs = new logs();
$constants = new constants();
$keyboards = new keyboards();
$email = new email();
$swiftmailer = new swiftmailer();
$authroute = new authroute();
$commonmistakeroute = new commonmistakeroute();

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

$json = file_get_contents('constants/localization.json');
$data = json_decode($json, true);
$commandList = $data['commands'];
$statesList = $data['states'];

# Return userinfo if authorized, otherwise return null
$user = $access->getUserByChatID($chatID);
$inlineUser = $access->getUserByChatID($queryUserID);
$isAuthorized = $user['is_authorized'];
$firstname = $user['firstname'];
$position = $user["position"];
$confirmation_code = $user["confirmation_code"];
$emailAddress = $user["email"];
$fullname = $user["fullname"];
$companyID = $user["company_id"];

$stateResult = $access->getState($chatID);
$state = $stateResult["dialog_state"];

$inlineStateResult = $access->getState($queryUserID);
$inlineState = $inlineStateResult["dialog_state"];

// Main logics
if (!$user) {
    //sendMessage($chatID, "UnathorizedUserScenario", null); exit;
    $unauthorizedUserScenario = new UnauthorizedUserScenario($chatID, $user, $username, $access, $swiftmailer, $authroute, $commonmistakeroute, $commandList, $statesList, $state, $email);
    $unauthorizedUserScenario->run($text);
} else {
    if (!$isAuthorized) {
        if ($queryData) {
            //sendMessage($queryUserID, "NonFinishedAuthorizationUserScenario Inline", null); exit;
            $nonFinishedAuthorizationUserScenario = new NonFinishedAuthorizationUserScenario($queryUserID, $inlineUser, $queryUserName, $access, $swiftmailer, $authroute, $commonmistakeroute, $commandList, $statesList, $inlineState, $email);
            $nonFinishedAuthorizationUserScenario->runInline($queryData);
        } else {
            //sendMessage($chatID, "NonFinishedAuthorizationUserScenario Usual", null); exit;
            $nonFinishedAuthorizationUserScenario = new NonFinishedAuthorizationUserScenario($chatID, $user, $username, $access, $swiftmailer, $authroute, $commonmistakeroute, $commandList, $statesList, $state, $email);
            $nonFinishedAuthorizationUserScenario->run($text);
        }

    } else {
        //sendMessage($chatID, "AuthorizedUserScenario", null);
        $authorizedUserScenario = new AuthorizedUserScenario($chatID, $user, $username, $access, $authroute, $commandList);
        $authorizedUserScenario->run($text);
    }
}

//sendMessage($chatID, $text, null);

$access->disconnect();

function sendMessage($chatID, $text, $keyboard) {
  $url = $GLOBALS[website]."/sendMessage?chat_id=$chatID&parse_mode=HTML&text=".urlencode($text)."&reply_markup=".$keyboard;
  file_get_contents($url);
}

?>