<?php
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

class Update extends Api_Controller 
{
    public function __construct() 
    {
        parent::__construct();
        $this->load->model('update_m');
    }

    public function index_get() 
    {
        $this->retdata['updates'] = $this->update_m->get_update();

        $this->response([
            'status'    => true,
            'message'   => 'Success',
            'data'      => $this->retdata
        ], REST_Controller::HTTP_OK);
    }

    public function view_get($id = null)
    {
        if((int)$id) {
            $this->retdata['update'] = $this->update_m->get_single_update(array('updateID' => $id));
            if(count($this->retdata['update'])) {
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
