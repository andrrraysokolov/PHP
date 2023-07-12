<?php
  
    // Initialize a file URL to the variable
    $url = 
    'https://ptk-svarka.ru/personal/export/prices.xlsx';
      
    // Use basename() function to return the base name of file
    $date = date('d_m_Y H:i', time());
    $file_name = 'ptk_'.basename($url);
      
    // Use file_get_contents() function to get the file
    // from url and use file_put_contents() function to
    // save the file by using base name
    if (file_put_contents($file_name, file_get_contents($url)))
    {
        echo "File downloaded successfully";
        print "<br>".$date;
    }
    else
    {
        echo "File downloading failed.";
    }

require '/var/www/u2136285/data/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
$reader = PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xlsx");
    $spreadsheet = $reader->load('ptk_prices.xlsx');
 
    $sheet = $spreadsheet->getActiveSheet();
    $last_row = (int) $sheet->getHighestRow();
    $i = 5;
    $sheet->setCellValue('E2', 'Общее наличие МСК+СПБ');
    
    while ($i <= $last_row) {
        $sheet->setCellValue('E'.$i, '=F'.$i.'+H'.$i++);
    }
 
    $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, "Xlsx");
    $writer->save('ptk_prices.xlsx');
?>