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

    function formatPhoneNumber($number) {
        $s = str_split($number);
        return "+$s[0] ($s[1]$s[2]$s[3]) $s[4]$s[5]$s[6]-$s[7]$s[8]-$s[9]$s[10]";
    }

    function getUserPrivelegesForUserCards($user) {
        if ($user['is_sigma_available'] == '1' && $user['is_greenhouse_available'] == '1' && $user['is_diall_available'] == '1') {
            return "1,2,3";
        } else if ($user['is_sigma_available'] == '0' && $user['is_greenhouse_available'] == '1' && $user['is_diall_available'] == '0') {
            return "2";
        } else if ($user['is_sigma_available'] == '0' && $user['is_greenhouse_available'] == '0' && $user['is_diall_available'] == '1') {
            return "3";
        }
    }
}

?>
