<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Question_bank extends Admin_Controller {
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
        $this->load->model("question_bank_m");
        $this->load->model("question_group_m");
        $this->load->model("question_level_m");
        $this->load->model("classes_m");
        $this->load->model("subject_m");
        $this->load->model("question_type_m");
        $this->load->model("question_answer_m");
        $this->load->model("question_option_m");
        $this->load->model("online_exam_question_m");
        $language = $this->session->userdata('lang');
        $this->lang->load('question_bank', $language);
    }

    public function index() {
        $this->data['groups']  = pluck($this->question_group_m->get_order_by_question_group(), 'obj', 'questionGroupID');
        $this->data['levels']  = pluck($this->question_level_m->get_order_by_question_level(), 'obj', 'questionLevelID');
        $this->data['types']   = pluck($this->question_type_m->get_order_by_question_type(), 'obj', 'typeNumber');
        $this->data['question_banks'] = $this->question_bank_m->get_order_by_question_bank();
        $this->data["subview"] = "question/bank/index";
        $this->load->view('_layout_main', $this->data);
    }

    protected function rules($postOption) {
        $rules = array(
            array(
                'field' => 'group',
                'label' => $this->lang->line("question_bank_group"),
                'rules' => 'trim|numeric|required|xss_clean|callback_unique_group'
            ),
            array(
                'field' => 'class',
                'label' => $this->lang->line("question_bank_level"),
                'rules' => 'trim|numeric|required|xss_clean'
            ),
            array(
                'field' => 'subject',
                'label' => $this->lang->line("question_bank_subject"),
                'rules' => 'trim|numeric|required|xss_clean'
            ),
            array(
                'field' => 'year',
                'label' => $this->lang->line("question_bank_year"),
                'rules' => 'trim|required|xss_clean'
            ),
            array(
                'field' => 'question',
                'label' => $this->lang->line("question_bank_question"),
                'rules' => 'trim|required|xss_clean'
            ),
            array(
                'field' => 'explanation',
                'label' => $this->lang->line("question_bank_explanation"),
                'rules' => 'trim|xss_clean'
            ),
            array(
                'field' => 'photo',
                'label' => $this->lang->line("question_bank_image"),
                'rules' => 'trim|max_length[200]|xss_clean|callback_photoupload'
            ),
            array(
                'field' => 'hints',
                'label' => $this->lang->line("question_bank_hints"),
                'rules' => 'trim|xss_clean'
            ),
            array(
                'field' => 'mark',
                'label' => $this->lang->line("question_bank_mark"),
                'rules' => 'trim|xss_clean|numeric'
            ),
            array(
                'field' => 'type',
                'label' => $this->lang->line("question_bank_type"),
                'rules' => 'trim|xss_clean'
            ),
            array(
                'field' => 'totalOption',
                'label' => $this->lang->line("question_bank_totalOption"),
                'rules' => 'trim|xss_clean'
            )
        );

        if(count($postOption) > 0) {
            $postOption = count($postOption);
            $ruleForAns = 'trim|xss_clean';

            $j = 9;
            for($i = 1; $i <= $postOption; $i++) {
                $rules[$j] = array(
                    'field' => 'option'.$i,
                    'label' => $this->lang->line("question_bank_option").' '.$i,
                    'rules' => 'trim|xss_clean'
                );
                $j++;
                
                if($i == 1) {
                    $ruleForAns = 'trim|xss_clean';
                } else {
                    $ruleForAns = 'trim|xss_clean';
                }

                $rules[$j] = array(
                    'field' => 'answer'.$i,
                    'label' => 'Answer'. ' '.$i,
                    'rules' => $ruleForAns
                ); 
                $j++;
            }
        }

        return $rules;
    }

    public function send_mail_rules() {
        $rules = array(
            array(
                'field' => 'to',
                'label' => $this->lang->line("question_bank_to"),
                'rules' => 'trim|required|max_length[60]|valid_email|xss_clean'
            ),
            array(
                'field' => 'subject',
                'label' => $this->lang->line("question_bank_subject"),
                'rules' => 'trim|required|xss_clean'
            ),
            array(
                'field' => 'message',
                'label' => $this->lang->line("question_bank_message"),
                'rules' => 'trim|xss_clean'
            ),
            array(
                'field' => 'questionBankID',
                'label' => $this->lang->line("question_bank_questionBankID"),
                'rules' => 'trim|required|max_length[10]|xss_clean|callback_unique_data'
            )
        );
        return $rules;
    }

    public function unique_data($data) {
        if($data != '') {
            if($data == '0') {
                $this->form_validation->set_message('unique_data', 'The %s field is required.');
                return FALSE;
            }
        }
        return TRUE;
    }

    public function photoupload() {
        $id = htmlentities(escapeString($this->uri->segment(3)));
        $question_bank = array();
        if((int)$id) {
            $question_bank = $this->question_bank_m->get_question_bank($id);
        }

        $new_file = "default.png";
        if($_FILES["photo"]['name'] !="") {
            $file_name = $_FILES["photo"]['name'];
            $random = random19();
            $makeRandom = hash('sha512', $random.$_FILES["photo"]['name'].date('Y-M-d-H:i:s') . config_item("encryption_key"));
            $file_name_rename = $makeRandom;
            $explode = explode('.', $file_name);
            if(count($explode) >= 2) {
                $new_file = $file_name_rename.'.'.end($explode);
                $config['upload_path'] = "./uploads/images";
                $config['allowed_types'] = "gif|jpg|jpeg|png";
                $config['file_name'] = $new_file;
                $config['max_size'] = (1024*20);
                $config['max_width'] = '3000';
                $config['max_height'] = '3000';
                $this->load->library('upload');
                $this->upload->initialize($config);
                if(!$this->upload->do_upload("photo")) {
                    $this->form_validation->set_message("photoupload", $this->upload->display_errors());
                    return FALSE;
                } else {
                    $this->upload_data['file'] =  $this->upload->data();
                    return TRUE;
                }
            } else {
                $this->form_validation->set_message("photoupload", "Invalid file");
                return FALSE;
            }
        } else {
            if(count($question_bank)) {
                $this->upload_data['file'] = array('file_name' => $question_bank->upload);
                return TRUE;
            } else {
                $this->upload_data['file'] = array('file_name' => '');
                return TRUE;
            }
        }
    }

    public function imageUpload($imgArrays) {
        $returnArray = array();
        $error = '';

        if(count($imgArrays)) {
            foreach ($imgArrays as $imgkKey => $imgValue) {
                $new_file = '';
                if($_FILES[$imgValue]['name'] !="") {
                    $file_name = $_FILES[$imgValue]['name'];
                    $random = random19();
                    $makeRandom = $new_file = hash('sha512', $random. $_FILES[$imgValue]['name'] .date('Y-M-d-H:i:s') . config_item("encryption_key"));
                    $file_name_rename = $makeRandom;
                    $explode = explode('.', $file_name);
                    if(count($explode) >= 2) {
                        $new_file = $file_name_rename.'.'.end($explode);
                        $config['upload_path'] = "./uploads/images";
                        $config['allowed_types'] = "gif|jpg|png";
                        $config['file_name'] = $new_file;
                        $config['max_size'] = (1024*2);
                        $config['max_width'] = '3000';
                        $config['max_height'] = '3000';
                        $this->load->library('upload');
                        $this->upload->initialize($config);
                        if(!$this->upload->do_upload($imgValue)) {
                            preg_match_all('!\d+!', $imgValue, $matches);
                            $returnArray['error'][$this->upload->display_errors()][$imgkKey] = 'image '.$matches[0][0];
                        } else {
                            $returnArray['success'][$imgkKey] = $new_file;
                        }

                    }
                }
            }
        }

        return $returnArray;
    }

    public function add() {
        $this->data['headerassets'] = array(
            'css' => array(
                'assets/datepicker/datepicker.css',
                'assets/editor/jquery-te-1.4.0.css',
                'assets/select2/css/select2.css',
                'assets/select2/css/select2-bootstrap.css'
            ),
            'js' => array(
                'assets/editor/jquery-te-1.4.0.min.js',
                'assets/datepicker/datepicker.js',
                'assets/select2/select2.js'
            )
        );
        $usertypeID = $this->session->userdata('usertypeID');
        $loginuserID = $this->session->userdata('loginuserID');

        $this->data['groups']  = $this->question_group_m->get_order_by_question_group();
        $this->data['levels']  = $this->question_level_m->get_order_by_question_level();
        $this->data['classes']  = $this->classes_m->general_get_order_by_classes();
        $this->data['subjects']  = $this->subject_m->general_get_order_by_subject();
        $this->data['types']  = $this->question_type_m->get_order_by_question_type();
        $this->data['options'] = array();
        $this->data['answers'] = array();
        $this->data['typeID'] = 0;
        $this->data['totalOptionID'] = 0;

        if($_POST) {
            $postOption = $this->input->post("option");
            $rules = $this->rules($postOption);
            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == FALSE) {
                
                $this->data['form_validation'] = $this->form_validation->error_array();
                $this->data['typeID'] = $this->input->post("type");
                $this->data['totalOptionID'] = $this->input->post("totalOption");
                $this->data['options'] = $this->input->post("option");
                $this->data['answers'] = $this->input->post("answer");
                $this->data["subview"] = "question/bank/add";
                $this->load->view('_layout_main', $this->data);
            } else {
                $imageUpload = array();
                $question_bank = array(
                    "groupID" => $this->input->post("group"),
                    "levelID" => $this->input->post("level"),
                    "classID" => $this->input->post("class"),
                    "subjectID" => $this->input->post("subject"),
                    "year" => $this->input->post("year"),
                    "question" => $this->input->post("question"),
                    "explanation" => $this->input->post("explanation"),
                    "hints" => $this->input->post("hints"),
                    "mark" => empty($this->input->post('mark')) ? NULL : $this->input->post('mark'),
                    "typeNumber" => $this->input->post("type"),
                    "totalOption" => $this->input->post("totalOption"),
                    "create_date" => date("Y-m-d H:i:s"),
                    "modify_date" => date("Y-m-d H:i:s"),
                    "create_userID" => $usertypeID,
                    "create_usertypeID" => $loginuserID
                );

                $question_bank['upload'] = $this->upload_data['file']['file_name'];

                $options = $this->input->post("option");
                $answers = $this->input->post("answer");

                $questionInsertID = $this->question_bank_m->insert_question_bank($question_bank);

                if($this->input->post("type") == 1 || $this->input->post("type") == 2) {

                    $imgArray = array();
                    if($this->input->post("totalOption") > 0) {
                        for($imgi=1; $imgi<=$this->input->post("totalOption"); $imgi++) {
                            if($_FILES['image'.$imgi]['name'] !="") {
                                $imgArray[$imgi] = 'image'.$imgi;
                            }
                        }
                    }

                    if(count($imgArray)) {
                        $imageUpload = $this->imageUpload($imgArray);
                    }

                    $getQuestionOptions = pluck($this->question_option_m->get_order_by_question_option(['questionID' => $questionInsertID]), 'optionID');

                    if(!count($getQuestionOptions)) {
                        foreach (range(1,10) as $optionID) {
                            $data = [
                                'name' => '',
                                'questionID' => $questionInsertID
                            ];
                            $getQuestionOptions[] = $this->question_option_m->insert_question_option($data);
                        }
                    }

                    $totalOption = $this->input->post("totalOption");
                    foreach ($options as $key => $option) {
                        if($option == '' && !isset($imageUpload['success'][$key+1])) {
                            $totalOption--;
                            continue;
                        }

                        $data = [
                            'name' => $option,
                            'img' => isset($imageUpload['success'][$key+1]) ? $imageUpload['success'][$key+1] : ''
                        ];
                        $this->question_option_m->update_question_option($data, $getQuestionOptions[$key]);
                        if(in_array($key+1, $answers)) {
                            $ansData = [
                                'questionID' => $questionInsertID,
                                'optionID' => $getQuestionOptions[$key],
                                'typeNumber' =>$this->input->post("type")
                            ];
                            $this->question_answer_m->insert_question_answer($ansData);
                        }
                    }

                    if($totalOption != $this->input->post("totalOption")) {
                        $this->question_bank_m->update_question_bank(['totalOption' => $totalOption], $questionInsertID);
                    }
                } elseif ($this->input->post("type") == 3) {
                    $totalOption = $this->input->post("totalOption");
                    foreach ($answers as $answer) {
                        if($answer == '') {
                            $totalOption--;
                            continue;
                        }
                        $ansData = [
                            'questionID' => $questionInsertID,
                            'text' => $answer,
                            'typeNumber' =>$this->input->post("type")
                        ];
                        $this->question_answer_m->insert_question_answer($ansData);

                    }
                    if($totalOption != $this->input->post("totalOption")) {
                        $this->question_bank_m->update_question_bank(['totalOption' => $totalOption], $questionInsertID);
                    }
                }

                
                if(isset($imageUpload['error'])) {
                    if(count($imageUpload['error'])) {
                        $errorData = '';
                        foreach ($imageUpload['error'] as $imgErrorKey => $imgErrorValue) {
                            $optionErrors = implode(',', $imgErrorValue);
                            $errorData .= $imgErrorKey .' : '. $optionErrors.'<br/>';
                        }
                        $this->session->set_flashdata('error', $errorData);
                        redirect(base_url("question_bank/edit/$questionInsertID"));
                    } else {
                        $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                        redirect(base_url("question_bank/index"));
                    }
                }  else {
                    $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                    redirect(base_url("question_bank/index"));
                }
            }
        } else {
            $this->data["subview"] = "question/bank/add";
            $this->load->view('_layout_main', $this->data);
        }
    }

    public function edit() {
        $this->data['headerassets'] = array(
            'css' => array(
                'assets/datepicker/datepicker.css',
                'assets/editor/jquery-te-1.4.0.css',
                'assets/select2/css/select2.css',
                'assets/select2/css/select2-bootstrap.css'
            ),
            'js' => array(
                'assets/editor/jquery-te-1.4.0.min.js',
                'assets/datepicker/datepicker.js',
                'assets/select2/select2.js'
            )
        );
        $questionID = htmlentities(escapeString($this->uri->segment(3)));
        if((int)$questionID) {
            $this->data['question_bank'] = $this->question_bank_m->get_single_question_bank(array('questionBankID' => $questionID));
            if($this->data['question_bank']) {

                $this->data['typeID'] = $this->data['question_bank']->typeNumber;
                $this->data['totalOptionID'] = $this->input->post("totalOption");
                $this->data['dbTotalOptionID'] = pluck($this->question_option_m->get_order_by_question_option(['questionID' => $questionID, 'name !=' => '']), 'name', 'optionID');

                
                $this->data['classes']  = $this->classes_m->general_get_order_by_classes();
                $this->data['subjects']  = $this->subject_m->general_get_order_by_subject();

                $this->data['groups']  = $this->question_group_m->get_order_by_question_group();
                $this->data['levels']  = $this->question_level_m->get_order_by_question_level();
                $this->data['types']   = $this->question_type_m->get_order_by_question_type();
                $this->data['options'] = pluck($this->question_option_m->get_order_by_question_option(['questionID' => $questionID]), 'name', 'optionID');

                if($this->data['question_bank']->typeNumber == 1 || $this->data['question_bank']->typeNumber == 2) {
                    $this->data['answers'] = pluck($this->question_answer_m->get_order_by_question_answer(['questionID' => $questionID]), 'optionID');
                } elseif ($this->data['question_bank']->typeNumber == 3) {
                    $this->data['answers'] = pluck($this->question_answer_m->get_order_by_question_answer(['questionID' => $questionID]), 'text');
                }
                // return print_r(json_encode($this->data));
                $this->data['f'] = 0;
                if($_POST) {
                    $postOption = $this->input->post("option");
                    $rules = $this->rules($postOption = 0);
                    $this->form_validation->set_rules($rules);
                    if ($this->form_validation->run() == FALSE) {
                        return print_r(json_encode($this->form_validation));
                        $this->data['typeID']   = $this->input->post("type");
                        $this->data['postData'] = 1;
                        $this->data['options']  = $this->input->post("option");
                        $this->data['answers']  = $this->input->post("answer");
                        $this->data['totalOptionID'] = $this->input->post("totalOption");
                        $this->data['f'] = 1;

                        $this->session->set_flashdata('error', iniArrayToString($this->form_validation->error_array()));
                        $this->data["subview"] = "question/bank/edit";
                        $this->load->view('_layout_main', $this->data);
                    } else {
                        $imageUpload = [];
                        $question_bank = array(
                            "groupID" => $this->input->post("group"),
                            "classID" => $this->input->post("class"),
                            "subjectID" => $this->input->post("subject"),
                            "year" => $this->input->post("year"),
                            "question" => $this->input->post("question"),
                            "explanation" => $this->input->post("explanation"),
                            "hints" => $this->input->post("hints"),
                            "mark" => empty($this->input->post('mark')) ? NULL : $this->input->post('mark'),
                            "typeNumber" => $this->input->post("type"),
                            "totalOption" => ($this->input->post("type") == 0) ? NULL : $this->input->post("totalOption"),
                            "modify_date" => date("Y-m-d H:i:s")
                        );
                        // return print_r(json_encode($this->input->post()));

                        $question_bank['upload'] = $this->upload_data['file']['file_name'];

                        $options = $this->input->post("option");
                        $answers = $this->input->post("answer");

                        if($this->input->post("type") == 1 || $this->input->post("type") == 2) {

                            $imgArray = [];
                            if($this->input->post("totalOption") > 0) {
                                for($imgi=1; $imgi<=$this->input->post("totalOption"); $imgi++) {
                                    if($_FILES['image'.$imgi]['name'] !="") {
                                        $imgArray[$imgi] = 'image'.$imgi;
                                    }
                                }
                            }

                            if(count($imgArray)) {
                                $imageUpload = $this->imageUpload($imgArray);
                            }
                            $questionOptionModel = $this->question_option_m->get_order_by_question_option(['questionID' => $questionID]);

                            $getQuestionOptions = pluck($questionOptionModel, 'optionID');
                            $getQuestionAnswers = pluck($this->question_answer_m->get_order_by_question_answer(['questionID' => $questionID]), 'optionID', 'answerID');

                            $getQuestionOptionsImages = pluck($questionOptionModel, 'img');
                            $totalOption     = $this->input->post("totalOption");
                            $corrcetAnswer   = [];
                            $questionOptions = [];

                            if(count($questionOptionModel)) {
                                $countOption = count($options);
                                $k = 1;
                                foreach ($questionOptionModel as $key => $questionOption) {
                                    if($countOption < $k) {
                                        $this->question_option_m->update_question_option(array('name' => '', 'img' => NULL), $questionOption->optionID);
                                    }
                                    $k++;
                                }
                            }

                            if(!count($getQuestionOptions)) {
                                $this->question_answer_m->delete_question_answer_by_questionID($questionID);
                            }


                            foreach ($options as $key => $option) {
                                if(($option == '') && (array_key_exists($key, $getQuestionOptionsImages) && ($getQuestionOptionsImages[$key] == '' || $getQuestionOptionsImages[$key] === null) && !count($imageUpload))) {
                                    $totalOption--;
                                    continue;
                                }

                                $data = [
                                    'name' => $option,
                                ];

                                if(isset($imageUpload['success'][$key+1])) {
                                    $data['img'] = isset($imageUpload['success'][$key+1]) ? $imageUpload['success'][$key+1] : '';
                                }

                                if(isset($getQuestionOptions[$key])) {
                                    $this->question_option_m->update_question_option($data, $getQuestionOptions[$key]);
                                } else {
                                    $data['questionID'] = $questionID;
                                    $questionOptions[] =  $this->question_option_m->insert_question_option($data);
                                }

                                if(in_array($key+1, $answers)) {
                                    if(count($getQuestionOptions)) {
                                        $corrcetAnswer [] = $getQuestionOptions[$key];
                                    } else {
                                        $ansData = [
                                            'questionID' => $questionID,
                                            'optionID' => $questionOptions[$key],
                                            'typeNumber' =>$this->input->post("type")
                                        ];
                                        $this->question_answer_m->insert_question_answer($ansData);
                                    }
                                }
                            }

                            if($totalOption != $this->input->post("totalOption")) {
                                $question_bank['totalOption'] = $totalOption;
                            }
                            $this->question_bank_m->update_question_bank($question_bank, $questionID);

                            if(count($getQuestionOptions)) {
                                $i = 0;
                                foreach ($getQuestionAnswers as $answerID => $optionID) {
                                    if(isset($corrcetAnswer[$i])) {
                                        $this->question_answer_m->update_question_answer(['optionID' => $corrcetAnswer[$i]], $answerID);
                                    } else {
                                        $this->question_answer_m->delete_question_answer($answerID);
                                    }
                                    $i++;
                                }
                                $countOfCorrectAnswer = count($corrcetAnswer);
                                for($j = $i; $j < $countOfCorrectAnswer; $j++) {
                                    $ansData = [
                                        'questionID' => $questionID,
                                        'optionID' => $getQuestionOptions[$j],
                                        'typeNumber' => $this->input->post("type")
                                    ];
                                    $this->question_answer_m->insert_question_answer($ansData);
                                }
                            }
                        } elseif ($this->input->post("type") == 3) {
                            $getQuestionAnswers = pluck($this->question_answer_m->get_order_by_question_answer(['questionID' => $questionID]), 'text', 'answerID');

                            if(count($this->data['options'])) {
                                $optionsArray = [];
                                foreach ($this->data['options'] as $optionKey => $option) {
                                    $optionsArray[] =  $optionKey;
                                }
                                if(count($optionsArray)) {
                                    $this->question_option_m->delete_batch_option($optionsArray);
                                }
                            }


                            $i = 0;
                            $totalOption = 0;
                            foreach ($getQuestionAnswers as $answerID => $text) {
                                if(isset($answers[$i]) && $answers[$i] != '') {
                                    $totalOption++;
                                    $this->question_answer_m->update_question_answer(['optionID' => NULL, 'typeNumber' => $this->input->post("type"), 'text' => $answers[$i]], $answerID);
                                } else {
                                    $this->question_answer_m->delete_question_answer($answerID);
                                }
                                $i++;
                            }

                            for($j = $i; $j< count($answers); $j++) {
                                $ansData = [
                                    'questionID' => $questionID,
                                    'text' => $answers[$j],
                                    'typeNumber' => $this->input->post("type")
                                ];
                                $this->question_answer_m->insert_question_answer($ansData);
                                $totalOption++;
                            }

                            if($totalOption != $this->input->post("totalOption")) {
                                $question_bank['totalOption'] = $totalOption;
                            }
                            $this->question_bank_m->update_question_bank($question_bank, $questionID);
                        }

                        if(isset($imageUpload['error'])) {
                            if(count($imageUpload['error'])) {
                                $errorData = '';
                                foreach ($imageUpload['error'] as $imgErrorKey => $imgErrorValue) {
                                    $optionErrors = implode(',', $imgErrorValue);
                                    $errorData .= $imgErrorKey .' : '. $optionErrors.'<br/>';
                                }
                                $this->session->set_flashdata('error', $errorData);
                                redirect(base_url("question_bank/edit/$questionID"));
                            } else {
                                $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                                redirect(base_url("question_bank/index"));
                            }
                        } else {
                            $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                            redirect(base_url("question_bank/index"));
                        }
                    }
                } else {
                    $this->data["subview"] = "question/bank/edit";
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
        $this->data['headerassets'] = array(
            'css' => array(
                'assets/checkbox/checkbox.css',
            )
        );
        $questionID = htmlentities(escapeString($this->uri->segment(3)));
        if((int)$questionID) {
            $questionBank = $this->question_bank_m->get_single_question_bank(array('questionBankID' => $questionID));
            $this->data['question'] =  $questionBank;
            if(count($questionBank)) {
                $allOptions = $this->question_option_m->get_order_by_question_option(array('questionID'=>$questionBank->questionBankID));
                $options = [];
                $oc = 1;
                $ocOption = $questionBank->totalOption;
                foreach ($allOptions as $option) {
                    if($option->name == "" && $option->img == "") continue;
                    if($ocOption >= $oc) {
                        $options[$option->questionID][] = $option;
                        $oc++;
                    }
                }
                $this->data['options'] = $options;
                $allAnswers = $this->question_answer_m->get_order_by_question_answer(array('questionID' => $questionID));
                $answers = [];
                foreach ($allAnswers as $answer) {
                    $answers[$answer->questionID][] = $answer;
                }
                $this->data['answers'] = $answers;
                $this->data["subview"] = "question/bank/view";
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
        $id = htmlentities(escapeString($this->uri->segment(3)));
        if((int)$id) {
            $this->data['question_bank'] = $this->question_bank_m->get_single_question_bank(array('questionBankID' => $id));
            if(count($this->data['question_bank'])) {
                $this->question_bank_m->delete_question_bank($id);
                $onlineExamQuestions = $this->online_exam_question_m->get_order_by_online_exam_question(array('questionID' => $id));

                if(count($onlineExamQuestions)) {
                    foreach ($onlineExamQuestions as $onlineExamQuestion) {
                        $this->online_exam_question_m->delete_online_exam_question($onlineExamQuestion->onlineExamQuestionID);
                    }
                }

                $questionOptions = pluck($this->question_option_m->get_order_by_question_option(array('questionID' => $id)), 'optionID');
                $questionAnswers = pluck($this->question_answer_m->get_order_by_question_answer(array('questionID' => $id)), 'answerID');

                if(count($questionOptions)) {
                    $this->question_option_m->delete_batch_option($questionOptions);
                }

                if(count($questionAnswers)) {
                    $this->question_answer_m->delete_batch_question_answer($questionAnswers);
                }

                $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                redirect(base_url("question_bank/index"));
                
            } else {
                redirect(base_url("question_bank/index"));
            }
        } else {
            redirect(base_url("question_bank/index"));
        }
    }

    public function print_preview() {
        if(permissionChecker('question_bank_view')) {
            $id = htmlentities(escapeString($this->uri->segment(3)));
            if((int)$id) {
                $questionBank = $this->question_bank_m->get_single_question_bank(array('questionBankID' => $id));
                $this->data['question'] =  $questionBank;
                if(count($questionBank)) {
                    $allOptions = $this->question_option_m->get_order_by_question_option(array('questionID'=>$questionBank->questionBankID));
                    $options = [];
                    $oc = 1;
                    $ocOption = $questionBank->totalOption;
                    foreach ($allOptions as $option) {
                        if($option->name == "" && $option->img == "") continue;
                        if($ocOption >= $oc) {
                            $options[$option->questionID][] = $option;
                            $oc++;
                        }
                    }
                    $this->data['options'] = $options;
                    $allAnswers = $this->question_answer_m->get_order_by_question_answer(array('questionID' => $id));
                    $answers = [];
                    foreach ($allAnswers as $answer) {
                        $answers[$answer->questionID][] = $answer;
                    }

                    $this->data['answers'] = $answers;
                    $this->data['panel_title'] = $this->lang->line('panel_title');
                    $this->reportPDF('questionbankmodule.css',$this->data, 'question/bank/print_preview');
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

    public function send_mail() {
        $retArray['status'] = FALSE;
        $retArray['message'] = '';
        if(permissionChecker('question_bank_view')) {
            if($_POST) {
                $rules = $this->send_mail_rules();
                $this->form_validation->set_rules($rules);
                if ($this->form_validation->run() == FALSE) {
                    $retArray = $this->form_validation->error_array();
                    $retArray['status'] = FALSE;
                    echo json_encode($retArray);
                    exit;
                } else {
                    $id = $this->input->post('questionBankID');
                    if ((int)$id) {
                        $questionBank = $this->question_bank_m->get_single_question_bank(array('questionBankID' => $id));
                        $this->data['question'] =  $questionBank;
                        if(count($questionBank)) {
                            $allOptions = $this->question_option_m->get_order_by_question_option(array('questionID'=>$questionBank->questionBankID));
                            $options = [];
                            $oc = 1;
                            $ocOption = $questionBank->totalOption;
                            foreach ($allOptions as $option) {
                                if($option->name == "" && $option->img == "") continue;
                                if($ocOption >= $oc) {
                                    $options[$option->questionID][] = $option;
                                    $oc++;
                                }
                            }
                            $this->data['options'] = $options;
                            $allAnswers = $this->question_answer_m->get_order_by_question_answer(array('questionID' => $id));
                            $answers = [];
                            foreach ($allAnswers as $answer) {
                                $answers[$answer->questionID][] = $answer;
                            }
                            $this->data['answers'] = $answers;

                            $this->data['panel_title'] = $this->lang->line('panel_title');
                            $email = $this->input->post('to');
                            $subject = $this->input->post('subject');
                            $message = $this->input->post('message');

                            $this->reportSendToMail('questionbankmodule.css',$this->data, 'question/bank/print_preview', $email, $subject, $message);
                            $retArray['message'] = "Message";
                            $retArray['status'] = TRUE;
                            echo json_encode($retArray);
                            exit;
                        } else {
                            $retArray['message'] = $this->lang->line('question_bank_data_not_found');
                            echo json_encode($retArray);
                            exit;
                        }
                    } else {
                        $retArray['message'] = $this->lang->line('question_bank_data_not_found');
                        echo json_encode($retArray);
                        exit;
                    }
                }
            } else {
                $retArray['message'] = $this->lang->line('question_bank_permissionmethod');
                echo json_encode($retArray);
                exit;
            }
        } else {
            $retArray['message'] = $this->lang->line('question_bank_permission');
            echo json_encode($retArray);
            exit;
        }
    }

    public function unique_group() {
        if($this->input->post('group') == 0) {
            $this->form_validation->set_message("unique_group", "The %s field is required");
            return FALSE;
        }
        return TRUE;    
    }

    public function unique_level() {
        if($this->input->post('level') == 0) {
            $this->form_validation->set_message("unique_level", "The %s field is required");
            return FALSE;
        }
        return TRUE;
    }

    public function unique_type() {
        if($this->input->post('type') == 0) {
            $this->form_validation->set_message("unique_type", "The %s field is required");
            return FALSE;
        }
        return TRUE;
    }

    public function unique_total_option() {
        if($this->input->post('totalOption') == 0) {
            $this->form_validation->set_message("unique_total_option", "The %s field is required");
            return FALSE;
        }
        return TRUE;
    }

    public function unique_answer() {
        if($this->input->post('type') == 3) {
            $f = 0;
            if(count($this->input->post("answer"))) {
                foreach ($this->input->post("answer") as $value) {
                    if($value != '') {
                        $f = 1;
                    }
                }
            }
            if($f != 1) {
                $this->form_validation->set_message("unique_answer", "Please Select Atleast one Answer");
                return FALSE;
            }
            return TRUE;
        } else {
            if(count($this->input->post('answer')) <= 0) {
                $this->form_validation->set_message("unique_answer", "Please Select Atleast one Answer");
                return FALSE;
            }
            return TRUE;
        }
    }

    public function valid_answer() {
        $type     = $this->input->post('type');
        $answers  = $this->input->post('answer');
        $options  = $this->input->post('option');

        $retArr   = [];

        // if($type != 3) {
        //     $questionID = htmlentities(escapeString($this->uri->segment(3)));
        //     $optionsDB  = [];
        //     if((int)$questionID) {
        //         $optionsDB  = $this->question_option_m->get_order_by_question_option(['questionID' => $questionID]);
        //     }


        //     if(count($options)) {
        //         foreach ($options as $key=> $option) {
        //             $key++;
        //             if($option != '') {
        //                 $retArr[$key] = $key;
        //             }

        //             if(isset($_FILES['image'.$key]['name']) && $_FILES['image'.$key]['name'] !='') {
        //                 $retArr[$key] = $key;
        //             }

        //             if(count($optionsDB)) {
        //                 $dbKey = $key;
        //                 $dbKey--;
        //                 if(isset($optionsDB[$dbKey])) {
        //                     if($optionsDB[$dbKey]->img != '') {
        //                         $retArr[$key] = $key;
        //                     }
        //                 }
        //             }
        //         }
        //     }
        //     if(count($answers) && count($retArr)) {
        //         $f=0;
        //         foreach($answers as $answer) {
        //             if(in_array($answer, $retArr)) {
        //                 $f= 1;
        //             }
        //         }
        //         if(!$f) {
        //             $this->form_validation->set_message("valid_answer", "Please Select Atleast one valid Answer");
        //             return FALSE;
        //         }
        //     } else {
        //         $this->form_validation->set_message("valid_answer", "Please Select Atleast one valid Answer");
        //         return FALSE;
        //     }
        // }
        return TRUE;
    }

}
