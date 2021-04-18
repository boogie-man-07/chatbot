<?php
/**
 * Created by PhpStorm.
 * User: murad
 * Date: 05.01.2018
 * Time: 15:38
 */


$text = ' Murad Adygezalov Arifovich';

echo preg_match('/\s/',$text);
echo  "\n bla bla bla";
echo substr_count(trim($text), ' ');

?>