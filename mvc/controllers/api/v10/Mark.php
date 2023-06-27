<?php
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

class Mark extends Api_Controller 
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('classes_m');
        $this->load->model('section_m');
        $this->load->model('subject_m');
        $this->load->model('exam_m');
        $this->load->model('grade_m');
        $this->load->model('mark_m');
        $this->load->model('markpercentage_m');
        $this->load->model('studentrelation_m');
        $this->load->model('marksetting_m');
    }

    public function index_get($id = null) 
    {
        $myProfile = false;
        $schoolyearID = $this->session->userdata('defaultschoolyearID');
        if($this->session->userdata('usertypeID') == 3) {
            $id = $this->data['myclass'];
            if(!permissionChecker('mark_view')) {
                $myProfile = true;
            }
        }

        if($this->session->userdata('usertypeID') == 3 && $myProfile) {
            $url = $id;
            $id = $this->session->userdata('loginuserID');
            $this->view_get($id, $url);
        } else {
            $this->retdata['classesID'] = $id;
            $this->retdata['classes']   = $this->classes_m->get_classes();

            if((int)$id) {
                $fetchClass = pluck($this->retdata['classes'], 'classesID', 'classesID');
                if(isset($fetchClass[$id])) {
                    $this->retdata['students'] = $this->studentrelation_m->get_order_by_student(array('srclassesID' => $id, 'srschoolyearID' => $schoolyearID));
                    if(count($this->retdata['students'])) {
                        $sections = $this->section_m->general_get_order_by_section(array("classesID" => $id));
                        $this->retdata['sections'] = $sections;
                        if(count($sections)) {
                            foreach ($sections as $key => $section) {
                                $this->retdata['allsection'][$section->sectionID] = $this->studentrelation_m->get_order_by_student(array('srclassesID' => $id, "srsectionID" => $section->sectionID, 'srschoolyearID' => $schoolyearID));
                            }
                        }
                    } else {
                        $this->retdata['students'] = [];
                    }
                } else {
                    $this->retdata['students'] = [];
                }
            } else {
                $this->retdata['students'] = [];
            }

            $this->response([
                'status'    => true,
                'message'   => 'Success',
                'data'      => $this->retdata
            ], REST_Controller::HTTP_OK);
        }
    }

    public function view_get($studentID = null, $classID = null) 
    {
        $schoolyearID = $this->session->userdata('defaultschoolyearID');
        if((int)$studentID && (int)$classID) {
            $schoolyearID = $this->session->userdata('defaultschoolyearID');
            $student = $this->studentrelation_m->get_single_student(array('srstudentID' => $studentID, 'srclassesID' => $classID, 'srschoolyearID' => $schoolyearID));
            if(count($student)) {
                $fetchClass = pluck($this->classes_m->get_classes(), 'classesID', 'classesID');
                if(isset($fetchClass[$classID])) {
                    $this->getView($studentID, $classID);
                } else {
                    $this->retdata['classesID']        = $classID;
                    $this->retdata['profile']          = [];
                    $this->retdata['usertype']         = [];
                    $this->retdata['class']            = [];
                    $this->retdata['section']          = [];
                    $this->retdata['classesID']        = $url;
                    $this->retdata["exams"]            = [];
                    $this->retdata["grades"]           = [];
                    $this->retdata['markpercentages']  = [];
                    $this->retdata["highestmarks"]     = [];
                    $this->retdata["settingmarktypeID"]  = 0;
                    $this->retdata["optionalsubjectArr"] = [];
                    $this->retdata["marksettings"]       = [];


                    $this->response([
                        'status'    => false,
                        'message'   => 'Error 404',
                        'data'      => $this->retdata,
                    ], REST_Controller::HTTP_NOT_FOUND);
                }
            } else {
                $this->retdata['classesID']        = $classID;
                $this->retdata['profile']          = [];
                $this->retdata['usertype']         = [];
                $this->retdata['class']            = [];
                $this->retdata['section']          = [];
                $this->retdata['classesID']        = $url;
                $this->retdata["exams"]            = [];
                $this->retdata["grades"]           = [];
                $this->retdata['markpercentages']  = [];
                $this->retdata["highestmarks"]     = [];
                $this->retdata["settingmarktypeID"]  = 0;
                $this->retdata["optionalsubjectArr"] = [];
                $this->retdata["marksettings"]       = [];

                $this->response([
                    'status'    => false,
                    'message'   => 'Error 404',
                    'data'      => $this->retdata,
                ], REST_Controller::HTTP_NOT_FOUND);
            }
        } else {
            $this->retdata['classesID']        = $classID;
            $this->retdata['profile']          = [];
            $this->retdata['usertype']         = [];
            $this->retdata['class']            = [];
            $this->retdata['section']          = [];
            $this->retdata['classesID']        = $url;
            $this->retdata["exams"]            = [];
            $this->retdata["grades"]           = [];
            $this->retdata['markpercentages']  = [];
            $this->retdata["highestmarks"]     = [];
            $this->retdata["settingmarktypeID"]  = 0;
            $this->retdata["optionalsubjectArr"] = [];
            $this->retdata["marksettings"]       = [];

            $this->response([
                'status'    => false,
                'message'   => 'Error 404',
                'data'      => $this->retdata,
            ], REST_Controller::HTTP_NOT_FOUND);
        }
    }

    private function getView($id, $url) 
    {
        $schoolyearID = $this->session->userdata('defaultschoolyearID');
        if((int)$id && (int)$url) {
            $schoolyearID = $this->session->userdata('defaultschoolyearID');
            $studentInfo = $this->studentrelation_m->get_single_student(array('srstudentID' => $id, 'srclassesID' => $url, 'srschoolyearID' => $schoolyearID));


            if(count($studentInfo)) {
                $this->pluckInfo();
                $this->basicInfo($studentInfo);
                $this->markInfo($studentInfo);
            } else {
                $this->retdata['classesID']        = 0;
                $this->retdata['profile']          = [];
                $this->retdata['usertype']         = [];
                $this->retdata['class']            = [];
                $this->retdata['section']          = [];
                $this->retdata['classesID']        = $url;
                $this->retdata["exams"]            = [];
                $this->retdata["grades"]           = [];
                $this->retdata['markpercentages']  = [];
                $this->retdata["highestmarks"]     = [];
                $this->retdata["settingmarktypeID"]  = 0;
                $this->retdata["optionalsubjectArr"] = [];
                $this->retdata["marksettings"]       = [];
            }

            $this->response([
                'status'    => true,
                'message'   => 'Success',
                'data'      => $this->retdata
            ], REST_Controller::HTTP_OK);
        }
    }

    private function pluckInfo() 
    {
        $this->retdata['subjects'] = pluck($this->subject_m->general_get_subject(), 'subject', 'subjectID');
    }

    private function basicInfo($studentInfo) 
    {
        if(count($studentInfo)) {
            $this->retdata['profile']  = $studentInfo;
            $this->retdata['usertype'] = $this->usertype_m->get_single_usertype(array('usertypeID' => $studentInfo->usertypeID));
            $this->retdata['class']    = $this->classes_m->get_single_classes(array('classesID' => $studentInfo->srclassesID));
            $this->retdata['section']  = $this->section_m->general_get_single_section(array('sectionID' => $studentInfo->srsectionID));

            $optionalsubject = null;
            if($studentInfo->sroptionalsubjectID > 0) {
                $optionalsubject = $this->subject_m->general_get_single_subject(array('type' => 0, 'classesID' => $studentInfo->srclassesID, 'subjectID' => $studentInfo->sroptionalsubjectID));
            }
            $this->retdata['optionalsubject'] = $optionalsubject;
        } else {
            $this->retdata['profile']           = [];
            $this->retdata['usertype']          = [];
            $this->retdata['class']             = [];
            $this->retdata['section']           = [];
            $this->retdata['optionalsubject']   = null;
        }
    }

    private function markInfo($studentInfo) 
    {
        if(count($studentInfo)) {
            $this->getMark($studentInfo->studentID, $studentInfo->srclassesID);
        } else {
            $this->retdata['classesID']        = 0;
            $this->retdata["exams"]            = [];
            $this->retdata["grades"]           = [];
            $this->retdata['markpercentages']  = [];
            $this->retdata["highestmarks"]     = [];
            $this->retdata["section"]          = [];
            $this->retdata["settingmarktypeID"]  = 0;
            $this->retdata["optionalsubjectArr"] = [];
            $this->retdata["marksettings"]       = [];
        }
    }

    private function getMark($studentID, $classesID) {
        if((int)$studentID && (int)$classesID) {
            $schoolyearID = $this->session->userdata('defaultschoolyearID');
            $student      = $this->studentrelation_m->get_single_student(array('srstudentID' => $studentID, 'srclassesID' => $classesID, 'srschoolyearID' => $schoolyearID));
            $classes      = $this->classes_m->get_single_classes(array('classesID' => $classesID));

            if(count($student) && count($classes)) {
                $queryArray = [
                    'classesID'    => $student->srclassesID,
                    'sectionID'    => $student->srsectionID,
                    'studentID'    => $student->srstudentID, 
                    'schoolyearID' => $schoolyearID, 
                ];

                $exams             = pluck($this->exam_m->get_exam(), 'exam', 'examID');
                $grades            = $this->grade_m->get_grade();
                $marks             = $this->mark_m->student_all_mark_array($queryArray);
                $markpercentages   = $this->markpercentage_m->get_markpercentage();

                $subjects          = $this->subject_m->general_get_order_by_subject(array('classesID' => $classesID));
                $subjectArr        = [];
                $optionalsubjectArr= [];
                if(count($subjects)) {
                    foreach ($subjects as $subject) {
                        if($subject->type == 0) {
                            $optionalsubjectArr[$subject->subjectID] = $subject->subjectID;
                        }
                        $subjectArr[$subject->subjectID] = $subject;
                    }
                }

                $retMark = [];
                if(count($marks)) {
                    foreach ($marks as $mark) {
                        if(isset($optionalsubjectArr[$mark->subjectID]) && ($mark->subjectID != $student->sroptionalsubjectID)) {
                            continue;
                        }
                        $retMark[$mark->examID][$mark->subjectID][$mark->markpercentageID] = $mark->mark;
                    }
                }

                $allStudentMarks = $this->mark_m->student_all_mark_array(array('classesID' => $classesID, 'schoolyearID' => $schoolyearID));
                $highestMarks    = [];
                foreach ($allStudentMarks as $value) {
                    if(!isset($highestMarks[$value->examID][$value->subjectID][$value->markpercentageID])) {
                        $highestMarks[$value->examID][$value->subjectID][$value->markpercentageID] = -1;
                    }
                    $highestMarks[$value->examID][$value->subjectID][$value->markpercentageID] = max($value->mark, $highestMarks[$value->examID][$value->subjectID][$value->markpercentageID]);
                }
                $marksettings  = $this->marksetting_m->get_marksetting_markpercentages();

                $this->retdata['settingmarktypeID'] = $this->data['siteinfos']->marktypeID;
                $this->retdata['subjects']          = $subjectArr;
                $this->retdata['exams']             = $exams;
                $this->retdata['grades']            = $grades;
                $this->retdata['markpercentages']   = pluck($markpercentages, 'obj', 'markpercentageID');
                $this->retdata['optionalsubjectArr']= $optionalsubjectArr;
                $this->retdata['marks']             = $retMark;
                $this->retdata['highestmarks']      = $highestMarks;
                $this->retdata['marksettings']      = isset($marksettings[$classesID]) ? $marksettings[$classesID] : [];
            } else {
                $this->retdata['settingmarktypeID'] = 0;
                $this->retdata['subjects']          = [];
                $this->retdata['exams']             = [];
                $this->retdata['grades']            = [];
                $this->retdata['markpercentages']   = [];
                $this->retdata['optionalsubjectArr']= [];
                $this->retdata['marks']             = [];
                $this->retdata['highestmarks']      = [];
                $this->retdata['marksettings']      = [];
            }
        } else {
            $this->retdata['settingmarktypeID'] = 0;
            $this->retdata['subjects']          = [];
            $this->retdata['exams']             = [];
            $this->retdata['grades']            = [];
            $this->retdata['markpercentages']   = [];
            $this->retdata['optionalsubjectArr']= [];
            $this->retdata['marks']             = [];
            $this->retdata['highestmarks']      = [];
            $this->retdata['marksettings']      = [];
        }
    }

}
