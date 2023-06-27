<?php (! defined('BASEPATH')) and exit('No direct script access allowed');
include "phpqrcode/qrlib.php";

class Qrcodegenerator {
	public function generate_qrcode($text, $filename, $folder) {
		$PNG_TEMP_DIR = FCPATH.'uploads/'.$folder.'/';

		if (!file_exists($PNG_TEMP_DIR)) {
        	mkdir($PNG_TEMP_DIR);
        }

	    $filename = $PNG_TEMP_DIR.$filename.'.png';
		QRcode::png($text, $filename,"H",4);
	}
}