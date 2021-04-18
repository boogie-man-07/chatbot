<?php
/**
 * Created by PhpStorm.
 * User: murad
 * Date: 16.01.2018
 * Time: 21:59
 */



 $confirmationCode = null;
 $token = "1715673258:AAEy6Msgyaj_U8XFQtQFD7v0EbN4qOWjHXE";
 $website = "https://api.telegram.org/bot".$token;

 $updates = file_get_contents('php://input');
 $updates = json_decode($updates,TRUE);

 $text = $updates['message']['text'];
 $chatID = $updates['message']['from']['id'];
 $username = $updates['message']['from']['first_name'];
 $query = $updates["callback_query"];
 $queryID = $query["id"];
 $queryUserID = $query["from"]["id"];
 $queryData = $query["data"];
 $queryUserName = $query["from"]["first_name"];


 // STEP 1. Build connection
 // Secure way to biuld connection
 $file = parse_ini_file("Botdb.ini"); // accessing the file with connection info


 // store in php var information from ini var
 $host = trim($file["dbhost"]);
 $user = trim($file["dbuser"]);
 $pass = trim($file["dbpass"]);
 $name = trim($file["dbname"]);


 // include access.php to call func from access.php file
 require ("secure/access.php");
 $access = new access($host, $user, $pass, $name);
 $access->connect();


