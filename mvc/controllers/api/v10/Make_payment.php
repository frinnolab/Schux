<?php
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

class Make_payment extends Api_Controller {

    function __construct() {
        parent::__construct();
        $this->methods['users_get']['limit']    = 500;
        $this->methods['users_post']['limit']   = 100;
        $this->methods['users_delete']['limit'] = 50;

        $this->load->model('user_m');
        $this->load->model('teacher_m');
        $this->load->model('usertype_m');
        $this->load->model('systemadmin_m');
        $this->load->model('make_payment_m');
        $this->load->model('salaryoption_m');
        $this->load->model('manage_salary_m');
        $this->load->model('salary_template_m');
        $this->load->model('hourly_template_m');

        $language = $this->session->userdata('lang');
        $this->lang->load('make_payment', $language);
    }

    public function index_get($setrole= null) {
        $this->retdata['roles'] = $this->usertype_m->get_usertype();
        if(!isset($setrole)) {
            $setrole = 0;
            $this->retdata['setrole'] = $setrole;
        } else {
            $this->retdata['setrole'] = $setrole;
        }


        if($setrole == 1) {
            $this->retdata['users'] = $this->systemadmin_m->get_systemadmin();
            $this->retdata['managesalary'] = pluck($this->manage_salary_m->get_order_by_manage_salary(array('usertypeID' => 1)), 'userID');
         } elseif($setrole == 2) {
            $this->retdata['users'] = $this->teacher_m->get_teacher();
            $this->retdata['managesalary'] = pluck($this->manage_salary_m->get_order_by_manage_salary(array('usertypeID' => 2)), 'userID');
        } else {
            $this->retdata['users'] = $this->user_m->get_order_by_user(array('usertypeID' => $setrole));
            $this->retdata['managesalary'] = pluck($this->manage_salary_m->get_order_by_manage_salary(array('usertypeID' => $setrole)), 'userID');
        }
        $retArray['status']     = true;
        $retArray['message']    = 'Success'; 
        $retArray['data']       = $this->retdata;
        $this->response($retArray, REST_Controller::HTTP_OK);
    }

