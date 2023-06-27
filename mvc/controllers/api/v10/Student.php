<?php
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

class Student extends Api_Controller 
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('classes_m');
        $this->load->model('subject_m');
        $this->load->model('teacher_m');
        $this->load->model('feetypes_m');
        $this->load->model('section_m');
        $this->load->model('parents_m');
        $this->load->model('routine_m');
        $this->load->model('exam_m');
        $this->load->model('grade_m');
        $this->load->model('mark_m');
        $this->load->model('invoice_m');
        $this->load->model('payment_m');
        $this->load->model('document_m');
        $this->load->model('weaverandfine_m');
        $this->load->model('markpercentage_m');
        $this->load->model('sattendance_m');
        $this->load->model('subjectattendance_m');
        $this->load->model('studentgroup_m');
        $this->load->model('studentrelation_m');
        $this->load->model('leaveapplication_m');
        $this->load->model('marksetting_m');

        $this->lang->load('student', $this->data['language']);
    }

    public function index_get($id = null)
    {
        $myProfile = false;
        $schoolyearID = $this->session->userdata('defaultschoolyearID');
        if($this->session->userdata('usertypeID') == 3) {
            $id = $this->data['myclass'];
            if(!permissionChecker('student_view')) {
                $myProfile = true;
            }
        }

        if($this->session->userdata('usertypeID') == 3 && $myProfile) {
            $url = $id;
            $id = $this->session->userdata('loginuserID');
            $this->getView($id, $url);
        } else {
            $this->data['classesID'] = $id;
            $this->retdata['classes'] = $this->classes_m->get_classes();

            if((int)$id) {
                $this->retdata['students'] = $this->studentrelation_m->get_order_by_student(array('srclassesID' => $id, 'srschoolyearID' => $schoolyearID));
                if(count($this->retdata['students'])) {
                    $sections = $this->section_m->general_get_order_by_section(array("classesID" => $id));
                    foreach ($sections as $key => $section) {
                        $this->retdata['allsection'][$section->sectionID] = $this->studentrelation_m->get_order_by_student(array('srclassesID' => $id, "srsectionID" => $section->sectionID, 'srschoolyearID' => $schoolyearID));
                    }
                } else {
                    $this->retdata['students'] = [];
                }
            } else {
                $this->retdata['students'] = [];
            }
        }

        $this->response([
            'status'    => true,
            'message'   => 'Success',
            'data'      => $this->retdata
        ], REST_Controller::HTTP_OK);
    }

    public function view_get($id = 0, $url = 0) {
        $this->getView($id, $url);
    }

    private function getView($id, $url)
    {
        $schoolyearID = $this->session->userdata('defaultschoolyearID');
        $fetchClasses = pluck($this->classes_m->get_classes(), 'classesID', 'classesID');
        if(isset($fetchClasses[$url])) {
            if((int)$id && (int)$url) {
                $studentInfo = $this->studentrelation_m->get_single_student(array('srstudentID' => $id, 'srclassesID' => $url, 'srschoolyearID' => $schoolyearID), TRUE);
                $this->pluckInfo();
                $this->basicInfo($studentInfo);
                $this->parentInfo($studentInfo);
                $this->routineInfo($studentInfo);
                $this->attendanceInfo($studentInfo);
                $this->markInfo($studentInfo);
                $this->invoiceInfo($studentInfo);
                $this->paymentInfo($studentInfo);
                $this->documentInfo($studentInfo);

                if(count($studentInfo)) {
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
        } else {
            $this->response([
                'status' => false,
                'message' => 'Error 404',
                'data' => []
            ], REST_Controller::HTTP_NOT_FOUND);
        }
    }

    private function allPaymentByInvoice($payments) 
    {
        $retPaymentArr = [];
        if($payments) {
            foreach ($payments as $payment) {
                if(isset($retPaymentArr[$payment->invoiceID])) {
                    $retPaymentArr[$payment->invoiceID] += $payment->paymentamount;
                } else {
                    $retPaymentArr[$payment->invoiceID] = $payment->paymentamount;                  
                }
            }
        }
        return $retPaymentArr;
    }

    private function allWeaverAndFineByInvoice($weaverandfines) 
    {
        $retWeaverAndFineArr = [];
        if($weaverandfines) {
            foreach ($weaverandfines as $weaverandfine) {
                if(isset($retWeaverAndFineArr[$weaverandfine->invoiceID]['weaver'])) {
                    $retWeaverAndFineArr[$weaverandfine->invoiceID]['weaver'] += $weaverandfine->weaver;
                } else {
                    $retWeaverAndFineArr[$weaverandfine->invoiceID]['weaver'] = $weaverandfine->weaver;                 
                }

                if(isset($retWeaverAndFineArr[$weaverandfine->invoiceID]['fine'])) {
                    $retWeaverAndFineArr[$weaverandfine->invoiceID]['fine'] += $weaverandfine->fine;
                } else {
                    $retWeaverAndFineArr[$weaverandfine->invoiceID]['fine'] = $weaverandfine->fine;                 
                }
            }
        }
        return $retWeaverAndFineArr;
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

                $grades            = $this->grade_m->get_grade();
                $exams             = pluck($this->exam_m->get_exam(), 'exam', 'examID');
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

    private function subjectattendance($id = null, $url = null)
    {
        $schoolyearID       = $this->session->userdata('defaultschoolyearID');

        $attendances        = $this->subjectattendance_m->get_order_by_sub_attendance(array("studentID" => $id, "classesID" => $url,'schoolyearID'=> $schoolyearID));
        $attendances        = pluck_multi_array_key($attendances, 'obj', 'subjectID', 'monthyear');
        $mandatorySubjects  = $this->subject_m->general_get_order_by_subject(array('type' => 1, 'classesID' => $url));
        $schoolYearMonths   = $this->schoolYearMonth($this->data['schoolyearsessionobj']);
        $holidays           = $this->getHolidaysSession();
        $weekends           = $this->getWeekendDaysSession();
        $leaves             = $this->leaveApplicationsDateListByUser($id, $schoolyearID);

        $student = $this->studentrelation_m->get_single_student(array('srstudentID' => $id, 'srclassesID' => $url, 'srschoolyearID' => $schoolyearID));
        if(count($student)) {
            if($student->sroptionalsubjectID > 0) {
                $optionalSubject = $this->subject_m->general_get_order_by_subject(array('type' => 0, 'classesID' => $url, 'subjectID' => $student->sroptionalsubjectID));
                if(count($optionalSubject)) {
                    $mandatorySubjects[] = (object) $optionalSubject[0];
                }
            }
        }

        $attendacneArray = [];
        if(count($mandatorySubjects)) {
            foreach ($mandatorySubjects as $mandatorySubject) {
                if(count($schoolYearMonths)) {
                    foreach ($schoolYearMonths as $schoolYearMonth) {
                        for ($i=1; $i <= 31; $i++) {
                            $d = sprintf('%02d',$i);
                            $date = $d."-".$schoolYearMonth;

                            if(!isset($totalDayCount[$mandatorySubject->subjectID]['totalpresent'])) {
                                $totalDayCount[$mandatorySubject->subjectID]['totalpresent'] = 0;
                            }

                            if(!isset($totalDayCount[$mandatorySubject->subjectID]['totallatewithexcuse'])) {
                                $totalDayCount[$mandatorySubject->subjectID]['totallatewithexcuse'] = 0;
                            }

                            if(!isset($totalDayCount[$mandatorySubject->subjectID]['totallate'])) {
                                $totalDayCount[$mandatorySubject->subjectID]['totallate'] = 0;
                            }

                            if(!isset($totalDayCount[$mandatorySubject->subjectID]['totalabsent'])) {
                                $totalDayCount[$mandatorySubject->subjectID]['totalabsent'] = 0;
                            }

                            if(in_array($date, $holidays)) {
                                $attendacneArray[$mandatorySubject->subjectID][$schoolYearMonth][$i] = 'H';
                                if(isset($totalDayCount[$mandatorySubject->subjectID]['totalholiday'])) {
                                    $totalDayCount[$mandatorySubject->subjectID]['totalholiday']++;
                                } else {
                                    $totalDayCount[$mandatorySubject->subjectID]['totalholiday'] = 1;
                                }
                            } elseif (in_array($date, $weekends)) {
                                $attendacneArray[$mandatorySubject->subjectID][$schoolYearMonth][$i] = 'W';
                                if(isset($totalDayCount[$mandatorySubject->subjectID]['totalweekend'])) {
                                    $totalDayCount[$mandatorySubject->subjectID]['totalweekend']++;
                                } else {
                                    $totalDayCount[$mandatorySubject->subjectID]['totalweekend'] = 1;
                                }
                            } elseif(in_array($date, $leaves)) {
                                $attendacneArray[$mandatorySubject->subjectID][$schoolYearMonth][$i] = 'LA';
                                if(isset($totalDayCount[$mandatorySubject->subjectID]['totalleave'])) {
                                    $totalDayCount[$mandatorySubject->subjectID]['totalleave']++;
                                } else {
                                    $totalDayCount[$mandatorySubject->subjectID]['totalleave'] = 1;
                                }
                            } else {
                                $a = 'a'.$i;
                                if(isset($attendances[$mandatorySubject->subjectID][$schoolYearMonth]) && $attendances[$mandatorySubject->subjectID][$schoolYearMonth]->$a != null) {
                                    $attendacneArray[$mandatorySubject->subjectID][$schoolYearMonth][$i] = $attendances[$mandatorySubject->subjectID][$schoolYearMonth]->$a;
                                    
                                    if($attendances[$mandatorySubject->subjectID][$schoolYearMonth]->$a == 'P') {
                                        $totalDayCount[$mandatorySubject->subjectID]['totalpresent']++;
                                    } elseif($attendances[$mandatorySubject->subjectID][$schoolYearMonth]->$a == 'LE') {
                                        $totalDayCount[$mandatorySubject->subjectID]['totallatewithexcuse']++;
                                    } elseif($attendances[$mandatorySubject->subjectID][$schoolYearMonth]->$a == 'L') {
                                        $totalDayCount[$mandatorySubject->subjectID]['totallate']++;
                                    } elseif($attendances[$mandatorySubject->subjectID][$schoolYearMonth]->$a == 'A') {
                                        $totalDayCount[$mandatorySubject->subjectID]['totalabsent']++;
                                    }
                                } else {
                                    $attendacneArray[$mandatorySubject->subjectID][$schoolYearMonth][$i] = 'N/A';
                                }
                            };
                        }
                    }
                }
            }
        }

        $retArray = ['attendance' => $attendacneArray, 'totalcount' => $totalDayCount, 'subjects' => $mandatorySubjects];
        return $retArray;
    }

    private function dayattendance($id = null, $url = null)
    {
        $schoolyearID       = $this->session->userdata('defaultschoolyearID');
        $attendances        = $this->sattendance_m->get_order_by_attendance(array("studentID" => $id, "classesID" => $url,'schoolyearID'=> $schoolyearID));
        $attendances        = pluck($attendances,'obj','monthyear');
        $schoolYearMonths   = $this->schoolYearMonth($this->data['schoolyearsessionobj']);
        $holidays           = $this->getHolidaysSession();
        $weekends           = $this->getWeekendDaysSession();
        $leaves             = $this->leaveApplicationsDateListByUser($id, $schoolyearID);

        $attendacneArray = [];
        if(count($schoolYearMonths)) {
            foreach ($schoolYearMonths as $schoolYearMonth) {
                for ($i=1; $i <= 31; $i++) {
                    $d = sprintf('%02d',$i);
                    $date = $d."-".$schoolYearMonth;

                    if(!isset($totalDayCount['totalpresent'])) {
                        $totalDayCount['totalpresent'] = 0;
                    }

                    if(!isset($totalDayCount['totallatewithexcuse'])) {
                        $totalDayCount['totallatewithexcuse'] = 0;
                    }

                    if(!isset($totalDayCount['totallate'])) {
                        $totalDayCount['totallate'] = 0;
                    }

                    if(!isset($totalDayCount['totalabsent'])) {
                        $totalDayCount['totalabsent'] = 0;
                    }

                    if(in_array($date, $holidays)) {
                        $attendacneArray[$schoolYearMonth][$i] = 'H';
                        if(isset($totalDayCount['totalholiday'])) {
                            $totalDayCount['totalholiday']++;
                        } else {
                            $totalDayCount['totalholiday'] = 1;
                        }
                    } elseif (in_array($date, $weekends)) {
                        $attendacneArray[$schoolYearMonth][$i] = 'W';
                        if(isset($totalDayCount['totalweekend'])) {
                            $totalDayCount['totalweekend']++;
                        } else {
                            $totalDayCount['totalweekend'] = 1;
                        }
                    } elseif(in_array($date, $leaves)) {
                        $attendacneArray[$schoolYearMonth][$i] = 'LA';
                        if(isset($totalDayCount['totalleave'])) {
                            $totalDayCount['totalleave']++;
                        } else {
                            $totalDayCount['totalleave'] = 1;
                        }
                    } else {
                        $a = 'a'.$i;
                        if(isset($attendances[$schoolYearMonth]) && $attendances[$schoolYearMonth]->$a != null) {
                            $attendacneArray[$schoolYearMonth][$i] = $attendances[$schoolYearMonth]->$a;
                            
                            if($attendances[$schoolYearMonth]->$a == 'P') {
                                $totalDayCount['totalpresent']++;
                            } elseif($attendances[$schoolYearMonth]->$a == 'LE') {
                                $totalDayCount['totallatewithexcuse']++;
                            } elseif($attendances[$schoolYearMonth]->$a == 'L') {
                                $totalDayCount['totallate']++;
                            } elseif($attendances[$schoolYearMonth]->$a == 'A') {
                                $totalDayCount['totalabsent']++;
                            }
                        } else {
                            $attendacneArray[$schoolYearMonth][$i] = 'N/A';
                        }
                    };
                }
            }
        }
        
        $retArray = ['attendance' => $attendacneArray, 'totalcount' => $totalDayCount];
        return $retArray;
    }

    private function schoolYearMonth($schoolYear, $keyExist = false)
    {
        $dateArray = [];
        $startDate    = (new DateTime($schoolYear->startingdate))->modify('first day of this month');
        $endDate      = (new DateTime($schoolYear->endingdate))->modify('last day of this month');
        $dateInterval = DateInterval::createFromDateString('1 month');
        $datePeriods   = new DatePeriod($startDate, $dateInterval, $endDate);
        
        if(count($datePeriods)) {
            foreach ($datePeriods as $datePeriod) {
                if($keyExist) {
                    $dateArray[] = ['monthkey' => $datePeriod->format("m").'-'.$datePeriod->format("Y"), 'monthname' => $datePeriod->format("M")];
                } else {
                    $dateArray[] = $datePeriod->format("m-Y");
                }
            }
        }
        return $dateArray;
    }

    private function leaveApplicationsDateListByUser($studentID, $schoolyearID) 
    {
        $leaveapplications = $this->leaveapplication_m->get_order_by_leaveapplication(array('create_userID' => $studentID, 'create_usertypeID' => 3, 'schoolyearID' => $schoolyearID, 'status' => 1));
        
        $retArray = [];
        if(count($leaveapplications)) {
            $oneday    = 60*60*24;
            foreach($leaveapplications as $leaveapplication) {
                for($i=strtotime($leaveapplication->from_date); $i<= strtotime($leaveapplication->to_date); $i= $i+$oneday) {
                    $retArray[] = date('d-m-Y', $i);
                }
            }
        }
        return $retArray;
    }

    private function pluckInfo() 
    {
        $this->retdata['subjects'] = pluck($this->subject_m->general_get_subject(), 'subject', 'subjectID');
        $this->retdata['teachers'] = pluck($this->teacher_m->get_teacher(), 'name', 'teacherID');
        $this->retdata['feetypes'] = pluck($this->feetypes_m->get_feetypes(), 'feetypes', 'feetypesID');
    }

    private function basicInfo($studentInfo) 
    {
        if(count($studentInfo)) {
            $this->retdata['profile'] = $studentInfo;
            $this->retdata['usertype'] = $this->usertype_m->get_single_usertype(array('usertypeID' => 3));
            $this->retdata['class'] = $this->classes_m->get_single_classes(array('classesID' => $studentInfo->srclassesID));
            $this->retdata['section'] = $this->section_m->general_get_single_section(array('sectionID' => $studentInfo->srsectionID));
            $this->retdata['group'] = $this->studentgroup_m->get_single_studentgroup(array('studentgroupID' => $studentInfo->srstudentgroupID));
            $this->retdata['optionalsubject'] = $this->subject_m->general_get_single_subject(array('subjectID' => $studentInfo->sroptionalsubjectID));
        } else {
            $this->retdata['profile'] = [];
        }
    }

    private function parentInfo($studentInfo) 
    {
        if(count($studentInfo)) {
            $this->retdata['parents'] = $this->parents_m->get_single_parents(array('parentsID' => $studentInfo->parentID));
        } else {
            $this->retdata['parents'] = [];
        }
    }

    private function weekend()
    {
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

    private function routineInfo($studentInfo) 
    {
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

        $weekend        = $this->weekend();

        if(count($studentInfo)) {
            $schoolyearID   = $this->session->userdata('defaultschoolyearID');
            $subject        = pluck(
                $this->subject_m->general_get_subject(),
                'obj',
                'subjectID'
            );
            
            $teacher        = pluck(
                $this->teacher_m->get_select_teacher(),
                'obj', 
                'teacherID'
            );
            
            $routines       = pluck_multi_array(
                $this->routine_m->get_order_by_routine(
                    array(
                        'classesID'     => $studentInfo->srclassesID, 
                        'sectionID'     =>$studentInfo->srsectionID, 
                        'schoolyearID'  => $schoolyearID
                    )
                ), 
                'obj', 
                'day'
            );

            $routineArray = [];
            foreach ($days as $dayKey => $day) {
                if(isset($routines[$dayKey]) && !isset($weekend[$dayKey])) {
                    foreach ($routines[$dayKey] as $routine) {
                        $subjectName    = 'None';
                        $teacherName    = 'None';

                        if(isset($subject[$routine->subjectID])) {
                            $subjectName = $subject[$routine->subjectID]->subject;
                        }

                        if(isset($teacher[$routine->teacherID])) {
                            $teacherName = $teacher[$routine->teacherID]->name;
                        }

                        $routineArray[$dayKey][] = ['time' => $routine->start_time.'-'.$routine->end_time, 'subject' => $subjectName, 'teacher' => $teacherName, 'room' => $routine->room];
                    }
                } elseif (isset($weekend[$dayKey])) {
                    $routineArray[$dayKey] = 'Weekend';
                } else {
                    $routineArray[$dayKey] = null;
                }
            }

            $this->retdata['routine'] = $routineArray;
        } else {
            $routineArray = [];
            foreach ($days as $dayKey => $day) {
                $routineArray[$dayKey] = null;
            }
            $this->retdata['routines'] = $routineArray;
        }
    }

    private function attendanceInfo($studentInfo) 
    {
        if(count($studentInfo)) {
            $id     = $studentInfo->srstudentID;
            $url    = $studentInfo->srclassesID;
            $this->retdata['attendanceType'] = 'day'; 
            if($this->data['siteinfos']->attendance == "subject") {
                $this->retdata['attendanceType'] = 'subject';
            }
            $this->retdata['attendancesmonths'] = $this->schoolYearMonth($this->data['schoolyearsessionobj'], true);
            if($this->data['siteinfos']->attendance == "subject") {
                $attendance = $this->subjectattendance($id, $url);
                $this->retdata['attendance'] = $attendance['attendance'];
                $this->retdata['totalcount'] = $attendance['totalcount'];
                $this->retdata['attendancesubjects'] = $attendance['subjects'];
            } else {
                $attendance = $this->dayattendance($id, $url);
                $this->retdata['attendance'] = $attendance['attendance'];
                $this->retdata['totalcount'] = $attendance['totalcount'];
            }
        } else {
            $this->retdata['attendance'] = [];
            $this->retdata['totalcount'] = [];
            $this->retdata['attendancesubjects'] = [];
        }
    }

    private function markInfo($studentInfo) 
    {
        if(count($studentInfo)) {
            $this->getMark($studentInfo->srstudentID, $studentInfo->srclassesID);
        } else {
            $this->retdata["exams"]            = [];
            $this->retdata["grades"]           = [];
            $this->retdata['markpercentages']  = [];
            $this->retdata['validExam']        = [];
            $this->retdata['separatedMarks']   = [];
            $this->retdata["highestMarks"]     = [];
            $this->retdata["section"]          = [];
        }
    }

    private function invoiceInfo($studentInfo) 
    {
        $schoolyearID = $this->session->userdata('defaultschoolyearID');
        if(count($studentInfo)) {
            $this->retdata['invoices'] = $this->invoice_m->get_order_by_invoice(array('schoolyearID' => $schoolyearID, 'studentID' => $studentInfo->srstudentID, 'classesID' => $studentInfo->srclassesID,'deleted_at' => 1));

            $payments = $this->payment_m->get_order_by_payment(array('schoolyearID' => $schoolyearID, 'studentID' => $studentInfo->srstudentID));
            $weaverandfines = $this->weaverandfine_m->get_order_by_weaverandfine(array('schoolyearID' => $schoolyearID, 'studentID' => $studentInfo->srstudentID));

            $this->retdata['allpaymentbyinvoice'] = $this->allPaymentByInvoice($payments);
            $this->retdata['allweaverandpaymentbyinvoice'] = $this->allWeaverAndFineByInvoice($weaverandfines);
        } else {
            $this->retdata['invoices'] = [];
            $this->retdata['allpaymentbyinvoice'] = [];
            $this->retdata['allweaverandpaymentbyinvoice'] = [];
        }
    }

    private function paymentInfo($studentInfo) 
    {
        $schoolyearID = $this->session->userdata('defaultschoolyearID');
        if(count($studentInfo)) {
            $this->retdata['payments'] = $this->payment_m->get_payment_with_studentrelation_by_studentID_and_schoolyearID($studentInfo->srstudentID, $schoolyearID);
        } else {
            $this->retdata['payments'] = [];
        }
    }

    private function documentInfo($studentInfo) 
    {
        if(count($studentInfo)) {
            $this->retdata['documents'] = $this->document_m->get_order_by_document(array('usertypeID' => 3, 'userID' => $studentInfo->srstudentID));
        } else {
            $this->retdata['documents'] = [];
        }
    }
}
