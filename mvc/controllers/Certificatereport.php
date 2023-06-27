<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Certificatereport extends Admin_Controller {
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
| WEBSITE:			http://inilabs.net
| -----------------------------------------------------
*/
	function __construct() {
		parent::__construct();
		$this->load->model('section_m');
		$this->load->model("classes_m");
		$this->load->model("certificate_template_m");
		$this->load->model("schoolyear_m");
		$this->load->model("studentrelation_m");
		$this->load->model("studentgroup_m");
		$this->load->model("subject_m");
		$this->load->model("mailandsmstemplatetag_m");
		$language = $this->session->userdata('lang');
		$this->lang->load('certificatereport', $language);
	}

	public function index() {
		$this->data['headerassets'] = array(
			'css' => array(
				'assets/select2/css/select2.css',
				'assets/select2/css/select2-bootstrap.css'
			),
			'js' => array(
				'assets/select2/select2.js'
			)
		);

		$this->data['classes']   = $this->classes_m->general_get_classes();
		$this->data['templates'] = $this->certificate_template_m->get_certificate_template();
		$this->data["subview"] = "report/certificate/CertificateReportView";
		$this->load->view('_layout_main', $this->data);
	}

	protected function rules() {
		$rules = array(
			array(
				'field' => 'classesID',
				'label' => $this->lang->line('certificatereport_classname'),
				'rules' => 'trim|required|xss_clean|callback_unique_data'
			),
			array(
				'field' => 'sectionID',
				'label' => $this->lang->line('certificatereport_sectionname'),
				'rules' => 'trim|required|xss_clean'
			),
			array(
				'field' => 'templateID',
				'label' => $this->lang->line('certificatereport_templatename'),
				'rules' => 'trim|required|xss_clean|callback_unique_data'
			)
		);

		return $rules;
	}

	public function unique_data($data) {
		if($data === "0") {
			$this->form_validation->set_message('unique_data', 'The %s field is required.');
			return FALSE;
		}
		return TRUE;
	}

	public function getStudentList() {
		$retArray['status'] = FALSE;
		$retArray['render'] = '';

		if(permissionChecker('certificatereport')) {
			if($_POST) {
				$rules = $this->rules();
				$this->form_validation->set_rules($rules);
				if($this->form_validation->run() == FALSE) {
					$retArray = $this->form_validation->error_array();
					$retArray['status'] = FALSE;
				    echo json_encode($retArray);
				    exit;
				} else {		
					$classID 		= $this->input->post('classesID');
					$sectionID 		= $this->input->post('sectionID');
					$templateID 	= $this->input->post('templateID');
					$schoolyearID 	= $this->session->userdata('defaultschoolyearID');
					
					$sections = pluck($this->section_m->general_get_section(), 'section', 'sectionID');
					$classes = pluck($this->classes_m->general_get_classes(), 'classes', 'classesID');

					$studentArray = [];
					$studentArray['srschoolyearID'] = $schoolyearID;
					if($classID > 0) {
						$studentArray['srclassesID'] = $classID;
					} 
					
					if($sectionID > 0) {
						$studentArray['srsectionID'] = $sectionID;
					}

					$students = $this->studentrelation_m->general_get_order_by_student($studentArray);
					$this->data['students']		= $students;
					$this->data['classes'] 		= $classes;
					$this->data['sections'] 	= $sections;
					$this->data['classesID']	= $classID;
					$this->data['sectionID']	= $sectionID;
					$this->data['templateID'] 	= $templateID;
					$retArray['render'] = $this->load->view('report/certificate/CertificateReport', $this->data, true);
					$retArray['status'] = TRUE;
					echo json_encode($retArray);
				    exit;
				}
			}
		} else {
			echo json_encode($retArray);
			exit;
		}
	}

	public function generate_certificate() {
		$this->data['headerassets'] = array(
            'js' => array(
                'assets/CircleType/dist/circletype.min.js'
            )
        );

		$tagArray = array();
		$this->data['themeArray'] = array(
            '1' => 'theme1',
            '2' => 'theme2'
        );

		$userID 		= htmlentities(escapeString($this->uri->segment(3)));
		$usertypeID 	= htmlentities(escapeString($this->uri->segment(4)));
		$templateID 	= htmlentities(escapeString($this->uri->segment(5)));
		$classID 		= htmlentities(escapeString($this->uri->segment(6)));
		$schoolyearID 	= $this->session->userdata('defaultschoolyearID');

		if(permissionChecker('certificatereport')) {
			if((int)$userID && (int)$usertypeID && (int)$templateID && (int)$schoolyearID && (int)$classID) {
				$student = $this->studentrelation_m->general_get_single_student(array('srstudentID' => $userID, 'srschoolyearID' => $schoolyearID), TRUE);

				$usertype = $this->usertype_m->get_single_usertype(array('usertypeID' => $usertypeID));
				$template = $this->certificate_template_m->get_single_certificate_template(array('certificate_templateID' => $templateID));
				$schoolyear = $this->schoolyear_m->get_single_schoolyear(array('schoolyearID' => $schoolyearID));


				if(count($student) && count($usertype) && count($template) && count($schoolyear)) {
					$this->data['certificate_template'] = $template;
					$tagClasses = $this->classes_m->general_get_single_classes(array('classesID' => $student->srclassesID));
					$tagSection = $this->section_m->general_get_single_section(array('sectionID' => $student->srsectionID));
					$tagGroup   = $this->studentgroup_m->get_single_studentgroup(array('studentgroupID' => $student->srstudentgroupID));
					$tagSubject = $this->subject_m->general_get_single_subject(array('subjectID' => $student->sroptionalsubjectID));


					$country = $this->getAllCountry();


					$tagArray['[name]'] 		= $student->srname;
					$tagArray['[dob]'] 			= isset($student->dob) ? date("d M Y", strtotime($student->dob)) : '';
					$tagArray['[gender]'] 		= $student->sex;
					$tagArray['[blood_group]'] 	= $student->bloodgroup;
					$tagArray['[religion]'] 	= $student->religion;
					$tagArray['[email]'] 		= $student->email;
					$tagArray['[phone]'] 		= $student->phone;
					$tagArray['[address]']  	= $student->address;
					$tagArray['[state]'] 		= $student->state;
					$tagArray['[country]'] 		= isset($country[$student->country]) ? $country[$student->country] : '';
					$tagArray['[class]'] 		= count($tagClasses) ? $tagClasses->classes : '';
					$tagArray['[section]'] 		= count($tagSection) ? $tagSection->section : '';
					$tagArray['[group]'] 		= count($tagGroup) ? $tagGroup->group : '';
					$tagArray['[optional_subject]'] = count($tagSubject) ? $tagSubject->subject : '';
					$tagArray['[register_no]'] 	= $student->srregisterNO;
					$tagArray['[roll]']	 		= $student->srroll;
					$tagArray['[extra_curricular_activities]'] 	= $student->extracurricularactivities;
					$tagArray['[remarks]'] 		= $student->remarks;
					$tagArray['[username]'] 	= $student->username;
					$tagArray['[date]'] 		= date('d M Y');


					$userTags = pluck($this->mailandsmstemplatetag_m->get_order_by_mailandsmstemplatetag(array('usertypeID' => 3)), 'tagname');

					$this->data['template'] = $this->tagConvertForTemplate($userTags, $template->template, 3, $tagArray);

					$this->data['top_heading_title'] = $this->tagConvertForTemplate($userTags, $template->top_heading_title, 3, $tagArray, FALSE);

					$this->data['top_heading_left'] = $this->tagConvertForTemplate($userTags, $template->top_heading_left, 3, $tagArray, FALSE);

					$this->data['top_heading_middle'] = $this->tagConvertForTemplate($userTags, $template->top_heading_middle, 3, $tagArray, FALSE);

					$this->data['top_heading_right'] = $this->tagConvertForTemplate($userTags, $template->top_heading_right, 3, $tagArray, FALSE);

					$this->data['main_middle_text'] = $this->tagConvertForTemplate($userTags, $template->main_middle_text, 3, $tagArray, FALSE);

					$this->data['footer_left_text'] = $this->tagConvertForTemplate($userTags, $template->footer_left_text, 3, $tagArray, FALSE);

					$this->data['footer_middle_text'] = $this->tagConvertForTemplate($userTags, $template->footer_middle_text, 3, $tagArray, FALSE);

					$this->data['footer_right_text'] = $this->tagConvertForTemplate($userTags, $template->footer_right_text, 3, $tagArray, FALSE);

					$this->data['theme'] = $this->data['themeArray'][$template->theme];
					$this->load->view('report/certificate/CertificateReportLayout', $this->data);		
				} else {
					$this->data["subview"] = "error";
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

	private function tagConvertForTemplate($userTags, $message, $usertypeID=1, $convertArray, $design = TRUE) {
        if($message) {
        	if($usertypeID == 3) {
	            if(count($userTags)) {
	                foreach ($userTags as $key => $userTag) {
	                    if(array_key_exists($userTag, $convertArray)) {
	                        $length = strlen($convertArray[$userTag]);
	                        $width = (20*$length);
	                        if($design) {
	                        	$message = str_replace($userTag, '<span style="width:'.$width.'px;" class="dots widthcss" data-hover="'.$convertArray[$userTag].'"></span>' , $message);
	                        } else {
	                        	$message = str_replace($userTag, $convertArray[$userTag], $message);
	                        }

	                    }
	                }
	            }
        	}
        }
        return $message;
    }

	public function getSection() {
		$id = $this->input->post('id');
		if((int)$id) {
			$allSection = $this->section_m->get_order_by_section(array('classesID' => $id));
			echo "<option value='0'>", $this->lang->line("certificatereport_please_select"),"</option>";
			foreach ($allSection as $value) {
				echo "<option value=\"$value->sectionID\">",$value->section,"</option>";
			}
		}
	}
}
