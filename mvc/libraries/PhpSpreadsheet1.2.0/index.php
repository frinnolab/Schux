<?php

require __DIR__ . '/vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

// add code that show the issue here...
$spreadsheet = new Spreadsheet();



$sheet = $spreadsheet->getActiveSheet();

// $sheet->setCellValue('A1', 'Hello World !');

// $sheet->setCellValue('B1', 'PhpSpreadsheet');

$datas = array(
	array('Home1','About1','Register1','Gallery1','Contact1'),
	array('Home2','About2','Register2','Gallery2','Contact2')
);

// $col = 'A';

// foreach($datas as $key => $data) {
// 	$sheet->setCellValue($col.'1',$data);
// 	$col++;
// }

// $arrayData = [
//     [NULL, 2010, 2011, 2012],
//     ['Q1',   12,   15,   21],
//     ['Q2',   56,   73,   86],
//     ['Q3',   52,   61,   69],
//     ['Q4',   30,   32,    0],
// ];

// print_r($arrayData);

// EXIT();


$sheet->fromArray($datas,NULL,'C3');


$sheet->getDefaultColumnDimension()->setWidth(10);
$sheet->getDefaultRowDimension()->setRowHeight(20);


// Redirect output to a client’s web browser (Xlsx)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="test.xlsx"');
header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
header('Cache-Control: max-age=1');

// If you're serving to IE over SSL, then the following may be needed
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
header('Pragma: public'); // HTTP/1.0

$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
$writer->save('php://output');