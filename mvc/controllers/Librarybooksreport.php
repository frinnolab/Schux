<?php

class Librarybooksreport extends Admin_Controller{
	
	public function __construct() {
		parent::__construct();
		$this->load->model('book_m');
		$language = $this->session->userdata('lang');
		$this->lang->load('librarybooksreport', $language);
	}

	public function rules() {
		$rules = array(
			array(
				'field'=>'bookname',
				'label'=>$this->lang->line('librarybooksreport_bookname'),
				'rules' => 'trim|xss_clean'
			),
			array(
				'field'=>'subjectcode',
				'label'=>$this->lang->line('librarybooksreport_subjectcode'),
				'rules' => 'trim|xss_clean'
			),
			array(
				'field'=>'rackNo',
				'label'=>$this->lang->line('librarybooksreport_rackNo'),
				'rules' => 'trim|xss_clean'
			),
            array(
				'field'=>'status',
				'label'=>$this->lang->line('librarybooksreport_status'),
				'rules' => 'trim|xss_clean'
			)
		);
		return $rules;
	}

	public function send_pdf_to_mail_rules() {
		$rules = array(
			array(
				'field'=>'to',
				'label'=>$this->lang->line('librarybooksreport_to'),
				'rules' => 'trim|required|xss_clean|valid_email'
			),
			array(
				'field'=>'subject',
				'label'=>$this->lang->line('librarybooksreport_subject'),
				'rules' => 'trim|required|xss_clean'
			),
			array(
				'field'=>'message',
				'label'=>$this->lang->line('librarybooksreport_message'),
				'rules' => 'trim|xss_clean'
			),
			array(
				'field'=>'bookname',
				'label'=>$this->lang->line('librarybooksreport_bookname'),
				'rules' => 'trim|xss_clean'
			),
			array(
				'field'=>'subjectcode',
				'label'=>$this->lang->line('librarybooksreport_subjectcode'),
				'rules' => 'trim|xss_clean'
			),
			array(
				'field'=>'rackNo',
				'label'=>$this->lang->line('librarybooksreport_rackNo'),
				'rules' => 'trim|xss_clean'
			),
            array(
				'field'=>'status',
				'label'=>$this->lang->line('librarybooksreport_status'),
				'rules' => 'trim|xss_clean'
			)
		);
		return $rules;
	}

	public function index() {
		$this->data['headerassets'] = array(
			'css' => array(
				'assets/datepicker/datepicker.css',
				'assets/select2/css/select2.css',
				'assets/select2/css/select2-bootstrap.css'
			),
			'js' => array(
				'assets/datepicker/datepicker.js',
				'assets/select2/select2.js'
			)
		);
		$this->data['books'] = $this->book_m->get_order_by_book();
		$this->data["subview"] = "report/librarybooks/LibraryBooksReportView";
		$this->load->view('_layout_main', $this->data);
	}

	public function getLibrarybooksReport() {
		$retArray['status'] = FALSE;
		$retArray['render'] = '';

        if(permissionChecker('librarybooksreport')) {
			if($_POST) {
				$rules = $this->rules();
				$this->form_validation->set_rules($rules);
			    if ($this->form_validation->run() == FALSE) {
					$retArray = $this->form_validation->error_array();
					$retArray['status'] = FALSE;
				    echo json_encode($retArray);
				    exit;
				} else {
					$this->data['bookname']    = $this->input->post('bookname'); 
					$this->data['subjectcode'] = !empty($this->input->post('subjectcode')) ? $this->input->post('subjectcode') : '0'; 
					$this->data['rackNo']      = !empty($this->input->post('rackNo')) ? $this->input->post('rackNo') : '0';
					$this->data['status']      = $this->input->post('status');

					$postArray = [];
					if($this->data['bookname'] != '0') {
						$postArray['bookID'] = $this->data['bookname'];
					}

					if(!empty($this->data['subjectcode'])) {
						$postArray['subject_code'] = $this->data['subjectcode'];
					}

					if(!empty($this->data['rackNo'])) {
						$postArray['rack'] = $this->data['rackNo'];
					}

					$books = $this->book_m->get_order_by_book($postArray);
					$booksPluck = pluck($books,'book','bookID');
					$bookfullname = $this->data['bookname'];
					$this->data['bookfullname'] = isset($booksPluck[$bookfullname]) ? $booksPluck[$bookfullname] : '';

					$i = 0;
					$bookArray = [];
					$status = $this->data['status'];
					if(count($books)) {
						foreach($books as $book) {
							$i++;
							if(($status==1) && ($book->quantity == $book->due_quantity)) {
								continue;
							} elseif(($status==2) && ($book->quantity != $book->due_quantity)) {
								continue;
							}
							$bookArray[$i]['bookname'] = $book->book;
							$bookArray[$i]['author'] = $book->author;
							$bookArray[$i]['subjectcode'] = $book->subject_code;
							$bookArray[$i]['rackNo'] = $book->rack;
							if($book->quantity == $book->due_quantity) {
								$bookArray[$i]['status'] = $this->lang->line('librarybooksreport_unavailable');
	                        } else {
								$bookArray[$i]['status'] = $this->lang->line('librarybooksreport_available');
	                        }
						}
					}
					$this->data['books'] = $bookArray;
					$retArray['render'] = $this->load->view('report/librarybooks/LibraryBooksReport', $this->data,true);
					$retArray['status'] = TRUE;
					echo json_encode($retArray);
					exit;
				}
			} else {
				echo json_encode($retArray);
				exit;
			}
		} else {
			$retArray['render'] =  $this->load->view('report/reporterror', $this->data, true);
			$retArray['status'] = TRUE;
			echo json_encode($retArray);
			exit;
		}
	}

