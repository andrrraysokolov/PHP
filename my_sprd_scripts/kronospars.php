<?php
 
 // функция для устранения проблем с ssl и file_get_contents на сервере 100kwatt
 function file_get_contents_curl( $url ) {

     $ch = curl_init();
   
     curl_setopt( $ch, CURLOPT_AUTOREFERER, TRUE );
     curl_setopt( $ch, CURLOPT_HEADER, 0 );
     curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
     curl_setopt( $ch, CURLOPT_URL, $url );
     curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, TRUE );
   
     $data = curl_exec( $ch );
     curl_close( $ch );
   
     return $data;
   
   }  
 // Initialize a file URL to the variable
 
 $baseurl = 'https://kronos5.ru/price/';

 ### Записываем в переменную baseoutput HTML код страницы

    // Инициализация сеанса cURL
    $ch = curl_init();
    // Установка URL
    curl_setopt($ch, CURLOPT_URL, $baseurl);
    // Установка CURLOPT_RETURNTRANSFER (вернуть ответ в виде строки)
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    // Выполнение запроса cURL
	//$baseoutput содержит полученную строку
    $baseoutput = curl_exec($ch);
    // закрытие сеанса curl для освобождения системных ресурсов

    curl_close($ch);

$basestep1 = strstr($baseoutput, 'price-pdf', false);
$basestep2 = strstr($basestep1, '.pdf', true);
$basestep3 = str_replace("price-pdf\" content=\"", "", $basestep2);
 
 $url = "https://kronos5.ru$basestep3.pdf";
 print "Актуальный прайс-лист сегодня располагается по адресу $url.\n";
   
 // Use basename() function to return the base name of file
 $date = date('d_m_Y H:i', time());
 $file_name = 'kronos2.pdf';
   
 // Use file_get_contents() function to get the file
 // from url and use file_put_contents() function to
 // save the file by using base name
 if (file_put_contents($file_name, file_get_contents_curl($url)))
 {
     echo "Прайс-лист KRONOS успешно скачан. ";
     print $date."\n";
 }
 else
 {
     echo "File downloading failed.";
 }


require 'www/100kwatt.ru/myscripts/vendor/autoload.php';
require 'www/100kwatt.ru/myscripts/pdfpars/alt_autoload.php-dist';
require "config.php";
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


$spreadsheetkronos = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
$readerkronos = PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xlsx");
$spreadsheetkronos = $readerkronos->load('KRONOS_pdf_parser_codes.xlsx');
$last_row = (int) $spreadsheetkronos->getActiveSheet()->getHighestRow();

$parser = new \Smalot\PdfParser\Parser();
$pdf = $parser->parseFile('kronos2.pdf');
$text = $pdf->getText();

$datestep1 = strstr($text, 'Мы ВКонтакте', false);
$datestep2 = strstr($datestep1, 'Прайс-лист', true);
$datestep3 = str_replace("Мы ВКонтакте", "", $datestep2);
$datestep4 = str_replace("\n", "", $datestep3);
$datekronos = "Прайс-лист от ".$datestep4;
print "$datekronos\n";
$spreadsheetkronos->getActiveSheet()->setCellValue('I2', $datekronos);

function str_replace_first($search, $replace, $subject)
{
    $search = '/'.preg_quote($search, '/').'/';
    return preg_replace($search, $replace, $subject, 1);
}

### ЦИКЛ

$stn = 2;
while ($stn < ($last_row + 1)) {

### Парсим наличие

$manart = $spreadsheetkronos->getActiveSheet()->getCell('D'.$stn)->getValue();

$step1 = strstr($text, $manart, false);
$step2 = strstr($step1, 'Москва', false);
$stores = strstr($step2, 'Краснодар', true);
print "Наличие товара по складам \"$manart\":\n".$stores;

# Суммируем наличие по складам

$mstep1 = str_replace("Меньше 5", "5", $stores);
$mstep2 = str_replace("Больше 5", "100", $mstep1);
$mstep3 = strstr($mstep2, 'Уфа', true);
$mqt = intval(str_replace("Москва", "", $mstep3));

$ustep1 = strstr($mstep2, 'Уфа', false);
$uqt = intval(str_replace("Уфа", "", $ustep1));

$ovqt = $mqt + $uqt;

print "Суммарное наличие товара \"$manart\":\n".$ovqt."\n";

### Парсим РРЦ

$step4 = strstr($step2, '00 ', false);
$step5 = str_replace_first('00 ', '', $step4);
$step6 = strstr($step5, '00 ', false);
$step7 = str_replace_first('00 ', '', $step6);
$step8 = strstr($step7, '00 ', true);
$step9 = str_replace(" ", "", $step8);
$step10 = intval($step9);
$pricek = $step10;
print "Цена товара \"$manart\":\n$pricek\n";

### Записываем значения в ячейки

$date = date("d-m-Y H:i:s");
$spreadsheetkronos->getActiveSheet()->setCellValue('E'.$stn, $pricek);
$spreadsheetkronos->getActiveSheet()->setCellValue('F'.$stn, $ovqt);
$spreadsheetkronos->getActiveSheet()->setCellValue('G'.$stn, $stores);

$spreadsheetkronos->getActiveSheet()->setCellValue('H'.$stn, $date);

$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheetkronos, "Xlsx");
$writer->save('www/100kwatt.ru/myscripts/kronos_prices_pars_temp.xlsx');
$stn = ($stn + 1);

}

# Записываем финальный файл

$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheetkronos, "Xlsx");
$writer->save('www/100kwatt.ru/myscripts/kronos_prices_pars_final.xlsx');

?>