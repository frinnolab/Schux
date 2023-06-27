<?php
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

class Asset_category extends Api_Controller 
{
    public function __construct() 
    {
        parent::__construct();
        $this->load->model('asset_category_m');
    }

    public function index_get() 
    {
        $this->retdata['asset_categorys'] = $this->asset_category_m->get_order_by_asset_category();
        $this->response([
            'status'    => true,
            'message'   => 'Success',
            'data'      => $this->retdata
        ], REST_Controller::HTTP_OK);
    }
}
