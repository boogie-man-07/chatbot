<?php
/**
 * Created by PhpStorm.
 * User: murad
 * Date: 05.01.2018
 * Time: 15:38
 */


class constants {

    function getReplyForAllowToCheckMobileNumber($username) {
        return "$username, давайте убедимся, что Вы являетесь сотрудником. Для продоложения авторизации необходимо получить Ваш номер мобильного телефона. Нажмите на кнопку \"Передать мобильный номер\" ниже и подтвердите согласие";
    }

    function getReplyForNonAuthorizedUser($username) {
        return "Привет, $username!\nЯ Ваш личный ассистент по возникающим внутренним вопросам Компании.\nПохоже вы зашли впервые, давайте убедимся, что Вы являетесь сотрудником Компании, для этого, выберите один из способов авторизации в меню ниже. Если Вы являетесь сотрудником офиса и у Вас есть корпоративный email адрес - выберите \"Авторизация по Email\", в остальных случаях выберите \"Авторизация по номеру телефона\". Это не займет много времени";
    }

    function getReplyForUserNotFinishedAuthorization($username) {
        return "Привет, $username!\nЯ Ваш личный ассистент по возникающим внутренним вопросам Компании.\nПохоже вы уже заглядывали в гости, но что-то пошло не так и мы не завершили авторизацию. Готовы попробовать еще раз?\nвыберите один из способов авторизации в меню ниже. Если Вы являетесь сотрудником офиса и у Вас есть корпоративный email адрес - выберите \"Авторизация по Email\", в остальных случаях выберите \"Авторизация по номеру телефона\". Это не займет много времени";
    }

    function getReplyForGoToTheStart($username) {
        return "$username!\nЯ Ваш личный ассистент по возникающим внутренним вопросам Компании. Для использования моих возможностей необходимо авторизироваться";
    }

    function getReplyForAuthorizedUser($username) {
        return "С возвращением, $username!\n Вы уже авторизованы и можете использовать меня на полную катушку!\nНиже меню с командами, которые я умею выполнять";
    }

    function getReplyForDeclinedExit($username) {
        return "Отличное решение!".hex2bin('f09f9982');
    }

    function getReplyForCommonMistake() {
        return "Ничего не понял, но я быстро учусь ".hex2bin('f09f9982').". Пожалуйста, воспользуйтесь командами в меню ниже!";
    }

    function getReplyForCommonErrorIfNotAuthorized($username) {
        return "$username, для дальнейшего работы необходимо авторизоваться, для этого, выберите один из способов авторизации в меню ниже. Если Вы являетесь сотрудником офиса и у Вас есть корпоративный email адрес - выберите \"Авторизация по Email\", в остальных случаях выберите \"Авторизация по номеру телефона\"";
    }

    function getReplyForLoginWaiting() {
        return "Пожалуйста, введите логин вашей учетной записи.\nЛогин - это часть email, расположенная до знака @";
    }

    function getReplyForCommonErrorIfLoginIncorrect() {
        return "Неверный формат логина.\nЛогин может содержать латинские буквы и цифры.\nПопробуйте снова";
    }

    function getReplyForCommonErrorIfLoginNotFound() {
        return "Сотрудник с таким логином не числится в Компании. Проверьте правильность введенного логина и попробуйте снова";
    }

    function getReplyForCommonErrorIfAuthorizedByAnotherChatId() {
        return "Авторизация невозможна! Сотрудник с таким логином уже авторизован в боте на другом устройстве. Для авторизации на данном устройстве необходимо выйти из бота на другом устройстве и повторить процедуру";
    }

    function getReplyForCommonErrorIfMobilePhoneNotFound() {
        return "Извините, работник с таким номером мобильного телефона не числится в Компании";
    }

    function getReplyForCommonErrorIfEmailAuthorizationUnavailable() {
        return "Извините, вы не являетесь сотрудником офиса! Пожалуйста, вернитесь в начало и воспользуйтесь способом авторизации по номеру телефона";
    }

    function getReplyForCommonErrorIfMobileAuthorizationUnavailable() {
        return "Извините, у Вас есть корпоративный email! Пожалуйста, вернитесь в начало и воспользуйтесь способом авторизации по email";
    }

    function getReplyForCommonErrorIfConfirmationCodeIsIncorrect() {
        return "код неверен.\nПопробуйте снова";
    }

    function getReplyForCommonErrorIfConfirmationCodeFormatIsIncorrect() {
        return "Неверный формат кода подтверждения.\nКод может содержать латинские буквы, цифры и специальные знаки и не может быть меньше 10 символов.\nПопробуйте снова";
    }

    function getReplyForConfirmationCodeExpired() {
        return "Время жизни кода активации истекло.\nНеобходимо повторить процесс авторизации";
    }

    function getReplyForSendConfirmationCodeApprovalFromUser($username) {
        return "$username, нажмите продолжить, для получения на рабочую почту письма с инструкциями по завершению авторизации";
    }

    function getReplyForMoveToStart($username) {
        return "$username!\nЯ Ваш личный ассистент по возникающим внутренним вопросам Компании. Для использования моих возможностей необходимо авторизироваться. Выберите один из способов авторизации в меню ниже. Если Вы являетесь сотрудником офиса и у Вас есть корпоративный email адрес - выберите \"Авторизация по Email\", в остальных случаях выберите \"Авторизация по номеру телефона\"";
    }

