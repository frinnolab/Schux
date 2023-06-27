<?php
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

class Certificate_template extends Api_Controller 
{
    public function __construct() 
    {
        parent::__construct();
        $this->load->model('certificate_template_m');
        $this->load->model('mailandsmstemplatetag_m');

        $this->lang->load('certificate_template', $this->data['language']);
        $this->retdata['buildinThemes'] = $array = array(
            '0' => $this->lang->line('certificate_template_select_theme'),
            '1' => $this->lang->line('certificate_template_theme1'),
            '2' => $this->lang->line('certificate_template_theme2')
        );
    }

    public function index_get() 
    {
        $this->retdata['certificatetemplates'] = $this->certificate_template_m->get_order_by_certificate_template();
        $this->response([
            'status'    => true,
            'message'   => 'Success',
            'data'      => $this->retdata
        ], REST_Controller::HTTP_OK);
    }

    public function view_get($id = null) 
    {
        if((int)$id) {
            $this->retdata['themes'] = array(
                '1' => 'theme1',
                '2' => 'theme2'
            );
            $this->retdata['certificatetemplate'] = $this->certificate_template_m->get_single_certificate_template(array('certificate_templateID' => $id));
            if(count($this->retdata['certificatetemplate'])) {
                $this->retdata['templateconvert'] = $this->studentTagHiglightForTemplate($this->retdata['certificatetemplate']->template);
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

    private function studentTagHiglightForTemplate($message) 
    {
        return $message;
    }
}
