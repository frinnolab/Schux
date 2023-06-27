<?php
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

class Asset extends Api_Controller 
{
    public function __construct() 
    {
        parent::__construct();
        $this->load->model('asset_m');
    }

    public function index_get() 
    {
        $this->retdata['assets'] = $this->asset_m->get_asset_with_category_and_location();
        $this->response([
            'status'    => true,
            'message'   => 'Success',
            'data'      => $this->retdata
        ], REST_Controller::HTTP_OK);
    }

    public function view_get($id = 0) 
    {
        if((int)$id) {
            $this->retdata['asset'] = $this->asset_m->get_single_asset_with_category_and_location(array('asset.assetID' => $id));
            if(count($this->retdata['asset'])) {
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