    function getReplyForEmailIsSended($username) {
        return "$username, письмо с кодом потверждения направлено на ваш рабочий email.\nПожалуйста, введи код подтверждения!\n\nЕсли вы не получили письмо с кодом, пожалуйста, проверьте папку \"Спам\", возможно письмо там";
    }

    function getReplyForSuccessfulLogin($username) {
        return "Поздравляю, $username! Вы успешно прошли процедуру авторизации и можете использовать меня на полную катушку!\nНиже меню с командами, которые я умею выполнять";
    }

    function getReplyForFindPhoneNumber() {
        return "Введи Имя и Фамилию сотрудника\nПример: <b>Иван Петров</b>";
    }

    function getReplyForEmployeeCardOptions() {
        return "Для получения информации о сотруднике воспользуйтесь командами меню ниже";
    }

    function getReplyForEmployeeCard($user) {
        return "<b>Карточка работника</b>\nФИО: <b>".$user["fullname"]."</b>\nСтатус: <b>".$user["state"]."</b>\nДобавочный номер: <b>".$user["internal_number"]."</b>\nМобильный телефон: <b>".str_replace(' ', '', $user["mobile_number"])."</b>\nE-mail: <b>".$user["email"]."</b>\nДолжность: <b>".$user["position"]."</b>\nКомпания: <b>".$user["company_name"]."</b>";
    }

    function getReplyForEmployeeEmail($email) {
        return "<b>Email работника</b>\n".$email;
    }

    function getReplyForEmployeeMobileNumber($mobileNumber) {
        return "<b>Номер мобильного телефона работника</b>\n".$mobileNumber;
    }

    function getReplyForEmployeeOfficeNumber($officeNumber, $internalNumber) {
        return "<b>Номер рабочего телефона работника</b>\n".$officeNumber.", доб. ".$internalNumber;
    }

    function getWelcomeValueText($firstname) {
        return "$firstname!\nНаша корпоративная культура основана на ценностях – наших глубоких убеждениях, формирующих наше поведение!\nМы считаем, что каждый работник Компании – носитель этих ценностей, обязан культивировать и транслировать их на всех уровнях взаимодействия!";
    }

    function getTruthAndFactsValueText() {
        return "- Мы всегда говорим правду и ожидаем слышать правду от других.\n- Составляя мнение, мы основываемся только на фактах.\n- Мы стремимся понять реальную суть вещей, и не выдаём желаемое за действительное";
    }

    function getTruthAndFactsValueSticker($id) {
        switch ($id) {
            case 1:
                return 'CAACAgIAAxkBAAEBMbZgfy8CinSskHVXOg8nZsQgoTfdpgACdAoAAvQI6EtmsXAM6Y84IB8E';
                break;
            case 2:
                return 'CAACAgIAAxkBAAEBMZ5gfyqlDmXIhm1NofTmUtk6PbwLVgAC0AwAArFD6EuF3nv4a0AKGh8E';
                break;
            case 3:
                return 'CAACAgIAAxkBAAEBMaFgfyq6ZJsFH80MGK5BHhZ5c4JKiwAChAsAArEpyUt4xWeiyu4u_B8E';
                break;
        }
    }

    function getOpennessAndTransparencyValueText() {
        return "- Мы с благодарностью воспринимаем обратную связь и считаем своим долгом предоставлять её другим.\n- Мы охотно делимся всей необходимой информацией, чтобы помочь коллегам детально погрузиться в тему или предмет.\n- Мы ценим альтернативные точки зрения.\n- Мы считаем, что мнением надо делиться прямо и открыто, а обсуждения за спиной неприемлемы";
    }

    function getOpennessAndTransparencyValueSticker($id) {
        switch ($id) {
            case 1:
                return 'CAACAgIAAxkBAAEBMblgfy8ejQb9oxs7E8Z9O1XiCfV3IwACxw4AAmf68EtgwsOgqbLjMR8E';
                break;
            case 2:
                return 'CAACAgIAAxkBAAEBMaRgfyt7kkLq78G_yOx23s9z1EqXtwACEwwAAqHX6Uvjv4F3N6K59x8E';
                break;
            case 3:
                return 'CAACAgIAAxkBAAEBMa1gfyu36mdSBXDGWAnt3jOPHTNoDAAC5gsAAqo0yEvCcwitIB2H4h8E';
                break;
        }
    }

    function getWorkIsAFavoriteAffairValueText() {
        return "- Мы любим и гордимся тем, что мы делаем, и поэтому, чтобы получить лучший результат, мы отдаём работе больше времени и сил, чем ожидается или требуется.\n- Мы считаем, что цели Компании – это наши цели, и их достижение – личная миссия для каждого из нас.\n- Мы мыслим и действуем как собственники нашей Компании.\n- Постоянное развитие и совершенствование – часть нашей жизни";
    }

