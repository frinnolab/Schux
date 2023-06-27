<?php
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

class Instruction extends Api_Controller 
{
    public function __construct() 
    {
        parent::__construct();
        $this->load->model('instruction_m');
    }

    public function index_get() 
    {
        $this->retdata['instructions'] = $this->instruction_m->get_order_by_instruction();

        $this->response([
            'status'    => true,
            'message'   => 'Success',
            'data'      => $this->retdata
        ], REST_Controller::HTTP_OK);
    }

    public function view_get($id=null) 
    {
        if((int)$id) {
            $this->retdata['instruction'] = $this->instruction_m->get_single_instruction(array('instructionID' => $id));
            if(count($this->retdata['instruction'])) {
                $this->response([
                    'status'    => true,
                    'message'   => 'Success',
                    'data'      => $this->retdata
                ], REST_Controller::HTTP_OK);
            } else {
                $this->response([
                    'status'    => false,
                    'message'   => 'Error 404',
                    'data'      => [],
                ], REST_Controller::HTTP_OK);
            }
        } else {
            $this->response([
                'status'    => false,
                'message'   => 'Error 404',
                'data'      => [],
            ], REST_Controller::HTTP_OK);
        }
    }
}
