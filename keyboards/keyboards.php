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
                    "text" => "Авторизация по номеру телефона"
                    )
                )
            ),
            "resize_keyboard" => true,
            "one_time_keyboard" => false
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
            "one_time_keyboard" => false
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
            "one_time_keyboard" => false
        );
        return json_encode($keyboard);
    }

    function emailAuthorizationProceedKeyboard() {
        $keyboard = array(
            "inline_keyboard" => array(
                array(
                    array(
                        "text" => "Продолжить",
                        "callback_data" => "sendConfirmationCode"
                    )
                )
            )
        );
        return json_encode($keyboard);
    }

    function backToAuthorizationKeyboard() {
        $keyboard = array(
            "inline_keyboard" => array(
                array(
                    array(
                        "text" => "Авторизоваться",
                        "callback_data" => "goToTheStart"
                    )
                )
            )
        );
        return json_encode($keyboard);
    }

    function mainKeyboard() {
        $keyboard = array(
            "keyboard" => array(
                array(
                    array("text" => "Телефонный справочник"),
                    array("text" => "КДП и Заработная плата")
                ),
                array(
                    array("text" => "Наши ценности"),
                    array("text" => "Общая информация")
                ),
                array(
                    array("text" => "Правила"),
                    array("text" => "Помощь ИТ специалиста")
                ),
                array(
                    array("text" => "ДМС"),
                    array("text" => "Выход")
                )
            ),
            "resize_keyboard" => true,
            "one_time_keyboard" => false
        );
        return json_encode($keyboard);
    }

    function employeeCardOptionsKeyboard() {
        $keyboard = array(
            "inline_keyboard" => array(
                array(
                    array(
                        "text" => 'Карточка',
                        "callback_data" => 'getUserCard'
                    )
                ),
                array(
                    array(
                        "text" => "Email",
                        "callback_data" => 'getUserEmail'
                    )
                ),
                array(
                    array(
                        "text" => "Мобильный телефон",
                        "callback_data" => 'getUserMobileNumber'
                    )
                )
            )
        );
        return json_encode($keyboard);
    }

    function getValueKeyboard($inlineValue) {
        return json_encode(array(
            "inline_keyboard" => array(
                array(
                    array(
                        "text" => "Далее",
                        "callback_data" => "$inlineValue"
                    )
                )
            ),
            "resize_keyboard" => true,
            "one_time_keyboard" => false
        ));
    }

    function getMainRulesMenuKeyboard() {
        return json_encode(array(
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
        ));
    }

    function getMainInformationMenuKeyboard($companyID) {
        switch ($companyID) {
            case 1:
                return json_encode(array(
                    "keyboard" => array(
                        array(
                            array("text" => "Как добраться")
                        ),
                        array(
                            array("text" => "Схема проезда (Сколково)")
                        ),
                        array(
                            array("text" => "Назад")
                        )
                    ),
                    "resize_keyboard" => true,
                    "one_time_keyboard" => false
                ));
            case 3:
                return json_encode(array(
                    "keyboard" => array(
                        array(
                            array("text" => "Как добраться")
                        ),
                        array(
                            array("text" => "Схема проезда (Сколково)"),
                            array("text" => "Схема проезда (Саратов)")
                        ),
                        array(
                            array("text" => "Помощь ИТ специалиста"),
                            array("text" => "Назад")
                        )
                    ),
                    "resize_keyboard" => true,
                    "one_time_keyboard" => false
                ));
        }
    }

    function getItHelpMenuInlineKeyboard($companyId, $email) {
        switch ($companyId) {
            case 1:
                return null;
            case 3:
                $keyboard = array(
                    "keyboard" => array(
                        array(
                            array("text" => "1С, ERP"),
                            array("text" => "Оборудование"),
                        ),
                        array(
                            array("text" => "Ресурсы"),
                            array("text" => "Другое"),
                        ),
                    ),
                    "resize_keyboard" => true,
                    "one_time_keyboard" => false
                );

                $domainString = substr($email, strpos($email, "@") + 1);
                $domain = strtok($domainString, '.');
                $unlockLine = array(array("text" => "Разблокировать учетную запись"));
                $bottomLine = array(array("text" => "Назад"));
                if ($domain === 'diall') {
                    array_push($keyboard['keyboard'], $unlockLine);
                }
                array_push($keyboard['keyboard'], $bottomLine);
                return json_encode($keyboard);
        }
    }

    function getFeedbackSendingInlineKeyboard() {
        return json_encode(array(
            "inline_keyboard" => array(
                array(
                    array(
                        "text" => "Отправить обращение",
                        "callback_data" => "sendFeedback"
                    )
                )
            )
        ));
    }

    function getDmsSendingInlineKeyboard() {
        return json_encode(array(
            "inline_keyboard" => array(
                array(
                    array(
                        "text" => "Отправить вопрос",
                        "callback_data" => "sendDmsQuestion"
                    )
                )
            )
        ));
    }

    function getSalaryMenuKeyboard() {
        return json_encode(array(
            "keyboard" => array(
                array(
                    array("text" => "Общая информация"),
                    array("text" => "Сроки выплаты"),
                ),
                array(
                    array("text" => "Заявления"),
                    array("text" => "Мой отпуск")
                ),
                array(
                    array("text" => "Мой график"),
                    array("text" => "Назад")
                )
            ),
            "resize_keyboard" => true,
            "one_time_keyboard" => false
        ));
    }

    function getDmsMenuKeyboard($dmsType, $isPollFinished, $isPollAvailable) {
        if ($dmsType == 0) {
            return json_encode(array(
                "keyboard" => array(
                    array(
                        array("text" => "Задать вопрос"),
                        array("text" => "Назад")
                    )
                ),
                "resize_keyboard" => true,
                "one_time_keyboard" => false
            ));
        } else {
            if (!$isPollFinished && $isPollAvailable) {
                return json_encode(array(
                    "keyboard" => array(
                        array(
                            array("text" => "Памятка"),
                            array("text" => "Перечень клиник"),
                        ),
                        array(
                            array("text" => "Контакты"),
                            array("text" => "Пройти опрос")
                        ),
                        array(
                            array("text" => "Задать вопрос"),
                            array("text" => "Назад")
                        )
                    ),
                    "resize_keyboard" => true,
                    "one_time_keyboard" => false
                ));
            } else {
                return json_encode(array(
                    "keyboard" => array(
                        array(
                            array("text" => "Памятка"),
                            array("text" => "Перечень клиник"),
                        ),
                        array(
                            array("text" => "Контакты"),
                            array("text" => "Задать вопрос")
                        ),
                        array(
                            array("text" => "Назад")
                        )
                    ),
                    "resize_keyboard" => true,
                    "one_time_keyboard" => false
                ));
            }
        }
    }

    function getApplicationMenuInlineKeyboard() {
        return json_encode(array(
            "inline_keyboard" => array(
                array(
                    array(
                        "text" => "Заявление на выдачу документов",
                        "callback_data" => "documentsIssuingCase"
                    )
                ),
                array(
                    array(
                        "text" => "Заявление на выдачу копии документов",
                        "callback_data" => "documentsCopiesIssuingCase"
                    )
                )
            )
        ));
    }

    function getVacationInformationKeyboard() {
        return json_encode(array(
            "keyboard" => array(
                array(array("text" => "Количество дней")),
                array(array("text" => "Заявление на отпуск")),
                array(array("text" => "Заявление на перенос отпуска")),
                array(array("text" => "Назад"))
            ),
            "resize_keyboard" => true,
            "one_time_keyboard" => false
        ));
    }

    function getIssuingDocumentsListInlineKeyboard() {
        return json_encode(array(
            "inline_keyboard" => array(
                array(
                    array("text" => "1", "callback_data" => "1"),
                    array("text" => "2", "callback_data" => "2"),
                    array("text" => "3", "callback_data" => "3"),
                    array("text" => "4", "callback_data" => "4"),
                    array("text" => "5", "callback_data" => "5"),
                    array("text" => "6", "callback_data" => "6"),
                    array("text" => "7", "callback_data" => "7")

                )
            )
        ));
    }

    function getIssuingDocumentsConfirmSmSSendingInlineKeyboard() {
        return json_encode(array(
            "inline_keyboard" => array(
                array(
                    array("text" => "Отправить код", "callback_data" => "sendConfirmationSms")
                )
            )
        ));
    }

    function getIssuingPostponedDocumentsConfirmSmSSendingInlineKeyboard() {
        return json_encode(array(
            "inline_keyboard" => array(
                array(
                    array("text" => "Отправить код", "callback_data" => "sendPostponedConfirmationSms")
                )
            )
        ));
    }

    function getIssuingPostponedDocumentCopyConfirmSmSSendingInlineKeyboard() {
        return json_encode(array(
            "inline_keyboard" => array(
                array(
                    array("text" => "Отправить код", "callback_data" => "sendDocumentCopyConfirmationSms")
                )
            )
        ));
    }

    function smsRetryInlineKeyboard($issueType) {
        $callbackData = "";
        switch($issueType) {
            case 0; case 1; case 2; case 3:
                $callbackData = "sendConfirmationSms";
                break;
            case 4:
                $callbackData = "sendPostponedConfirmationSms";
                break;
            case 5; case 6:
                $callbackData = "sendDocumentCopyConfirmationSms";
                break;
        }
        return json_encode(array(
            "inline_keyboard" => array(
                array(
                    array("text" => "Повторить отправку кода", "callback_data" => $callbackData)
                )
            )
        ));
    }

    function getChooseVacationToPostponeInlineKeyboard($chatID, $data) {
        $vacation = array();
        foreach($data['vacations'] as $key=>$value) {
            $newDate = date('d.m.Y', strtotime($value['date1']));
            $itemTitle = "$newDate (".$this->getDaysString($value['amount']).")";
            $callback_data = $chatID."_".$key;
            $vacationItem = array(array(
                "text" => $itemTitle,
                "callback_data" => $callback_data
            ));
            array_push($vacation, $vacationItem);
        }

        return json_encode(array(
            "inline_keyboard" => $vacation
        ));
    }

    function getApplicationPreparationsInlineKeyboard($companyId) {
        switch ($companyId) {
            case 1:
                return json_encode(array(
                    "inline_keyboard" => array(
                        array(
                            array(
                                "text" => "Продолжить",
                                "callback_data" => "sendOldRegularVacationForm"
                            )
                        )
                    )
                ));
            case 3:
                return json_encode(array(
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
                ));
        }
    }

    function getApplicationPreparationsInlineKeyboardNew($companyId) {
        switch ($companyId) {
            case 1:
                return json_encode(array(
                    "inline_keyboard" => array(
                        array(
                            array(
                                "text" => "Продолжить",
                                "callback_data" => "sendOldRegularVacationForm"
                            )
                        )
                    )
                ));
            case 3:
                return json_encode(array(
                    "inline_keyboard" => array(
                        array(
                            array(
                                "text" => "Основной",
                                "callback_data" => "1"
                            ),
                            array(
                                "text" => "Дополнительный",
                                "callback_data" => "2"
                            )
                        ),
                        array(
                            array(
                                "text" => "Без сохранения",
                                "callback_data" => "3"
                            ),
                            array(
                                "text" => "Учебный",
                                "callback_data" => "4"
                            )
                        )
                    )
                ));
        }
    }

    function getPostponedApplicationPreparationsInlineKeyboard($companyId) {
        switch ($companyId) {
            case 1:
                return json_encode(array(
                    "inline_keyboard" => array(
                        array(
                            array(
                                "text" => "Продолжить",
                                "callback_data" => "sendOldPostponeVacationForm"
                            )
                        )
                    )
                ));
            case 2; case 3; case 22; case 33:
                return null;
        }
    }

    // to delete
    function getSendRegularVacationFormInlineKeyboard() {
        return json_encode(array(
            "inline_keyboard" => array(
                array(
                    array(
                        "text" => "Отправить заявление",
                        "callback_data" => "sendNewRegularVacationForm"
                    )
                )
            )
        ));
    }

    // to delete
    function getSendPostponedVacationFormInlineKeyboard() {
        return json_encode(array(
            "inline_keyboard" => array(
                array(
                    array(
                        "text" => "Отправить заявление",
                        "callback_data" => "sendPostponedVacationForm"
                    )
                )
            )
        ));
    }

    function getRegisterVacationFormInlineKeyboard() {
        return json_encode(array(
            "inline_keyboard" => array(
                array(
                    array(
                        "text" => "Зарегистрировать заявление в \"HRLink\"",
                        "callback_data" => "sendNewRegularVacationForm"
                    )
                )
            )
        ));
    }

    function getRegisterPostponedVacationFormInlineKeyboard() {
        return json_encode(array(
            "inline_keyboard" => array(
                array(
                    array(
                        "text" => "Зарегистрировать заявление в \"HRLink\"",
                        "callback_data" => "sendPostponedVacationForm"
                    )
                )
            )
        ));
    }

    function getDocumentDeliveryTypeInlineKeyboard() {
        return json_encode(array(
            "inline_keyboard" => array(
                array(
                    array(
                        "text" => "Отправить непосредственному руководителю",
                        "callback_data" => "1"
                    )
                ),
                array(
                    array(
                        "text" => "Заберу сам",
                        "callback_data" => "2"
                    )
                ),
                array(
                    array(
                        "text" => "Другое",
                        "callback_data" => "3"
                    )
                )
            )
        ));
    }

    function getRegisterDocumentFormInlineKeyboard() {
        return json_encode(array(
            "inline_keyboard" => array(
                array(
                    array(
                        "text" => "Зарегистрировать заявление в \"HRLink\"",
                        "callback_data" => "sendDocumentForm"
                    )
                )
            )
        ));
    }

    function getRegisterDocumentCopyFormInlineKeyboard() {
        return json_encode(array(
            "inline_keyboard" => array(
                array(
                    array(
                        "text" => "Зарегистрировать заявление в \"HRLink\"",
                        "callback_data" => "sendDocumentCopyForm"
                    )
                )
            )
        ));
    }

    function getAskToProceedDmsSurveyInlineKeyboard() {
        return json_encode(array(
            "inline_keyboard" => array(
                array(
                    array("text" => "Пользовался", "callback_data" => "dmsGoToSurvey"),
                    array("text" => "Пока нет", "callback_data" => "dmsNotRelevantToProceedWithSurvey")
                )
            )
        ));
    }

    function getProceedDmsSurveyInlineKeyboard($pollState) {
        $title = $pollState == 0 ? "Начать" : "Продолжить";
        $callbackData = $pollState == 0 ? "proceedDmsSurvey" : "returnToNonFinishedDmsSurvey";
        return json_encode(array(
            "inline_keyboard" => array(
                array(
                    array(
                        "text" => $title,
                        "callback_data" => $callbackData
                    )
                )
            )
        ));
    }

    function getNotUsedKeyboard() {
        return json_encode(array(
            "inline_keyboard" => array(
                array(
                    array("text" => "Не пользовался", "callback_data" => "Не пользовался")
                )
            )
        ));
    }

    function getInlineKeyboardForAskADmsPollQuestionWithSingleChoose($pollInfo, $pollQuestionInfo) {
        $list = array();
        $replyList = array();
        $notUsedReplyList = array();
        $id = $pollInfo['poll_state'];
        $options = json_decode($pollQuestionInfo[$id]['responses'], true);
        foreach($options['options'] as $key=>$value) {
            $itemTitle = $value['id'] == 6 ? (string)$value['title'] : (string)$value['id'];
            $callbackData = array(
                'pollId'=> $pollQuestionInfo[$id]['poll_id'],
                'questionId' => $pollQuestionInfo[$id]['question_id'],
                'selectedReplyId' => (string)$value['id']
            );
            $replyItem = array(
                "text" => $itemTitle,
                "callback_data" => json_encode($callbackData)
            );
            if ($value['id'] < 6) {
                array_push($replyList, $replyItem);
            } else {
                array_push($notUsedReplyList, $replyItem);
            }
        }
        array_push($list, $replyList);
        if (count($notUsedReplyList) > 0) {
            array_push($list, $notUsedReplyList);
        }
        return json_encode(array(
            "inline_keyboard" => $list
        ));
    }

    function getInlineKeyboardForAskADmsPollQuestionWithMultipleChoose($pollInfo, $pollQuestionInfo) {
        $replyList = array();
        $id = $pollInfo['poll_state'];
        $options = json_decode($pollQuestionInfo[$id]['responses'], true);
        $nextButtonText = $pollQuestionInfo[$id]['question_id'] >= array_count_values(array_column($pollQuestionInfo, 'question_id')) ? "Завершить" : "Продолжить";
        $nextButtonCallbackData =  $pollQuestionInfo[$id]['question_id'] >= array_count_values(array_column($pollQuestionInfo, 'question_id')) ? 'finishDmsPoll' : 'nextDmsPollOption';
        foreach($options['options'] as $key=>$value) {
            $itemTitle = $value['isSelected'] ? hex2bin("E29C85") : (string)$value['id'];
//             $itemTitle = (string)$value['id'];
            $callbackData = array(
                'pollId'=> $pollQuestionInfo[$id]['poll_id'],
                'questionId' => $pollQuestionInfo[$id]['question_id'],
                'selectedReplyId' => $value['id']
            );
            $replyItem = array(
                "text" => $itemTitle,
                "callback_data" => json_encode($callbackData)
            );
            array_push($replyList, $replyItem);
        }
        $nextButtonItem = array(array(
            "text" => $nextButtonText,
            "callback_data" => $nextButtonCallbackData
        ));
//         array_push($replyList, $nextButtonItem);

        return json_encode(array(
            "inline_keyboard" => array($replyList, $nextButtonItem)
        ));
    }

    function getInlineKeyboardForUpdateADmsPollQuestionWithMultipleChoose($pollInfo, $pollQuestionInfo, $pollUserQuestionInfo) {
        $replyList = array();
        $id = $pollInfo['poll_state'];
        $options = json_decode($pollUserQuestionInfo[$id]['responses'], true);
        $nextButtonText = $pollQuestionInfo[$id]['question_id'] >= array_count_values(array_column($pollQuestionInfo, 'question_id')) ? "Завершить" : "Продолжить";
        $nextButtonCallbackData =  $pollQuestionInfo[$id]['question_id'] >= array_count_values(array_column($pollQuestionInfo, 'question_id')) ? 'finishDmsPoll' : 'nextDmsPollOption';
        foreach($options['options'] as $key=>$value) {
            $itemTitle = $value['isSelected'] ? hex2bin("E29C85") : (string)$value['id'];
//             $itemTitle = (string)$value['id'];
            $callbackData = array(
                'pollId'=> $pollQuestionInfo[$id]['poll_id'],
                'questionId' => $pollQuestionInfo[$id]['question_id'],
                'selectedReplyId' => $value['id']
            );
            $replyItem = array(
                "text" => $itemTitle,
                "callback_data" => json_encode($callbackData)
            );
            array_push($replyList, $replyItem);
        }
        $nextButtonItem = array(array(
            "text" => $nextButtonText,
            "callback_data" => $nextButtonCallbackData
        ));
//         array_push($replyList, $nextButtonItem);

        return json_encode(array(
            "inline_keyboard" => array($replyList, $nextButtonItem)
        ));
    }

    function getEmployeeMonthlyWorkdaysCalendar($monthlyWorkData) {
        $data = $this->createdCalendar($monthlyWorkData);
        $mainArray = array();

        $headerArray = array(
            array("text" => "<<", "callback_data" => "previousMonthCalendarDataAction"),
            array("text" => $monthlyWorkData['currentMonth'], "callback_data" => "defaultCallbackResponse"),
            array("text" => ">>","callback_data" => "nextMonthCalendarDataAction")
        );
        $weeksDayArray = array(
            array("text" => "Пн", "callback_data" => "defaultCallbackResponse"),
            array("text" => "Вт", "callback_data" => "defaultCallbackResponse"),
            array("text" => "Ср", "callback_data" => "defaultCallbackResponse"),
            array("text" => "Чт", "callback_data" => "defaultCallbackResponse"),
            array("text" => "Пт", "callback_data" => "defaultCallbackResponse"),
            array("text" => "Сб", "callback_data" => "defaultCallbackResponse"),
            array("text" => "Вс", "callback_data" => "defaultCallbackResponse")
        );
        $footerArray = array(
             array(
                 "text" => hex2bin("E29880")." Дней ".$monthlyWorkData['totalWorkDays']." = ".$monthlyWorkData['totalDayWorkHours']." ч / ".hex2bin("F09F8C99")." Ночей ".$monthlyWorkData['totalWorkNights']." = ".$monthlyWorkData['totalNightWorkHours']." ч",
                 "callback_data" => "defaultCallbackResponse"
             )
        );

        array_push($mainArray, $headerArray);
        array_push($mainArray, $weeksDayArray);
        foreach ($data as $value) {
            array_push($mainArray, $value);
        }
        array_push($mainArray, $footerArray);

        return json_encode(array("inline_keyboard" => $mainArray));
    }

    function createdCalendar($monthlyWorkData) {
        $daysList = $monthlyWorkData['daysList'];
        $itemsCount = count($daysList);
        $startCell = $monthlyWorkData['firstDayOfMonthWeekIndex'];
        $mainArray = array();
        $firstRowArray = array();
        $c = 0;
        $count = 0;
        $headerIndex = 0;
        $offset = 14;
        for ($i = 0; $i < $startCell; $i++) {
            array_push($firstRowArray, array("text" => " ", "callback_data" => "defaultCallbackResponse"));
            $count++;
            $headerIndex = $count;
        }

        for ($m = $startCell; $m < 7; $m++) {
            array_push($firstRowArray, array(
                "text" => (string)$daysList[$c]['buttonText'],
                "callback_data" => "defaultCallbackResponse")
            );
            $c++;
            $count++;
        }
        array_push($mainArray, $firstRowArray);

        while($count <= ($itemsCount + $headerIndex)) {
            $rowArray = array();
            while ($count < $offset) {
                array_push($rowArray, array(
                    "text" => (string)$daysList[$c]['buttonText'],
                    "callback_data" => "defaultCallbackResponse")
                );
                $c++;
                $count++;
            }
            $offset += 7;
            array_push($mainArray, $rowArray);
        }

        $b1 = 0;
        foreach($mainArray as $value) {
            foreach($value as $value2) {
                if ($value2['text'] != '') {
                    $b1++; // 31
                }
            }
        }

        $b2 = 0;
        foreach($mainArray as $value) {
            foreach($value as $value2) {
                $b2++; // 35
            }
        }


        $rest = $b2 - $b1;
        for ($i = 0; $i < $rest; $i++) {
            array_push($mainArray[count($mainArray) - 1], array("text" => " ", "callback_data" => "defaultCallbackResponse"));
        }

        return $mainArray;
    }

    function exitConfirmationKeyboard() {
        $keyboard = array(
            "keyboard" => array(
                array(
                    array(
                        "text" => "Выйти"
                    ),
                    array(
                        "text" => "Остаться"
                    )
                )
            ),
            "resize_keyboard" => true,
            "one_time_keyboard" => false
        );
        return json_encode($keyboard);
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