    function getWorkIsAFavoriteAffairValueSticker($id) {
        switch ($id) {
            case 1:
                return 'CAACAgIAAxkBAAEBMbxgfy8uiznhMaqr-kWkzGHnduYr-wAChA8AAqVX6EumUZ6cBmCYNR8E';
                break;
            case 2:
                return 'CAACAgIAAxkBAAEBMadgfyuSPSLSbsDXjrz55YS7VCjJQwAC8g0AAvgi8UtWbnaI-4e_NB8E';
                break;
            case 3:
                return 'CAACAgIAAxkBAAEBMbBgfyvKHBdDrirJqKHxTL383r4f3wAC6g4AAuEkyEtHZUP58TmpEx8E';
                break;
        }
    }

    function getMindedTeamValueText() {
        return "- Наши коллеги - это команда единомышленников. Наши отношения строятся на уважении, искренности, открытости и доброжелательности.\n- Мы показываем на собственном примере то, что ожидаем от других.\n- Мы охотно делимся знаниями, вдохновляя друг друга расти и совершенствоваться.\n- Мы всегда готовы прийти на помощь, особенно в сложных ситуациях";
    }

    function getMindedTeamValueSticker($id) {
        switch ($id) {
            case 1:
                return 'CAACAgIAAxkBAAEBMb9gfy9CRTlmoIEmHYlFbV46nT_SYwACZwwAAgZ36Utd3rGptn8xjh8E';
                break;
            case 2:
                return 'CAACAgIAAxkBAAEBMapgfyuit8l_zH4BMLhBgFq6pl1kaQACNwsAAgSV6UvkGQooTnLg4R8E';
                break;
            case 3:
                return 'CAACAgIAAxkBAAEBMbNgfyvfGNxt2iwyETSj78IJNf3fSQAC8wkAAk59yEsPQURLZJvOCR8E';
                break;
        }
    }

    function getFinalValueText($firstname) {
        return "$firstname!\nВы всегда можете вернуться к описанию наших ценностей, нажав соответствующую кнопку в главном меню";
    }

    function getRouteText($id) {
        switch ($id) {
            case 1:
                return "До офиса добраться можно следующими способами:\n- доехать до ст. м. «Славянский бульвар», выход 4, сесть на автобус № 818 до ост. ЖК «Грюнвальд»;\n- Доехать до ст. м. «Славянский бульвар», выход 4, сесть на автобус SK «Сколково» до ост. «МШУ Сколково», далее пешком до ул. Весенняя 2., корп. 1";
            case 2:
                return "До тепличного комплекса добраться можно на автобусах:\n- Автобусы: № 115 (от ост. «Линия», остановка у ТК по требованию);\n- Автобусы: № 151 (от ост. «Линия», остановка у ТК по требованию);\n- Корпоративный транспорт.\n\nДо офиса в ОСП Сколково добраться можно следующими способами:\n- доехать до ст. м. «Славянский бульвар», выход 4, сесть на автобус № 818 до ост. ЖК «Грюнвальд»;\n- Доехать до ст. м. «Славянский бульвар», выход 4, сесть на автобус SK «Сколково» до ост. «МШУ Сколково», далее пешком до ул. Весенняя 2., корп. 1";
            case 3:
                return "До офиса в г. Саратове добраться можно на автобусах, троллейбусах и маршрутных такси, которые проезжают через остановку \"Гостиница Олимпия\"\n- Автобусы: № 2д, 6, 53, 90;\n- Троллейбусы: № 4, 15;\n- Маршрутные такси: 21, 42, 42к, 79, 83, 99, 105, 110.\n\nДо офиса в ОСП Сколково добраться можно следующими способами:\n- доехать до ст. м. «Славянский бульвар», выход 4, сесть на автобус № 818 до ост. ЖК «Грюнвальд»;\n- Доехать до ст. м. «Славянский бульвар», выход 4, сесть на автобус SK «Сколково» до ост. «МШУ Сколково», далее пешком до ул. Весенняя 2., корп. 1";
        }
    }

    function getPaymentText($id) {
        switch ($id) {
            case 1:
                return "Установленными днями для выплаты заработной платы являются 10-е число месяца (за период работы с 16-го по последнее число предыдущего месяца) и 25-е число (за период работы с 1-го по 15-е число текущего месяца).\nПри совпадении дня выплаты с выходным или нерабочим праздничным днем выплата заработной платы производится накануне этого дня";
                break;
            case 3:
                return "Установленными днями для выплаты заработной платы являются 10-е число месяца (за период работы с 16-го по последнее число предыдущего месяца) и 25-е число (за период работы с 1-го по 15-е число текущего месяца).\nПри совпадении дня выплаты с выходным или нерабочим праздничным днем выплата заработной платы производится накануне этого дня";
                break;
        }
    }

    function getMeetingsRulesText($firstname) {
        return "$firstname! Совещания, которые Вы проводите, должны быть чёткими, короткими и эффективными.\n\nВсе участники должны знать, где и когда проводится совещание - оповестите каждого и по возможности получите подтверждение.\n\nКаждый должен чётко представлять, какие вопросы будут обсуждаться и какова цель совещания – сообщите цель и разошлите материалы всем участникам.\n\nНикогда не опаздывайте, лучше приходите несколько раньше.\n\nВыключайте звук у сотового телефона. Если необходимо позвонить - выйдите из помещения, предварительно спросив разрешения у ведущего.\n\nБудьте максимально кратки и соблюдайте регламент.\n\nНепременно подведите итоги совещания.\n\n";
    }

