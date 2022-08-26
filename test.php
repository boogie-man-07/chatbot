<?Php

$date = strtok('1.09.2022', '.');
$correctDate = mb_strlen($date) == 1 ? '0'.$date : $date;

echo $correctDate;

?>