<?php
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

class Mailandsms extends Api_Controller 
{

    public function __construct() 
    {
        parent::__construct();
        $this->load->model('mailandsms_m');
    }

    public function index_get() 
    {
        $this->retdata['mailandsms'] = $this->mailandsms_m->get_mailandsms_with_usertypeID();
        $this->response([
            'status'    => true,
            'message'   => 'Success',
            'data'      => $this->retdata
        ], REST_Controller::HTTP_OK);
    }

    public function view_get($id = null)
    {
        if((int)$id) {
            $this->retdata['mailandsms'] = $this->mailandsms_m->get_mailandsms($id);
            if(count($this->retdata['mailandsms'])) {
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