    function getPhoneConversationsRulesText($firstname) {
        return "$firstname! Общаясь по телефону, будьте вежливы и уважительны по отношению к собеседнику. При ответе на телефонный звонок поздоровайтесь и представьтесь.\n\nРекомендуем пользоваться функцией телефона «автоматический перевод звонков на другой аппарат» – тогда ни один важный звонок не будет пропущен.\n\nЕсли Вам необходимо отлучиться или вы не можете говорить, попросите коллег ответить на телефонный звонок за Вас. В следующий раз Вы точно так же сможете помочь им.\n\nЕсли вы не можете ответить на звонок, обязательно перезвоните при первом же удобном случае.\n\nЕсли у вас изменились контактные данные (номер служебного мобильного телефона, номер внутреннего телефона, адрес электронной почты), незамедлительно сообщите об этом офис-менеджеру/секретарю";
    }

    function getOfficeRulesText($firstname) {
        return "$firstname, пожалуйста, соблюдайте следующие простые правила работы в офисах Компании:\n\nРабочее место должно содержаться в порядке. Перед уходом убедитесь, что на столе не осталось груды беспорядочно разложенных бумаг, грязные кружки после чая/кофе и т.п.\n\nЕсли у вас есть потребность что-то обсудить с коллегой, который сидит с вами на расстоянии, необходимо подойти к коллеге или позвонить ему, не стоит кричать через ряды других коллег.\n\nБеседы с коллегами и телефонные разговоры на темы, не относящиеся к работе, необходимо вести только за пределами open space.\n\nЕсли у вас с коллегами планируется какое-то длительное обсуждение рабочих вопросов, для этих целей необходимо забукировать и воспользоваться переговорной комнатой.\n\nЗапрещается ставить телефонный звонок вызова слишком громко.\n\nЗапрещается принимать пищу в open space на рабочем месте.\n\nНе злоупотребляйте парфюмерией, так как у ваших коллег может быть аллергия или их просто раздражают запахи. Нанесение парфюма в open space может быть расценено коллегами как неуважение.\n\nСоблюдайте требования к эргономике и интерьеру помещений. Не развешивайте самостоятельно информацию на стенах, не выкатывайте тумбы в места прохода ваших коллег, соблюдайте план расстановки мебели.\n\nВ случае болезни работа удаленно по согласованию с руководителем расценивается как забота о здоровье коллег";
    }

    function getAppearanceRulesText($firstname) {
        return "$firstname! Ваш внешний вид – имидж Компании в целом.\n\nВсе работники участвуют в укреплении делового имиджа Компании своим безупречным деловым поведением, элементами которого являются подобающий внешний вид работника и стиль его делового общения.\n\nВнешний вид работников должен соответствовать деловой атмосфере, общепринятым в деловом мире нормам и правилам. Работник обязан иметь аккуратный и опрятный внешний вид.\n\nДелового стиля одежды обязательно придерживаться всем Работникам в дни участия в официальных встречах, переговорах, деловых визитах. Если вы планируете важные встречи, придерживайтесь строгого стиля – деловой костюм и галстук для мужчин, платье или деловой костюм – для женщин.\n\nЕсли вы работаете на производстве, значит, вам выдали спецодежду – носите её.\n\nВ повседневной рабочей обстановке для Работников, которым не установлено ношение специальной одежды, предназначенной для защиты, или униформы, допустим демократичный стиль одежды. Одежда демократичного покроя и расцветок, умеренность и естественность в макияже, маникюре, украшениях, обувь должна сочетаться и гармонировать с остальными элементами одежды.\n\nЗапрещено ношение: спортивной одежды и обуви, пляжной одежды, глубоко декольтированной одежды, высоких разрезов, открытых линии живота и спины, сетчатых тканей, сланцев, шлепок.\n\nПриветствуется присутствие в одежде элементов корпоративной символики.\n\nПрическа Работника должна быть аккуратной и ухоженной";
    }

    function getReplyForEnterMainRulesMenu() {
        return "Выберите пункт меню, для получения информации о правилах поведения в Компании";
    }

    function getNavigateBackText() {
        return "Вы в главном меню, для получения информации воспользуйтесь командами меню ниже";
    }

    function getReplyForEnterMainInformationMenu() {
        return "Выберите пункт меню, для получения общей информации";
    }

    function getSkolkovoMapUrl() {
        return "https://sigmabot.ddns.net/files/skolkovo_map.jpg";
    }

    function getOskolMapUrl() {
        return "https://sigmabot.ddns.net/files/greenhouse_map.jpg";
    }

    function getSaratovMapUrl() {
        return "https://sigmabot.ddns.net/files/diall_map.jpg";
    }

    function getPhoneCardPrivelegesError($firstname) {
        return "Извините, $firstname!\nВаши привелегии не позволяют просмотреть карточку данного сотрудника";
    }

    function getNoPhoneCardError($firstname) {
        return "Извините, $firstname!\nСотрудник, которого вы ищете не найден, либо ваши привелегии не позволяют просмотреть карточку данного сотрудника";
    }

