<?php
/**
 * Created by PhpStorm.
 * User: murad
 * Date: 13.01.2018
 * Time: 17:34
 */



class Forms {

    // функция формирует форму для плановго отпуска
    function getRegularVacationForm($position, $fullName, $startDate, $days, $bonus, $day, $month, $year, $sign) {

        require('Classes/PHPExcel.php');
        $objPHPExcel = PHPExcel_IOFactory::load("forms/vacation_form.xlsx");

        if ($bonus) {
            $text = "Прошу предоставить ежегодный оплачиваемый отпуск c {$startDate}г., в количестве ".$days." к. д., с единовременной выплатой 90% от оклада.";
        }  else {
            $text = "Прошу предоставить ежегодный оплачиваемый отпуск c {$startDate}г., в количестве ".$days." к. д.";
        }

        switch ($month) {
            case "January":
                $newMonth = str_replace("January", "января", $month);
                break;
            case "February":
                $newMonth = str_replace("February", "февраля", $month);
                break;
            case "March":
                $newMonth = str_replace("March", "марта", $month);
                break;
            case "April":
                $newMonth = str_replace("April", "апреля", $month);
                break;
            case "May":
                $newMonth = str_replace("May", "мая", $month);
                break;
            case "June":
                $newMonth = str_replace("June", "июня", $month);
                break;
            case "July":
                $newMonth = str_replace("July", "июля", $month);
                break;
            case "August":
                $newMonth = str_replace("August", "августа", $month);
                break;
            case "September":
                $newMonth = str_replace("September", "сентября", $month);
                break;
            case "Oсtober":
                $newMonth = str_replace("Oсtober", "октября", $month);
                break;
            case "November":
                $newMonth = str_replace("November", "ноября", $month);
                break;
            case "December":
                $newMonth = str_replace("December", "декабря", $month);
                break;
        }

        $date = $day." ".$newMonth." ".$year." г.";

        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B8', "От ".$position);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B9', $fullName);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A16', $text);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A22', $date);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C22', "___________/".$sign."/");

        $objExcelWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $excelFilename = "forms/vacation_form.xlsx";
        $objExcelWriter->save($excelFilename);

    }

    // функция формирует форму для отпуска за свой счет
    function getSelfpayedVacationForm($position, $fullName, $startDate, $days, $day, $month, $year, $sign) {

        require('Classes/PHPExcel.php');
        $objPHPExcel = PHPExcel_IOFactory::load("forms/selfpayed_vacation_form.xlsx");

        $text = "Прошу предоставить отпуск за свой счет c {$startDate}г., в количестве ".$days." к. д.";

        switch ($month) {
            case "January":
                $newMonth = str_replace("January", "января", $month);
                break;
            case "February":
                $newMonth = str_replace("February", "февраля", $month);
                break;
            case "March":
                $newMonth = str_replace("March", "марта", $month);
                break;
            case "April":
                $newMonth = str_replace("April", "апреля", $month);
                break;
            case "May":
                $newMonth = str_replace("May", "мая", $month);
                break;
            case "June":
                $newMonth = str_replace("June", "июня", $month);
                break;
            case "July":
                $newMonth = str_replace("July", "июля", $month);
                break;
            case "August":
                $newMonth = str_replace("August", "августа", $month);
                break;
            case "September":
                $newMonth = str_replace("September", "сентября", $month);
                break;
            case "Oсtober":
                $newMonth = str_replace("Oсtober", "октября", $month);
                break;
            case "November":
                $newMonth = str_replace("November", "ноября", $month);
                break;
            case "December":
                $newMonth = str_replace("December", "декабря", $month);
                break;
        }

        $date = $day." ".$newMonth." ".$year." г.";

        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B8', "От ".$position);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B9', $fullName);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A16', $text);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A22', $date);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C22', "___________/".$sign."/");

        $objExcelWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $excelFilename = "forms/selfpayed_vacation_form.xlsx";
        $objExcelWriter->save($excelFilename);

    }

    // функция формирует форму для переноса отпуска
    function getGreenhouseRegularVacationForm($position, $fullName, $day, $month, $year, $sign) {

        $newMonth = "";

        require('Classes/PHPExcel.php');
        $objPHPExcel = PHPExcel_IOFactory::load("forms/gnhsRegularDynamicVacationForm.xlsx");

        switch ($month) {
            case "January":
                $newMonth = str_replace("January", "января", $month);
                break;
            case "February":
                $newMonth = str_replace("February", "февраля", $month);
                break;
            case "March":
                $newMonth = str_replace("March", "марта", $month);
                break;
            case "April":
                $newMonth = str_replace("April", "апреля", $month);
                break;
            case "May":
                $newMonth = str_replace("May", "мая", $month);
                break;
            case "June":
                $newMonth = str_replace("June", "июня", $month);
                break;
            case "July":
                $newMonth = str_replace("July", "июля", $month);
                break;
            case "August":
                $newMonth = str_replace("August", "августа", $month);
                break;
            case "September":
                $newMonth = str_replace("September", "сентября", $month);
                break;
            case "Oсtober":
                $newMonth = str_replace("Oсtober", "октября", $month);
                break;
            case "November":
                $newMonth = str_replace("November", "ноября", $month);
                break;
            case "December":
                $newMonth = str_replace("December", "декабря", $month);
                break;
        }

        $date = $day." ".$newMonth." ".$year." г.";

        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E7', $position);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D13', $fullName);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E34', $date);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C34', $sign);

        $objExcelWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $excelFilename = "forms/gnhsRegularDynamicVacationForm.xlsx";
        $objExcelWriter->save($excelFilename);
    }

    // функция формирует форму для переноса отпуска
    function getPostponeVacationForm($tg_chat_id, $formInfo, $sign) {

        $newMonth = "";
        $seo = "";
        $companyName = "";
        $date = new dateTime();
        $day = $date->format("d");
        $newDay = mb_strlen($day) == 1 ? '0'.$day : $day;
        $month = $date->format("F");
        $year = $date->format("Y");
        $sendInfo = Array();
        $position = strstr($formInfo['position'], '/', true) == false ? $formInfo['position'] : strstr($formInfo['position'], '/', true);
        $fullName = $formInfo['formFullName'];
        $companyId = $formInfo['companyId'];
        $seoInitials = $formInfo['boss'];
        $bossPosition = $formInfo['bossPosition'];

        switch ($month) {
            case "January":
                $newMonth = str_replace("January", "января", $month);
                break;
            case "February":
                $newMonth = str_replace("February", "февраля", $month);
                break;
            case "March":
                $newMonth = str_replace("March", "марта", $month);
                break;
            case "April":
                $newMonth = str_replace("April", "апреля", $month);
                break;
            case "May":
                $newMonth = str_replace("May", "мая", $month);
                break;
            case "June":
                $newMonth = str_replace("June", "июня", $month);
                break;
            case "July":
                $newMonth = str_replace("July", "июля", $month);
                break;
            case "August":
                $newMonth = str_replace("August", "августа", $month);
                break;
            case "September":
                $newMonth = str_replace("September", "сентября", $month);
                break;
            case "Oсtober":
                $newMonth = str_replace("Oсtober", "октября", $month);
                break;
            case "November":
                $newMonth = str_replace("November", "ноября", $month);
                break;
            case "December":
                $newMonth = str_replace("December", "декабря", $month);
                break;
        }

        $date = $newDay." ".$newMonth." ".$year." г.";

        switch ($companyId) {
            case 2:
                $seo = "Генеральному директору ООО \"Гринхаус\" Шилову Г.Ю.";
                $companyName = "ООО \"Гринхаус\"";
                break;
            case 3:
                $seo = "Генеральному директору ООО \"ДИАЛЛ АЛЬЯНС\" Александрову В.В.";
                $companyName = "ООО \"ДИАЛЛ АЛЬЯНС\"";
                break;
        }

        require('Classes/PHPExcel.php');
        $objPHPExcel = PHPExcel_IOFactory::load("forms/postponedDynamicVacationForm.xlsx");

        foreach ($formInfo['vacations'] as $key=>$info) {
            $id = $info['id'];
            $text = "Прошу перенести ежегодный основной оплачиваемый отпуск, запланированный по графику отпусков в период с ".$formInfo['startDate']."г. по ".$formInfo['endDate']."г. на период с ".$info['startDate']."г. по ".$info['endDate']."г. по причине: ".$info['reason'].".";

            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C5', $seo);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D7', $position);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C11', $companyName);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C14', $fullName);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A20', $text);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D23', $date);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B23', $sign);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A36', $bossPosition);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D36', $seoInitials);

            $objExcelWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            $excelFilename = "forms/postponedDynamicVacationForm_$tg_chat_id"."_"."$id.xlsx";
            array_push($sendInfo, $excelFilename);
            $objExcelWriter->save($excelFilename);

        }
        return $sendInfo;

    }

    // функция формирует форму для расчетного листка
    function getPayslip($fullname) {

        require('Classes/PHPExcel.php');
        $objPHPExcel = PHPExcel_IOFactory::load("forms/payslip.xlsx");

        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A2', "Сотрудник:".$fullname);
        $objExcelWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $excelFilename = "forms/payslip.xlsx";
        $objExcelWriter->save($excelFilename);
    }

    function getNewRegularVacationForm($position, $fullname, $vacationType, $startDate, $vacationDuration, $vacationReason, $day, $month, $year, $sign, $companyId) {

        $newMonth = "";
        $path = "";
        $text = "";
        $seo = "";
        $seoInitials = "";
        $companyName = "";

        switch ($vacationType) {
            case 0:
                $path = "forms/regularDynamicVacationForm_main.xlsx";
                $text = "Предоставить ежегодный оплачиваемый отпуск с {$startDate}г. в количестве ".$vacationDuration." календарных дней.";
                break;
            case 1:
                $path = "forms/regularDynamicVacationForm_additional.xlsx";
                $text = "Предоставить ежегодный дополнительный оплачиваемый отпуск с {$startDate}г. в количестве ".$vacationDuration." календарных дней.";
                break;
            case 2:
                $path = "forms/regularDynamicVacationForm_nopayment.xlsx";
                $text = "Предоставить отпуск без сохранения заработной платы с {$startDate}г. в количестве ".$vacationDuration." календарных дней.";
                break;
            case 3:
                $path = "forms/regularDynamicVacationForm_academic.xlsx";
                $text = "Предоставить учебный отпуск с {$startDate}г. в количестве ".$vacationDuration." календарных дней.";
                break;
        }

        switch ($month) {
            case "January":
                $newMonth = str_replace("January", "января", $month);
                break;
            case "February":
                $newMonth = str_replace("February", "февраля", $month);
                break;
            case "March":
                $newMonth = str_replace("March", "марта", $month);
                break;
            case "April":
                $newMonth = str_replace("April", "апреля", $month);
                break;
            case "May":
                $newMonth = str_replace("May", "мая", $month);
                break;
            case "June":
                $newMonth = str_replace("June", "июня", $month);
                break;
            case "July":
                $newMonth = str_replace("July", "июля", $month);
                break;
            case "August":
                $newMonth = str_replace("August", "августа", $month);
                break;
            case "September":
                $newMonth = str_replace("September", "сентября", $month);
                break;
            case "Oсtober":
                $newMonth = str_replace("Oсtober", "октября", $month);
                break;
            case "November":
                $newMonth = str_replace("November", "ноября", $month);
                break;
            case "December":
                $newMonth = str_replace("December", "декабря", $month);
                break;
        }

        switch ($companyId) {
            case 2:
                $seo = "Генеральному директору ООО \"Гринхаус\" Шилову Г.Ю.";
                $seoInitials = "Г.Ю. Шилов";
                $companyName = "ООО \"Гринхаус\"";
                break;
            case 3:
                $seo = "Генеральному директору ООО \"ДИАЛЛ АЛЬЯНС\" Александрову В.В.";
                $seoInitials = "В.В. Александров";
                $companyName = "ООО \"ДИАЛЛ АЛЬЯНС\"";
                break;
        }

        $date = $day." ".$newMonth." ".$year." г.";

        require('Classes/PHPExcel.php');
        $objPHPExcel = PHPExcel_IOFactory::load($path);

        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D5', $seo);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E7', strstr($position, '/', true));
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D11', $companyName);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D14', $fullname);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B24', $text);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E29', $date);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C29', $sign);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E35', $seoInitials);

        if ($vacationReason != null) {
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C26', $vacationReason);
        }

        $objExcelWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objExcelWriter->save($path);
    }

}



?>