	public function pdf() {
		if(permissionChecker('librarybooksreport')) {
			$bookname    = htmlentities(escapeString($this->uri->segment(3)));
			$subjectcode = htmlentities(escapeString($this->uri->segment(4)));
			$rackNo  = htmlentities(escapeString($this->uri->segment(5)));
			$status  = htmlentities(escapeString($this->uri->segment(6)));

        	if(((int)$bookname >= 0) && (((int)$subjectcode >= 0) || (string)$subjectcode) && (((int)$rackNo >= 0) || (string)$rackNo) && ((int)$status >= 0)) {
				$this->data['bookname']    = $bookname;
				$this->data['subjectcode'] = $subjectcode;
				$this->data['rackNo'] = $rackNo;
				$this->data['status'] = $status;

				$postArray = [];
				if($this->data['bookname'] != '0') {
					$postArray['bookID'] = $this->data['bookname'];
				}

				if(!empty($this->data['subjectcode'])) {
					$postArray['subject_code'] = $this->data['subjectcode'];
				}

				if(!empty($this->data['rackNo'])) {
					$postArray['rack'] = $this->data['rackNo'];
				}

				$books = $this->book_m->get_order_by_book($postArray);
				$booksPluck = pluck($books,'book','bookID');
				$bookfullname = $this->data['bookname'];
				$this->data['bookfullname'] = isset($booksPluck[$bookfullname]) ? $booksPluck[$bookfullname] : '';

				$i = 0;
				$bookArray = [];
				if(count($books)) {
					foreach($books as $book) {
						$i++;
						if(($status==1) && ($book->quantity == $book->due_quantity)) {
							continue;
						} elseif(($status==2) && ($book->quantity != $book->due_quantity)) {
							continue;
						}
						$bookArray[$i]['bookname'] = $book->book;
						$bookArray[$i]['author'] = $book->author;
						$bookArray[$i]['subjectcode'] = $book->subject_code;
						$bookArray[$i]['rackNo'] = $book->rack;
						if($book->quantity == $book->due_quantity) {
							$bookArray[$i]['status'] = $this->lang->line('librarybooksreport_unavailable');
                        } else {
							$bookArray[$i]['status'] = $this->lang->line('librarybooksreport_available');
                        }
					}
				}
				$this->data['books'] = $bookArray;
				$this->reportPDF('librarybooksreport.css', $this->data, 'report/librarybooks/LibraryBooksReportPDF');
			} else {
				$this->data["subview"] = "error";
				$this->load->view('_layout_main', $this->data);	
			}
		} else {
			$this->data["subview"] = "error";
			$this->load->view('_layout_main', $this->data);
		}
	}

	public function xlsx() {
		if(permissionChecker('librarybooksreport')) {
			$this->load->library('phpspreadsheet');

			$sheet = $this->phpspreadsheet->spreadsheet->getActiveSheet();
			$sheet->getDefaultColumnDimension()->setWidth(25);
			$sheet->getDefaultRowDimension()->setRowHeight(25);
			$sheet->getColumnDimension('A')->setWidth(20);
			$sheet->getRowDimension('1')->setRowHeight(25);
			$sheet->getRowDimension('2')->setRowHeight(25);
			
			$data = $this->xmlData();

			// Redirect output to a client’s web browser (Xlsx)
			header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			header('Content-Disposition: attachment;filename="librarybooksreport.xlsx"');
			header('Cache-Control: max-age=0');
			// If you're serving to IE 9, then the following may be needed
			header('Cache-Control: max-age=1');

			// If you're serving to IE over SSL, then the following may be needed
			header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
			header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
			header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
			header('Pragma: public'); // HTTP/1.0

			$this->phpspreadsheet->output($this->phpspreadsheet->spreadsheet);
		} else {
			$this->data["subview"] = "errorpermission";
			$this->load->view('_layout_main', $this->data);
		}
	}