    function getReplyForIncorrectFLFormatError() {
        return "Ну не может быть такого имени или фамилии ".hex2bin('f09f9982');
    }

    function getReplyForRestartFindUser() {
        return "Похоже вы ищете сотрудника? Давайте я поищу, введите имя и фамилию";
    }

    function getReplyForEnterItHelpInlineMenu($companyId, $email) {
        $domainString = substr($email, strpos($email, "@") + 1);
        $domain = strtok($domainString, '.');
        switch ($companyId) {
            case 1:
                return "Раздел находится в разработке";
            case 3:
                if ($domain === 'diall') {
                    return "Выберите категорию:\n<b>1С, ERP</b> - вопросы по функционированию программ 1С и внедрению ERP;\n<b>Оборудование</b> - вопросы связанные с работой ИТ техники и телефонии (не включается, не показывает, не печатает и пр.);\n<b>Ресурсы</b> - вопросы по работе Интернет, электронной почты, сетевых папок и пр.;\n<b>Другое</b> - вопросы, не относящиеся к остальным категориям.;\n<b>Разблокировать учетную запись</b> - возможность разблокировки учетной записи.";
                } else {
                    return "Выберите категорию:\n<b>1С, ERP</b> - вопросы по функционированию программ 1С и внедрению ERP;\n<b>Оборудование</b> - вопросы связанные с работой ИТ техники и телефонии (не включается, не показывает, не печатает и пр.);\n<b>Ресурсы</b> - вопросы по работе Интернет, электронной почты, сетевых папок и пр.;\n<b>Другое</b> - вопросы, не относящиеся к остальным категориям";
                }
        }
    }

    function getFeedbackText($firstname) {
        return "$firstname, пожалуйста, сформулируйте проблему максимально конкретно, с перечислением сложностей, с которыми вы столкнулись";
    }

    function getReplyForFeedbackSending() {
        return "Нажмите на кнопку ниже и Ваше обращение будет направлено в поддержку";
    }

    function getReplyForDmsSending() {
        return "Ваш вопрос будет направлен специалисту";
    }

    function getReplyForFeedbackIsSent() {
        return "Сообщение успешно отправлено, номер зарегистрированного обращения придет на почту";
    }

    function getReplyForDmsEmptyEmail() {
        return "К сожалению, я не нашел Вашего адреса электронной почты, введите, пожалуйста, email, на который сотрудник сможет направить ответ";
    }

    function getReplyForDmsIsSent() {
        return "Ваш вопрос успешно отправлен, специалист ответит в ближайшее время";
    }

    function gerReplyForSendFeedbackError() {
        return "Не удалось отправить заявление. Повторите попытку позже";
    }

    function getReplyForEnterSalaryMenu() {
        return "Выберите пункт меню, для получения информации о заработной плате";
    }

    function getIssuingDocumentsListText() {
        return "Выберите документ, который Вы хотели бы заказать, нажав на соответствующую цифру.\n\n1. Справка с места работы (подтверждение трудовой деятельности в Компании);\n\n2. Справка, уточняющая особый характер работ или труда (в Пенсионный фонд РФ, льготная пенсия);\n\n3. Справка при рождении ребенка (оформление супругой/ом отпуска по уходу за ребенком, пособий);\n\n4. Справка о доходах и налогах физического лица (2 НДФЛ);\n\n5. Справка о среднем заработке сотрудника;\n\n6. Справка о доходах в произвольной форме;\n\n7. Иной документ";
    }

    function getRequestDocumentTypeCopyText() {
        return "Укажите документ или документы через запятую, копии которых Вы хотели бы заказать";
    }

    function getIssuingDocumentConfirmSmsSendingText() {
        return "Запрос документа необходимо подтвердить вводом кода подтверждения, полученного в SMS";
    }

    function getDocumentDeliveryTypeText() {
        return "Выберите способ получения документа";
    }

    function getDocumentDeliveryTypeFreeFormText() {
        return "Введите свой способ получения документа";
    }

    function getDocumentStartDateText() {
        return "Введите дату начала периода, за который запрашиваются данные.\nПример: <b>01.01.2023</b>";
    }

    function getDocumentEndDateText() {
        return "Введите дату окончания периода, за который запрашиваются данные.\nПример: <b>01.01.2023</b>";
    }

    function getConfirmationSmsEnteringText() {
        return "Введите код, полученный в SMS сообщении";
    }

    function getSuccessApplicationRegisteringText() {
        return "Ваше заявление успешно зарегистрировано";
    }

    function getReplyForEnterDmsMenu($firstname, $dmsType, $isPollFinished, $isPollAvailable) {
        switch ($dmsType) {
            case 0:
                return "Добрый день, $firstname. Вы будете подключены к программе ДМС по истечении испытательного срока, информацию о подключении Вы получите на электронную почту, либо Вас уведомит Ваш непосредственный руководитель";
            case 1:
                if ($isPollFinished || !$isPollAvailable) {
                    return " Добрый день, $firstname. Вы подключены к программе ДМС СК ИНГОССТРАХ. Выберите необходимый пункт меню";
                } else {
                    return "Добрый день, $firstname. Вы подключены к программе ДМС СК ИНГОССТРАХ. Предлагаем Вам пройти опрос по удовлетворенности услугами ДМС, для этого выберите пункт меню -  \"Пройти опрос\"";
                }
            case 2:
                if ($isPollFinished || !$isPollAvailable) {
                    return " Добрый день, $firstname. Вы подключены к программе ДМС СК Согласие. Выберите необходимый пункт меню";
                } else {
                    return "Добрый день, $firstname. Вы подключены к программе ДМС СK Согласие. Предлагаем Вам пройти опрос по удовлетворенности услугами ДМС, для этого выберите пункт меню -  \"Пройти опрос\"";
                }
        }
    }