     public function view_get($id= null) {
        if(permissionChecker('make_payment')) {
            $schoolyearID = $this->session->userdata('defaultschoolyearID');
            if((int)$id) {
                $this->retdata['paymentMethod'] = array(
                    '1' => $this->lang->line('make_payment_payment_cash'),
                    '2' => $this->lang->line('make_payment_payment_cheque'),
                );

                $this->retdata['make_payment'] = $this->make_payment_m->get_single_make_payment(array('make_paymentID' => $id, 'schoolyearID' => $schoolyearID));

                if(count($this->retdata['make_payment'])) {
                    $userID = $this->retdata['make_payment']->userID;
                    $usertypeID = $this->retdata['make_payment']->usertypeID;

                    if((int)$userID && (int) $usertypeID) {
                        $this->retdata['usertypeID'] = $usertypeID;
                        $this->retdata['userID'] = $userID;

                        if($usertypeID == 1) {
                            $user = $this->systemadmin_m->get_single_systemadmin(array('usertypeID' => $usertypeID, 'systemadminID' => $userID));
                        } elseif($usertypeID == 2) {
                            $user = $this->teacher_m->get_single_teacher(array('usertypeID' => $usertypeID, 'teacherID' => $userID));
                        } else {
                            $user = $this->user_m->get_single_user(array('usertypeID' => $usertypeID, 'userID' => $userID));
                        }

                        if(count($user)) {
                            $this->retdata['usertype'] = $this->usertype_m->get_usertype($user->usertypeID);
                            $this->retdata['user'] = $user;
                            $manageSalary = $this->manage_salary_m->get_single_manage_salary(array('usertypeID' => $usertypeID, 'userID' => $userID));
                            if(count($manageSalary)) {
                                $this->retdata['manage_salary'] = $manageSalary;

                                if($this->retdata['make_payment']->salaryID == 1) {
                                    $this->retdata['persent_salary_template'] = $this->salary_template_m->get_single_salary_template(array('salary_templateID' => $this->retdata['make_payment']->templateID));
                                } elseif($this->retdata['make_payment']->salaryID == 2) {
                                    $this->retdata['persent_salary_template'] = $this->hourly_template_m->get_single_hourly_template(array('hourly_templateID'=> $this->retdata['make_payment']->templateID));
                                }

                                if(count($this->retdata['persent_salary_template'])) {
                                    if($this->retdata['make_payment']->salaryID == 1) {

                                        $this->retdata['salary_template'] = $this->salary_template_m->get_single_salary_template(array('salary_templateID' => $this->retdata['make_payment']->templateID));

                                        if(count($this->retdata['salary_template'])) {
                                            $this->db->order_by("salary_optionID", "asc");
                                            $this->retdata['salaryoptions'] = $this->salaryoption_m->get_order_by_salaryoption(array('salary_templateID' => $this->retdata['make_payment']->templateID));

                                            $grosssalary = 0;
                                            $totaldeduction = 0;
                                            $netsalary = $this->retdata['salary_template']->basic_salary;
                                            $grosssalarylist = array();
                                            $totaldeductionlist = array();

                                            if(count($this->retdata['salaryoptions'])) {
                                                foreach ($this->retdata['salaryoptions'] as $salaryOptionKey => $salaryOption) {
                                                    if($salaryOption->option_type == 1) {
                                                        $netsalary += $salaryOption->label_amount;
                                                        $grosssalary += $salaryOption->label_amount;
                                                        $grosssalarylist[$salaryOption->label_name] = $salaryOption->label_amount;
                                                    } elseif($salaryOption->option_type == 2) {
                                                        $netsalary -= $salaryOption->label_amount;
                                                        $totaldeduction += $salaryOption->label_amount;
                                                        $totaldeductionlist[$salaryOption->label_name] = $salaryOption->label_amount;
                                                    }
                                                }
                                            }

                                            $this->retdata['grosssalary'] = $grosssalary;
                                            $this->retdata['totaldeduction'] = $totaldeduction;
                                            $this->retdata['netsalary'] = $netsalary;
                                            
                                            $retArray['status']     = true;
                                            $retArray['message']    = 'Success'; 
                                            $retArray['data']       = $this->retdata;
                                            $this->response($retArray, REST_Controller::HTTP_OK);
                                        } else {
                                            $retArray['status']     = false;
                                            $retArray['message']    = 'Error 404'; 
                                            $retArray['data']       = $this->retdata;
                                            $this->response($retArray, REST_Controller::HTTP_OK);
                                        }
                                    } elseif($this->retdata['make_payment']->salaryID == 2) {
                                        $this->retdata['hourly_salary'] = $this->hourly_template_m->get_single_hourly_template(array('hourly_templateID'=> $this->retdata['make_payment']->templateID));
                                        if(count($this->retdata['hourly_salary'])) {

                                            $this->retdata['grosssalary'] = 0;
                                            $this->retdata['totaldeduction'] = 0;
                                            $this->retdata['netsalary'] = $this->retdata['hourly_salary']->hourly_rate;

                                            $retArray['status']     = true;
                                            $retArray['message']    = 'Success'; 
                                            $retArray['data']       = $this->retdata;
                                            $this->response($retArray, REST_Controller::HTTP_OK);
                                        } else {
                                            $retArray['status']     = false;
                                            $retArray['message']    = 'Error 404'; 
                                            $retArray['data']       = $this->retdata;
                                            $this->response($retArray, REST_Controller::HTTP_OK);
                                        }
                                    } else {
                                        $retArray['status']     = false;
                                        $retArray['message']    = 'Error 404'; 
                                        $retArray['data']       = $this->retdata;
                                        $this->response($retArray, REST_Controller::HTTP_OK);
                                    }
                                } else{
                                    $retArray['status']     = false;
                                    $retArray['message']    = 'Error 404'; 
                                    $retArray['data']       = $this->retdata;
                                    $this->response($retArray, REST_Controller::HTTP_OK);
                                }
                            } else {
                                $retArray['status']     = false;
                                $retArray['message']    = 'Error 404'; 
                                $retArray['data']       = $this->retdata;
                                $this->response($retArray, REST_Controller::HTTP_OK);
                            }
                        } else {
                            $retArray['status']     = false;
                            $retArray['message']    = 'Error 404'; 
                            $retArray['data']       = $this->retdata;
                            $this->response($retArray, REST_Controller::HTTP_OK);
                        }
                    } else {
                        $retArray['status']     = false;
                        $retArray['message']    = 'Error 404'; 
                        $retArray['data']       = $this->retdata;
                        $this->response($retArray, REST_Controller::HTTP_OK);
                    }
                } else {
                    $retArray['status']     = false;
                    $retArray['message']    = 'Error 404'; 
                    $retArray['data']       = $this->retdata;
                    $this->response($retArray, REST_Controller::HTTP_OK);
                }
            } else {
                $retArray['status']     = false;
                $retArray['message']    = 'Error 404'; 
                $retArray['data']       = $this->retdata;
                $this->response($retArray, REST_Controller::HTTP_OK);
            }
        } else {
            $retArray['status']     = false;
            $retArray['message']    = 'Error 404'; 
            $retArray['data']       = $this->retdata;
            $this->response($retArray, REST_Controller::HTTP_OK);
        }
    }






}
