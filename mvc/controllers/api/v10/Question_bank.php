<?php
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

class Question_bank extends Api_Controller 
{
    public function __construct() 
    {
        parent::__construct();
        $this->load->model('question_bank_m');
        $this->load->model('question_group_m');
        $this->load->model('question_level_m');
        $this->load->model('question_type_m');
        $this->load->model('question_option_m');
        $this->load->model('question_answer_m');
    }

    public function index_get() 
    {
        $this->retdata['question_banks'] = $this->question_bank_m->get_order_by_question_bank();
        $this->retdata['groups']  = pluck($this->question_group_m->get_order_by_question_group(), 'obj', 'questionGroupID');
        $this->retdata['levels']  = pluck($this->question_level_m->get_order_by_question_level(), 'obj', 'questionLevelID');
        $this->retdata['types']   = pluck($this->question_type_m->get_order_by_question_type(), 'obj', 'typeNumber');

        $this->response([
            'status'    => true,
            'message'   => 'Success',
            'data'      => $this->retdata
        ], REST_Controller::HTTP_OK);
    }

    public function view_get($id = 0)
    {
        if((int)$id) {
            $questionBank = $this->question_bank_m->get_single_question_bank(array('questionBankID' => $id));
            $this->retdata['question'] =  $questionBank;
            if(count($questionBank)) {
                $allOptions = $this->question_option_m->get_order_by_question_option(array('questionID'=>$questionBank->questionBankID));
                $options = [];
                $oc = 1;
                $ocOption = $questionBank->totalOption;
                foreach ($allOptions as $option) {
                    if($option->name == "" && $option->img == "") continue;
                    if($ocOption >= $oc) {
                        $options[$option->questionID][] = $option;
                        $oc++;
                    }
                }
                $this->retdata['options'] = $options;
                $allAnswers = $this->question_answer_m->get_order_by_question_answer(array('questionID' => $id));
                $answers = [];
                foreach ($allAnswers as $answer) {
                    $answers[$answer->questionID][] = $answer;
                }
                $this->retdata['answers'] = $answers;

                $this->response([
                    'status'    => true,
                    'message'   => 'Success',
                    'data'      => $this->retdata
                ], REST_Controller::HTTP_OK);
            } else {
                $this->retdata['options'] = [];
                $this->retdata['answers'] = [];

                $this->response([
                    'status'    => false,
                    'message'   => 'Error 404',
                    'data'      => $this->retdata
                ], REST_Controller::HTTP_NOT_FOUND);
            }
        } else {
            $this->retdata['question'] = [];
            $this->retdata['options'] = [];
            $this->retdata['answers'] = [];

            $this->response([
                'status'    => false,
                'message'   => 'Error 404',
                'data'      => $this->retdata
            ], REST_Controller::HTTP_NOT_FOUND);
        }
    }
}