switch ($text) {

    case '/start':
        $result = $access->getUserByChatID($chatID);
        if ($result) {
            $fullname = $result["fullname"];
            $isAuthorized = $result["is_authorized"];

            if (!$isAuthorized) {
                // Если пользователя с таким chatID есть в БД, но он не проходил авторизацию
                $reply = "Добро пожаловать, ".$fullname."!\nЯ Ваш помошник по вопросам к отделу персонала.\nПройдите простую авторизацию, чтобы я мог удостовериться, что Вы являетесь сотрудником Компании";
                $keyboard = array(
                    "keyboard" => array(
                        array(
                            array(
                                "text" => "Авторизация"
                            )
                        )
                    ),
                    "resize_keyboard" => true,
                    "one_time_keyboard" => true

                );
                $markup = json_encode($keyboard);
                $access->setState($chatID, "waiting for authorization");
                sendMessage($chatID, $reply, $markup);
                break;

            } else {
                // Если пользователь с таким chatID есть в БД, и он проходил авторизацию
                $reply = "С возвращением, ".$fullname."!\nНиже команды меню, которые я умею выполнять!\nЕсли не знаете с чего начать, посмотрите раздел <b>\"Помощь</b>\"";
                $keyboard = array(
                    "keyboard" => array(
                        array(
                            array(
                                "text" => "Заявления"
                            ),
                            array(
                                "text" => "Телефоны"
                            ),
                            array(
                                "text" => "Отпуска"
                            )
                        ),
                        array(
                            array(
                                "text" => "Расчетный листок"
                            ),
                            array(
                                "text" => "Помощь"
                            )
                        )
                    ),
                    "resize_keyboard" => true,
                    "one_time_keyboard" => false
                );
                $markup = json_encode($keyboard);
                sendMessage($chatID, $reply, $markup);
                break;
            }

        } else {
            // Если пользователя с таким chatID нет в БД
            $reply = "Добро пожаловать, ".$username."!\nЯ Ваш помошник по вопросам к отделу персонала.\nПройдите простую авторизацию, чтобы я мог удостовериться, что Вы являетесь сотрудником Компании";
            $keyboard = array(
                "keyboard" => array(
                    array(
                        array(
                            "text" => "Авторизация"
                        )
                    )
                ),
                "resize_keyboard" => true,
                "one_time_keyboard" => false

            );
            $markup = json_encode($keyboard);
            $access->setState($chatID, "waiting for authorization");
            sendMessage($chatID, $reply, $markup);
            break;
        }

    case 'Авторизация':
        $result = $access->getUserByChatID($chatID);
        if ($result) {
            $fullname = $result["fullname"];
            $isAuthorized = $result["is_authorized"];

            if (!$isAuthorized) {

                $stateResult = $access->setState($chatID, "waiting for personal number");

                $reply = "Пожалуйста, введите логин Вашей учетной записи.\nЛогин - это текст вашего email, который расположен до знака @";
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
                $markup = json_encode($keyboard);
                sendMessage($chatID, $reply, $markup);
                break;

            } else {
                $reply = "$fullname, авторизация не требуется, Вы уже подтвердили, что являетесь сотрудником Компании.\nВоспользуйтесь командами меню или нажмите /Помощь, чтобы ознакомиться с моими возможностями.";
                $keyboard = array(
                    "keyboard" => array(
                        array(
                            array(
                                "text" => "Заявления"
                            ),
                            array(
                                "text" => "Телефоны"
                            ),
                            array(
                                "text" => "Отпуска"
                            )
                        ),
                        array(
                            array(
                                "text" => "Расчетный листок"
                            ),
                            array(
                                "text" => "Помощь"
                            )
                        )
                    ),
                    "resize_keyboard" => true,
                    "one_time_keyboard" => true

                );
                $markup = json_encode($keyboard);
                sendMessage($chatID, $reply, $markup);
                break;
            }
        } else {

          $stateResult = $access->setState($chatID, "waiting for personal number");

          $reply = "Пожалуйста, введите логин Вашей учетной записи.\nЛогин - это текст вашего email, который расположен до знака @";
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
          $markup = json_encode($keyboard);
          sendMessage($chatID, $reply, $markup);
          break;
        }

    case 'Вернуться в начало':
        $stateResult = $access->getState($chatID);
        $state = $stateResult["dialog_state"];
        if ($state != 'waiting for authorization') {
            $access->setState($chatID, "waiting for authorization");
            $reply = "$username, пройдите простую авторизацию, чтобы я мог удостовериться, что Вы являетесь сотрудником Компании";
            $keyboard = array(
                "keyboard" => array(
                    array(
                        array(
                            "text" => "Авторизация"
                        )
                    )
                ),
                "resize_keyboard" => true,
                "one_time_keyboard" => true

            );
            $markup = json_encode($keyboard);
            sendMessage($chatID, $reply, $markup);
            break;
        }

    case 'Телефоны':
        $user = $access->getUserByChatID($chatID);
        $isAuthorized = $user["is_authorized"];

        if ($isAuthorized) {

            $access->setState($chatID, "find telefone number");
            $reply = "Введите Имя и Фамилию сотрудника\nПример: <b>Иван Петров</b>";
            sendMessage($chatID, $reply, null);
            break;

        } else {
            $reply = "Данная команда доступна только авторизованным пользователям.\nПройдите процедуру авторизации для подтверждения, что Вы являетесь сотрудником Компании.";
            $keyboard = array(
                            "inline_keyboard" => array(
                                array(
                                    array(
                                        "text" => "Перейти к процессу авторизации?",
                                        "callback_data" => "go to the start"
                                    )
                                )
                            )
                        );
            $markup = json_encode($keyboard);
            sendMessage($chatID, $reply, $markup);
            break;
        }

    default:
        // Обработка всех непредустановленных запросов
        $stateResult = $access->getState($chatID);
        $state = $stateResult["dialog_state"];
        switch ($state) {

            case 'waiting for authorization':
                $reply = "Здравствуй, я твой помошник по внутренним вопросам для сотрудников Компании.\nДля начала использования моих возможностей мне нужно убедиться, что ты являешься сотрудником компании, для чего необходимо пройти простую авторизацию.\nПожалуйста, нажми на кнопку /Авторизация в меню снизу и следуй моим инструкциям.";
                sendMessage($chatID, $reply, null);
                break;

            case 'waiting for personal number':

                if (preg_match('/([A-Za-z])/', mb_strtolower($text))) {

                    $result = $access->getUserByPersonnelNumber($text);
                    if ($result) {
                        $fullname = $result["fullname"];
                        $emailString = $result["email"];
                        $position = $result["position"];

                        $at = strpos($emailString,  "@");
                        $login = mb_strtolower(substr($emailString, 0, $at), $encoding='UTF-8');
                        $comparsion_result = strcmp(mb_strtolower($text, $encoding='UTF-8'), $login);

                        if ($comparsion_result == 0) {
                          require ("secure/email.php");

                          // store all class in $email var
                          $email = new email();

                          $confirmationCode = $email->generateConfirmationCode(10);
                          $access->saveConfirmationCode($confirmationCode, $chatID, $emailString);
                          $access->setState($chatID, "waiting for confirmation code");
                          $reply = $fullname.", поздравляю!\nНажмите продолжить, для получения письма на рабочую почту с инструкциями по завершению авторизации.";
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
                          $markup = json_encode($keyboard);
                          sendMessage($chatID, $reply, $markup);
                          break;
                        } else {
                          $reply = "Сотрудник с таким логином не числится в Компании. Проверьте правильность введенного логина и попробуйте снова.";
                          sendMessage($chatID, $reply, null);
                          break;
                        }

                    } else {
                        $reply = "Сотрудник с таким логином не числится в Компании. Проверьте правильность введенного логина и попробуйте снова.";
                        sendMessage($chatID, $reply, null);
                        break;
                    }

                } else {

                    $reply = "Неверный формат логина. Логин может содержать только латинские буквы и не может быть менее двух символов в длину. Попробуйте снова.";
                    sendMessage($chatID, $reply, null);
                    break;
                }

            case 'waiting for confirmation code':

                if ((preg_match('^/[A-Za-z0-9]/', $text)) || (strlen($text) != 10)) {

                    $reply = "Неверный формат кода подтверждения.\nКод может содержать латинские буквы, цифры и специальные знаки и не может быть меньше 10 символов.\nПопробуйте снова.";
                    sendMessage($chatID, $reply, null);
                    break;

                } else {

                    $result = $access->getUserByChatID($chatID);
                    if ($result) {

                      $confirmation_code = $result["confirmation_code"];
                      $fullname = $result["fullname"];
                      $string_result = strcmp($text, $confirmation_code);

                      if ($string_result == 0) {

                        $expirationDate = new DateTime($result['confirmation_code_creation_date']);
                        $now = new DateTime();

                        if ($expirationDate > $now) {

                          $result = $access->updateAuthorizationFlag(1, null, $chatID);
                          if ($result) {
                              $access->setState($chatID, "authorization completed");
                              $reply = "Поздравляю, $fullname!\nВы успешно прошли процедуру авторизации и можете использовать меня на полную катушку!\nНиже меню с командами, которые я умею выполнять.";
                              $keyboard = array(
                                  "keyboard" => array(
                                      array(
                                          array(
                                              "text" => "Заявления"
                                          ),
                                          array(
                                              "text" => "Телефоны"
                                          ),
                                          array(
                                              "text" => "Отпуска"
                                          )
                                      ),
                                      array(
                                          array(
                                              "text" => "Расчетный листок"
                                          ),
                                          array(
                                              "text" => "Помощь"
                                          )
                                      )
                                  ),
                                  "resize_keyboard" => true,
                                  "one_time_keyboard" => true

                              );
                              $markup = json_encode($keyboard);
                              sendMessage($chatID, $reply, $markup);
                              break;
                          }

                        } else {
                          $reply = "Время жизни кода активации истекло.\nПолучите код снова.";
                          $keyboard = array(
                              "inline_keyboard" => array(
                                  array(
                                      array(
                                          "text" => "Авторизоваться заново?",
                                          "callback_data" => "go to the start"
                                      )
                                  )
                              )
                          );
                          $markup = json_encode($keyboard);
                          sendMessage($chatID, $reply, $markup);
                          break;
                        }


                      } else {

                          $reply = "код неверен.\nПопробуйте снова.";
                          sendMessage($chatID, $reply, null);
                          break;
                      }




                    } else {

                        $reply = "Ошибка связи.\nПопробуйте снова.";
                        sendMessage($chatID, $reply, null);
                        break;
                    }
                }

            case 'find telefone number':

                $space = strpos($text,  " ");
                $lastname = mb_strtolower(substr($text, $space + 1), $encoding='UTF-8');
                $firstname = mb_strtolower(substr($text, 0, $space), $encoding='UTF-8');

                $result = $access->getPhoneNumberByFirstnameAndLastName($firstname, $lastname);
                if ($result) {
                    $access->setState($chatID, "authorization completed");

                    $reply = "<b>Карточка сотрудника</b>\nФИО: ".$result["fullname"]."\nРабочий телефон: <b>".$result["office_number"]."</b>\nМобильный телефон: <b>".$result["mobile_number"]."</b>\nДолжность: ".$result["position"]."\nКомпания: ".$result["company_name"];
                    sendMessage($chatID, $reply, null);
                    break;

                } else {
                    $reply = "Вероятно данный сотрудник не работает в Компании, проверьте, пожалуйста, нет ли ошибок в написании имени и фамилии и попробуйте снова.";
                    sendMessage($chatID, $reply, null);
                    break;
                }

            case 'waiting for feedback':

                $user = $access->getUserByChatID($chatID);

                if ($user) {

                    $fullname = $user["fullname"];
                    $emailAddress = $user["email"];
                    $position = $user["position"];

                    // ОТПРАВКА ПИСЬМА НА EMAIL
                    require ("secure/email.php");
                    $email = new email();

                    //Данные для письма
                    $details = array();
                    $details["subject"] = "Жалоба/Предложение от пользователя Company HR Bot";
                    $details["to"] = 'chernuylab@gmail.com';
                    $details["fromName"] = "Company HR Team";
                    $details["fromEmail"] = "info.chernuylabs.ru";
                    $details["body"] = "<b>ФИО сотрудника:</b> $fullname<br><b>Должность:</b> $position<br><b>email:</b> $emailAddress<br><br><b>Сообщение:</b><br>$text";
                    $isSended = $email->sendEmail($details);

                    if ($isSended) {
                        $access->setState($chatID, "authorization completed");
                        $reply = "Уважаемый(ая) ".$fullname.", Ваше обращение принято и будет рассмотрено в ближайшее время!\nСпасибо за обращение!";
                        sendMessage($chatID, $reply, null);
                        break;

                    } else {
                        $reply = "Не удалось отправить письмо.\nПопробуйте немного позднее.";
                        sendMessage($chatID, $reply, null);
                        break;
                    }

                } else {
                    $reply = "Ошибка связи.\nПопробуйте снова.";
                    sendMessage($chatID, $reply, null);
                    break;
                }

                case 'waiting for bug report':

                    $user = $access->getUserByChatID($chatID);

                    if ($user) {

                        $fullname = $user["fullname"];
                        $emailAddress = $user["email"];
                        $position = $user["position"];

                        // ОТПРАВКА ПИСЬМА НА EMAIL
                        require ("secure/email.php");
                        $email = new email();

                        //Данные для письма
                        $details = array();
                        $details["subject"] = "Сообщение об ошибке от пользователя Company HR Bot";
                        $details["to"] = 'chernuylab@gmail.com';
                        $details["fromName"] = "Company HR Team";
                        $details["fromEmail"] = "info.chernuylabs.ru";
                        $details["body"] = "<b>ФИО сотрудника:</b> $fullname<br><b>Должность:</b> $position<br><b>email:</b> $emailAddress<br><br><b>Сообщение:</b><br>$text";
                        $isSended = $email->sendEmail($details);

                        if ($isSended) {
                            $access->setState($chatID, "authorization completed");
                            $reply = "Уважаемый(ая) ".$fullname.", Ваше обращение принято и будет рассмотрено в ближайшее время!\nСпасибо за обращение!";
                            sendMessage($chatID, $reply, null);
                            break;

                        } else {
                            $reply = "Не удалось отправить письмо.\nПопробуйте немного позднее.";
                            sendMessage($chatID, $reply, null);
                            break;
                        }

                    } else {
                        $reply = "Ошибка связи.\nПопробуйте снова.";
                        sendMessage($chatID, $reply, null);
                        break;
                    }

            case 'authorization completed':
                $reply = "Команда непонятна, воспользуйтесь кнопками меню!";
                sendMessage($chatID, $reply, null);
                break;
        }

}


