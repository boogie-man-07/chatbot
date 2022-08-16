<?Php

$date = new dateTime();
$day = $date->format("d");

if (mb_strlen((string)$day) <= 2) {
    echo '0'.$day;
} else {
    echo $day;
}

?>