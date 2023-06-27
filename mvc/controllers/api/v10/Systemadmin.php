<?php
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

class Systemadmin extends Api_Controller 
{
    public function __construct() {
        parent::__construct();
        $this->load->model("systemadmin_m");
        $this->load->model("usertype_m");
        $this->load->model("manage_salary_m");
        $this->load->model("salaryoption_m");
        $this->load->model("salary_template_m");
        $this->load->model("hourly_template_m");
        $this->load->model("make_payment_m");
        $this->load->model("document_m");
    }

    public function index_get() 
    {
        $this->retdata['systemadmins'] = $this->systemadmin_m->get_systemadmin_by_usertype();
        $this->response([
            'status'    => true,
            'message'   => 'Success',
            'data'      => $this->retdata
        ], REST_Controller::HTTP_OK);
    }

    public function view_get($id=null) 
    {
        if((int)$id) {
            $this->getView($id);
        } else {
            $this->response([
                'status' => false,
                'message' => 'Error 404',
                'data' => []
            ], REST_Controller::HTTP_NOT_FOUND);
        }
    }

    private function getView($systemadminID) 
    {
        if((int)$systemadminID) {
            $systemadmin = $this->systemadmin_m->get_systemadmin_by_usertype($systemadminID);
            $this->pluckInfo();
            $this->basicInfo($systemadmin);
            $this->salaryInfo($systemadmin);
            $this->paymentInfo($systemadmin);
            $this->documentInfo($systemadmin);
            if(count($systemadmin)) {
                if($systemadminID != 1 && $this->session->userdata('loginuserID') != $systemadmin->systemadminID) {
                    
                    $this->response([
                        'status'    => true,
                        'message'   => 'Success',
                        'data'      => $this->retdata
                    ], REST_Controller::HTTP_OK);

                } else {
                    $this->response([
                        'status' => false,
                        'message' => 'Error 404',
                        'data' => []
                    ], REST_Controller::HTTP_NOT_FOUND);
                }
            } else {
                $this->response([
                    'status' => false,
                    'message' => 'Error 404',
                    'data' => []
                ], REST_Controller::HTTP_NOT_FOUND);
            }
        } else {
            $this->response([
                'status' => false,
                'message' => 'Error 404',
                'data' => []
            ], REST_Controller::HTTP_NOT_FOUND);
        }
    }

    private function pluckInfo() 
    {
        $this->retdata['usertypes'] = pluck($this->usertype_m->get_usertype(),'usertype','usertypeID');
    }

    private function basicInfo($systemadmin) 
    {
        if(count($systemadmin)) {
            $this->retdata['profile'] = $systemadmin;
        } else {
            $this->retdata['profile'] = [];
        }
    }

    private function salaryInfo($systemadmin) 
    {
        if(count($systemadmin)) {
            $manageSalary = $this->manage_salary_m->get_single_manage_salary(array('usertypeID' => $systemadmin->usertypeID, 'userID' => $systemadmin->systemadminID));
            if(count($manageSalary)) {
                $this->retdata['manage_salary'] = $manageSalary;
                if($manageSalary->salary == 1) {
                    $this->retdata['salary_template'] = $this->salary_template_m->get_single_salary_template(array('salary_templateID' => $manageSalary->template));
                    if($this->retdata['salary_template']) {
                        $this->db->order_by("salary_optionID", "asc");
                        $this->retdata['salaryoptions'] = $this->salaryoption_m->get_order_by_salaryoption(array('salary_templateID' => $manageSalary->template));

                        $grosssalary = 0;
                        $totaldeduction = 0;
                        $netsalary = $this->retdata['salary_template']->basic_salary;
                        $orginalNetsalary = $this->retdata['salary_template']->basic_salary;
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

                        $this->retdata['grosssalary'] = ($orginalNetsalary+$grosssalary);
                        $this->retdata['totaldeduction'] = $totaldeduction;
                        $this->retdata['netsalary'] = $netsalary;
                    } else {
                        $this->retdata['salary_template'] = [];
                        $this->retdata['salaryoptions'] = [];
                        $this->retdata['grosssalary'] = 0;
                        $this->retdata['totaldeduction'] = 0;
                        $this->retdata['netsalary'] = 0;
                    }
                } elseif($manageSalary->salary == 2) {
                    $this->retdata['hourly_salary'] = $this->hourly_template_m->get_single_hourly_template(array('hourly_templateID'=> $manageSalary->template));
                    if(count($this->retdata['hourly_salary'])) {
                        $this->retdata['grosssalary'] = 0;
                        $this->retdata['totaldeduction'] = 0;
                        $this->retdata['netsalary'] = $this->retdata['hourly_salary']->hourly_rate;
                    } else {
                        $this->retdata['hourly_salary'] = [];
                        $this->retdata['grosssalary'] = 0;
                        $this->retdata['totaldeduction'] = 0;
                        $this->retdata['netsalary'] = 0;
                    }
                }
            } else {
                $this->retdata['manage_salary'] = [];
                $this->retdata['salary_template'] = [];
                $this->retdata['salaryoptions'] = [];
                $this->retdata['hourly_salary'] = [];
                $this->retdata['grosssalary'] = 0;
                $this->retdata['totaldeduction'] = 0;
                $this->retdata['netsalary'] = 0;
            }
        } else {
            $this->retdata['manage_salary'] = [];
            $this->retdata['salary_template'] = [];
            $this->retdata['salaryoptions'] = [];
            $this->retdata['hourly_salary'] = [];
            $this->retdata['grosssalary'] = 0;
            $this->retdata['totaldeduction'] = 0;
            $this->retdata['netsalary'] = 0;
        }
    }

    private function paymentInfo($systemadmin) 
    {
        if(count($systemadmin)) {
            $schoolyearID = $this->session->userdata('defaultschoolyearID');
            $this->retdata['make_payments'] = $this->make_payment_m->get_order_by_make_payment(array('usertypeID' => $systemadmin->usertypeID, 'userID' => $systemadmin->systemadminID,'schoolyearID'=> $schoolyearID));
        } else {
            $this->retdata['make_payments'] = [];
        }
    }

    private function documentInfo($systemadmin) 
    {
        if(count($systemadmin)) {
            $this->retdata['documents'] = $this->document_m->get_order_by_document(array('usertypeID' => 1, 'userID' => $systemadmin->systemadminID));
        } else {
            $this->retdata['documents'] = [];
        }
    }
}