switch ($queryData) {

    case 'sendMessage':

        $result = $access->getUserByChatID($queryUserID);

        if ($result) {

            $confirmation_code = $result["confirmation_code"];
            $emailAddress = $result["email"];
            $fullname = $result["fullname"];

            // ОТПРАВКА ПИСЬМА НА EMAIL
            require ("secure/email.php");
            $email = new email();

            //Данные для письма
            $details = array();
            $details["subject"] = "Подтверждение регистрации в Company HR Bot";
            $details["to"] = $emailAddress;
            $details["fromName"] = "HR Team";
            $details["fromEmail"] = "info.chernuylabs.ru";

            // Доступ к шаблону письма
            $template = $email->confirmationTemplate();

            // replace {token} from confirmationTemplate.html by $token and store all content in $template var
            $template = str_replace("{confirmationCode}", $confirmation_code, $template);
            $template = str_replace("{fullname}", $fullname, $template);

            $details["body"] = $template;
            $email->sendEmail($details);

            $reply = $fullname.", письмо с кодом потверждения направлено на Ваш рабочий email.\nПожалуйста, введите код подтверждения!";
            sendMessage($queryUserID, $reply, null);
            break;
        } else {
            $reply = "Не удалось отправить письмо.\nПопробуйте немного позднее.";
            sendMessage($queryUserID, $reply, null);
            break;
        }

    case 'feedback':

        $access->setState($queryUserID, "waiting for feedback");
        $reply = "Опишите Вашу жалобу или предложение!\nДля нас важны все отзывы, чтобы вместе сделать сервис еще лучше!";
        sendMessage($queryUserID, $reply, null);
        break;

    case 'bug report':

        $access->setState($queryUserID, "waiting for bug report");
        $reply = "Опишите, пожалуйста, максимально подробно обнаруженную Вами ошибку.\nВместе мы сделаем сервис лучше!";
        sendMessage($queryUserID, $reply, null);
        break;

    case 'go to the start':
        $reply = $queryUserName."!\nЯ твой помошник по вопросам к отделу персонала.\nПройди простую авторизацию, чтобы я мог удостовериться, что ты являешься сотрудником Компании";
        $keyboard = array(
            "keyboard" => array(
                array(
                    array(
                        "text" => "Авторизация"
                    )
                )
            ),
            "resize_keyboard" => true,
            "one_time_keyboard" => false

        );
        $markup = json_encode($keyboard);
        $access->setState($queryUserID, "waiting for authorization");
        sendMessage($queryUserID, $reply, $markup);
        break;
}



// STEP 2. Close connection
$access->disconnect();

function sendMessage($chatID, $text, $keyboard) {
    $url = $GLOBALS[website]."/sendMessage?chat_id=$chatID&parse_mode=HTML&text=".urlencode($text)."&reply_markup=".$keyboard;
    file_get_contents($url);
}

function deleteMessage($chatID, $messageID) {
    $url = $GLOBALS[website]."/deleteMessage?chat_id=".$chatID."&message_id=".$messageID;
    file_get_contents($url);
}

// Первая буква заглавная, работает для русского языка
function mb_ucfirst($str, $encoding='UTF-8')
{
    $str = mb_ereg_replace('^[\ ]+', '', $str);
    $str = mb_strtoupper(mb_substr($str, 0, 1, $encoding), $encoding).
        mb_substr($str, 1, mb_strlen($str), $encoding);
    return $str;
}


?>