    function getUrlForSendDmsMemo($dmsType) {
        switch ($dmsType) {
            case 1:
                return "https://sigmabot.ddns.net/files/memo_ingosstrah.pdf";
            case 2:
                return "https://sigmabot.ddns.net/files/memo_soglasie.pdf";
        }
    }

    function getUrlForSendDmsClinics($dmsType) {
        switch ($dmsType) {
            case 1:
                return "https://sigmabot.ddns.net/files/dms_clinics_ingosstrah.pdf";
            case 2:
                return "https://sigmabot.ddns.net/files/dms_clinics_soglasie.pdf";
        }
    }

    function getDmsContacts($dmsType) {
        switch ($dmsType) {
            case 1:
                return "<b>Крыжановская Светлана</b>\nВремя работы: с 9.00 до 18.00\nМоб. телефон: +7 (963) 976-56-14\nКруглосуточный пульт: +7 (800) 200-39-11";
            case 2:
                return "<b>Серякова Екатерина</b>\nВремя работы: с 8.00 до 17.00\nМоб. телефон: +7 (927) 119-70-31\nКруглосуточный пульт: +7 (800) 250-01-01";
        }
    }

    function getReplyForAskToProceedDmsSurvey() {
        return "Подскажите, пользовались ли Вы программой ДМС?";
    }

    function getReplyForNotRelevantToProceedDmsSurvey() {
        return "Спасибо за участие, в настоящее время Ваш опыт не релевантен для прохождения опроса";
    }

    function getReplyForProceedDmsSurvey($pollState) {
        switch ($pollState) {
            case 0:
                return "Пройдите, пожалуйста, небольшой опрос. Ваше мнение очень важно для нас!";
            default:
                return "Ранее Вы уже начали проходить данный опрос, продолжим?";
        }
    }

    function getReplyForAskADmsPollQuestionWithSingleChoose($pollInfo, $pollQuestionInfo) {
        $id = $pollInfo['poll_state'];
        $pollQuestionData = $pollQuestionInfo[$id];
        $replyOptions = json_decode($pollQuestionData['responses'], true);
        $responses = "";
        foreach ($replyOptions['options'] as $key=>$value) {
            $responses .= (string)$value['id'].". ".$value['title']."\n";
        }
        return "<b>Вопрос №".$pollQuestionData['question_id']."</b>\n".$pollQuestionData['question_text']."\n\nВарианты ответа:\n".$responses."\n\nДля ответа на вопрос отправьте цифру с вариантом ответа";
    }

    function getReplyForAskADmsPollQuestionWithMultipleChoose($pollInfo, $pollQuestionInfo) {
        $id = $pollInfo['poll_state'];
        $pollQuestionData = $pollQuestionInfo[$id];
        $replyOptions = json_decode($pollQuestionData['responses'], true);
        $responses = "";
        foreach ($replyOptions['options'] as $key=>$value) {
            $responses .= (string)$value['id'].". ".$value['title']."\n";
        }
        return "<b>Вопрос №".$pollQuestionData['question_id']."</b>\n".$pollQuestionData['question_text']."\n\nВарианты ответа:\n".$responses."\n\nПожалуйста, отметьте все релевантные пункты. Если Вы не пользовались отдельными блоками, пропустите их";
    }

    function getReplyForAskADmsPollQuestionWithFreeReply($pollInfo, $pollQuestionInfo) {
        $id = $pollInfo['poll_state'];
        $pollQuestionData = $pollQuestionInfo[$id];
        return "<b>Вопрос №".$pollQuestionData['question_id']."</b>\n".$pollQuestionData['question_text'];
    }

    function getReplyForAskADmsPollQuestionWithScaleChoose($pollInfo, $pollQuestionInfo) {
        $id = $pollInfo['poll_state'];
        $pollQuestionData = $pollQuestionInfo[$id];
        return "<b>Вопрос №".$pollQuestionData['question_id']."</b>\n".$pollQuestionData['question_text'];
    }

    function getReplyForFinishDmsPoll() {
        return "Это были все вопросы! Спасибо за уделенное время!";
    }

    function getReplyForPollIsAlreadyFinished() {
        return "Вы уже прошли данный опрос, спасибо за уделенное время!";
    }

    function getErrorForSaveDmsPollReply() {
        return "Не удалось сохранить ответ. Введите, пожалуйста, цифру еще раз!";
    }

    function getReplyForAskADmsQuestion() {
        return "Вы можете задать интересующий Вас вопрос по ДМС, ответ будет направлен на Ваш адрес электронной почты";
    }

