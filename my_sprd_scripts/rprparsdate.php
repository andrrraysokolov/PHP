<?php
require 'www/100kwatt.ru/myscripts/vendor/autoload.php';
require "config.php";
 
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

### переводим первую sql таблицу в эксельку

$spreadsheet2 = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
 
$spreadsheet2->setActiveSheetIndex(0);
$activeSheet2 = $spreadsheet2->getActiveSheet();
 
$activeSheet2->setCellValue('A1', 'P ID');
$activeSheet2->setCellValue('B1', 'Product ID');
$activeSheet2->setCellValue('C1', 'Auto pricing');
 
$query = $db->query("SELECT * FROM cscart_ab__rpr_products");
 
if($query->num_rows > 0) {
    $i = 2;
    while($row = $query->fetch_assoc()) {
        $activeSheet2->setCellValue('A'.$i , $row['p_id']);
        $activeSheet2->setCellValue('B'.$i , $row['product_id']);
        $activeSheet2->setCellValue('C'.$i , $row['allow_pricing_automatically']);
        $i++;
    }
} 

$writer2 = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet2, "Xlsx");
$writer2->save('rpr_products.xlsx');

### переводим вторую sql таблицу в эксельку

$spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
 
$spreadsheet->setActiveSheetIndex(0);
$activeSheet = $spreadsheet->getActiveSheet();
 
$activeSheet->setCellValue('A1', 'P ID');
$activeSheet->setCellValue('B1', 'Price');
$activeSheet->setCellValue('D1', 'HTTP Status');
$activeSheet->setCellValue('E1', 'Auto pricing');
 
$query = $db->query("SELECT * FROM cscart_ab__rpr_product_competitors");
 
if($query->num_rows > 0) {
    $i = 2;
    while($row = $query->fetch_assoc()) {
        $activeSheet->setCellValue('A'.$i , $row['p_id']);
        $activeSheet->setCellValue('B'.$i , $row['last_parsing_attributes']);
        $activeSheet->setCellValue('D'.$i , $row['last_http_status']);
        $activeSheet->setCellValue('F'.$i , date('d.m.y', $row['last_parsing_timestamp']));
        $i++;
    }
} 

$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, "Xlsx");
$writer->save('rpr_product_competitors.xlsx');

#соединяем две таблицы в третью

$spreadsheet->getActiveSheet()->setCellValue('C1', 'Product ID');
$stn2 = 2;
$prd = 'product';
$sheetcsv = $spreadsheet->getActiveSheet();
$sheetcsv2 = $spreadsheet2->getActiveSheet();
$last_rowcsv = (int) $sheetcsv->getHighestRow();
$last_rowcsv2 = (int) $sheetcsv2->getHighestRow();
$autoprice = 'Y';

for ($stn = 2; $stn < ($last_rowcsv + 1); $stn++) {
    $stn2 = 2;
    while ($stn2 < ($last_rowcsv2 + 1)) {
        if (($spreadsheet->getActiveSheet()->getCell('A'.$stn)->getValue()) == ($spreadsheet2->getActiveSheet()->getCell('A'.$stn2)->getValue())) {
        $prd = $spreadsheet2->getActiveSheet()->getCell('B'.$stn2)->getValue();
        $autoprice = $spreadsheet2->getActiveSheet()->getCell('C'.$stn2)->getValue();
        $spreadsheet->getActiveSheet()->setCellValue('C'.$stn, $prd);
        $spreadsheet->getActiveSheet()->setCellValue('E'.$stn, $autoprice);
        $stn2 = ($last_rowcsv2 + 1);
    }
        else {
        ++$stn2;
    }
}

}

$randcell = rand(2, $last_rowcsv);
$rpres = $spreadsheet->getActiveSheet()->getCell('B'.$randcell)->getValue();
$pid = $spreadsheet->getActiveSheet()->getCell('A'.$randcell)->getValue();

