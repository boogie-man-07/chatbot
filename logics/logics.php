<?php

/**
 * Created by Murad Adygezalov. All Rights Reserved.
 *
 * Date: 20.01.2021
 * Time: 17:30
 */

class logics {

    function toDate($string_data) {
      // return DateTime::createFromFormat('d M Y H:i:s:u', $string_data);
      $date_string = date("d.m.Y", strtotime($string_data));
      $time_string = date("H:m:s", strtotime($string_data));
      return $date_string." ".$time_string;
    }

    function getCurrentDate() {
      return (string) date("dmY");
    }

    function getDateForLogging() {
      return (string) date("Y-m-d")."T".(string)date("H:m:s");
    }
}

?>
