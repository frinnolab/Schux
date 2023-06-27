<?php
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

class Purchase extends Api_Controller 
{
    public function __construct() 
    {
        parent::__construct();
        $this->load->model('purchase_m');
    }

    public function index_get() 
    {
        $this->data['unit'] = array(
            1 => $this->lang->line('purchase_unit_kg'), 
            2 => $this->lang->line('purchase_unit_piece'), 
            3 => $this->lang->line('purchase_unit_other')
        );

        $this->retdata['purchases'] = $this->purchase_m->get_purchase_with_all();

        $this->response([
            'status'    => true,
            'message'   => 'Success',
            'data'      => $this->retdata
        ], REST_Controller::HTTP_OK);
    }
}
