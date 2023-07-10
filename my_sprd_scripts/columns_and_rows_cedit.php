<?php

require '/var/www/u2136285/data/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
$reader = PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xlsx");
    $spreadsheet = $reader->load('ptk-pricelist-10-07-2023.xlsx');
 
    $sheet = $spreadsheet->getActiveSheet();
    $last_row = (int) $sheet->getHighestRow();
    $gethigh_column = $sheet->getHighestColumn();
    $last_column = $gethigh_column;
    ##++$last_column;
    ##$new_row = $last_row+1;
    $new_column = ++$gethigh_column;
    
    $sheet->setCellValue($new_column.'5', $last_column);
    ##$sheet->setCellValue('B'.$new_row, "Alina");
    ##$sheet->setCellValue('C'.$new_row, "PG");
    ##$sheet->setCellValue('D'.$new_row, "$32");
    ##$sheet->setCellValue('E'.$new_row, "Pending");
 
    $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, "Xlsx");
    $writer->save('testptk_sum.xlsx');
?>