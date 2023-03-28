<?Php


header ('Content-Type: application/json; charset=utf-8');
header ('Access-Control-Allow-Origin: *');
header ('Access-Control-Allow-Methods: GET');

require ("secure/access.php");

$file = parse_ini_file("../../Testbotdb.ini");

$dbhost = trim($file["dbhost"]);
$dbuser = trim($file["dbuser"]);
$dbpass = trim($file["dbpass"]);
$dbname = trim($file["dbname"]);
$dbtoken = trim($file["token"]);

$access = new access($dbhost, $dbuser, $dbpass, $dbname);
$access->connect();

$user = $access->getUserByUserId('43f4da94-7b4e-11eb-a215-00155d93a619');

if ($user) {
    echo json_encode(array('result' => true));
} else {
    echo json_encode(array('result' => false));
}

$access->disconnect();

?>