    function getReplyForMainSalaryInformation($companyId) {
        switch ($companyId) {
            case 1:
                return "Заработная плата в Компании конфиденциальна, руководители на всех уровнях обязаны обеспечивать ее конфиденциальность";
            case 2; case 3:
                return "Заработная плата в Компании конфиденциальна, руководители на всех уровнях обязаны обеспечивать ее конфиденциальность.\nРаботник может узнать грейд своей должности и базовую заработную плату только у своего руководителя и только в Департаменте по работе с персоналом";
        }
    }

    function getApplicationsText($firstname) {
        return "$firstname,\nКакое заявление Вы хотели бы оформить?";
    }

    function getVacationInformationText() {
        return "Выбирите необходимый пункт в меню ниже";
    }

    function getRestVacationInfoText($data, $vacations) {
        $vacationsList = "";
        $currentDate = (string) date("d.m.Y");
        $currentYear = (string) date("Y");
        if ($data['main'] == 0 && $data['additional'] == 0) {
            return "Извините, информация по количеству оставшихся дней отпуска недоступна, попробуйте запросить позднее";
        } else {
            foreach ($vacations['vacations'] as $value) {
                $newDate = date('d.m.Y', strtotime($value['date1']));
                $vacationsList .= "$newDate (".$this->getDaysString($value['amount']).")\n";
            }
            return "На $currentDate Вы имеете право на ".$this->getDaysString($data['main'])." основного отпуска, ".$this->getDaysString($data['additional'])." дополнительного отпуска.\n\nВаш график отпуска на $currentYear год:\n$vacationsList";
        }
    }

    function getRestVacationInfoToChooseText($firstname, $listCount) {
        if (!$listCount) {
            return "$firstname, к сожалению, у Вас не осталось отпусков для переноса в этом году";
        } else {
            return "$firstname, выберите отпуск, который Вы хотели бы перенести";
        }
    }

    function getReplyForApplicationPreparations($firstname, $companyId) {
        switch ($companyId) {
            case 1:
                return "$firstname, образец заявления на отпуск будет направлен на вашу рабочую почту. Нажмите продолжить";
            case 3:
                return "$firstname, выберите тип отпуска, нажав на соответствующую кнопку ниже";
        }
    }

    function getReplyForPostponedApplicationPreparations($firstname, $companyId) {
        switch ($companyId) {
            case 1:
                return "$firstname, образец заявления на отпуск будет направлен на вашу рабочую почту. Нажмите продолжить";
            case 2; case 3:
                return "$firstname, введите дату начала отпуска, на которую был запланирован отпуск изначально.\nПример: <b>01.01.2023</b>";
            case 22; case 33:
                return "Извините, опция недоступна для Вас";
        }
    }

    function getReplyForRegularVacationStartPreparations() {
        return "Введите желаемую дату начала отпуска.\nПример: <b>1.01.2023 или 01.01.2023</b>";
    }

    function getSetRegularVacationEndDateText() {
        return "Введите желаемое количество дней отпуска, которое Вы хотели бы оформить.\nПример: <b>14</b>";
    }

    function getPostponedVacationDurationText($restVacationDuration) {
        return "Максимальное количество дней, которое вы можете перенести в этом отпуске: $restVacationDuration. Введите желаемую длительность отпуска (количество дней).\nПример: <b>$restVacationDuration</b>";
    }

    function getRegularVacationDurationText($restVacationData, $vacationType) {
        $currentDate = (string) date("d.m.Y");
        switch($vacationType) {
            case 0; case 1:
                $restVacationDuration = $vacationType == 0 ? $restVacationData['main'] : $restVacationData['additional'];
                $whichVacation = $vacationType == 0 ? 'основного' : 'дополнительного';
                switch ($whichVacation) {
                    case 'основного':
                        return "На $currentDate Вы имеете право на ".$this->getDaysString($restVacationDuration)." $whichVacation отпуска.\nВведите желаемое количество дней отпуска, которое Вы хотели бы оформить.\nПример: <b>14</b>";
                    case 'дополнительного':
                        return "На $currentDate Вы заработали ".$this->getDaysString($restVacationDuration)." $whichVacation отпуска.\nВведите желаемое количество дней отпуска, которое Вы хотели бы оформить.\nПример: <b>4</b>";
                }
            case 2; case 3:
                return "Введите желаемое количество дней отпуска, которое Вы хотели бы оформить.\nПример: <b>5</b>";
        }
    }

    function getSetPostponedVacationEndDateText() {
        return "Введи дату окончания отпуска, на которую был запланирован отпуск изначально.\nПример: <b>01.01.2023</b>";
    }

    function getSetPostponedVacationNewStartDateText() {
        return "Введи новую дату начала отпуска.\nПример: <b>1.01.2023 или 01.01.2023</b>";
    }

    function getSetPostponedVacationNewEndDateText() {
        return "Введи новую дату окончания отпуска.\nПример: <b>1.01.2023 или 01.01.2023</b>";
    }

    function getSetPostponedVacationReasonText() {
        return "Введите причину.\nПример: <b>по семейным обстоятельствам.</b>";
    }

    function getDateInThePastErrorText() {
        return "Дата не соответствует необходимым условиям, убедитесь, что дата отпуска:\nНе находится в прошлом;\nКак минимум на 5 дней позднее текущей даты.\nВведите, пожалуйста, корректную дату";
    }

