<?php
  
    // Initialize a file URL to the variable
    $url = 
    'https://svarog-rf.ru/export/products/pricelist.csv';
      
    // Use basename() function to return the base name of file
    $date = date('d_m_Y H:i', time());
    $file_name = 'svarog_csv.csv';
      
    // Use file_get_contents() function to get the file
    // from url and use file_put_contents() function to
    // save the file by using base name
    if (file_put_contents($file_name, file_get_contents($url)))
    {
        echo "Прайс-лист СВАРОГ успешно скачан.";
        print "<br>".$date;
    }
    else
    {
        echo "File downloading failed.";
    }

require '/var/www/u2136285/data/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

#converting csv to xlsx
$spreadsheetcsv = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
$readercsv = new \PhpOffice\PhpSpreadsheet\Reader\Csv();

$readercsv->setDelimiter(',');
$readercsv->setEnclosure('"');
$readercsv->setInputEncoding('UTF-8');

$spreadsheetcsv = $readercsv->load('svarog_csv.csv');

#ширина столбцов
for ($tz = 'A'; $tz <=  $spreadsheetcsv->getActiveSheet()->getHighestColumn(); $tz++) {
    $spreadsheetcsv->getActiveSheet()->getColumnDimension($tz)->setWidth(40);
}

#записываем конечный файл (из csv получается xlsx)
$svarcsvwriter = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheetcsv, "Xlsx");
$svarcsvwriter->save('svarog_csv_to_xlsx.xlsx');

?>