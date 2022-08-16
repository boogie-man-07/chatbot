<?Php

$date = new dateTime();
$day = $date->format("d");

if (mb_strlen($day) <= 2) {
    echo '0'.$day;
} else {
    echo $day;
}

?>