<?php
/**
 * Created by Murad Adygezalov
 * Date: 28.03.2021
 * Time: 16:59
 */


// STEP 1. Build connection
// Secure way to biuld connection
$file = parse_ini_file("../Botdb.ini"); // accessing the file with connection info

// store in php var information from ini var
$host = trim($file["dbhost"]);
$user = trim($file["dbuser"]);
$pass = trim($file["dbpass"]);
$name = trim($file["dbname"]);
$token = trim($file["token"]);

$confirmationCode = null;
$website = "https://api.telegram.org/bot".$token;

$updates = file_get_contents('php://input');
$updates = json_decode($updates,TRUE);

$text = $updates['message']['text'];
$chatID = $updates['message']['from']['id'];
$phoneNumber = $updates['message']['contact']['phone_number'];
$username = $updates['message']['from']['first_name'];
$query = $updates["callback_query"];
$queryID = $query["id"];
$queryUserID = $query["from"]["id"];
$queryData = $query["data"];
$queryUserName = $query["from"]["first_name"];

require ("vendor/autoload.php");
require ("keyboards/keyboards.php");
require ("constants/constants.php");
// require ("logs/logs.php");

$json = file_get_contents('constants/localization.json');
$data = json_decode($json, true);

// include access.php to call func from access.php file
require ("secure/access.php");
$access = new access($host, $user, $pass, $name);
$access->connect();


