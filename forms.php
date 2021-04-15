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
            $text = "Прошу предоставить ежегодный оплачиваемый отпуск c ".$startDate.", в количестве ".$days." к. д., с единовременной выплатой 90% от оклада.";
        }  else {
            $text = "Прошу предоставить ежегодный оплачиваемый отпуск c ".$startDate.", в количестве ".$days." к. д.";
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

        $text = "Прошу предоставить отпуск за свой счет c ".$startDate.", в количестве ".$days." к. д.";

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
    function getPostponeVacationForm($position, $fullName, $startDate, $postponedDate, $postponedDays, $totalDuration, $bonus, $day, $month, $year, $sign) {

        require('Classes/PHPExcel.php');
        $objPHPExcel = PHPExcel_IOFactory::load("forms/postponed_vacation_form.xlsx");

        if ($bonus) {
            if ($totalDuration == $postponedDays) {
                $text = "Прошу перенести ежегодный оплачиваемый отпуск в количестве $totalDuration к. д. с $startDate на $postponedDate и предоставить его с единовременной выплатой 90% от оклада.";
            } else {
                $text = "Прошу перенести ежегодный оплачиваемый отпуск в количестве $totalDuration к. д. с $startDate на $postponedDate и предоставить отпуск в количестве $postponedDays к.д. с единовременной выплатой 90% от оклада.";
            }

        } else {
            if ($totalDuration == $postponedDays) {
                $text = "Прошу перенести ежегодный оплачиваемый отпуск в количестве $totalDuration календарных дней с $startDate на $postponedDate и предоставить его.";
            } else {
                $text = "Прошу перенести ежегодный оплачиваемый отпуск в количестве $totalDuration календарных дней с $startDate на $postponedDate и предоставить отпуск в количестве $postponedDays к.д.";
            }
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
        $excelFilename = "forms/postponed_vacation_form.xlsx";
        $objExcelWriter->save($excelFilename);
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


}



?>