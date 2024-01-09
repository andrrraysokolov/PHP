<?php

require 'www/100kwatt.ru/myscripts/vendor/autoload.php';
require "config.php";
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
    
    // Инициализация сеанса cURL
    $ch = curl_init();
    // Установка URL
    curl_setopt($ch, CURLOPT_URL, "https://gortorgsnab.ru/product/el_gidravlicheskaya_telezhka_cbd20r_ii_2000_kg_24v_400ach_eps_smartlift/");
    // Установка CURLOPT_RETURNTRANSFER (вернуть ответ в виде строки)
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    // Выполнение запроса cURL
	//$output содержит полученную строку
    $output = curl_exec($ch);
    // закрытие сеанса curl для освобождения системных ресурсов

    curl_close($ch);


###$pattern = "/online-demonstration-banner__button(.*)Производитель оставляет/";
###preg_match($pattern , $output, $matches);
###print $pattern."\n";
###print ($matches[1]);
$descrt = strstr($output, 'data-price', false);
$descr = strstr($descrt, 'data-price-old', true);
print $descr;
#$descr = $matches[1];
$spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
$spreadsheet->setActiveSheetIndex(0);
$activeSheet = $spreadsheet->getActiveSheet();
$activeSheet->setCellValue('A1', $descr);
$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, "Xlsx");
$writer->save('gtrsparst.xlsx');
$file = 'file.txt';
file_put_contents($file, $output);
?>