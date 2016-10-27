<?php
/** PHPExcel_IOFactory */
require_once 'vendor/autoload.php';
use PHPExcel\IOFactory;
$filename = 'Report-' . $argv[1] . '.xlsx';
// set headers to redirect output to client browser as a file download
header('Content-Type: application/vnd.openXMLformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="myfile.xlsx"');
header('Cache-Control: max-age=0');

//-----Create a reader, set some parameters and read in the file-----
$objReader = PHPExcel_IOFactory::createReader('CSV');
$objReader->setDelimiter(' ');
$objReader->setEnclosure('');
$objReader->setLineEnding("\r\n");
$objReader->setSheetIndex(0);
$objPHPExcel = $objReader->load(__DIR__ . "/new-" . $argv[1] . ".csv");

$objPHPExcel->getActiveSheet()->insertNewRowBefore(1, 6);
$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(60);
//-----Create a Writer and output the file to the browser-----
$objWriter2007 = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objPHPExcel->getActiveSheet()->getProtection()->setSort(true);

$allDataInSheet = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
$sortArray = array();

foreach($allDataInSheet as $person){
    foreach($person as $key=>$value){
        if(!isset($sortArray[$key])){
            $sortArray[$key] = array();
        }
        $sortArray[$key][] = $value;
    }
}

$orderby = "A"; //change this to whatever key you want from the array

array_multisort($sortArray[$orderby],SORT_ASC,$allDataInSheet);




$objPHPExcel->getActiveSheet()->fromArray(
    $allDataInSheet,
    NULL,
    'A2'
);


//create new cell
for($i=7;$i<=count($allDataInSheet);$i++){
	$objPHPExcel->getActiveSheet()->SetCellValue('C'.$i, 5);
}

//multiply two cells
for($j=7;$j<=37;$j++){
	$colD = ($objPHPExcel->getActiveSheet()->getCell('B'.$j)->getValue())*($objPHPExcel->getActiveSheet()->getCell('C'.$j)->getValue());
	$objPHPExcel->getActiveSheet()->SetCellValue('D'.$j, $colD);

}

//addition of cells
$colDSum=0;
for($k=7;$k<=37;$k++){
	$colDSum = ($objPHPExcel->getActiveSheet()->getCell('D'.$k)->getValue()) + $colDSum;
}
$objPHPExcel->getActiveSheet()->setCellValue('B6', 'Instance');
$objPHPExcel->getActiveSheet()->setCellValue('C6', 'Multiplier');
$objPHPExcel->getActiveSheet()->setCellValue('D6', 'Score');
$objPHPExcel->getActiveSheet()->setCellValue('A5', 'Problems Score (0 is perfect, less is better)');
$objPHPExcel->getActiveSheet()->SetCellValue('D5', $colDSum);
$objPHPExcel->getActiveSheet()->setCellValue('A4', 'Grade - 10 (Perfect) to 0 (Worse) (Score out of 10)');

if($colDSum == 0){
	$objPHPExcel->getActiveSheet()->SetCellValue('D4', '10');
}elseif($colDSum <=10){
	$objPHPExcel->getActiveSheet()->SetCellValue('D4', '9');	
}elseif($colDSum <=50){
	$objPHPExcel->getActiveSheet()->SetCellValue('D4', '8');
}elseif($colDSum <=100){
	$objPHPExcel->getActiveSheet()->SetCellValue('D4', '7');
}elseif($colDSum <=250){
	$objPHPExcel->getActiveSheet()->SetCellValue('D4', '6');
}elseif($colDSum <=500){
	$objPHPExcel->getActiveSheet()->SetCellValue('D4', '5');
}elseif($colDSum <=1000){
	$objPHPExcel->getActiveSheet()->SetCellValue('D4', '4');
}elseif($colDSum <=1500){
	$objPHPExcel->getActiveSheet()->SetCellValue('D4', '3');
}elseif($colDSum <=2000){
	$objPHPExcel->getActiveSheet()->SetCellValue('D4', '2');
}elseif($colDSum <=2500){
	$objPHPExcel->getActiveSheet()->SetCellValue('D4', '1');
}else{
	$objPHPExcel->getActiveSheet()->SetCellValue('D4', '0');
} 


//get mess detector count
$filepath = __DIR__ . "/" . $argv[1] . "/reports/phpmd/phpmd.txt";
$handle = fopen($filepath, "r");
$lineNo = 0;
if ($handle) {
    while (($line = fgets($handle)) !== false) {
	$lineNo++;
    }
fclose($handle);
} else {
    // error opening the file.
} 
$objPHPExcel->getActiveSheet()->setCellValue('A55', 'PHP Mess detector Report');
$objPHPExcel->getActiveSheet()->setCellValue('B55', $lineNo);


//get copypaste detector count
$filepath = __DIR__ . "/" . $argv[1] . "/reports/copypaste/phpcpd.txt";
$handle = fopen($filepath, "r");
$lineNo = 0;
if ($handle) {
    while (($line = fgets($handle)) !== false) {
	$lineNo++;
    }
fclose($handle);
} else {
    // error opening the file.
} 

$objWriter2007->save("$filename");  //push out to the client browser

?>
