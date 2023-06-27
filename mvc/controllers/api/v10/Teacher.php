<?php
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

class Teacher extends Api_Controller 
{
    public function __construct() 
    {
        parent::__construct();
        $this->load->model('teacher_m');
        $this->load->model('subject_m');
        $this->load->model('classes_m');
        $this->load->model('section_m');
        $this->load->model('routine_m');
        $this->load->model('tattendance_m');
        $this->load->model('manage_salary_m');
        $this->load->model('salary_template_m');
        $this->load->model('salaryoption_m');
        $this->load->model('hourly_template_m');
        $this->load->model('make_payment_m');
        $this->load->model('document_m');
        $this->load->model('leaveapplication_m');

        $this->lang->load('teacher', $this->data['language']);
    }

    public function index_get() 
    {
        $myProfile = false;
        if($this->session->userdata('usertypeID') == 2) {
            if(!permissionChecker('teacher_view')) {
                $myProfile = true;
            }
        }

        if($this->session->userdata('usertypeID') == 2 && $myProfile) {
            $teacherID = $this->session->userdata('loginuserID');
            $this->getView($teacherID);
        } else {
            $teachers = $this->teacher_m->get_teacher();
            if(count($teachers)) {
                $this->retdata['teachers'] = $teachers;
            } else {
                $this->retdata['teachers'] = [];
            }
        }

        $retArray['status']     = true;
        $retArray['message']    = 'Success'; 
        $retArray['data']       = $this->retdata;
        $this->response($retArray, REST_Controller::HTTP_OK);
    }

    public function view_get($teacherID = 0) 
    {
        $this->getView($teacherID);
    }

