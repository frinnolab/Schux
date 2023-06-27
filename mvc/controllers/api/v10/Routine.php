<?php
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

class Routine extends Api_Controller 
{
    public function __construct() 
    {
        parent::__construct();
        $this->load->model('classes_m');
        $this->load->model('section_m');
        $this->load->model('subject_m');
        $this->load->model('routine_m');
        $this->load->model('teacher_m');
        $this->lang->load('routine', $this->data['language']);
    }

    public function index_get($id = null) 
    {
        if($this->session->userdata('usertypeID') == 3) {
            $id = $this->data['myclass'];
        }
        
        $schoolyearID = $this->session->userdata('defaultschoolyearID');
        $this->retdata['classes'] = $this->classes_m->get_classes();
        if((int)$id) {
            $this->retdata['classesID'] = $id;
            $routines   = $this->routine_m->get_order_by_routine(array('classesID' => $id, 'schoolyearID' => $schoolyearID));
            $routines   = $this->routineManipulate($routines);
            $subject    = pluck($this->subject_m->general_get_subject(), 'obj', 'subjectID');
            $teacher    = pluck($this->teacher_m->get_select_teacher(), 'obj', 'teacherID');
            $classes    = pluck($this->classes_m->general_get_classes(), 'obj', 'classesID');
            $section    = pluck($this->section_m->general_get_section(), 'obj', 'sectionID');
            $weekend    = $this->weekend();

            $days                  = [
                0 => $this->lang->line('sunday'),
                1 => $this->lang->line('monday'),
                2 => $this->lang->line('tuesday'),
                3 => $this->lang->line('wednesday'),
                4 => $this->lang->line('thursday'),
                5 => $this->lang->line('friday'),
                6 => $this->lang->line('saturday')
            ];
            $this->retdata['days'] = $days;



            $fetchClass = pluck($this->retdata['classes'], 'classesID', 'classesID');
            if(isset($fetchClass[$id])) {
                $routineArray = [];
                $routineSectionArray = [];
                if(count($routines)) {
                    $this->retdata['sections'] = $this->section_m->general_get_order_by_section(array("classesID" => $id));
                    $sections                  = $this->retdata['sections'];

                    foreach ($days as $dayKey => $day) {
                        foreach ($sections as $sec) {
                            if(isset($routines[$dayKey][$sec->sectionID])) {
                                $rt = $routines[$dayKey][$sec->sectionID];
                                if(count($rt)) {
                                    foreach ($rt as  $r) {
                                        if(!isset($weekend[$r->day])) {
                                            $subjectName    = 'None';
                                            $teacherName    = 'None';
                                            $className      = 'None';
                                            $sectionName    = 'None';

                                            if(isset($subject[$r->subjectID])) {
                                                $subjectName = $subject[$r->subjectID]->subject;
                                            }

                                            if(isset($teacher[$r->teacherID])) {
                                                $teacherName = $teacher[$r->teacherID]->name;
                                            }

                                            if(isset($classes[$r->classesID])) {
                                                $className = $classes[$r->classesID]->classes;
                                            }

                                            if(isset($section[$r->sectionID])) {
                                                $sectionName = $section[$r->sectionID]->section;
                                            }

                                            $routineSectionArray[$sec->sectionID][$dayKey][] = ['time' => $r->start_time.'-'.$r->end_time, 'subject' => $subjectName, 'classes' => $className, 'section' => $sectionName, 'teacher' => $teacherName];
                                        } else {
                                            $routineSectionArray[$sec->sectionID][$dayKey] = 'Weekend';
                                        }
                                    }
                                }
                            } else {
                                if(!isset($routineSectionArray[$sec->sectionID][$dayKey])) {
                                    if(isset($weekend[$dayKey])) {
                                        $routineSectionArray[$sec->sectionID][$dayKey] = 'Weekend';
                                    } else {
                                        $routineSectionArray[$sec->sectionID][$dayKey] = null;
                                    }
                                }
                            }
                        }

                        foreach ($routines as $key => $routine) {
                            if(count($routine)) {
                                foreach ($routine as $rt) {
                                    foreach ($rt as  $r) {
                                        if($dayKey == $r->day) {
                                            if(!isset($weekend[$r->day])) {
                                                $subjectName    = 'None';
                                                $teacherName    = 'None';
                                                $className      = 'None';
                                                $sectionName    = 'None';

                                                if(isset($subject[$r->subjectID])) {
                                                    $subjectName = $subject[$r->subjectID]->subject;
                                                }

                                                if(isset($teacher[$r->teacherID])) {
                                                    $teacherName = $teacher[$r->teacherID]->name;
                                                }

                                                if(isset($classes[$r->classesID])) {
                                                    $className = $classes[$r->classesID]->classes;
                                                }

                                                if(isset($section[$r->sectionID])) {
                                                    $sectionName = $section[$r->sectionID]->section;
                                                }

                                                $routineArray[$dayKey][] = ['time' => $r->start_time.'-'.$r->end_time, 'subject' => $subjectName, 'classes' => $className, 'section' => $sectionName, 'teacher' => $teacherName];
                                            } else {
                                                $routineArray[$dayKey] = 'Weekend';
                                            }
                                        } else {
                                            if(!isset($routineArray[$dayKey])) {
                                                if(isset($weekend[$dayKey])) {
                                                    $routineArray[$dayKey] = 'Weekend';
                                                } else {
                                                    $routineArray[$dayKey] = null;
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }

                $this->retdata['routines'] = $routineArray;
                $this->retdata['routinesections'] = $routineSectionArray;
            } else {
                $this->retdata['routines'] = [];
                $this->retdata['routinesections'] = [];
            }
        } else {
            $this->retdata['classesID'] =  $id;
            $this->retdata['routines'] = [];
            $this->retdata['routinesections'] = [];
        }

        $this->response([
            'status'    => true,
            'message'   => 'Success',
            'data'      => $this->retdata
        ], REST_Controller::HTTP_OK);   
    }


    private function routineManipulate($routines)
    {
        $routineArray = [];
        if(count($routines)) {
            foreach ($routines as $routine) {
                $routineArray[$routine->day][$routine->sectionID][] = $routine;
            }
        }

        return $routineArray;
    }

    private function weekend()
    {
//        $weekendsArray = array('0' => 'SUNDAY', '1' => 'MONDAY', '2' => 'TUESDAY', '3' => 'WEDNESDAY', '4' => 'THURSDAY', '5' => 'FRIDAY', '6' => 'SATURDAY');
        $weekends   = $this->data['siteinfos']->weekends;
        $weekendsKeys = explode(',', $weekends);
        $weekendsDays = [];
        if(count($weekendsKeys)) {
            foreach($weekendsKeys  as $key => $value) {
                if($value !='') {
                    $weekendsDays[$key] = $key;
                }
            }
        }

        return $weekendsDays;
    }
}
