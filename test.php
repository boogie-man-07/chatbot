<?Php

$date = new dateTime();
$day = $date->format("d");

if (count_chars($day) <= 2) {
    echo '0'.$day;
} else {
    echo $day;
}

?>