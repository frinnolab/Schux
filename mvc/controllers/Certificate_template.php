<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Certificate_template extends Admin_Controller {
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
        $this->load->model("certificate_template_m");
        $this->load->model("mailandsmstemplatetag_m");
        $language = $this->session->userdata('lang');
        $this->lang->load('certificate_template', $language);
        $this->data['buildinThemes'] = $array = array(
            '0' => $this->lang->line('certificate_template_select_theme'),
            '1' => $this->lang->line('certificate_template_theme1'),
            '2' => $this->lang->line('certificate_template_theme2')
        );
    }

    public function index() {
        $this->data['certificate_templates'] = $this->certificate_template_m->get_order_by_certificate_template();
        $this->data["subview"] = "certificate_template/index";
        $this->load->view('_layout_main', $this->data);
    }

    protected function rules() {
        $rules = array(
            array(
                'field' => 'name',
                'label' => $this->lang->line("certificate_template_name"),
                'rules' => 'trim|required|xss_clean|max_length[60]'
            ),
            array(
                'field' => 'theme',
                'label' => $this->lang->line("certificate_template_theme"),
                'rules' => 'trim|required|numeric|xss_clean|max_length[11]|callback_unique_theme'
            ),
            array(
                'field' => 'main_middle_text',
                'label' => $this->lang->line("certificate_template_main_middle_text"),
                'rules' => 'trim|required|xss_clean|max_length[5000000]'
            ),
            array(
                'field' => 'top_heading_title',
                'label' => $this->lang->line("certificate_template_top_heading_title"),
                'rules' => 'trim|xss_clean|max_length[5000000]'
            ),
            array(
                'field' => 'top_heading_left',
                'label' => $this->lang->line("certificate_template_top_heading_left"),
                'rules' => 'trim|xss_clean|max_length[5000000]'
            ),
            array(
                'field' => 'top_heading_middle',
                'label' => $this->lang->line("certificate_template_top_heading_middle"),
                'rules' => 'trim|xss_clean|max_length[5000000]'
            ),
            array(
                'field' => 'top_heading_right',
                'label' => $this->lang->line("certificate_template_top_heading_right"),
                'rules' => 'trim|xss_clean|max_length[5000000]'
            ),
            array(
                'field' => 'template',
                'label' => $this->lang->line("certificate_template_template"),
                'rules' => 'trim|required|xss_clean|max_length[5000000]'
            ),
            array(
                'field' => 'footer_left_text',
                'label' => $this->lang->line("certificate_template_footer_left_text"),
                'rules' => 'trim|xss_clean|max_length[5000000]'
            ),
            array(
                'field' => 'footer_middle_text',
                'label' => $this->lang->line("certificate_template_footer_middle_text"),
                'rules' => 'trim|xss_clean|max_length[5000000]'
            ),
            array(
                'field' => 'footer_right_text',
                'label' => $this->lang->line("certificate_template_footer_right_text"),
                'rules' => 'trim|xss_clean|max_length[5000000]'
            ),
            array(
                'field' => 'background_image',
                'label' => $this->lang->line("certificate_template_background_image"),
                'rules' => 'trim|max_length[200]|xss_clean|callback_photoupload'
            ),
        );
        return $rules;
    }

    public function photoupload() {
        $id = htmlentities(escapeString($this->uri->segment(3)));
        $certificate_template = array();
        if((int)$id) {
            $certificate_template = $this->certificate_template_m->get_certificate_template($id);
        }

        $new_file = "certificate-default.jpg";
        if($_FILES["background_image"]['name'] !="") {
            $file_name = $_FILES["background_image"]['name'];
            $random = random19();
            $makeRandom = hash('sha512', $random.$this->input->post('username') . config_item("encryption_key"));
            $file_name_rename = $makeRandom;
            $explode = explode('.', $file_name);
            if(count($explode) >= 2) {
                $new_file = $file_name_rename.'.'.end($explode);
                $config['upload_path'] = "./uploads/images";
                $config['allowed_types'] = "gif|jpg|png";
                $config['file_name'] = $new_file;
                $config['max_size'] = '1024';
                $config['max_width'] = '3000';
                $config['max_height'] = '3000';
                $this->load->library('upload', $config);
                if(!$this->upload->do_upload("background_image")) {
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
            if(count($certificate_template)) {
                $this->upload_data['file'] = array('file_name' => $certificate_template->background_image);
                return TRUE;
            } else {
                $this->upload_data['file'] = array('file_name' => $new_file);
            return TRUE;
            }
        }
    }

    public function add() {
        $this->data['headerassets'] = array(
            'css' => array(
                'assets/editor/jquery-te-1.4.0.css',
                'assets/jqueryUI/jqueryui.css'
            ),
            'js' => array(
                'assets/jqueryUI/jqueryui.min.js',
                'assets/editor/jquery-te-1.4.0.min.js'
            )
        );
        $studenttags = $this->mailandsmstemplatetag_m->get_order_by_mailandsmstemplatetag(array('usertypeID' => 3));
        $flag = '';

        if(count($studenttags)) {
            foreach ($studenttags as $studenttagkey => $studenttag) {
                if($studenttag->tagname != '[result_table]')
                $flag .= '<span class="btn bg-black btn-xs sms_alltag s-tag" id="'.$studenttag->tagname.'">'.$studenttag->tagname.'</span>';
            }
        }

        $this->data['tag'] = $flag;

        if($_POST) {
            $rules = $this->rules();
            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == FALSE) {
                $this->data['form_validation'] = validation_errors();
                $this->data["subview"] = "certificate_template/add";
                $this->load->view('_layout_main', $this->data);
            } else {
                $array = array(
                    "name" => $this->input->post("name"),
                    "theme" => $this->input->post("theme"),
                    "main_middle_text" => $this->input->post("main_middle_text"),
                    "top_heading_title" => $this->input->post("top_heading_title"),
                    "top_heading_left" => $this->input->post("top_heading_left"),
                    "top_heading_middle" => $this->input->post("top_heading_middle"),
                    "top_heading_right" => $this->input->post("top_heading_right"),
                    "template" => $this->input->post("template"),
                    "footer_left_text" => $this->input->post("footer_left_text"),
                    "footer_middle_text" => $this->input->post("footer_middle_text"),
                    "footer_right_text" => $this->input->post("footer_right_text"),
                    "usertypeID" => 3,
                );

                if(empty($this->input->post("top_heading_title"))) {
                    $array['top_heading_title'] = $this->data['siteinfos']->sname;
                }

                $array['background_image'] = $this->upload_data['file']['file_name'];

                $this->certificate_template_m->insert_certificate_template($array);
                $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                redirect(base_url("certificate_template/index"));
            }
        } else {
            $this->data["subview"] = "/certificate_template/add";
            $this->load->view('_layout_main', $this->data);
        }
    }

    public function edit() {
        $this->data['headerassets'] = array(
            'css' => array(
                'assets/editor/jquery-te-1.4.0.css',
                'assets/jqueryUI/jqueryui.css'
            ),
            'js' => array(
                'assets/jqueryUI/jqueryui.min.js',
                'assets/editor/jquery-te-1.4.0.min.js'
            )
        );
        $studenttags = $this->mailandsmstemplatetag_m->get_order_by_mailandsmstemplatetag(array('usertypeID' => 3));
        $flag = '';

        if(count($studenttags)) {
            foreach ($studenttags as $studenttagkey => $studenttag) {
                if($studenttag->tagname != '[result_table]')
                $flag .= '<span class="btn bg-black btn-xs sms_alltag s-tag" id="'.$studenttag->tagname.'">'.$studenttag->tagname.'</span>';
            }
        }

        $this->data['tag'] = $flag;

        $id = htmlentities(escapeString($this->uri->segment(3)));
        if((int)$id) {
            $this->data['certificate_template'] = $this->certificate_template_m->get_single_certificate_template(array('certificate_templateID' => $id));
            if($this->data['certificate_template']) {
                if($_POST) {
                    $rules = $this->rules();
                    $this->form_validation->set_rules($rules);
                    if ($this->form_validation->run() == FALSE) {
                        $this->data["subview"] = "certificate_template/edit";
                        $this->load->view('_layout_main', $this->data);
                    } else {
                        $array = array(
                            "name" => $this->input->post("name"),
                            "theme" => $this->input->post("theme"),
                            "main_middle_text" => $this->input->post("main_middle_text"),
                            "top_heading_title" => $this->input->post("top_heading_title"),
                            "top_heading_left" => $this->input->post("top_heading_left"),
                            "top_heading_middle" => $this->input->post("top_heading_middle"),
                            "top_heading_right" => $this->input->post("top_heading_right"),
                            "template" => $this->input->post("template"),
                            "footer_left_text" => $this->input->post("footer_left_text"),
                            "footer_middle_text" => $this->input->post("footer_middle_text"),
                            "footer_right_text" => $this->input->post("footer_right_text"),
                            "usertypeID" => 3,
                        );

                        if(empty($this->input->post("top_heading_title"))) {
                            $array['top_heading_title'] = $this->data['siteinfos']->sname;
                        }

                        $array['background_image'] = $this->upload_data['file']['file_name'];


                        $this->certificate_template_m->update_certificate_template($array, $id);
                        $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                        redirect(base_url("certificate_template/index"));
                    }
                } else {
                    $this->data["subview"] = "certificate_template/edit";
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
                // 'assets/editor/jquery-te-1.4.0.css'
            ),
            'js' => array(
                'assets/CircleType/dist/circletype.min.js'
            )
        );

        $id = htmlentities(escapeString($this->uri->segment(3)));
        if((int)$id) {
            $this->data['themeArray'] = array(
                // '1' => 'default',
                '1' => 'theme1',
                '2' => 'theme2'
            );
            $this->data['certificate_template'] = $this->certificate_template_m->get_single_certificate_template(array('certificate_templateID' => $id));
            if($this->data['certificate_template']) {
                
                $this->data['template_convert'] = $this->studentTagHiglightForTemplate($this->data['certificate_template']->template);

                $this->data["subview"] = "certificate_template/view";
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
            $this->data['certificate_template'] = $this->certificate_template_m->get_single_certificate_template(array('certificate_templateID' => $id));
            if($this->data['certificate_template']) {
                $this->certificate_template_m->delete_certificate_template($id);
                $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                redirect(base_url("certificate_template/index"));
            } else {
                redirect(base_url("certificate_template/index"));
            }
        } else {
            redirect(base_url("certificate_template/index"));
        }
    }


    public function unique_theme() {
        if($this->input->post('theme') == 0) {
            $this->form_validation->set_message('unique_theme', 'The %s field is required.');
            return FALSE;
        }
        return TRUE;
    }

    function studentTagHiglightForTemplate($message) {
        if($message) {
            $userTags = pluck($this->mailandsmstemplatetag_m->get_order_by_mailandsmstemplatetag(array('usertypeID' => 3)), 'tagname');

            if(count($userTags)) {
                unset($userTags[10]);
                foreach ($userTags as $key => $userTag) {
                    if($userTag == '[name]') {
                        $length = strlen($userTag);
                        $width = (18*$length);
                        $message = str_replace('[name]', '<span style="width:'.$width.'px;" class="dots widthcss" data-hover="'.$userTag.'"></span>' , $message);
                    }

                    if($userTag == '[class/department]') {
                        $length = strlen($userTag);
                        $width = (18*$length);
                        $message = str_replace($userTag, '<span style="width:'.$width.'px;" class="dots widthcss" data-hover="'.$userTag.'"></span>' , $message);
                    }

                    if($userTag == '[roll]') {
                        $length = strlen($userTag);
                        $width = (18*$length);
                        $message = str_replace($userTag, '<span style="width:'.$width.'px;" class="dots widthcss" data-hover="'.$userTag.'"></span>' , $message);
                    }

                    if($userTag == '[dob]') {
                        $length = strlen($userTag);
                        $width = (18*$length);
                        $message = str_replace($userTag, '<span style="width:'.$width.'px;" class="dots widthcss" data-hover="'.$userTag.'"></span>' , $message);
                    }

                    if($userTag == '[gender]') {
                        $length = strlen($userTag);
                        $width = (18*$length);
                        $message = str_replace($userTag, '<span style="width:'.$width.'px;" class="dots widthcss" data-hover="'.$userTag.'"></span>' , $message);
                    }

                    if($userTag == '[religion]') {
                        $length = strlen($userTag);
                        $width = (18*$length);
                        $message = str_replace($userTag, '<span style="width:'.$width.'px;" class="dots widthcss" data-hover="'.$userTag.'"></span>' , $message);
                    }

                    if($userTag == '[email]') {
                        $length = strlen($userTag);
                        $width = (18*$length);
                        $message = str_replace($userTag, '<span style="width:'.$width.'px;" class="dots widthcss" data-hover="'.$userTag.'"></span>' , $message);
                    }

                    if($userTag == '[phone]') {
                        $length = strlen($userTag);
                        $width = (18*$length);
                        $message = str_replace($userTag, '<span style="width:'.$width.'px;" class="dots widthcss" data-hover="'.$userTag.'"></span>' , $message);
                    }

                    if($userTag == '[section]') {
                        $length = strlen($userTag);
                        $width = (18*$length);
                        $message = str_replace($userTag, '<span style="width:'.$width.'px;" class="dots widthcss" data-hover="'.$userTag.'"></span>' , $message);
                    }

                    if($userTag == '[username]') {
                        $length = strlen($userTag);
                        $width = (18*$length);
                        $message = str_replace($userTag, '<span style="width:'.$width.'px;" class="dots widthcss" data-hover="'.$userTag.'"></span>' , $message);
                    }

                    if($userTag == '[country]') {
                        $length = strlen($userTag);
                        $width = (18*$length);
                        $message = str_replace($userTag, '<span style="width:'.$width.'px;" class="dots widthcss" data-hover="'.$userTag.'"></span>' , $message);
                    }

                    if($userTag == '[register_no]') {
                        $length = strlen($userTag);
                        $width = (18*$length);
                        $message = str_replace($userTag, '<span style="width:'.$width.'px;" class="dots widthcss" data-hover="'.$userTag.'"></span>' , $message);
                    }

                    if($userTag == '[state]') {
                        $length = strlen($userTag);
                        $width = (18*$length);
                        $message = str_replace($userTag, '<span style="width:'.$width.'px;" class="dots widthcss" data-hover="'.$userTag.'"></span>' , $message);
                    }
                }
            }
        }
        return $message;
    }

}
