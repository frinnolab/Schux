<?php
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

class Salary_template extends Api_Controller 
{

    public function __construct() 
    {
        parent::__construct();
        $this->load->model('salary_template_m');
        $this->load->model('salaryoption_m');
    }

    public function index_get() 
    {
        $this->retdata['salary_templates'] = $this->salary_template_m->get_order_by_salary_template();
        
        $this->response([
            'status'    => true,
            'message'   => 'Success',
            'data'      => $this->retdata
        ], REST_Controller::HTTP_OK);
    }

    public function view_get($id = null) 
    {
        if((int)$id) {
            $this->retdata['salary_template'] = $this->salary_template_m->get_single_salary_template(array('salary_templateID' => $id));
            if(count($this->retdata['salary_template'])) {
                $this->db->order_by("salary_optionID", "asc");
                $this->retdata['salaryoptions'] = $this->salaryoption_m->get_order_by_salaryoption(array('salary_templateID' => $id));

                $grosssalary = 0;
                $totaldeduction = 0;
                $netsalary = $this->retdata['salary_template']->basic_salary;
                $orginalNetsalary = $this->retdata['salary_template']->basic_salary;

                if(count($this->retdata['salaryoptions'])) {
                    foreach ($this->retdata['salaryoptions'] as $salaryOptionKey => $salaryOption) {
                        if($salaryOption->option_type == 1) {
                            $netsalary += $salaryOption->label_amount;
                            $grosssalary += $salaryOption->label_amount;
                        } elseif($salaryOption->option_type == 2) {
                            $netsalary -= $salaryOption->label_amount;
                            $totaldeduction += $salaryOption->label_amount;
                        }
                    }
                }
                
                $this->retdata['grosssalary'] = $grosssalary+$orginalNetsalary;
                $this->retdata['totaldeduction'] = $totaldeduction;
                $this->retdata['netsalary'] = $netsalary;

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
    }
}
