<?php
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

class Ebooks extends Api_Controller 
{
    public function __construct() 
    {
        parent::__construct();
        $this->load->model('classes_m');
        $this->load->model('ebooks_m');
        $this->load->library('pagination');
    }

    public function index_get($id = null) 
    {
        $classes = pluck($this->classes_m->get_classes(),'classesID','classesID');
        $ebooks = $this->ebooks_m->get_order_by_ebooks_with_authority($classes);

        $config['base_url'] = base_url('ebooks/index');
        $config['total_rows'] = count($ebooks);
        $config['per_page'] = 10;
        $config['num_links'] = 5;
        $config['full_tag_open'] = '<ul class="pagination">';
        $config['full_tag_close'] = '</ul>';
        $config['first_link'] = false;
        $config['last_link'] = false;
        $config['prev_link'] = '&lt; Previous';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
        $config['last_link'] = false;
        $config['next_link'] = 'Next &gt;';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><a>';
        $config['cur_tag_close'] = '</a></li>';
        $this->pagination->initialize($config);
        $this->retdata['ebooks'] = $this->ebooks_m->get_order_by_ebooks_with_authority_pagination($classes, $config['per_page'], $id);        
        $this->response([
            'status'    => true,
            'message'   => 'Success',
            'data'      => $this->retdata
        ], REST_Controller::HTTP_OK);    
    }
}