$pattern1 = "/DP\";a:6:{s:5:\"value(.*)status/"; 
preg_match($pattern1 , $rpres, $matches1);
$tq1 = str_replace("\";d:", "", $matches1[1]);
$tq2 = str_replace("\";s:3:\"", "", $tq1);
$tq3 = str_replace("\";s:1:\"", "", $tq2);
$tq4 = str_replace("\";s:2:\"", "", $tq3);
$tq5 = str_replace("\";s:4:\"", "", $tq4);
$tq6 = str_replace("\";s:5:\"", "", $tq5);
$tq7 = str_replace("\";s:7:\"", "", $tq6);
$tq8 = str_replace("\";s:6:\"", "", $tq7);
$quantity = intval($tq8);
$prdid = $spreadsheet->getActiveSheet()->getCell('C'.$randcell)->getValue();
print "Например, количество у p_id ".$pid." равно ".$quantity.". Product ID - ".$prdid;


for ($stn = 2; $stn < ($last_rowcsv + 1); $stn++) {
    $rpres = $spreadsheet->getActiveSheet()->getCell('B'.$stn)->getValue();
    $pattern1 = "/DP\";a:6:{s:5:\"value(.*)status/"; 
    preg_match($pattern1 , $rpres, $matches1);
    $tq1 = str_replace("\";d:", "", $matches1[1]);
    $tq2 = str_replace("\";s:3:\"", "", $tq1);
    $tq3 = str_replace("\";s:1:\"", "", $tq2);
    $tq4 = str_replace("\";s:2:\"", "", $tq3);
    $tq5 = str_replace("\";s:4:\"", "", $tq4);
    $tq6 = str_replace("\";s:5:\"", "", $tq5);
    $tq7 = str_replace("\";s:7:\"", "", $tq6);
    $tq8 = str_replace("\";s:6:\"", "", $tq7);
    $quantity = intval($tq8);
    $spreadsheet->getActiveSheet()->setCellValue('B'.$stn, $quantity);
    #print "Количество ".$quantity." у Product ID ".$spreadsheet->getActiveSheet()->getCell('C'.$stn)->getValue().".\n";
}

for ($stn = 2; $stn < ($last_rowcsv + 1); $stn++) {
    if (($spreadsheet->getActiveSheet()->getCell('D'.$stn)->getValue()) != "200" and ($spreadsheet->getActiveSheet()->getCell('D'.$stn)->getValue()) != "404") {
        $spreadsheet->getActiveSheet()->setCellValue('B'.$stn, 'Неверный код ответа сайта поставщика. Product ID '.$spreadsheet->getActiveSheet()->getCell('C'.$stn)->getValue());
        $spreadsheet->getActiveSheet()->setCellValue('C'.$stn, 'ERR SERVER');
    }
    elseif (($spreadsheet->getActiveSheet()->getCell('E'.$stn)->getValue()) != "Y") {
        $spreadsheet->getActiveSheet()->setCellValue('B'.$stn, 'Не включен перерасчёт. Product ID '.$spreadsheet->getActiveSheet()->getCell('C'.$stn)->getValue());
        $spreadsheet->getActiveSheet()->setCellValue('C'.$stn, 'ERR COUNT');
    }
}

for ($stn = 2; $stn < ($last_rowcsv + 1); $stn++) {
        $tempprice = intval($spreadsheet->getActiveSheet()->getCell('B'.$stn)->getValue());
        $tempid = intval($spreadsheet->getActiveSheet()->getCell('C'.$stn)->getValue());
        $setprice = $db->query("UPDATE `100kwatt_shop`.`cscart_product_prices` SET `price` = '$tempprice' WHERE `cscart_product_prices`.`product_id` = '$tempid';");
    }

for ($stn = 2; $stn < ($last_rowcsv + 1); $stn++) {
        if (($spreadsheet->getActiveSheet()->getCell('D'.$stn)->getValue()) == "404") {
            $spreadsheet->getActiveSheet()->setCellValue('C'.$stn, '-');}
    }

$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, "Xlsx");
$writer->save('www/100kwatt.ru/myscripts/rpr_parsdate.xlsx');
?>