// Main logics
switch ($text) {

  case '/start':
    $result = $access->getUserByChatID($chatID);
    if ($result) {
      $fullname = $result["fullname"];
      $isAuthorized = $result["is_authorized"];

      if (!$isAuthorized) {
        # Обработка кейса, когда пользователя с таким chatID есть в БД, но авторизацию он не довел до конца
        $reply = "Привет, $username!\nЯ Ваш личный ассистент по возникающим внутренним вопросам Компании.\nПохоже вы уже заглядывали в гости, но что-то пошло не так и мы не завершили авторизацию. Готовы попробовать еще раз?\nНажмите \"Авторизация\" в меню ниже. Это не займет много времени.";
        $keyboard = array(
          "keyboard" => array(
            array(
              array(
                "text" => "Авторизация по email"
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
        # Обработка кейса, когда пользователя с таким chatID есть в БД и ранее он уже успешно авторизовался
        // $logs = new logs();
        // $logs->log($text, $fullname);
        $reply = "С возвращением, $fullname!\nПока я умею выполнять команды в меню ниже, но я постоянно учусь!";
        $keyboard = array(
          "keyboard" => array(
            array(
              array(
                "text" => $data['mainKeyboard']['phones']
              ),
              array(
                "text" => "КДП и Заработная плата"
              )
            ),
            array(
              array(
                  "text" => "Наши ценности"
              ),
              array(
                  "text" => "Общая информация"
              )
            ),
            array(
              array(
                  "text" => "Правила"
              ),
              array(
                  "text" => "Выход"
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
      # Обработка кейса, когда пользователя с таким chatID нет в БД
      $reply = "Привет, $username!\nЯ Ваш личный ассистент по возникающим внутренним вопросам Компании.\nПохоже вы зашли впервые, давайте убедимся, что вы являетесь сотрудником Компании, для этого, нажмите \"Авторизация\" в меню ниже. Это не займет много времени.";
      $keyboard = array(
        "keyboard" => array(
          array(
            array(
              "text" => "Авторизация по email"
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
    }

  case 'Авторизация по email':
    $result = $access->getUserByChatID($chatID);
    if ($result) {
      $fullname = $result["fullname"];
      $isAuthorized = $result["is_authorized"];
      if (!$isAuthorized) {
        # Кейс когда неавторизованный пользователь нажал Авторизация и бот переходит в режим ожидания ввода логина
        $stateResult = $access->setState($chatID, "waiting for login");
        $reply = "Пожалуйста, введите логин вашей учетной записи.\nЛогин - это часть email, расположенная до знака @";
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
        # Кейс когда неавторизованный пользователь нажал Авторизация и бот переходит в режим ожидания ввода логина
        $reply = "$fullname, воспользуйтесь командами меню.";
        $$keyboard = array(
          "keyboard" => array(
            array(
              array(
                "text" => $data['mainKeyboard']['phones']
              ),
              array(
                "text" => "Заработная плата"
              )
            ),
            array(
              array(
                  "text" => "Наши ценности"
              ),
              array(
                  "text" => "Общая информация"
              )
            ),
            array(
              array(
                  "text" => "Правила"
              ),
              array(
                  "text" => "Выход"
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
      # Кейс когда нажали Авторизация и бот переходит в режим ожидания ввода логина
      $stateResult = $access->setState($chatID, "waiting for login");
      $reply = "Пожалуйста, введите логин вашей учетной записи.\nЛогин - это часть email, расположенная до знака @";
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
      $reply = mb_ucfirst($username)."!\nЯ Ваш личный ассистент по возникающим внутренним вопросам Компании. Для использования моих возможностей необходимо авторизироваться.";
      $keyboard = array(
        "keyboard" => array(
          array(
            array(
              "text" => "Авторизация по email"
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

  case $data['mainKeyboard']['phones']:
    $user = $access->getUserByChatID($chatID);
    $isAuthorized = $user["is_authorized"];
    if ($isAuthorized) {
      $access->setState($chatID, "find telefone number");
      $reply = "Введи Имя и Фамилию сотрудника\nПример: <b>Иван Петров</b>";
      sendMessage($chatID, $reply, null);
      break;
    } else {
      $reply = "Данная команда доступна только авторизованным пользователям.\nПройдите процедуру авторизации для подтверждения, что вы являетесь сотрудником Компании.";
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

  case 'КДП и Заработная плата':
    $access->setState($chatID, "salary");
    $reply = "Выберите пункт меню, для получения информации о заработной плате";
    $keyboard = array(
      "keyboard" => array(
        array(
          array(
            "text" => "Общая информация"
          ),
          array(
            "text" => "Сроки выплаты"
          ),
        ),
        array(
          array(
            "text" => "Заявления"
          ),
          array(
            "text" => "Назад"
          )
        )
      ),
      "resize_keyboard" => true,
      "one_time_keyboard" => true
    );
    $markup = json_encode($keyboard);
    sendMessage($chatID, $reply, $markup);
    break;

  case 'Заявления':
    $user = $access->getUserByChatID($chatID);
    $firstname = $user['firstname'];
    $isAuthorized = $user["is_authorized"];
    if ($isAuthorized) {
      $reply = "$firstname,\nОбразец какого заявления Вы хотели бы получить?";
      $keyboard = array(
        "inline_keyboard" => array(
          array(
            array(
              "text" => "Заявление на отпуск",
              "callback_data" => "regularVacationCase"
            )
          ),
          array(
            array(
              "text" => "Заявление на перенос отпуска",
              "callback_data" => "postponeVacationCase"
            )
          )
        )
      );
      $markup = json_encode($keyboard);
      sendMessage($chatID, $reply, $markup);
      break;
    } else {
      $reply = "Данная команда доступна только авторизованным пользователям.\nПройдите процедуру авторизации для подтверждения, что вы являетесь сотрудником Компании.";
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

  case 'Наши ценности':
    $user = $access->getUserByChatID($chatID);
    $constants = new constants();
    $reply = $constants->getWelcomeValueText($user['firstname']);
    $keyboard2 = array(
      "inline_keyboard" => array(
        array(
          array(
            "text" => "Далее",
            "callback_data" => "getFirstRuleText"
          )
        )
      ),
      "resize_keyboard" => true,
      "one_time_keyboard" => true
    );
    $markup2 = json_encode($keyboard2);
    sendMessage($chatID, $reply, $markup2);
    break;



  case 'Общая информация':
    $stateResult = $access->getState($chatID);
    $state = $stateResult["dialog_state"];
    switch ($state) {
      case 'salary':
        $user = $access->getUserByChatID($chatID);
        if ($user) {
          switch ($user["company_id"]) {
            case 1:
              $reply = "Заработная плата в Компании конфиденциальна, руководители на всех уровнях обязаны обеспечивать ее конфиденциальность.";
              sendMessage($chatID, $reply, null);
              break;
            case 2 || 3:
              $reply = "Заработная плата в Компании конфиденциальна, руководители на всех уровнях обязаны обеспечивать ее конфиденциальность.\nРаботник может узнать грейд своей должности и базовую заработную плату только у своего руководителя и только в Департаменте по работе с персоналом.";
              sendMessage($chatID, $reply, null);
              break;
          }
          break;
        } else {
          $reply = "Упс, что-то я задремал, напомните, что вы хотели?";
          sendMessage($chatID, $reply, null);
          break;
        }
      default:
        $user = $access->getUserByChatID($chatID);
        $companyID = $user['company_id'];
        $reply = "Выберите пункт меню, для получения общей информации.";
        switch ($companyID) {
          case 1:
            $keyboard = array(
              "keyboard" => array(
                array(
                  array(
                    "text" => "Как добраться"
                  )
                ),
                array(
                  array(
                    "text" => "Схема проезда (Сколково)"
                  )
                ),
                array(
                  array(
                    "text" => "Назад"
                  )
                )
              ),
              "resize_keyboard" => true,
              "one_time_keyboard" => true
            );
            break;
          case 2:
            $keyboard = array(
              "keyboard" => array(
                array(
                  array(
                    "text" => "Как добраться"
                  )
                ),
                array(
                  array(
                    "text" => "Схема проезда (Сколково)"
                  ),
                  array(
                    "text" => "Схема проезда (Ст.Оскол)"
                  )
                ),
                array(
                  array(
                    "text" => "Назад"
                  )
                )
              ),
              "resize_keyboard" => true,
              "one_time_keyboard" => true
            );
            break;
          case 3:
            $keyboard = array(
              "keyboard" => array(
                array(
                  array(
                    "text" => "Как добраться"
                  )
                ),
                array(
                  array(
                    "text" => "Схема проезда (Сколково)"
                  ),
                  array(
                    "text" => "Схема проезда (Саратов)"
                  )
                ),
                array(
                  array(
                    "text" => "Назад"
                  )
                )
              ),
              "resize_keyboard" => true,
              "one_time_keyboard" => true
            );
            break;
        }

        $markup = json_encode($keyboard);
        sendMessage($chatID, $reply, $markup);
        break;
    }
    break;

  case 'Правила':
    $reply = "Выберите пункт меню, для получения информации о правилах поведения в Компании.";
    $keyboard = array(
      "keyboard" => array(
        array(
          array(
            "text" => "Проведение совещаний"
          ),
          array(
            "text" => "Общение по телефону"
          ),
        ),
        array(
          array(
            "text" => "Работа в офисах"
          ),
          array(
            "text" => "Внешний вид"
          ),
        ),
        array(
          array(
            "text" => "Назад"
          )
        )
      ),
      "resize_keyboard" => true,
      "one_time_keyboard" => true
    );
    $markup = json_encode($keyboard);
    sendMessage($chatID, $reply, $markup);
    break;

  case 'Сроки выплаты':
    $user = $access->getUserByChatID($chatID);
    if ($user) {
      $constants = new constants();
      $reply = $constants->getPaymentText($user["company_id"]);
      sendMessage($chatID, $reply, null);
      break;
    } else {
      $reply = "Ой, что-то я задремал, напомните, что вы хотели?";
      sendMessage($chatID, $reply, null);
      break;
    }
    break;

  case 'Как добраться':
    $user = $access->getUserByChatID($chatID);
    if ($user) {
      $constants = new constants();
      $reply = $constants->getRouteText($user["company_id"]);
      sendMessage($chatID, $reply, null);
      break;
    } else {
      $reply = "Ой, что-то я задремал, напомните, что вы хотели?";
      sendMessage($chatID, $reply, null);
      break;
    }
    break;

  case 'Схема проезда (Сколково)':
    sendPhoto($chatID, 'https://sigmabot.ddns.net/hrbot/files/skolkovo_map.jpg', null);
    break;

  case 'Схема проезда (Ст.Оскол)':
    sendPhoto($chatID, 'https://sigmabot.ddns.net/hrbot/files/greenhouse_map.jpg', null);
    break;

  case 'Схема проезда (Саратов)':
    sendPhoto($chatID, 'https://sigmabot.ddns.net/hrbot/files/diall_map.jpg', null);
    break;

  case 'Проведение совещаний':
    $user = $access->getUserByChatID($chatID);
    $constants = new constants();
    $reply = $constants->getMeetingsRulesText($user["firstname"]);
    sendMessage($chatID, $reply, null);
    break;

  case 'Общение по телефону':
    $user = $access->getUserByChatID($chatID);
    $constants = new constants();
    $reply = $constants->getPhoneConversationsRulesText($user["firstname"]);
    sendMessage($chatID, $reply, null);
    break;

  case 'Работа в офисах':
    $user = $access->getUserByChatID($chatID);
    $constants = new constants();
    $reply = $constants->getOfficeRulesText($user["firstname"]);
    sendMessage($chatID, $reply, null);
    break;

  case 'Внешний вид':
    $user = $access->getUserByChatID($chatID);
    $constants = new constants();
    $reply = $constants->getAppearanceRulesText($user["firstname"]);
    sendMessage($chatID, $reply, null);
    break;

  case 'Назад':
    $access->setState($chatID, "authorization completed");
    $reply = "Вы в главном меню, для получения информации воспользуйтесь командами меню ниже.";
    $keyboard = array(
      "keyboard" => array(
        array(
          array(
            "text" => $data['mainKeyboard']['phones']
          ),
          array(
            "text" => "КДП и Заработная плата"
          )
        ),
        array(
          array(
              "text" => "Наши ценности"
          ),
          array(
              "text" => "Общая информация"
          )
        ),
        array(
          array(
              "text" => "Правила"
          ),
          array(
              "text" => "Выход"
          )
        )
      ),
      "resize_keyboard" => true,
      "one_time_keyboard" => true
    );
    $markup = json_encode($keyboard);
    sendMessage($chatID, $reply, $markup);
    break;

  case 'Выход':
    $isUserRemoved = $access->removeUserCredentialsByChatID($chatID);
    $isUserStateRemoved = $access->removeUserStateByChatID($chatID);

    if ($isUserRemoved && $isUserStateRemoved) {
      $reply = mb_ucfirst($username)."!\nЯ Ваш личный ассистент по возникающим внутренним вопросам Компании. Для использования моих возможностей необходимо авторизироваться.";
      $keyboard = array(
        "keyboard" => array(
          array(
            array(
              "text" => "Авторизация по email"
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

    default:
        $stateResult = $access->getState($chatID);
        $user = $access->getUserByChatID($chatID);
        $fullname = $user['fullname'];
        $state = $stateResult["dialog_state"];

        if (substr_count(trim($text), ' ') == 1) {
            // case with check if phone required
            $reply = "Для получения информации воспользуйтесь командами меню ниже.";
            $keyboard = array(
              "keyboard" => array(
                array(
                  array(
                    "text" => 'Карточка'
                  ),
                  array(
                    "text" => "Email"
                  )
                ),
                array(
                  array(
                      "text" => "Мобильный телефон"
                  ),
                  array(
                      "text" => "Рабочий телефон"
                  )
                ),
                array(
                  array(
                      "text" => "Назад"
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

            switch ($state) {

                case 'waiting for authorization':
                    // $logs = new logs();
                    // $logs->log($text, $fullname);
                    $reply = "Ничего не понял, но я быстро учусь ".hex2bin('f09f9982').". Пожалуйста, воспользуйтесь командами в меню ниже!";
                    sendMessage($chatID, $reply, null);
                    break;

                case 'waiting for login':
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
                                $email = new email();
                                $confirmationCode = $email->generateConfirmationCode(10);
                                $access->saveConfirmationCode($confirmationCode, $chatID, $emailString);

                                $access->setState($chatID, "waiting for confirmation code");
                                $reply = "$fullname, нажмите продолжить, для получения письма на рабочую почту с инструкциями по завершению авторизации.";
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
                        $reply = "Неверный формат логина.\nЛогин может содержать латинские буквы и цифры.\nПопробуйте снова.";
                        sendMessage($chatID, $reply, null);
                        break;
                    }

      case 'waiting for confirmation code':
        if ((preg_match('^/[A-Za-z0-9]/', $text)) || (strlen($text) < 10)) {
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
              $expirationDate = new DateTime($result['confirmation_code_expiration_date']);
              $now = new DateTime();
              if ($expirationDate > $now) {
                $result = $access->updateAuthorizationFlag(1, null, $chatID);
                if ($result) {
                  $access->setState($chatID, "authorization completed");
                  $reply = "Поздравляю, $fullname! Вы успешно прошли процедуру авторизации и можете использовать меня на полную катушку!\nНиже меню с командами, которые я умею выполнять.";
                  $keyboard = array(
                    "keyboard" => array(
                      array(
                        array(
                          "text" => $data['mainKeyboard']['phones']
                        ),
                        array(
                          "text" => "КДП и Заработная плата"
                        )
                      ),
                      array(
                        array(
                            "text" => "Наши ценности"
                        ),
                        array(
                            "text" => "Общая информация"
                        )
                      ),
                      array(
                        array(
                            "text" => "Правила"
                        ),
                        array(
                            "text" => "Выход"
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
                $reply = "Время жизни кода активации истекло.\nНеобходима повторить процесс авторизации.";
                $keyboard = array(
                  "inline_keyboard" => array(
                    array(
                      array(
                        "text" => "Авторизоваться",
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
            $reply = "Что-то пошло не так и я не смог найти вас среди сотрудников Компании.\nПопробуйте еще раз.";
            sendMessage($chatID, $reply, null);
            break;
          }
        }

      case 'find telefone number':
        $constants = new constants();
        $space = strpos($text,  " ");
        $lastname = mb_strtolower(substr($text, $space + 1), $encoding='UTF-8');
        $firstname = mb_strtolower(substr($text, 0, $space), $encoding='UTF-8');

        $result = $access->getUserByFirstnameAndLastName($firstname, $lastname);
        if ($result) {
          $user = $access->getUserByChatID($chatID);
          $access->setState($chatID, "authorization completed");
          switch ($result["company_id"]) {
            case 1:
              $reply = $user["is_sigma_available"] ? "<b>Карточка работника</b>\nФИО: ".$result["fullname"]."\nРабочий телефон: <b>".$result["office_number"]."</b>\nДобавочный номер: <b>".$result["internal_number"]."</b>\nМобильный телефон: <b>".$result["mobile_number"]."</b>\nE-mail: <b>".$result["email"]."</b>\nДолжность: <b>".$result["position"]."</b>\nКомпания: <b>".$result["company_name"]."</b>" : $constants->getPhoneCardPrivelegesError($user["firstname"]);
              break;
            case 2:
              $reply = $user["is_greenhouse_available"] ? "<b>Карточка работника</b>\nФИО: ".$result["fullname"]."\nРабочий телефон: <b>".$result["office_number"]."</b>\nДобавочный номер: <b>".$result["internal_number"]."</b>\nМобильный телефон: <b>".$result["mobile_number"]."</b>\nE-mail: <b>".$result["email"]."</b>\nДолжность: <b>".$result["position"]."</b>\nКомпания: <b>".$result["company_name"]."</b>" : $constants->getPhoneCardPrivelegesError($user["firstname"]);
              break;
            case 3:
              $reply = $user["is_diall_available"] ? "<b>Карточка работника</b>\nФИО: ".$result["fullname"]."\nРабочий телефон: <b>".$result["office_number"]."</b>\nДобавочный номер: <b>".$result["internal_number"]."</b>\nМобильный телефон: <b>".$result["mobile_number"]."</b>\nE-mail: <b>".$result["email"]."</b>\nДолжность: <b>".$result["position"]."</b>\nКомпания: <b>".$result["company_name"]."</b>" : $constants->getPhoneCardPrivelegesError($user["firstname"]);
              break;
          }
          sendMessage($chatID, $reply, null);
          break;
        }

      case 'waiting for feedback':
        $user = $access->getUserByChatID($chatID);
        if ($user) {
          $fullname = $user["fullname"];
          $emailAddress = $user["email"];
          $position = $user["position"];

          require ("secure/email.php");
          $email = new email();

          //Данные для письма
          $details = array();
          $details["subject"] = "Жалоба/Предложение от пользователя Company HR Bot";
          $details["to"] = 'chernuylab@gmail.com';
          $details["fromName"] = "HR Team";
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
          $reply = "Что-то пошло не так и я не смог найти вас среди сотрудников.\nПопробуйте еще раз.";
          sendMessage($chatID, $reply, null);
          break;
        }

        case 'waiting for bug report':
          $user = $access->getUserByChatID($chatID);
          if ($user) {
            $fullname = $user["fullname"];
            $emailAddress = $user["email"];
            $position = $user["position"];

            require ("secure/email.php");
            $email = new email();

            //Данные для письма
            $details = array();
            $details["subject"] = "Сообщение об ошибке от пользователя Company HR Bot";
            $details["to"] = 'chernuylab@gmail.com';
            $details["fromName"] = "HR Team";
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
            $reply = "Что-то пошло не так и я не смог найти вас среди сотрудников.\nПопробуйте еще раз.";
            sendMessage($chatID, $reply, null);
            break;
          }

      case 'authorization completed':
        // $logs = new logs();
        // $logs->log($text, $fullname);
        $reply = "Ничего не понял, но я быстро учусь ".hex2bin('f09f9982').". Пожалуйста, воспользуйтесь командами меню ниже!";
        sendMessage($chatID, $reply, null);
        break;
    }

    }
}


switch ($queryData) {

  case 'sendMessage':

    $result = $access->getUserByChatID($queryUserID);

    if ($result) {

      $confirmation_code = $result["confirmation_code"];
      $emailAddress = $result["email"];
      $fullname = $result["fullname"];
      $companyID = $result["company_id"];

      // ОТПРАВКА ПИСЬМА НА EMAIL
      require ("secure/email.php");
      $email = new email();
      //
      // //Данные для письма
      // $details = array();
      // $details["subject"] = "Подтверждение регистрации в telegram-боте \"Персональный ассистент работника\"";
      // $details["to"] = $emailAddress;
      // $details["fromName"] = "HR Team";
      // $details["fromEmail"] = "info.chernuylabs.ru";

      require ("secure/swiftmailer.php");
      $swiftmailer = new swiftmailer();


      // Доступ к шаблону письма
      $template = $email->confirmationTemplate($companyID);

      // replace {token} from confirmationTemplate.html by $token and store all content in $template var
      $template = str_replace("{confirmationCode}", $confirmation_code, $template);
      $template = str_replace("{fullname}", $fullname, $template);

      $swiftmailer->sendMailViaSmtp(
        $companyID,
        $emailAddress,
        "Подтверждение регистрации в telegram-боте \"Персональный ассистент работника\"",
        $template
      );

      // $details["body"] = $template;
      // $email->sendEmail($details);

      $reply = $fullname.", письмо с кодом потверждения направлено на ваш рабочий email.\nПожалуйста, введи код подтверждения!\n\nЕсли вы не получили письмо с кодом, пожалуйста, проверьте папку \"Спам\", возможно письмо там.";
      sendMessage($queryUserID, $reply, null);
      break;
    } else {
      $reply = "Не удалось отправить письмо.\nПопробуйте немного позднее.";
      sendMessage($queryUserID, $reply, null);
      break;
    }

  case 'regularVacationCase':
    $user = $access->getUserByChatID($queryUserID);
    if ($user) {
      $firstname = $user['firstname'];
      $reply = "$firstname, образец заявления на отпуск будет направлен на вашу рабочую почту. Нажмите продолжить.";
      $keyboard = array(
        "inline_keyboard" => array(
          array(
            array(
              "text" => "Продолжить",
              "callback_data" => "sendRegularVacationForm"
            )
          )
        )
      );
      $markup = json_encode($keyboard);
      sendMessage($queryUserID, $reply, $markup);
      break;
    } else {
      $reply = "Не удалось отправить заявление.\nПопробуйте немного позднее.";
      sendMessage($queryUserID, $reply, null);
      break;
    }


  case 'postponeVacationCase':
    $user = $access->getUserByChatID($queryUserID);
    if ($user) {
      $firstname = $user['firstname'];
      $reply = "$firstname, образец заявления на перенос отпуска будет направлен на рабочую почту. Нажмите продолжить.";
      $keyboard = array(
        "inline_keyboard" => array(
          array(
            array(
              "text" => "Продолжить",
              "callback_data" => "sendPostponeVacationForm"
            )
          )
        )
      );
      $markup = json_encode($keyboard);
      sendMessage($queryUserID, $reply, $markup);
      break;
    } else {
      $reply = "Не удалось отправить заявление.\nПопробуйте немного позднее.";
      sendMessage($queryUserID, $reply, null);
      break;
    }

  case 'sendRegularVacationForm':
    require('secure/email.php');
    $email = new email();
    $user = $access->getUserByChatID($queryUserID);
    if ($user) {
      $firstname = $user["firstname"];
      $emailAddress = $user["email"];
      $companyID = $user["company_id"];
      $companyName = $user["company_name"];
      $reply = "";

      require ("secure/swiftmailer.php");
      $swiftmailer = new swiftmailer();

      $template = $email->generateRegularVacationForm($companyID);
      $template = str_replace("{firstname}", $firstname, $template);


      $mailer = $swiftmailer->sendRegularVacationMailWithAttachementViaSmtp(
        $companyID,
        $emailAddress,
        "Образец заявления на отпуск",
        $template
      );
      switch ($companyID) {
        case 1:
          $reply = "$firstname, заявление отправлено на Ваш рабочий email. Если вы не получили письмо, пожалуйста, проверьте папку \"Спам\", возможно оно там.\n\rПожалуйста, заполните заявление, подпишите и передайте в отдел по работе с персоналом ООО \"СИГМА КЭПИТАЛ\".\n\rСпасибо за обращение!";
          break;
        case 2:
          $reply = "$firstname, заявление отправлено на Ваш рабочий email. Если вы не получили письмо, пожалуйста, проверьте папку \"Спам\", возможно оно там.\n\rПожалуйста, заполните заявление, подпишите и передайте в службу по работе с персоналом ООО \"Гринхаус\".\n\rСпасибо за обращение!";
          break;
        case 3:
          $reply = "$firstname, заявление отправлено на Ваш рабочий email. Если вы не получили письмо, пожалуйста, проверьте папку \"Спам\", возможно оно там.\n\rПожалуйста, заполните заявление, подпишите и передайте в департамент по работе с персоналом ООО \"ДИАЛЛ АЛЬЯНС\".\n\rСпасибо за обращение!";
          break;
      }

      if ($mailer) {
          $access->setState($queryUserID, "authorization completed");
          sendMessage($queryUserID, $reply, null);
          break;
      } else {
          $reply = "Не удалось отправить заявление. Повторите попытку позже.";
          sendMessage($queryUserID, $reply, null);
          break;
      }
      break;
    } else {
      $reply = "Упс, что-то я задремал, напомните, что вы хотели?";
      sendMessage($chatID, $reply, null);
      break;
    }

  case 'sendPostponeVacationForm':
    require('secure/email.php');
    $email = new email();
    $user = $access->getUserByChatID($queryUserID);
    if ($user) {
      $firstname = $user["firstname"];
      $emailAddress = $user["email"];
      $companyID = $user["company_id"];
      $companyName = $user["company_name"];
      $reply = "";

      require ("secure/swiftmailer.php");
      $swiftmailer = new swiftmailer();

      $template = $email->generatePostponeVacationForm($companyID);
      $template = str_replace("{firstname}", $firstname, $template);

      $swiftmailer->sendPostponedVacationMailWithAttachementViaSmtp(
        $companyID,
        $emailAddress,
        "Образец заявления на перенос отпуска",
        $template
      );

      switch ($companyID) {
        case 1:
          $reply = "$firstname, заявление отправлено на Ваш рабочий email. Если вы не получили письмо, пожалуйста, проверьте папку \"Спам\", возможно оно там.\n\rПожалуйста, заполните заявление, подпишите и передайте в отдел по работе с персоналом ООО \"СИГМА КЭПИТАЛ\".\n\rСпасибо за обращение!";
          break;
        case 2:
          $reply = "$firstname, заявление отправлено на Ваш рабочий email. Если вы не получили письмо, пожалуйста, проверьте папку \"Спам\", возможно оно там.\n\rПожалуйста, заполните заявление, подпишите и передайте в службу по работе с персоналом ООО \"Гринхаус\".\n\rСпасибо за обращение!";
          break;
        case 3:
          $reply = "$firstname, заявление отправлено на Ваш рабочий email. Если вы не получили письмо, пожалуйста, проверьте папку \"Спам\", возможно оно там.\n\rПожалуйста, заполните заявление, подпишите и передайте в департамент по работе с персоналом ООО \"ДИАЛЛ АЛЬЯНС\".\n\rСпасибо за обращение!";
          break;
      }

      $access->setState($queryUserID, "authorization completed");
      sendMessage($queryUserID, $reply, null);
      break;
    } else {
      $reply = "Упс, что-то я задремал, напомните, что вы хотели?";
      sendMessage($chatID, $reply, null);
      break;
    }

  case 'getFirstRuleText':
    #sendPhoto($queryUserID, 'https://sigmabot.ddns.net/hrbot/files/truth_and_facts.jpeg', null);
    $constants = new constants();
    $reply = $constants->getTruthAndFactsValueText();
    $keyboard = array(
      "inline_keyboard" => array(
        array(
          array(
            "text" => "Далее",
            "callback_data" => "getSecondRuleText"
          )
        )
      ),
      "resize_keyboard" => true,
      "one_time_keyboard" => true
    );
    $markup = json_encode($keyboard);
    sendMessage($queryUserID, $reply, $markup);
    break;

  case 'getSecondRuleText':
    #sendPhoto($queryUserID, 'https://sigmabot.ddns.net/hrbot/files/openness_and_transparency.jpeg', null);
    $constants = new constants();
    $reply = $constants->getOpennessAndTransparencyValueText();
    $keyboard = array(
      "inline_keyboard" => array(
        array(
          array(
            "text" => "Далее",
            "callback_data" => "getThirdRuleText"
          )
        )
      ),
      "resize_keyboard" => true,
      "one_time_keyboard" => true
    );
    $markup = json_encode($keyboard);
    sendMessage($queryUserID, $reply, $markup);
    break;

  case 'getThirdRuleText':
    #sendPhoto($queryUserID, 'https://sigmabot.ddns.net/hrbot/files/work_favorite_affair.jpeg', null);
    $constants = new constants();
    $reply = $constants->getWorkIsAFavoriteAffairValueText();
    $keyboard = array(
      "inline_keyboard" => array(
        array(
          array(
            "text" => "Далее",
            "callback_data" => "getFourthRuleText"
          )
        )
      ),
      "resize_keyboard" => true,
      "one_time_keyboard" => true
    );
    $markup = json_encode($keyboard);
    sendMessage($queryUserID, $reply, $markup);
    break;

  case 'getFourthRuleText':
    #sendPhoto($queryUserID, 'https://sigmabot.ddns.net/hrbot/files/minded_team.jpeg', null);
    $constants = new constants();
    $reply = $constants->getMindedTeamValueText();
    $keyboard = array(
      "inline_keyboard" => array(
        array(
          array(
            "text" => "Далее",
            "callback_data" => "getLastRuleText"
          )
        )
      ),
      "resize_keyboard" => true,
      "one_time_keyboard" => true
    );
    $markup = json_encode($keyboard);
    sendMessage($queryUserID, $reply, $markup);
    break;

  case 'getLastRuleText':
    $user = $access->getUserByChatID($queryUserID);
    $constants = new constants();
    $reply = $constants->getFinalValueText($user['firstname']);
    $keyboard = array(
      "keyboard" => array(
        array(
          array(
            "text" => $data['mainKeyboard']['phones']
          ),
          array(
            "text" => "КДП и Заработная плата"
          )
        ),
        array(
          array(
              "text" => "Наши ценности"
          ),
          array(
              "text" => "Общая информация"
          )
        ),
        array(
          array(
              "text" => "Правила"
          ),
          array(
              "text" => "Выход"
          )
        )
      ),
      "resize_keyboard" => true,
      "one_time_keyboard" => true
    );
    $markup = json_encode($keyboard);
    sendMessage($queryUserID, $reply, $markup);
    break;

  case 'feedback':
    $access->setState($queryUserID, "waiting for feedback");
    $reply = "Опишите, пожалуйста, Вашу жалобу или предложение!\nДля нас важны все отзывы, чтобы вместе сделать сервис еще лучше!";
    sendMessage($queryUserID, $reply, null);
    break;

  case 'bug report':
    $access->setState($queryUserID, "waiting for bug report");
    $reply = "Опишите, пожалуйста, максимально подробно обнаруженную Вами ошибку.\nВместе мы сделаем сервис лучше!";
    sendMessage($queryUserID, $reply, null);
    break;

  case 'go to the start':
    $reply = $queryUserName."!\nЯ Ваш личный ассистент по возникающим внутренним вопросам Компании. Для использования моих возможностей необходимо авторизироваться.";
    $keyboard = array(
      "keyboard" => array(
        array(
          array(
            "text" => "Авторизация по email"
          )
        )
      ),
      "resize_keyboard" => true,
      "one_time_keyboard" => true
    );
    $markup = json_encode($keyboard);
    $access->setState($queryUserID, "waiting for authorization");
    sendMessage($queryUserID, $reply, $markup);
    break;
}





$access->disconnect();

function sendMessage($chatID, $text, $keyboard) {
  $url = $GLOBALS[website]."/sendMessage?chat_id=$chatID&parse_mode=HTML&text=".urlencode($text)."&reply_markup=".$keyboard;
  file_get_contents($url);
}

function sendPhoto($chatID, $imageUrl, $keyboard) {
  $url = $GLOBALS[website]."/sendPhoto?chat_id=$chatID&parse_mode=HTML&photo=".$imageUrl."&reply_markup=".$keyboard;
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
