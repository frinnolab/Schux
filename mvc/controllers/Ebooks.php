<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ebooks extends Admin_Controller {
/*
| -----------------------------------------------------
| PRODUCT NAME: 	INILABS SCHOOL MANAGEMENT SYSTEM
| -----------------------------------------------------
| AUTHOR:			INILABS TEAM
| -----------------------------------------------------
| EMAIL:			info@inilabs.net
| -----------------------------------------------------
| COPYRIGHT:		RESERVED BY INILABS IT
| -----------------------------------------------------
| WEBSITE:			http://iNilabs.net
| -----------------------------------------------------
*/
	function __construct() {
		parent::__construct();
		$this->load->model('classes_m');
		$this->load->model('ebooks_m');
		$this->load->library('pagination');
		$language = $this->session->userdata('lang');
		$this->lang->load('ebooks', $language);
	}

	protected function rules() {
		$rules = array(
			array(
				'field' => 'name',
				'label' => $this->lang->line("ebooks_name"),
				'rules' => 'trim|required|xss_clean|max_length[255]'
			),
			array(
				'field' => 'author',
				'label' => $this->lang->line("ebooks_author"),
				'rules' => 'trim|required|xss_clean|max_length[255]'
			),
			array(
				'field' => 'classesID',
				'label' => $this->lang->line("ebooks_classes"),
				'rules' => 'trim|required|xss_clean|numeric|callback_unique_data'
			),
			array(
				'field' => 'authority',
				'label' => $this->lang->line("ebooks_private"),
				'rules' => 'trim|xss_clean'
			),
			array(
				'field' => 'cover_photo',
				'label' => $this->lang->line("ebooks_cover_photo"),
				'rules' => 'trim|xss_clean|callback_unique_cover_photo'
			),
			array(
				'field' => 'file',
				'label' => $this->lang->line("ebooks_file"),
				'rules' => 'trim|xss_clean|callback_unique_file'
			)
		);
		return $rules;
	}

	public function unique_data($data) {
		if($data != "") {
			if($data === "0") {
				$this->form_validation->set_message('unique_data', 'The %s field is required.');
				return FALSE;
			}
			return TRUE;
		} 
		return TRUE;
	}

	public function index() {
		$classes = pluck($this->classes_m->get_classes(),'classesID','classesID');
		$ebooks = $this->ebooks_m->get_order_by_ebooks_with_authority($classes);

		$config['base_url'] = base_url('ebooks/index');
        $config['total_rows'] = count($ebooks);
        $config['per_page'] = 10;
        $config['num_links'] = 5;
        $config['full_tag_open'] = '<ul class="pagination">';
        $config['full_tag_close'] = '</ul>';
        $config['first_link'] = false;
        $config['last_link'] = false;
        $config['prev_link'] = '&lt; Previous';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
        $config['last_link'] = false;
        $config['next_link'] = 'Next &gt;';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><a>';
        $config['cur_tag_close'] = '</a></li>';
        $this->pagination->initialize($config);
		$this->data['ebooks'] = $this->ebooks_m->get_order_by_ebooks_with_authority_pagination($classes, $config['per_page'], $this->uri->segment('3'));
		$this->data["subview"] = "ebooks/index";
		$this->load->view('_layout_main', $this->data);
	}

	public function add() {
		$this->data['headerassets'] = array(
			'css' => array(
				'assets/select2/css/select2.css',
				'assets/select2/css/select2-bootstrap.css',
				'assets/uploadjs/imageuploadify.min.css'
			),
			'js' => array(
				'assets/select2/select2.js',
				'assets/uploadjs/imageuploadify.min.js'
			)
		);

		$this->data['classes'] = $this->classes_m->get_classes();
		if($_POST) {
			$rules = $this->rules();
			$this->form_validation->set_rules($rules);
			if($this->form_validation->run() == FALSE) {
				$this->data["subview"] = "ebooks/add";
				$this->load->view('_layout_main', $this->data);
			} else {
				$array['name']      = $this->input->post('name');
				$array['author']    = $this->input->post('author');
				$array['classesID'] = $this->input->post('classesID');


				if($this->input->post('authority')) {
					$array['authority'] = $this->input->post('authority');
				} else {
					$array['authority'] = 0;
				}
				
				$array['cover_photo'] = $this->upload_data['cover_photo']['file_name'];
				$array['file'] = $this->upload_data['file']['file_name'];
				$this->ebooks_m->insert_ebooks($array);
				$this->session->set_flashdata('success', $this->lang->line('menu_success'));
				redirect('ebooks/index');
			}	
		} else {
			$this->data["subview"] = "ebooks/add";
			$this->load->view('_layout_main', $this->data);
		}
	}

	public function edit() {
		$ebooksID = htmlentities(escapeString($this->uri->segment(3)));
		if((int)$ebooksID) {
			$this->data['headerassets'] = array(
				'css' => array(
					'assets/select2/css/select2.css',
					'assets/select2/css/select2-bootstrap.css'
				),
				'js' => array(
					'assets/select2/select2.js'
				)
			);

			$classes = $this->classes_m->get_classes();
			$classesArray = pluck($classes, 'classesID','classesID');
			$this->data['ebooks'] = $this->ebooks_m->get_single_ebooks_with_authority($classesArray, $ebooksID);

			if(count($this->data['ebooks'])) {
				$this->data['classes'] = $classes;
				if($_POST) {
					$rules = $this->rules();
					$this->form_validation->set_rules($rules);
					if($this->form_validation->run() == FALSE) {
						$this->data["subview"] = "ebooks/edit";
						$this->load->view('_layout_main', $this->data);
					} else {
						$array['name']      = $this->input->post('name');
						$array['author']    = $this->input->post('author');
						$array['classesID'] = $this->input->post('classesID');

						if($this->input->post('authority')) {
							$array['authority'] = $this->input->post('authority');
						} else {
							$array['authority'] = 0;
						}
						
						if($_FILES["cover_photo"]['name'] != '') {
							$array['cover_photo'] = $this->upload_data['cover_photo']['file_name'];
						} 
						if($_FILES["file"]['name'] != '') {
							$array['file'] = $this->upload_data['file']['file_name'];
						}

						$this->ebooks_m->update_ebooks($array,$ebooksID);
						$this->session->set_flashdata('success', $this->lang->line('menu_success'));
						redirect('ebooks/index');
					}	
				} else {
					$this->data["subview"] = "ebooks/edit";
					$this->load->view('_layout_main', $this->data);
				}
			} else {
				$this->data["subview"] = "error";
				$this->load->view('_layout_main', $this->data);
			}
		} else {
			$this->data["subview"] = "error";
			$this->load->view('_layout_main', $this->data);
		}
	}

	public function view() {
		$ebooksID    = htmlentities(escapeString($this->uri->segment(3)));
		if((int)$ebooksID) {
			$this->data['headerassets'] = array(
				'js' => array(
					'assets/pdfjs/pdfobject.min.js'
				)
			);

			$classes = $this->classes_m->get_classes();
			$classesArray = pluck($classes, 'classesID','classesID');
			$this->data['ebooks'] = $this->ebooks_m->get_single_ebooks_with_authority($classesArray, $ebooksID);

			if(count($this->data['ebooks'])) {
				$this->data["subview"] = "ebooks/view";
				$this->load->view('_layout_main', $this->data);
			} else {
				$this->data["subview"] = "error";
				$this->load->view('_layout_main', $this->data);
			}
		} else {
			$this->data["subview"] = "error";
			$this->load->view('_layout_main', $this->data);
		}
	}

	public function delete() {
		$ebooksID = htmlentities(escapeString($this->uri->segment(3)));
		if((int)$ebooksID) {
			$classes = $this->classes_m->get_classes();
			$classesArray = pluck($classes, 'classesID','classesID');
			$ebooks = $this->ebooks_m->get_single_ebooks_with_authority($classesArray, $ebooksID);

			if(count($ebooks)) {
				if(file_exists(FCPATH.'uploads/ebooks/'.$ebooks->cover_photo)) {
					unlink(FCPATH.'uploads/ebooks/'.$ebooks->cover_photo);
				}

				if(file_exists(FCPATH.'uploads/ebooks/'.$ebooks->file)) {
					unlink(FCPATH.'uploads/ebooks/'.$ebooks->file);
				}

				$this->ebooks_m->delete_ebooks($ebooksID);
				$this->session->set_flashdata('success', $this->lang->line('menu_success'));
				redirect(base_url("ebooks/index"));
			} else {
				redirect(base_url("ebooks/index"));
			}
		} else {
			redirect(base_url("ebooks/index"));
		}

	}

	public function unique_cover_photo() {
		$new_file = '';
		if($_FILES["cover_photo"]['name'] !="") {
			$file_name = $_FILES["cover_photo"]['name'];
			$random = random19();
	    	$makeRandom = hash('sha512', $random.(strtotime(date('Y-m-d H:i:s'))). config_item("encryption_key"));
			$file_name_rename = $makeRandom;
            $explode = explode('.', $file_name);
            if(count($explode) >= 2) {
	            $new_file = $file_name_rename.'.'.end($explode);
				$config['upload_path'] = "./uploads/ebooks";
				$config['allowed_types'] = "gif|jpg|png|jpeg|JPG|PNG|JPEG";
				$config['file_name'] = $new_file;
				$config['max_size'] = '2048';
				$config['max_width'] = '3000';
				$config['max_height'] = '3000';
				$this->load->library('upload', $config);
				$this->upload->initialize($config);
				if(!$this->upload->do_upload("cover_photo")) {
					$this->form_validation->set_message("unique_cover_photo", $this->upload->display_errors());
	     			return FALSE;
				} else {
					$this->upload_data['cover_photo'] =  $this->upload->data();
					return TRUE;
				}
			} else {
				$this->form_validation->set_message("unique_cover_photo", "Invalid file");
	     		return FALSE;
			}
		} else {
			if($this->uri->segment('2') == 'edit') {
				return TRUE;
			} else {
				$this->form_validation->set_message("unique_cover_photo", "The file is required.");
				return FALSE;
			}
		}
	}

	public function unique_file() {
		$new_file = '';
		if($_FILES["file"]['name'] !="") {
			$file_name = $_FILES["file"]['name'];
			$random = random19();
	    	$makeRandom = hash('sha512', $random.(strtotime(date('Y-m-d H:i:s'))). config_item("encryption_key"));
			$file_name_rename = $makeRandom;
            $explode = explode('.', $file_name);
            if(count($explode) >= 2) {
	            $new_file = $file_name_rename.'.'.end($explode);
				$config['upload_path'] = "./uploads/ebooks";
				$config['allowed_types'] = "pdf|PDF";
				$config['file_name'] = $new_file;
				$config['max_size'] = '51200';
				$config['max_width'] = '10000';
				$config['max_height'] = '10000';
				$this->load->library('upload', $config);
				$this->upload->initialize($config);
				if(!$this->upload->do_upload("file")) {
					$this->form_validation->set_message("unique_file", $this->upload->display_errors());
	     			return FALSE;
				} else {
					$this->upload_data['file'] =  $this->upload->data();
					return TRUE;
				}
			} else {
				$this->form_validation->set_message("unique_file", "Invalid file");
	     		return FALSE;
			}
		} else {
			if($this->uri->segment('2') == 'edit') {
				return TRUE;
			} else {
				$this->form_validation->set_message("unique_file", "The file is required.");
				return FALSE;
			}
		}
	}
}