<?php

require 'www/100kwatt.ru/myscripts/vendor/autoload.php';
require "config.php";
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

### Читаем таблицу с ссылками на товары на сайте поставщика. Оцениваем количество строк и записываем это количество в переменную.

$spreadsheetnord = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
$readernord = PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xlsx");
$spreadsheetnord = $readernord->load('NORDBERG_urls_parser.xlsx');
$last_row = (int) $spreadsheetnord->getActiveSheet()->getHighestRow();

### ЦИКЛ

$stn = 2;
while ($stn < ($last_row + 1)) {

### Записываем в переменные Product ID и URL
$url = $spreadsheetnord->getActiveSheet()->getCell('D'.$stn)->getValue();
$productid = $spreadsheetnord->getActiveSheet()->getCell('B'.$stn)->getValue();
$productname = $spreadsheetnord->getActiveSheet()->getCell('C'.$stn)->getValue();

### Записываем в переменную output HTML код страницы на сайте поставщика

    // Инициализация сеанса cURL
    $ch = curl_init();
    // Установка URL
    curl_setopt($ch, CURLOPT_URL, $url);
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

###ПАРСИМ ЦЕНУ
$descrt = strstr($output, 'add2delay', false);
$descr = strstr($descrt, 'klik_dobavili_v_izbrannoe', true);
$descr3 = strstr($descr, '\',\'1\',\'', false);
$descr4 = str_replace('\',\'1\',\'', '', $descr3);
$pricer = intval($descr4);

###ПАРСИМ НАЛИЧИЕ
$nstep1 = strstr($output, 'Stock', true);
$nstep2 = strstr($nstep1, 'itemprop="availability" href', false);
$nstep3 = str_replace('itemprop="availability" href="http://schema.org/', '', $nstep2);
$nstep4 = str_replace('In', '1000', $nstep3);
$kolvo = str_replace('OutOf', '1', $nstep4);

print "Цена товара \'$productname\' (ID $productid) - $pricer RUB, наличие - $kolvo.\n";
#$descr = $matches[1];
#$spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
#$spreadsheet->setActiveSheetIndex(0);
$date = date("d-m-Y H:i:s");
$spreadsheetnord->getActiveSheet()->setCellValue('E'.$stn, $pricer);
$spreadsheetnord->getActiveSheet()->setCellValue('F'.$stn, $kolvo);

$spreadsheetnord->getActiveSheet()->setCellValue('G'.$stn, $date);

$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheetnord, "Xlsx");
$writer->save('www/100kwatt.ru/myscripts/nordberg_prices_pars_temp.xlsx');
$file = 'file.txt';
file_put_contents($file, $output);
$stn = ($stn + 1);
sleep(30);
}
$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheetnord, "Xlsx");
$writer->save('www/100kwatt.ru/myscripts/nordberg_prices_pars_final.xlsx');
?>