    private function getView($teacherID) 
    {
        if((int)$teacherID) {
            $teacherInfo = $this->teacher_m->get_single_teacher(array('teacherID' => $teacherID));
            $this->pluckInfo();
            $this->teacherInfo($teacherInfo);
            $this->routineInfo($teacherInfo);
            $this->attendanceInfo($teacherInfo);
            $this->salaryInfo($teacherInfo);
            $this->paymentInfo($teacherInfo);
            $this->documentInfo($teacherInfo);

            if(count($teacherInfo)) {
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
    
    private function pluckInfo() 
    {
        $this->retdata['subjects'] = pluck($this->subject_m->general_get_subject(), 'subject', 'subjectID');
        $this->retdata['classess'] = pluck($this->classes_m->get_classes(), 'classes', 'classesID');
        $this->retdata['sections'] = pluck($this->section_m->get_section(), 'section', 'sectionID');
    }

    private function teacherInfo($teacherInfo) 
    {
        if(count($teacherInfo)) {
            $this->retdata['profile'] = $teacherInfo;
        } else {
            $this->retdata['profile'] = [];
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

    private function routineInfo($teacherInfo) 
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
        if(count($teacherInfo)) {
            $schoolyearID   = $this->session->userdata('defaultschoolyearID');
            $subject        = pluck($this->subject_m->general_get_subject(), 'obj', 'subjectID');
            $classes        = pluck($this->classes_m->general_get_classes(), 'obj', 'classesID');
            $section        = pluck($this->section_m->general_get_section(), 'obj', 'sectionID');
            $routines       = pluck_multi_array($this->routine_m->get_order_by_routine(array('teacherID'=>$teacherInfo->teacherID, 'schoolyearID'=> $schoolyearID)), 'obj', 'day');

            $routineArray = [];
            foreach ($days as $dayKey => $day) {
                if(isset($routines[$dayKey]) && !isset($weekend[$dayKey])) {
                    foreach ($routines[$dayKey] as $routine) {
                        $subjectName    = 'None';
                        $className      = 'None';
                        $sectionName    = 'None';

                        if(isset($subject[$routine->subjectID])) {
                            $subjectName = $subject[$routine->subjectID]->subject;
                        }

                        if(isset($classes[$routine->classesID])) {
                            $className = $classes[$routine->classesID]->classes;
                        }

                        if(isset($section[$routine->sectionID])) {
                            $sectionName = $section[$routine->sectionID]->section;
                        }

                        $routineArray[$dayKey][] = ['time' => $routine->start_time.'-'.$routine->end_time, 'subject' => $subjectName, 'classes' => $className, 'section' => $sectionName, 'room' => $routine->room];
                    }
                } elseif (isset($weekend[$dayKey])) {
                    $routineArray[$dayKey] = 'Weekend';
                } else {
                    $routineArray[$dayKey] = null;
                }
            }

            $this->retdata['routines'] = $routineArray;
        } else {
            $routineArray = [];
            foreach ($days as $dayKey => $day) {
                $routineArray[$dayKey] = null;
            }
            $this->retdata['routines'] = $routineArray;
        }
    }

    private function dayattendance($id = null)
    {
        $schoolyearID       = $this->session->userdata('defaultschoolyearID');
        $attendances        = $this->tattendance_m->get_order_by_tattendance(array("teacherID" => $id, 'schoolyearID' => $schoolyearID));
        $attendances        = pluck($attendances,'obj','monthyear');
        $schoolYearMonths   = $this->schoolYearMonth($this->data['schoolyearsessionobj']);
        $holidays           = $this->getHolidaysSession();
        $weekends           = $this->getWeekendDaysSession();
        $leaves             = $this->leaveApplicationsDateListByUser($id, $schoolyearID);

        $attendacneArray = [];
        $totalDayCount   = [];
        if(count($schoolYearMonths)) {
            foreach ($schoolYearMonths as $schoolYearMonth) {
                for ($i=1; $i <= 31; $i++) {
                    $d = sprintf('%02d',$i);
                    $date = $d."-".$schoolYearMonth;

                    if(!isset($totalDayCount['totalholiday'])) {
                        $totalDayCount['totalholiday'] = 0;
                    }

                    if(!isset($totalDayCount['totalweekend'])) {
                        $totalDayCount['totalweekend'] = 0;
                    }

                    if(!isset($totalDayCount['totalleave'])) {
                        $totalDayCount['totalleave'] = 0;
                    }

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
                        $totalDayCount['totalholiday']++;
                    } elseif (in_array($date, $weekends)) {
                        $attendacneArray[$schoolYearMonth][$i] = 'W';
                        $totalDayCount['totalweekend']++;
                    } elseif(in_array($date, $leaves)) {
                        $attendacneArray[$schoolYearMonth][$i] = 'LA';
                        $totalDayCount['totalleave']++;
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

    private function leaveApplicationsDateListByUser($teacherID, $schoolyearID) 
    {
        $leaveapplications = $this->leaveapplication_m->get_order_by_leaveapplication(array('create_userID'=>$teacherID,'create_usertypeID' => 2 ,'schoolyearID' => $schoolyearID, 'status' => 1));
        
        $retArray = [];
        if(count($leaveapplications)) {
            $oneday    = 60*60*24;
            foreach($leaveapplications as $leaveapplication) {
                for($i = strtotime($leaveapplication->from_date); $i <= strtotime($leaveapplication->to_date); $i = ($i+$oneday)) {
                    $retArray[] = date('d-m-Y', $i);
                }
            }
        }
        return $retArray;
    }

    private function attendanceInfo($teacherInfo) 
    {
        if(count($teacherInfo)) {
            $id         = $teacherInfo->teacherID;
            $attendance = $this->dayattendance($id);
            $this->retdata['attendancesmonths'] = $this->schoolYearMonth($this->data['schoolyearsessionobj'], true);
            $this->retdata['attendance'] = $attendance['attendance'];
            $this->retdata['totalcount'] = $attendance['totalcount'];
        } else {
            $this->retdata['attendance'] = [];
            $this->retdata['totalcount'] = [];
        }
    }

    private function salaryInfo($teacherInfo) 
    {
        if(count($teacherInfo)) {
            $manageSalary = $this->manage_salary_m->get_single_manage_salary(array('usertypeID' => $teacherInfo->usertypeID, 'userID' => $teacherInfo->teacherID));
            if(count($manageSalary)) {
                $this->retdata['manage_salary'] = $manageSalary;
                if($manageSalary->salary == 1) {
                    $this->retdata['salary_template'] = $this->salary_template_m->get_single_salary_template(array('salary_templateID' => $manageSalary->template));
                    if($this->retdata['salary_template']) {
                        $this->db->order_by("salary_optionID", "asc");
                        $this->retdata['salaryoptions'] = $this->salaryoption_m->get_order_by_salaryoption(array('salary_templateID' => $manageSalary->template));

                        $grosssalary = 0;
                        $totaldeduction = 0;
                        $netsalary = $this->retdata['salary_template']->basic_salary;
                        $orginalNetsalary = $this->retdata['salary_template']->basic_salary;
                        $grosssalarylist = [];
                        $totaldeductionlist = [];

                        if(count($this->retdata['salaryoptions'])) {
                            foreach ($this->retdata['salaryoptions'] as $salaryOptionKey => $salaryOption) {
                                if($salaryOption->option_type == 1) {
                                    $netsalary += $salaryOption->label_amount;
                                    $grosssalary += $salaryOption->label_amount;
                                    $grosssalarylist[$salaryOption->label_name] = $salaryOption->label_amount;
                                } elseif($salaryOption->option_type == 2) {
                                    $netsalary -= $salaryOption->label_amount;
                                    $totaldeduction += $salaryOption->label_amount;
                                    $totaldeductionlist[$salaryOption->label_name] = $salaryOption->label_amount;
                                }
                            }
                        }

                        $this->retdata['grosssalary'] = ($orginalNetsalary+$grosssalary);
                        $this->retdata['totaldeduction'] = $totaldeduction;
                        $this->retdata['netsalary'] = $netsalary;
                    } else {
                        $this->retdata['salary_template'] = [];
                        $this->retdata['salaryoptions'] = [];
                        $this->retdata['grosssalary'] = 0;
                        $this->retdata['totaldeduction'] = 0;
                        $this->retdata['netsalary'] = 0;
                    }
                } elseif($manageSalary->salary == 2) {
                    $this->retdata['hourly_salary'] = $this->hourly_template_m->get_single_hourly_template(array('hourly_templateID'=> $manageSalary->template));
                    if(count($this->retdata['hourly_salary'])) {
                        $this->retdata['grosssalary'] = 0;
                        $this->retdata['totaldeduction'] = 0;
                        $this->retdata['netsalary'] = $this->retdata['hourly_salary']->hourly_rate;
                    } else {
                        $this->retdata['hourly_salary'] = [];
                        $this->retdata['grosssalary'] = 0;
                        $this->retdata['totaldeduction'] = 0;
                        $this->retdata['netsalary'] = 0;
                    }
                }
            } else {
                $this->retdata['manage_salary'] = [];
                $this->retdata['salary_template'] = [];
                $this->retdata['salaryoptions'] = [];
                $this->retdata['hourly_salary'] = [];
                $this->retdata['grosssalary'] = 0;
                $this->retdata['totaldeduction'] = 0;
                $this->retdata['netsalary'] = 0;
            }
        } else {
            $this->retdata['manage_salary'] = [];
            $this->retdata['salary_template'] = [];
            $this->retdata['salaryoptions'] = [];
            $this->retdata['hourly_salary'] = [];
            $this->retdata['grosssalary'] = 0;
            $this->retdata['totaldeduction'] = 0;
            $this->retdata['netsalary'] = 0;
        }
    }

    private function paymentInfo($teacherInfo) 
    {
        $schoolyearID = $this->session->userdata('defaultschoolyearID');
        if(count($teacherInfo)) {
            $this->retdata['make_payments'] = $this->make_payment_m->get_order_by_make_payment(array('usertypeID' => $teacherInfo->usertypeID, 'userID' => $teacherInfo->teacherID, 'schoolyearID' => $schoolyearID));
        } else {
            $this->retdata['make_payments'] = [];
        }
    }

    private function documentInfo($teacherInfo) 
    {
        if(count($teacherInfo)) {
            $this->retdata['documents'] = $this->document_m->get_order_by_document(array('usertypeID' => 2, 'userID' => $teacherInfo->teacherID));
        } else {
            $this->retdata['documents'] = [];
        }
    }
}