	private function xmlData() {
		if(permissionChecker('librarybooksreport')) {
            $bookname = htmlentities(escapeString($this->uri->segment(3)));
			$subjectcode = htmlentities(escapeString($this->uri->segment(4)));
			$rackNo = htmlentities(escapeString($this->uri->segment(5)));
			$status    = htmlentities(escapeString($this->uri->segment(6)));

        	if(((int)$bookname >= 0) && (((int)$subjectcode >= 0) || (string)$subjectcode) && (((int)$rackNo >= 0) || (string)$rackNo) && ((int)$status >= 0)) {
				$this->data['bookname']    = $bookname;
				$this->data['subjectcode'] = $subjectcode;
				$this->data['rackNo'] = $rackNo;
				$this->data['status'] = $status;

				$postArray = [];
				if($this->data['bookname'] != '0') {
					$postArray['bookID'] = $this->data['bookname'];
				}

				if(!empty($this->data['subjectcode'])) {
					$postArray['subject_code'] = $this->data['subjectcode'];
				}

				if(!empty($this->data['rackNo'])) {
					$postArray['rack'] = $this->data['rackNo'];
				}

				$books = $this->book_m->get_order_by_book($postArray);
				$booksPluck = pluck($books,'book','bookID');
				$bookfullname = $this->data['bookname'];
				$this->data['bookfullname'] = isset($booksPluck[$bookfullname]) ? $booksPluck[$bookfullname] : '';

				$i = 0;
				$bookArray = [];
				if(count($books)) {
					foreach($books as $book) {
						$i++;
						if(($status==1) && ($book->quantity == $book->due_quantity)) {
							continue;
						} elseif(($status==2) && ($book->quantity != $book->due_quantity)) {
							continue;
						}
						$bookArray[$i]['bookname'] = $book->book;
						$bookArray[$i]['author'] = $book->author;
						$bookArray[$i]['subjectcode'] = $book->subject_code;
						$bookArray[$i]['rackNo'] = $book->rack;
						if($book->quantity == $book->due_quantity) {
							$bookArray[$i]['status'] = $this->lang->line('librarybooksreport_unavailable');
                        } else {
							$bookArray[$i]['status'] = $this->lang->line('librarybooksreport_available');
                        }
					}
				}
				$this->data['books'] = $bookArray;
				return $this->generateXML($this->data);																
			} else {
				$this->data["subview"] = "error";
				$this->load->view('_layout_main', $this->data);	
			}
		} else {
			$this->data["subview"] = "error";
			$this->load->view('_layout_main', $this->data);
		}
	}

	private function generateXML($data) {
		extract($data);
		if(count($books)) {
			$sheet = $this->phpspreadsheet->spreadsheet->getActiveSheet();
			$maxColumnCount = 6;
			
			$headerColumn = "A";
        	for($i= 1; $i < $maxColumnCount; $i++) {
                $headerColumn++;
            }

	        $row = 1;
	        $column = 'A';

	        if($status == 1) {
                $label = $this->lang->line('librarybooksreport_status')." : ";
                $label .= $this->lang->line('librarybooksreport_available');
            	$sheet->setCellValue($column.$row,$label);
            } elseif($status == 2) {
                $label = $this->lang->line('librarybooksreport_status')." : ";
                $label .= $this->lang->line('librarybooksreport_unavailable');
            	$sheet->setCellValue($column.$row,$label);
            } elseif($bookname != '0') {
                $label = $this->lang->line('librarybooksreport_bookname')." : ".$bookfullname;
            	$sheet->setCellValue($column.$row,$label);
            } elseif ($subjectcode != '0') {
                $label = $this->lang->line('librarybooksreport_subjectcode')." : ".$subjectcode;
            	$sheet->setCellValue($column.$row,$label);
            } elseif($rackNo != '0') {
                $label = $this->lang->line('librarybooksreport_rackNo')." : ".$rackNo;
            	$sheet->setCellValue($column.$row,$label);
            } else {
            	$sheet->getRowDimension('1')->setVisible(false);
            }

	        //Make Header Data Array
	        $headers['slno'] = $this->lang->line('slno');
            $headers['bookname'] = $this->lang->line('librarybooksreport_bookname');
            $headers['author'] = $this->lang->line('librarybooksreport_author');
            $headers['subjectcode'] = $this->lang->line('librarybooksreport_subjectcode');
            $headers['rackNo'] = $this->lang->line('librarybooksreport_rackNo');
            $headers['status'] = $this->lang->line('librarybooksreport_status');

	        //Make Xml Header Array
			$column = 'A';    		
    		$row = 2;
	        foreach($headers as $header) {
	        	$sheet->setCellValue($column.$row,$header);
	            $column++;
	        }

	        //Make Body Array
	        $i=0;
	        $body = [];
            foreach($books as $book) { 
        		$i++;
                $body[$i][] = $i;
                $body[$i][] = $book['bookname'];
                $body[$i][] = $book['author'];
                $body[$i][] = $book['subjectcode'];
                $body[$i][] = $book['rackNo'];
                $body[$i][] = $book['status'];
            }

            //Make Here Xml Body
	        $row  = 3;
	        if(count($body)) {
	        	foreach($body as $rows) {
	        		$column = "A";
	        		foreach($rows as $value) {
	        			$sheet->setCellValue($column.$row,$value);
	            		$column++;
	        		}
	        		$row++;
	        	}
	        }

	        $styleArray = [
			    'font' => [
			        'bold' => true,
			    ],
			    'alignment' =>[
			    	'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
			    	'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
			    ],
			    'borders' => [
		            'allBorders' => [
		                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
		            ]
		        ]
			];

			$sheet->getStyle('A1:'.$headerColumn.'2')->applyFromArray($styleArray);

			$styleArray = [
			    'font' => [
			        'bold' => false,
			    ],
			    'alignment' =>[
			    	'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
			    	'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
			    ],
			    'borders' => [
		            'allBorders' => [
		                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
		            ]
		        ]
			];
			$row--;
			$sheet->getStyle('A3:'.$headerColumn.$row)->applyFromArray($styleArray);
            $sheet->mergeCells("B1:F1");
		} else {
			redirect(base_url('librarybooksreport'));
		}
	}

