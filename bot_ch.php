<?php
/**
 * Created by Murad Adygezalov
 * Date: 28.03.2021
 * Time: 16:59
 */


// STEP 1. Build connection
// Secure way to biuld connection
$file = parse_ini_file("../Testbotdb.ini"); // accessing the file with connection info

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
require ("logics/logics.php");
require ("logs/logs.php");

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
            ),
            array(
              array(
                "text" => "Авторизация по телефону"
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
      } else {
        # Обработка кейса, когда пользователя с таким chatID есть в БД и ранее он уже успешно авторизовался
        $logs = new logs();
        $logs->log($text, $fullname);
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
                    "text" => "Помощь ИТ специалиста"
                )
            ),
            array(
                array(
                    "text" => "Выход"
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
      # Обработка кейса, когда пользователя с таким chatID нет в БД
      $reply = "Привет, $username!\nЯ Ваш личный ассистент по возникающим внутренним вопросам Компании.\nПохоже вы зашли впервые, давайте убедимся, что вы являетесь сотрудником Компании, для этого, нажмите \"Авторизация\" в меню ниже. Это не займет много времени.";
      $keyboard = array(
        "keyboard" => array(
          array(
            array(
              "text" => "Авторизация по email"
            )
          ),
          array(
              array(
                "text" => "Авторизация по телефону"
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
          "one_time_keyboard" => false
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
                    "text" => "Помощь ИТ специалиста"
                )
            ),
            array(
                array(
                    "text" => "Выход"
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
        "one_time_keyboard" => false
      );
      $markup = json_encode($keyboard);
      sendMessage($chatID, $reply, $markup);
      break;
    }

    case 'Авторизация по телефону':
        $stateResult = $access->setState($chatID, "waiting for mobile number");

        if ($stateResult) {
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
                "one_time_keyboard" => false
            );
            $markup = json_encode($keyboard);
            sendMessage($chatID, "$username, давайте убедимся, что Вы являетесь сотрудником. Для продоложения авторизации необходимо получить Ваш номер мобильного телефона. Нажмите на кнопку \"Передать мобильный номер\" ниже и подтвердите согласие", $markup);
            exit;
        } else {
            sendMessage($chatID, "Не могу подключиться к серверу, попробуйте снова", null);
            exit;
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
          ),
          array(
              array(
                "text" => "Авторизация по телефону"
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
      "one_time_keyboard" => false
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
      "one_time_keyboard" => false
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
            case 2;
            case 22;
            case 33;
            case 3:
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
              "one_time_keyboard" => false
            );
            break;
          case 22;
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
              "one_time_keyboard" => false
            );
            break;
          case 33;
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
                    "text" => "Помощь ИТ специалиста"
                  ),
                  array(
                    "text" => "Назад"
                  )
                )
              ),
              "resize_keyboard" => true,
              "one_time_keyboard" => false
            );
            break;
        }

        $markup = json_encode($keyboard);
        sendMessage($chatID, $reply, $markup);
        break;
    }
    break;

    case 'Помощь ИТ специалиста':
        $user = $access->getUserByChatID($chatID);
        $companyID = $user['company_id'];

        switch ($companyID) {
            case 1 || 2 || 22:
                $reply = "Раздел находится в разработке";
                sendMessage($chatID, $reply, null);
                exit;
            case 3 || 33:
                $reply = "Выберите категорию:\n<b>1С, ERP</b> - вопросы по функционированию программ 1С и внедрению ERP;\n<b>Оборудование</b> - вопросы связанные с работой ИТ техники и телефонии (не включается, не показывает, не печатает и пр.);\n<b>Ресурсы</b> - вопросы по работе Интернет, электронной почты, сетевых папок и пр.;\n<b>Другое</b> - вопросы, не относящиеся к остальным категориям.";
                $keyboard = array(
                    "keyboard" => array(
                        array(
                            array(
                                "text" => "1С, ERP"
                            ),
                            array(
                                "text" => "Оборудование"
                            ),
                        ),
                        array(
                            array(
                                "text" => "Ресурсы"
                            ),
                            array(
                                "text" => "Другое"
                            ),
                        ),
                        array(
                            array(
                                "text" => "Назад"
                            )
                        )
                    ),
                    "resize_keyboard" => true,
                    "one_time_keyboard" => false
                );
                $markup = json_encode($keyboard);
                sendMessage($chatID, $reply, $markup);
                exit;
        }

    case '1С, ERP':
        $user = $access->getUserByChatID($chatID);
        $reply = $user['firstname'].", пожалуйста, сформулируйте проблему максимально конкретно, с перечислением сложностей, с которыми вы столкнулись.";
        $access->setState($chatID, "waiting for ERP feedback");
        sendMessage($chatID, $reply, null);
        break;

    case 'Оборудование':
        $user = $access->getUserByChatID($chatID);
        $reply = $user['firstname'].", пожалуйста, сформулируйте проблему максимально конкретно, с перечислением сложностей, с которыми вы столкнулись.";
        $access->setState($chatID, "waiting for hardware feedback");
        sendMessage($chatID, $reply, null);
        break;

    case 'Ресурсы':
        $user = $access->getUserByChatID($chatID);
        $reply = $user['firstname'].", пожалуйста, сформулируйте проблему максимально конкретно, с перечислением сложностей, с которыми вы столкнулись.";
        $access->setState($chatID, "waiting for resources feedback");
        sendMessage($chatID, $reply, null);
        break;

    case 'Другое':
        $user = $access->getUserByChatID($chatID);
        $reply = $user['firstname'].", пожалуйста, сформулируйте проблему максимально конкретно, с перечислением сложностей, с которыми вы столкнулись.";
        $access->setState($chatID, "waiting for other feedback");
        sendMessage($chatID, $reply, null);
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
      "one_time_keyboard" => false
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
    $access->removeFindUserDataByChatID($chatID);
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
                "text" => "Помощь ИТ специалиста"
            )
        ),
        array(
            array(
                "text" => "Выход"
            )
        )
      ),
      "resize_keyboard" => true,
      "one_time_keyboard" => false
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
          ),
          array(
              array(
                "text" => "Авторизация по телефону"
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

    default:
        $stateResult = $access->getState($chatID);
        $user = $access->getUserByChatID($chatID);
        $fullname = $user['fullname'];
        $state = $stateResult["dialog_state"];
        $constants = new constants();

        if (substr_count(trim($text), ' ') == 1) {
            // case with check if phone required
            $space = strpos($text,  " ");
            $lastname = mb_strtolower(substr($text, $space + 1), $encoding='UTF-8');
            $firstname = mb_strtolower(substr($text, 0, $space), $encoding='UTF-8');

            if (mb_strlen($firstname) < 2 || mb_strlen($lastname) < 2) {
                $reply = "Ну не может быть имя или фамилия из одной буквы ".hex2bin('f09f9982');
                sendMessage($chatID, $reply, null);
                break;
            } else {
                $result = $access->getUserByFirstnameAndLastName($firstname, $lastname);
                if ($result) {
                    switch ($result["company_id"]) {
                        case 1:
                            if ($user["is_sigma_available"]) {
                                $savedData = $access->saveFindUserData($chatID, $result['firstname'], $result['lastname']);
                                if ($savedData) {
                                    $reply = "Для получения информации о сотруднике воспользуйтесь командами меню ниже.";
                                    $keyboard = array(
                                        "inline_keyboard" => array(
                                            array(
                                                array(
                                                    "text" => 'Карточка',
                                                    "callback_data" => 'getUserCard'
                                                ),
                                                array(
                                                    "text" => "Email",
                                                    "callback_data" => 'getUserEmail'
                                                )
                                            ),
                                            array(
                                                array(
                                                    "text" => "Мобильный телефон",
                                                    "callback_data" => 'getUserMobileNumber'
                                                ),
                                                array(
                                                    "text" => "Рабочий телефон",
                                                    "callback_data" => 'getUserOfficeNumber'
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
                                // TODO Reply that user has no rights
                                $reply = $constants->getPhoneCardPrivelegesError($user["firstname"]);
                                sendMessage($chatID, $reply, $markup);
                                exit;
                            }
                        case 2:
                            if ($user["is_greenhouse_available"]) {
                                $savedData = $access->saveFindUserData($chatID, $result['firstname'], $result['lastname']);
                                if ($savedData) {
                                    $reply = "Для получения информации о сотруднике воспользуйтесь командами меню ниже.";
                                    $keyboard = array(
                                        "inline_keyboard" => array(
                                            array(
                                                array(
                                                    "text" => 'Карточка',
                                                    "callback_data" => 'getUserCard'
                                                ),
                                                array(
                                                    "text" => "Email",
                                                    "callback_data" => 'getUserEmail'
                                                )
                                            ),
                                            array(
                                                array(
                                                    "text" => "Мобильный телефон",
                                                    "callback_data" => 'getUserMobileNumber'
                                                ),
                                                array(
                                                    "text" => "Рабочий телефон",
                                                    "callback_data" => 'getUserOfficeNumber'
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
                                // TODO Reply that user has no rights
                                $reply = $constants->getPhoneCardPrivelegesError($user["firstname"]);
                                sendMessage($chatID, $reply, $markup);
                                exit;
                            }
                        case 3:
                            if ($user["is_diall_available"]) {
                                $savedData = $access->saveFindUserData($chatID, $result['firstname'], $result['lastname']);
                                if ($savedData) {
                                    $reply = "Для получения информации о сотруднике воспользуйтесь командами меню ниже.";
                                    $keyboard = array(
                                        "inline_keyboard" => array(
                                            array(
                                                array(
                                                    "text" => 'Карточка',
                                                    "callback_data" => 'getUserCard'
                                                ),
                                                array(
                                                    "text" => "Email",
                                                    "callback_data" => 'getUserEmail'
                                                )
                                            ),
                                            array(
                                                array(
                                                    "text" => "Мобильный телефон",
                                                    "callback_data" => 'getUserMobileNumber'
                                                ),
                                                array(
                                                    "text" => "Рабочий телефон",
                                                    "callback_data" => 'getUserOfficeNumber'
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
                                // TODO Reply that user has no rights
                                $reply = $constants->getPhoneCardPrivelegesError($user["firstname"]);
                                sendMessage($chatID, $reply, $markup);
                                exit;
                            }
                    }
                } else {
                    $logs = new logs();
                    $logs->log($text, $fullname);
                    $reply = "Ничего не понял, но я быстро учусь ".hex2bin('f09f9982').". Вероятно такого сотрудникиа нет. Пожалуйста, воспользуйтесь командами в меню ниже!";
                    sendMessage($chatID, $reply, null);
                    // exit;
                }
            }
        } else {
            switch ($state) {
                case 'waiting for authorization':
                    $logs = new logs();
                    $logs->log($text, $fullname);
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

                case 'waiting for mobile number':
                    $logics = new logics();
                    $number = $logics->formatPhoneNumber($phoneNumber);
                    $user = $access->getUserByPhoneNumber($number);

                    if ($user) {
                        $result = $access->activateUser($chatID, $number);
                        if ($result) {
                            $access->setState($chatID, "authorization completed");
                            $reply = "Поздравляю, ".$user['fullname']."! Вы успешно прошли процедуру авторизации и можете использовать меня на полную катушку!\nНиже меню с командами, которые я умею выполнять.";
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
                                            "text" => "Помощь ИТ специалиста"
                                        )
                                    ),
                                    array(
                                        array(
                                            "text" => "Выход"
                                        )
                                    )
                                ),
                                "resize_keyboard" => true,
                                "one_time_keyboard" => false
                            );
                            $markup = json_encode($keyboard);
                            sendMessage($chatID, $reply, $markup);
                            exit;
                        } else {
                            $reply = "Что-то пошло не так, попробуйте еще раз.";
                            sendMessage($chatID, $reply, null);
                            exit;
                        }

                    } else {
                        $reply = "Номер телефона не найден, либо вы являетесь сотруником офиса. В случае, если вы сотрудник офиса - вернитесь в начало и выберите способ авторизации по email, в случае, если вы не офисный работник, проверьте номер мобильного телефона и попробуйте еще раз.";
                            
                        sendMessage($chatID, $reply, null);
                        exit;
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
                                                        "text" => "Помощь ИТ специалиста"
                                                    )
                                                ),
                                                array(
                                                    array(
                                                        "text" => "Выход"
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

                case 'waiting for regular vacation startdate':
                    if (preg_match('/(0[1-9]|1[0-9]|2[0-9]|3[01]).(0[1-9]|1[012]).[0-9]{4}/', $text)) {
                        $date = new DateTime($text);
                        $now = new DateTime();

                        if ($date > $now) {
                            $access->setRegularVacationStartDate($chatID, $text);
                            $access->setState($chatID, "waiting for regular vacation duration");
                            $reply = "Введите желаемую длительно отпуска (количество дней).\nПример: <b>14</b>";
                            sendMessage($chatID, $reply, null);
                            exit;
                        } else {
                            $reply = "Дата находится в прошлом, введите корректную дату";
                            sendMessage($chatID, $reply, null);
                            exit;
                        }
                    } else {
                        $reply = "Неверный формат даты. Попробуйте снова.\nПример: <b>01.01.2018</b>";
                        sendMessage($chatID, $reply, null);
                        exit;
                    }

                case 'waiting for regular vacation duration':
                    if (preg_match('/[0-9]/', $text)) {
                        $vacationFormData = $access->getReguarVacationFormData($chatID);

                        if ($vacationFormData['vacation_type'] != '3') {
                            $access->setRegularVacationDuration($chatID, $text);
                            $access->setState($chatID, "waiting for regular vacation form sending");
                            $keyboard = array(
                                "inline_keyboard" => array(
                                    array(
                                        array(
                                            "text" => "Отправить заявление",
                                            "callback_data" => "sendNewRegularVacationForm"
                                        )
                                    )
                                )
                            );
                            $markup = json_encode($keyboard);
                            $reply = "Заявление будет отправлено на Ваш рабочий адрес электронной почты.";
                            sendMessage($chatID, $reply, $markup);
                            exit;
                        } else {
                            $access->setRegularVacationDuration($chatID, $text);
                            $access->setState($chatID, "waiting for regular vacation academic reason");
                            $reply = "Введите причину-основание.\nПример: <b>Справка-вызов, решение диссертационного совета и т.д.</b>";
                            sendMessage($chatID, $reply, $markup);
                            exit;
                        }
                        
                    } else {
                        $reply = "Длительность отпуска введена в неверном формате, возможны только цифры. \nПример: <b>14</b>";
                        sendMessage($chatID, $reply, null);
                        exit;
                    }

                case 'waiting for regular vacation academic reason':
                    $access->setRegularVacationAcademicReason($chatID, $text);
                    $access->setState($chatID, "waiting for regular vacation form sending");
                    $keyboard = array(
                        "inline_keyboard" => array(
                            array(
                                array(
                                    "text" => "Отправить заявление",
                                    "callback_data" => "sendNewRegularVacationForm"
                                )
                            )
                        )
                    );
                    $markup = json_encode($keyboard);
                    $reply = "Заявление будет отправлено на Ваш рабочий адрес электронной почты.";
                    sendMessage($chatID, $reply, $markup);
                    exit;

                case 'waiting for postponed vacation startdate':
                    if (preg_match('/(0[1-9]|1[0-9]|2[0-9]|3[01]).(0[1-9]|1[012]).[0-9]{4}/', $text)) {
                        $date = new DateTime($text);
                        $now = new DateTime();

                        if ($date > $now) {
                            $access->setVacationStartDate($chatID, $text);
                            $access->setState($chatID, "waiting for postponed vacation enddate");
                            $reply = "Введи дату окончания отпуска, на которую был запланирован отпуск изначально.\nПример: <b>01.01.2018</b>";
                            sendMessage($chatID, $reply, null);
                            exit;
                        } else {
                            $reply = "Дата находится в прошлом, введите корректную дату";
                            sendMessage($chatID, $reply, null);
                            exit;
                        }
                    } else {
                        $reply = "Неверный формат даты. Попробуйте снова.\nПример: <b>01.01.2018</b>";
                        sendMessage($chatID, $reply, null);
                        exit;
                    }

                case 'waiting for postponed vacation enddate':
                    if (preg_match('/(0[1-9]|1[0-9]|2[0-9]|3[01]).(0[1-9]|1[012]).[0-9]{4}/', $text)) {
                        $date = new DateTime($text);
                        $now = new DateTime();

                        if ($date > $now) {
                            $access->setVacationEndDate($chatID, $text);
                            $access->setState($chatID, "waiting for postponed vacation newstartdate");
                            $reply = "Введи новую дату начала отпуска.\nПример: <b>01.01.2018</b>";
                            sendMessage($chatID, $reply, null);
                            exit;
                        } else {
                            $reply = "Дата находится в прошлом, введите корректную дату";
                            sendMessage($chatID, $reply, null);
                            exit;
                        }
                    } else {
                        $reply = "Неверный формат даты. Попробуйте снова.\nПример: <b>01.01.2018</b>";
                        sendMessage($chatID, $reply, null);
                        exit;
                    }

                case 'waiting for postponed vacation newstartdate':
                    if (preg_match('/(0[1-9]|1[0-9]|2[0-9]|3[01]).(0[1-9]|1[012]).[0-9]{4}/', $text)) {
                        $date = new DateTime($text);
                        $now = new DateTime();

                        if ($date > $now) {
                            $access->setVacationNewStartDate($chatID, $text);
                            $access->setState($chatID, "waiting for postponed vacation newenddate");
                            $reply = "Введи новую дату окончания отпуска.\nПример: <b>01.01.2018</b>";
                            sendMessage($chatID, $reply, null);
                            exit;
                        } else {
                            $reply = "Дата находится в прошлом, введите корректную дату";
                            sendMessage($chatID, $reply, null);
                            exit;
                        }
                    } else {
                        $reply = "Неверный формат даты. Попробуйте снова.\nПример: <b>01.01.2018</b>";
                        sendMessage($chatID, $reply, null);
                        exit;
                    }

                case 'waiting for postponed vacation newenddate':
                    if (preg_match('/(0[1-9]|1[0-9]|2[0-9]|3[01]).(0[1-9]|1[012]).[0-9]{4}/', $text)) {
                        $date = new DateTime($text);
                        $now = new DateTime();

                        if ($date > $now) {
                            $access->setVacationNewEndDate($chatID, $text);
                            $access->setState($chatID, "waiting for postponed vacation reason");
                            $reply = "Введите причину.\nПример: <b>по личным обстоятельствам.</b>";
                            sendMessage($chatID, $reply, null);
                            exit;
                        } else {
                            $reply = "Дата находится в прошлом, введите корректную дату";
                            sendMessage($chatID, $reply, null);
                            exit;
                        }
                    } else {
                        $reply = "Неверный формат даты. Попробуйте снова.\nПример: <b>01.01.2018</b>";
                        sendMessage($chatID, $reply, null);
                        exit;
                    }

                case 'waiting for postponed vacation reason':
                    $access->setVacationReason($chatID, $text);
                    $access->setState($chatID, "waiting for vacation form sending");
                    $keyboard = array(
                        "inline_keyboard" => array(
                            array(
                                array(
                                    "text" => "Отправить заявление",
                                    "callback_data" => "sendPostponedVacationForm"
                                )
                            )
                        )
                    );
                    $markup = json_encode($keyboard);
                    $reply = "Заявление будет отправлено на Ваш рабочий адрес электронной почты.";
                    sendMessage($chatID, $reply, $markup);
                    exit;

                case 'waiting for ERP feedback':
                    $access->setFeedbackInfo($chatID, $text);
                    $reply = "Ваше сообщение будет направлено в поддержку";
                    $keyboard = array(
                        "inline_keyboard" => array(
                            array(
                                array(
                                    "text" => "Отправить обращение",
                                    "callback_data" => "sendFeedback"
                                )
                            )
                        )
                    );
                    $markup = json_encode($keyboard);
                    sendMessage($chatID, $reply, $markup);
                    exit;

                case 'waiting for hardware feedback':
                    $access->setFeedbackInfo($chatID, $text);
                    $reply = "Ваше сообщение будет направлено в поддержку";
                    $keyboard = array(
                        "inline_keyboard" => array(
                            array(
                                array(
                                    "text" => "Отправить обращение",
                                    "callback_data" => "sendFeedback"
                                )
                            )
                        )
                    );
                    $markup = json_encode($keyboard);
                    sendMessage($chatID, $reply, $markup);
                    exit;

                case 'waiting for resources feedback':
                    $access->setFeedbackInfo($chatID, $text);
                    $reply = "Ваше сообщение будет направлено в поддержку";
                    $keyboard = array(
                        "inline_keyboard" => array(
                            array(
                                array(
                                    "text" => "Отправить обращение",
                                    "callback_data" => "sendFeedback"
                                )
                            )
                        )
                    );
                    $markup = json_encode($keyboard);
                    sendMessage($chatID, $reply, $markup);
                    exit;

                case 'waiting for other feedback':
                    $access->setFeedbackInfo($chatID, $text);
                    $reply = "Ваше сообщение будет направлено в поддержку";
                    $keyboard = array(
                        "inline_keyboard" => array(
                            array(
                                array(
                                    "text" => "Отправить обращение",
                                    "callback_data" => "sendFeedback"
                                )
                            )
                        )
                    );
                    $markup = json_encode($keyboard);
                    sendMessage($chatID, $reply, $markup);
                    break;

                defaut:
		            if ($user['is_authorized']) {
                        $logs = new logs();
                        $logs->log($text, $fullname);
                        $reply = "Ничего не понял, но я быстро учусь ".hex2bin('f09f9982').". Пожалуйста, воспользуйтесь командами меню ниже!";
                        sendMessage($chatID, $reply, null);
                        break;
		            }
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
        switch ($user['company_id']) {
            case 1:
                $firstname = $user['firstname'];
                $reply = "$firstname, образец заявления на отпуск будет направлен на вашу рабочую почту. Нажмите продолжить.";
                $keyboard = array(
                "inline_keyboard" => array(
                    array(
                        array(
                            "text" => "Продолжить",
                            "callback_data" => "sendOldRegularVacationForm"
                        )
                    )
                )
                );
                $markup = json_encode($keyboard);
                sendMessage($queryUserID, $reply, $markup);
                exit;
            case 3:
                $firstname = $user['firstname'];
                $reply = "$firstname, образец заявления на отпуск будет направлен на вашу рабочую почту. Нажмите продолжить.";
                $keyboard = array(
                "inline_keyboard" => array(
                    array(
                        array(
                            "text" => "Продолжить",
                            "callback_data" => "sendOldRegularVacationForm"
                        )
                    )
                )
                );
                $markup = json_encode($keyboard);
                sendMessage($queryUserID, $reply, $markup);
                exit;
            case 2:
                $firstname = $user['firstname'];
                $reply = "$firstname, выберите тип отпуска, нажав на соответствующую кнопку ниже.";
                $keyboard = array(
                    "inline_keyboard" => array(
                        array(
                            array(
                                "text" => "Основной",
                                "callback_data" => "triggerMainVacation"
                            ),
                            array(
                                "text" => "Дополнительный",
                                "callback_data" => "triggerAdditionalVacation"
                            )
                        ),
                        array(
                            array(
                                "text" => "Без сохранения",
                                "callback_data" => "triggerNoPaymentVacation"
                            ),
                            array(
                                "text" => "Учебный",
                                "callback_data" => "triggerAcademicVacation"
                            )
                        )
                    )
                );
                $markup = json_encode($keyboard);
                sendMessage($queryUserID, $reply, $markup);
                exit;
            case 22 || 33:
                sendMessage($queryUserID, "Опция недоступна для Вас", $markup);
                exit;
        }
      
    } else {
      $reply = "Не удалось отправить заявление.\nПопробуйте немного позднее.";
      sendMessage($queryUserID, $reply, null);
      exit;
    }

    case 'triggerMainVacation':
        $access->setRegualarVacationType($queryUserID, '0');
        $access->setState($queryUserID, "waiting for regular vacation startdate");
        $reply = "Введите желаемую дату начала отпуска.\nПример: <b>01.01.2018</b>";
        sendMessage($queryUserID, $reply, null);
        exit;

    case 'triggerAdditionalVacation':
        $access->setRegualarVacationType($queryUserID, '1');
        $reply = "Введите желаемую дату начала отпуска.\nПример: <b>01.01.2018</b>";
        $access->setState($queryUserID, "waiting for regular vacation startdate");
        sendMessage($queryUserID, $reply, null);
        exit;

    case 'triggerNoPaymentVacation':
        $access->setRegualarVacationType($queryUserID, '2');
        $reply = "Введите желаемую дату начала отпуска.\nПример: <b>01.01.2018</b>";
        $access->setState($queryUserID, "waiting for regular vacation startdate");
        sendMessage($queryUserID, $reply, null);
        exit;

    case 'triggerAcademicVacation':
        $access->setRegualarVacationType($queryUserID, '3');
        $reply = "Введите желаемую дату начала отпуска.\nПример: <b>01.01.2018</b>";
        $access->setState($queryUserID, "waiting for regular vacation startdate");
        sendMessage($queryUserID, $reply, null);
        exit;

  case 'postponeVacationCase':
    $user = $access->getUserByChatID($queryUserID);
    if ($user) {
        switch ($user['company_id']) {
            case 1:
                $firstname = $user['firstname'];
                $reply = "$firstname, образец заявления на перенос отпуска будет направлен на рабочую почту. Нажмите продолжить.";
                $keyboard = array(
                    "inline_keyboard" => array(
                        array(
                            array(
                                "text" => "Продолжить",
                                "callback_data" => "sendOldPostponeVacationForm"
                            )
                        )
                    )
                );
                $markup = json_encode($keyboard);
                sendMessage($queryUserID, $reply, $markup);
                exit;

            case 3:
                $firstname = $user['firstname'];
                $reply = "$firstname, образец заявления на перенос отпуска будет направлен на рабочую почту. Нажмите продолжить.";
                $keyboard = array(
                    "inline_keyboard" => array(
                        array(
                            array(
                                "text" => "Продолжить",
                                "callback_data" => "sendOldPostponeVacationForm"
                            )
                        )
                    )
                );
                $markup = json_encode($keyboard);
                sendMessage($queryUserID, $reply, $markup);
                exit;

            case 2:
                $access->setState($queryUserID, "waiting for postponed vacation startdate");
                $reply = "Введите дату начала отпуска, на которую был запланирован отпуск изначально.\nПример: <b>01.01.2018</b>";
                sendMessage($queryUserID, $reply, null);
                exit;
            case 22 || 33:
                sendMessage($queryUserID, "Опция недоступна для Вас", $markup);
                exit;
        }
      
    } else {
      $reply = "Не удалось отправить заявление.\nПопробуйте немного позднее.";
      sendMessage($queryUserID, $reply, null);
      exit;
    }

    case 'sendRegularVacationForm':
        require('secure/email.php');
        $email = new email();
        $user = $access->getUserByChatID($queryUserID);

        if ($user) {

            $companyID = $user["company_id"];
            $companyName = $user["company_name"];
            $reply = "";
            $position = $user["form_position"];
            $formFullname = $user["form_fullname"];
            $firstname = $user["firstname"];
            $middlename = $user["middlename"];
            $lastname = $user["lastname"];
            $emailAddress = $user["email"];
            $nameFirstLetter = substr($firstname, 0, 2);
            $middlenameFirstLetter = substr($middlename, 0, 2);
            $sign = mb_ucfirst($nameFirstLetter, $encoding = 'UTF-8').".".mb_ucfirst($middlenameFirstLetter, $encoding = 'UTF-8')."."." ".mb_ucfirst($lastname, $encoding = 'UTF-8');

            $date = new dateTime();
            $day = $date->format("d");
            $month = $date->format("F");
            $year = $date->format("Y");

            require('forms.php');
            $form = new forms();

            $form->getGreenhouseRegularVacationForm($position, $formFullname, $day, $month, $year, $sign);

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
                exit;
            } else {
                $reply = "Не удалось отправить заявление. Повторите попытку позже.";
                sendMessage($queryUserID, $reply, null);
                exit;
            }
        } else {
            $reply = "Упс, что-то я задремал, напомните, что вы хотели?";
            sendMessage($queryUserID, $reply, null);
            exit;
        }

    case 'sendNewRegularVacationForm':
        $user = $access->getUserByChatID($queryUserID);
        $vacationFormData = $access->getReguarVacationFormData($queryUserID);

        if ($user && $vacationFormData) {

            $companyID = $user["company_id"];
            $companyName = $user["company_name"];
            $reply = "";
            $position = $user["form_position"];
            $formFullname = $user["form_fullname"];
            $firstname = $user["firstname"];
            $middlename = $user["middlename"];
            $lastname = $user["lastname"];
            $emailAddress = $user["email"];
            $nameFirstLetter = substr($firstname, 0, 2);
            $middlenameFirstLetter = substr($middlename, 0, 2);
            $sign = mb_ucfirst($nameFirstLetter, $encoding = 'UTF-8').".".mb_ucfirst($middlenameFirstLetter, $encoding = 'UTF-8')."."." ".mb_ucfirst($lastname, $encoding = 'UTF-8');

            $startDate = $vacationFormData["vacation_startdate"];
            $vacationType = $vacationFormData['vacation_type'];
            $vacationDuration = $vacationFormData["vacation_duration"];
            $vacationReason = $vacationFormData["reason"];

            $date = new dateTime();
            $day = $date->format("d");
            $month = $date->format("F");
            $year = $date->format("Y");

            require('forms.php');
            $form = new forms();

            if ($vacationType == '3') {
                $form->getGnhsNewRegularVacationForm($position, $formFullname, $vacationType, $startDate, $vacationDuration, $vacationReason, $day, $month, $year, $sign);
            } else {
                $form->getGnhsNewRegularVacationForm($position, $formFullname, $vacationType, $startDate, $vacationDuration, null, $day, $month, $year, $sign);
            }

            require('secure/email.php');
            $email = new email();
            $template = $email->generateNewRegularVacationForm($companyID);
            $template = str_replace("{firstname}", $firstname, $template);

            require ("secure/swiftmailer.php");
            $swiftmailer = new swiftmailer();

            $swiftmailer->sendNewRegularVacationMailWithAttachementViaSmtp(
                $vacationType,
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

            $access->setState($queryUserID, "authorization completed");
            // TODO erase reason from database
            sendMessage($queryUserID, $reply, null);
            exit;

        } else {
            $reply = "Упс, что-то я задремал, напомните, что вы хотели?";
            sendMessage($chatID, $reply, null);
            exit;
        }






        sendMessage($queryUserID, 'Availabe soon!', null);
        exit;

    case 'sendOldRegularVacationForm':
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
              exit;
          } else {
              $reply = "Не удалось отправить заявление. Повторите попытку позже.";
              sendMessage($queryUserID, $reply, null);
              exit;
          }
          break;
        } else {
          $reply = "Упс, что-то я задремал, напомните, что вы хотели?";
          sendMessage($chatID, $reply, null);
          exit;
        }

    case 'sendOldPostponeVacationForm':
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
          exit;
        } else {
          $reply = "Упс, что-то я задремал, напомните, что вы хотели?";
          sendMessage($queryUserID, $reply, null);
          exit;
        }

    case 'sendPostponedVacationForm':
        require('secure/email.php');
        $email = new email();
        $user = $access->getUserByChatID($queryUserID);
        $vacationFormData = $access->getDataForVacationForm($queryUserID);

        if ($user && $vacationFormData) {

            $companyID = $user["company_id"];
            $companyName = $user["company_name"];
            $reply = "";
            $position = $user["form_position"];
            $formFullname = $user["form_fullname"];
            $firstname = $user["firstname"];
            $middlename = $user["middlename"];
            $lastname = $user["lastname"];
            $emailAddress = $user["email"];
            $nameFirstLetter = substr($firstname, 0, 2);
            $middlenameFirstLetter = substr($middlename, 0, 2);
            $sign = mb_ucfirst($nameFirstLetter, $encoding = 'UTF-8').".".mb_ucfirst($middlenameFirstLetter, $encoding = 'UTF-8')."."." ".mb_ucfirst($lastname, $encoding = 'UTF-8');

            $startDate = $vacationFormData["vacation_start_date"];
            $endDate = $vacationFormData["vacation_end_date"];
            $postponedStartDate = $vacationFormData["postponed_vacation_start_date"];
            $postponedEndDate = $vacationFormData["postponed_vacation_end_date"];
            $reason = $vacationFormData["reason"];

            $date = new dateTime();
            $day = $date->format("d");
            $month = $date->format("F");
            $year = $date->format("Y");

            require('forms.php');
            $form = new forms();

            $form->getGreenhousePostponeVacationForm($position, $formFullname, $startDate, $endDate, $postponedStartDate, $postponedEndDate, $reason, $day, $month, $year, $sign);

            require ("secure/swiftmailer.php");
            $swiftmailer = new swiftmailer();

            $template = $email->generatePostponeVacationForm($companyID);
            $template = str_replace("{firstname}", $firstname, $template);

            $mailer = $swiftmailer->sendPostponedVacationMailWithAttachementViaSmtp(
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

            if ($mailer) {
                $access->setState($queryUserID, "authorization completed");
                sendMessage($queryUserID, $reply, null);
                exit;
            } else {
                $reply = "Не удалось отправить заявление. Повторите попытку позже.";
                sendMessage($queryUserID, $reply, null);
                exit;
            }
        } else {
            $reply = "Упс, что-то я задремал, напомните, что вы хотели?";
            sendMessage($queryUserID, $reply, null);
            exit;
        }

    case 'sendFeedback':
        $user = $access->getUserByChatID($queryUserID);
        $feedback = $access->getFeedbackInfo($queryUserID);
        $state = $access->getState($queryUserID);

        if ($user && $feedback && $state) {

            $emailAddress = $user["email"];
            $companyID = $user["company_id"];
            $feedbackText = $feedback['feedback_text'];

            require ("secure/swiftmailer.php");
            $swiftmailer = new swiftmailer();

            switch ($state['dialog_state']) {
                case 'waiting for ERP feedback':
                    $swiftmailer->sendFeedback(
                        $companyID,
                        'it_help@diall.ru',
                        "#1C &".$emailAddress."&",
                        $feedbackText
                    );
                    break;

                case 'waiting for hardware feedback':
                    $swiftmailer->sendFeedback(
                        $companyID,
                        'it_help@diall.ru',
                        "#ADM &".$emailAddress."&",
                        $feedbackText
                    );
                    break;

                case 'waiting for resources feedback':
                    $swiftmailer->sendFeedback(
                        $companyID,
                        'it_help@diall.ru',
                        "#ADM &".$emailAddress."&",
                        $feedbackText
                    );
                    break;

                case 'waiting for other feedback':
                    $swiftmailer->sendFeedback(
                        $companyID,
                        'it_help@diall.ru',
                        "&".$emailAddress."&",
                        $feedbackText
                    );
                    break;
            }

            $access->setState($queryUserID, "authorization completed");
            $reply = "Сообщение успешно отправлено, номер зарегистрированного обращения придет на почту.";
            sendMessage($queryUserID, $reply, null);
            exit;
        } else {
            $reply = "Не удалось отправить заявление. Повторите попытку позже.";
                sendMessage($queryUserID, $reply, null);
                exit;
        }

  case 'getFirstRuleText':
    $user = $access->getUserByChatID($queryUserID);
    $constants = new constants();
    sendSticker($queryUserID, $constants->getTruthAndFactsValueSticker($user['company_id']));
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
      "one_time_keyboard" => false
    );
    $markup = json_encode($keyboard);
    sendMessage($queryUserID, $reply, $markup);
    break;

  case 'getSecondRuleText':
    $user = $access->getUserByChatID($queryUserID);
    $constants = new constants();
    sendSticker($queryUserID, $constants->getOpennessAndTransparencyValueSticker($user['company_id']));
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
      "one_time_keyboard" => false
    );
    $markup = json_encode($keyboard);
    sendMessage($queryUserID, $reply, $markup);
    break;

  case 'getThirdRuleText':
    $user = $access->getUserByChatID($queryUserID);
    $constants = new constants();
    sendSticker($queryUserID, $constants->getWorkIsAFavoriteAffairValueSticker($user['company_id']));
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
      "one_time_keyboard" => false
    );
    $markup = json_encode($keyboard);
    sendMessage($queryUserID, $reply, $markup);
    break;

  case 'getFourthRuleText':
    $user = $access->getUserByChatID($queryUserID);
    $constants = new constants();
    sendSticker($queryUserID, $constants->getMindedTeamValueSticker($user['company_id']));
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
      "one_time_keyboard" => false
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
                "text" => "Помощь ИТ специалиста"
            )
        ),
        array(
            array(
                "text" => "Выход"
            )
        )
      ),
      "resize_keyboard" => true,
      "one_time_keyboard" => false
    );
    $markup = json_encode($keyboard);
    sendMessage($queryUserID, $reply, $markup);
    break;

  case 'go to the start':
    $reply = $queryUserName."!\nЯ Ваш личный ассистент по возникающим внутренним вопросам Компании. Для использования моих возможностей необходимо авторизироваться.";
    $keyboard = array(
      "keyboard" => array(
        array(
          array(
            "text" => "Авторизация по email"
          )
        ),
        array(
            array(
                "text" => "Авторизация по телефону"
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

    case 'getUserCard':
        $userForFind = $access->getFindUserData($queryUserID);
        if ($userForFind) {
            $result = $access->getUserByFirstnameAndLastName($userForFind['find_userfirstname'], $userForFind['find_userlastname']);
            $reply = "<b>Карточка работника</b>\nФИО: ".$result["fullname"]."\nРабочий телефон: <b>".$result["office_number"]."</b>\nДобавочный номер: <b>".$result["internal_number"]."</b>\nМобильный телефон: <b>".$result["mobile_number"]."</b>\nE-mail: <b>".$result["email"]."</b>\nДолжность: <b>".$result["position"]."</b>\nКомпания: <b>".$result["company_name"]."</b>";
            sendMessage($queryUserID, $reply, null);
            break;
        } else {
            $reply = "Похоже вы ищете сотрудника? Давайте я поищу, введите имя и фамилию.";
            sendMessage($queryUserID, $reply, null);
            break;
        }

    case 'getUserEmail':
        $userForFind = $access->getFindUserData($queryUserID);
        if ($userForFind) {
            $result = $access->getUserByFirstnameAndLastName($userForFind['find_userfirstname'], $userForFind['find_userlastname']);
            $reply = "<b>Email работника</b>\n".$result["email"];
            sendMessage($queryUserID, $reply, null);
            break;
        } else {
            $reply = "Похоже вы ищете сотрудника? Давайте я поищу, введите имя и фамилию.";
            sendMessage($queryUserID, $reply, null);
            break;
        }

    case 'getUserMobileNumber':
        $userForFind = $access->getFindUserData($queryUserID);
        if ($userForFind) {
            $result = $access->getUserByFirstnameAndLastName($userForFind['find_userfirstname'], $userForFind['find_userlastname']);
            $reply = "<b>Номер мобильного телефона работника</b>\n".$result["mobile_number"];
            sendMessage($queryUserID, $reply, null);
            break;
        } else {
            $reply = "Похоже вы ищете сотрудника? Давайте я поищу, введите имя и фамилию.";
            sendMessage($queryUserID, $reply, null);
            break;
        }

    case 'getUserOfficeNumber':
        $userForFind = $access->getFindUserData($queryUserID);
        if ($userForFind) {
            $result = $access->getUserByFirstnameAndLastName($userForFind['find_userfirstname'], $userForFind['find_userlastname']);
            $reply = "<b>Номер рабочего телефона работника</b>\n".$result["office_number"].", доб. ".$result["internal_number"];
            sendMessage($queryUserID, $reply, null);
            break;
        } else {
            $reply = "Похоже вы ищете сотрудника? Давайте я поищу, введите имя и фамилию.";
            sendMessage($queryUserID, $reply, null);
            break;
        }
    default:
        $logs = new logs();
        $logs->log($text, $fullname);
        $reply = "Ничего не понял, но я быстро учусь ".hex2bin('f09f9982').". Пожалуйста, воспользуйтесь командами меню ниже!";
        sendMessage($queryUserID, $reply, null);
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

function sendSticker($chatID, $sticker) {
  $url = $GLOBALS[website]."/sendSticker?chat_id=$chatID&sticker=$sticker";
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
