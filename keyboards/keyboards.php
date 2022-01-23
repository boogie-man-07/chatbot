<?php
/**
 * Created by PhpStorm.
 * User: murad
 * Date: 05.01.2018
 * Time: 15:38
 */


class keyboards {

    function helloKeyboard() {
        $keyboard = array(
            "keyboard" => array(
                array(
                    array(
                    "text" => "Авторизация по email"
                    )
                ),
                array(
                    array(
                    "text" => "Авторизация по SMS"
                    )
                )
            ),
            "resize_keyboard" => true,
            "one_time_keyboard" => true
        );
        return json_encode($keyboard);
    }

    function backToStartKeyboard() {
        $keyboard = array(
            "keyboard" => array(
                array(
                    array(
                    "text" => "Вернуться в начало"
                    )
                )
            ),
            "resize_keyboard" => true,
            "one_time_keyboard" => true
        );
        return json_encode($keyboard);
    }

    function smsAuthorizationKeyboard() {
        $keyboard = array(
            "keyboard" => array(
                array(
                    array(
                        "text" => "Передать мобильный номер",
                        'request_contact'=>true
                    )
                ),
                array(
                    array(
                        "text" => "Вернуться в начало"
                    )
                )
            ),
            "resize_keyboard" => true,
            "one_time_keyboard" => true
        );
        return json_encode($keyboard);
    }

    function emailAuthorizationProceedKeyboard() {
        $keyboard = array(
            "inline_keyboard" => array(
                array(
                    array(
                            "text" => "Продолжить",
                            "callback_data" => "sendMessage"
                    )
                )
            )
        );
        return json_encode($keyboard);
    }

//   function mainKeyboard(): string {
//
//     $keyboard = array(
//       "keyboard" => array(
//         array(
//           array(
//             "text" => $data['mainKeyboard']['phones']
//           ),
//           array(
//             "text" => $data['mainKeyboard']['salary']
//           )
//         ),
//         array(
//           array(
//               "text" => $data['mainKeyboard']['values']
//           ),
//           array(
//               "text" => $data['mainKeyboard']['generalInfo']
//           )
//         ),
//         array(
//           array(
//               "text" => $data['mainKeyboard']['rules']
//           ),
//           array(
//               "text" => $data['mainKeyboard']['exit']
//           )
//         )
//       ),
//       "resize_keyboard" => true,
//       "one_time_keyboard" => true
//     );
//     $markup = json_encode($keyboard);
//     return $markup;
//   }

}
