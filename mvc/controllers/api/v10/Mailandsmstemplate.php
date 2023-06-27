<?php
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

class Mailandsmstemplate extends Api_Controller 
{
    public function __construct() 
    {
        parent::__construct();
        $this->load->model('usertype_m');
        $this->load->model("mailandsmstemplate_m");
        $this->load->model("mailandsmstemplatetag_m");
    }

    public function index_get() 
    {
        $this->retdata['mailandsmstemplates'] = $this->mailandsmstemplate_m->get_order_by_mailandsmstemplate_with_usertypeID();
        $this->response([
            'status'    => true,
            'message'   => 'Success',
            'data'      => $this->retdata
        ], REST_Controller::HTTP_OK);
    }

    public function view_get($id=null) 
    {
        if((int)$id) {
            $this->retdata['mailandsmstemplate'] = $this->mailandsmstemplate_m->get_mailandsmstemplate($id);
            if($this->retdata['mailandsmstemplate']) {
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
