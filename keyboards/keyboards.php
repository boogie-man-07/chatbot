<?php
/**
 * Created by PhpStorm.
 * User: murad
 * Date: 05.01.2018
 * Time: 15:38
 */


$json = file_get_contents("constants/localization.json");
$data = json_decode($json, true);

class keyboards {

  function mainKeyboard(): Array {
    $keyboard = array(
      "keyboard" => array(
        array(
          array(
            "text" => $data['mainKeyboard']['phones']
          ),
          array(
            "text" => $data['mainKeyboard']['salary']
          )
        ),
        array(
          array(
              "text" => $data['mainKeyboard']['values']
          ),
          array(
              "text" => $data['mainKeyboard']['generalInfo']
          )
        ),
        array(
          array(
              "text" => $data['mainKeyboard']['rules']
          ),
          array(
              "text" => $data['mainKeyboard']['exit']
          )
        )
      ),
      "resize_keyboard" => true,
      "one_time_keyboard" => true
    );

    return $keyboard;
  }

}
