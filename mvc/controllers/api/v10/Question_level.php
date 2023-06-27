<?php
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

class Question_level extends Api_Controller 
{
    public function __construct() 
    {
        parent::__construct();
        $this->load->model('question_level_m');
    }

    public function index_get() 
    {
        $this->retdata['question_levels'] = $this->question_level_m->get_order_by_question_level();

        $this->response([
            'status'    => true,
            'message'   => 'Success',
            'data'      => $this->retdata
        ], REST_Controller::HTTP_OK);
    }
}
