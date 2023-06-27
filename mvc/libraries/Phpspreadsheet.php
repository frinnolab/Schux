<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require 'PhpSpreadsheet1.2.0/vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Worksheet\HeaderFooterDrawing;

class Phpspreadsheet {

	public $spreadsheet;
	public $iofactory;
	public $drawing;

	public function  __construct() {
		$this->spreadsheet = new Spreadsheet;
		$this->drawing = new Drawing;
	}

	public function output($data) {
		$writer = IOFactory::createWriter($data, 'Xlsx');
		$writer->save('php://output');
	}

	public function draw_images($path, $coordinates, $sheet, $height = 50)
	{
		$drawing = new Drawing;
	    $drawing->setPath($path);
	    $drawing->setHeight($height);
	    $drawing->setCoordinates($coordinates);
	    $drawing->setOffsetY(8);
	    $drawing->setOffsetX(20);
		$drawing->getShadow()->setVisible(true);
	    $drawing->setWorksheet($sheet);
	}

	public function print_header($path,$sheet) {
		$drawing = new HeaderFooterDrawing();
		$drawing->setName('Header logo');
		$drawing->setPath(FCPATH.'uploads/images/'.$path);
		$drawing->setHeight(36);
		$sheet->getHeaderFooter()->addImage($drawing, \PhpOffice\PhpSpreadsheet\Worksheet\HeaderFooter::IMAGE_HEADER_CENTER);
	}


}