    function getPostponedDateInThePastErrorText() {
        return "Дата не соотвествует условиям переноса, убедитесь, что дата, на которую переносится отпуск:\n1.Не находится в прошлом и как минимум на 5 дней позднее текущей даты;\n2.Не равна дате начала переносимого отпуска;\n3.В случае дробления переносимого отпуска каждая следующая часть начинается не ранее окончания предыдущей части отпуска.\nВведите, пожалуйста, корректную дату";
    }

    function getDateFormatErrorText() {
        return "Неверный формат даты. Попробуйте снова.\nПример: <b>1.01.2023 или 01.01.2023</b>";
    }

    //to delete
    function getSendVacationFormText() {
        return "Заявление будет отправлено на Ваш рабочий адрес электронной почты";
    }

    function getRegisterVacationFormText() {
        return "Для регистрации заявления нажмите на кнопку и ожидайте получения информации";
    }

    function getRegularVacationAcademicReasonText() {
        return "Введите причину необходимости учебного отпуска.\nПример: <b>Для прохождения промежуточной аттестации, для подготовки и защиты выпускной квалификационной работы и т.д.</b>";
    }

    function getRegularVacationAcademicCauseText() {
        return "Введите основание для предоставления учебного отпуска.\nПример: <b>Справка-вызов, решение диссертационного совета и т.д.</b>";
    }

    function getRegularAcademicVacationFormatErrorText() {
        return "Длительность отпуска введена в неверном формате, возможны только цифры. \nПример: <b>14</b>";
    }

    function getVacationDurationFormatErrorText() {
        return "Длительность отпуска введена в неверном формате, возможны только цифры. \nПример: <b>14</b>";
    }

    function getSentVacationFormResultText($firstname, $companyId) {
        switch ($companyId) {
            case 1:
              return "$firstname, заявление отправлено на Ваш рабочий email. Если вы не получили письмо, пожалуйста, проверьте папку \"Спам\", возможно оно там.\n\rПожалуйста, заполните заявление, подпишите и передайте в отдел по работе с персоналом ООО \"СИГМА КЭПИТАЛ\".\n\rСпасибо за обращение!";
            case 2:
              return "$firstname, заявление отправлено на Ваш рабочий email. Если вы не получили письмо, пожалуйста, проверьте папку \"Спам\", возможно оно там.\n\rПожалуйста, заполните заявление, подпишите и передайте в службу по работе с персоналом ООО \"Гринхаус\".\n\rСпасибо за обращение!";
            case 3:
              return "$firstname, заявление отправлено на Ваш рабочий email. Если вы не получили письмо, пожалуйста, проверьте папку \"Спам\", возможно оно там.\n\rПожалуйста, заполните заявление, подпишите и передайте в отдел расчета заработной платы и КДП ООО \"ДИАЛЛ АЛЬЯНС\".\n\rСпасибо за обращение!";
        }
    }

    function getVacationDurationErrorText($realDuration) {
        return "К сожалению, максимальное количество дней, которые Вы можете перенести в этом отпуске: $realDuration\nПопробуйте снова";
    }

    function getVacationDurationLimitErrorText($restVacationDuration, $vacationType) {
        switch($vacationType) {
            case 0; case 1:
                $whichVacation = $vacationType == 0 ? 'основного' : 'дополнительного';
                return "К сожалению, максимальное количество дней $whichVacation отпуска, которые Вы можете взять в этом году: $restVacationDuration\nПопробуйте снова";
            default:
                return "К сожалению, максимальное количество дней отпуска, которые Вы можете взять в этом году: $restVacationDuration\nПопробуйте снова";
        }
    }

    function getReplyCheckPostponedVacationDuration($restDuration) {
        return "У Вас еще остались дни в данном отпуске, которые необходимо перенесети. Количество оставшихся дней: $restDuration.Пожалуйста, укажите дату начала отпуска.\nПример: <b>1.01.2023 или 01.01.2023</b>";
    }

    function getReplyForADSuccessfulActivation() {
        return 'Ваша учетная запись разблокирована.';
    }

    function getReplyForIncorrectEmailFormatError() {
        return "К сожалению, введенный адрес электронной почты некорректен. Попробуйте еще раз!";
    }

    function getReplyForExitConfirmation($username) {
        return "$username, Вы уверены, что хотите выйти?";
    }

    function SmsCodeSendingErrorText($errorMessage) {
        return "<b>Ошибка!</b>\n$errorMessage";
    }

    function getADActivationErrorText() {
        return 'Ошибка разблокировки учетной записи.';
    }

    function getDaysString($value) {
        $lastNumber = null;
        if (strrpos($value, ',') === false) {
          $wholeNumber = $value;
          $checkNumber = mb_strlen($wholeNumber, "UTF-8") > 1 ? substr($wholeNumber, -2) : $wholeNumber;
          switch ((integer) $checkNumber) {
            case 1:
              return "$value день";
            case 2; case 3; case 4:
                return "$value дня";
            case 5; case 6; case 7; case 8; case 9; case 0:
                  return "$value дней";
              default:
                  return "$value дней";
          }
        } else {
          return "$value дня";
        }
    }
}
