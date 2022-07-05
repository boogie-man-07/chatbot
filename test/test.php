<?Php


require '../Library/NCL.NameCase.ru.php';
require '../Classes/PHPExcel.php';


$objPHPExcel = PHPExcel_IOFactory::load('test.xlsx');
$objPHPExcel->getActiveSheetIndex(0)->setCellValue('D5', $seo);

$column = 'D';
$lastRow = $worksheet->getHighestRow();
for ($row = 1; $row <= $lastRow; $row++) {
    $cell = $worksheet->getCell($column.$row);
    print_r($nc->q($cell->getValue())+"</br>");
}

//$nc = new NCLNameCaseRu();
//print_r($nc->q("Адыгезалов Мурад Арифович"));

?>