	public function send_pdf_to_mail() {
		$retArray['status'] = FALSE;
		$retArray['message'] = '';
		if(permissionChecker('librarybooksreport')) {
			if($_POST) {
				$rules = $this->send_pdf_to_mail_rules();
				$this->form_validation->set_rules($rules);
			    if ($this->form_validation->run() == FALSE) {
					$retArray = $this->form_validation->error_array();
					$retArray['status'] = FALSE;
				    echo json_encode($retArray);
				    exit;
				} else {
					$to = $this->input->post('to'); 
					$subject = $this->input->post('subject'); 
					$message = $this->input->post('message'); 

					$this->data['bookname'] = $this->input->post('bookname'); 
					$this->data['subjectcode'] = !empty($this->input->post('subjectcode')) ? $this->input->post('subjectcode') : '0'; 
					$this->data['rackNo']  = !empty($this->input->post('rackNo')) ? $this->input->post('rackNo') : '0';
					$this->data['status']  = $this->input->post('status');

					$postArray = [];
					if($this->data['bookname'] != '0') {
						$postArray['bookID'] = $this->data['bookname'];
					}

					if(!empty($this->data['subjectcode'])) {
						$postArray['subject_code'] = $this->data['subjectcode'];
					}

					if(!empty($this->data['rackNo'])) {
						$postArray['rack'] = $this->data['rackNo'];
					}

					$books = $this->book_m->get_order_by_book($postArray);
					$booksPluck = pluck($books,'book','bookID');
					$bookfullname = $this->data['bookname'];
					$this->data['bookfullname'] = isset($booksPluck[$bookfullname]) ? $booksPluck[$bookfullname] : '';

					$i = 0;
					$bookArray = [];
					$status = $this->data['status'];
					if(count($books)) {
						foreach($books as $book) {
							$i++;
							if(($status==1) && ($book->quantity == $book->due_quantity)) {
								continue;
							} elseif(($status==2) && ($book->quantity != $book->due_quantity)) {
								continue;
							}
							$bookArray[$i]['bookname'] = $book->book;
							$bookArray[$i]['author'] = $book->author;
							$bookArray[$i]['subjectcode'] = $book->subject_code;
							$bookArray[$i]['rackNo'] = $book->rack;
							if($book->quantity == $book->due_quantity) {
								$bookArray[$i]['status'] = $this->lang->line('librarybooksreport_unavailable');
	                        } else {
								$bookArray[$i]['status'] = $this->lang->line('librarybooksreport_available');
	                        }
						}
					}
					$this->data['books'] = $bookArray;
					$this->reportSendToMail('librarybooksreport.css', $this->data, 'report/librarybooks/LibraryBooksReportPDF', $to, $subject, $message);
					$retArray['status'] = TRUE;
					$retArray['message'] = 'Success';
					echo json_encode($retArray);
				    exit;
				}
			} else {
				$retArray['message'] = $this->lang->line('librarybooksreport_permissionmethod');
				echo json_encode($retArray);
		    	exit;
			}
		} else {
			$retArray['message'] = $this->lang->line('librarybooksreport_permission');
			echo json_encode($retArray);
		    exit;
		}
	}

}

?>