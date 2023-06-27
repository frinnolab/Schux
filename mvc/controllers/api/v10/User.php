<?php
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends Api_Controller 
{
    public function __construct() 
    {
        parent::__construct();
        $this->load->model('user_m');
        $this->load->model('usertype_m');
        $this->load->model('uattendance_m');
        $this->load->model('manage_salary_m');
        $this->load->model('salary_template_m');
        $this->load->model('salaryoption_m');
        $this->load->model('hourly_template_m');
        $this->load->model('make_payment_m');
        $this->load->model('document_m');
        $this->load->model('leaveapplication_m');
    }

    public function index_get() 
    {
        $usertype = pluck($this->usertype_m->get_usertype(), 'obj', 'usertypeID');
        unset($usertype[1], $usertype[2], $usertype[3], $usertype[4]);
        
        $myProfile = false;
        if(isset($usertype[$this->session->userdata('usertypeID')])) {
            if(!permissionChecker('user_view')) {
                $myProfile = true;
            }
        }

        if(isset($usertype[$this->session->userdata('usertypeID')]) && $myProfile) {
            $userID = $this->session->userdata('loginuserID');
            $this->getView($userID);
        } else {
            $users = $this->user_m->get_user_by_usertype();
            if(count($users)) {
                $this->retdata['users'] = $users;
            } else {
                $this->retdata['users'] = [];
            }
        }

        $this->response([
            'status'    => true,
            'message'   => 'Success',
            'data'      => $this->retdata
        ], REST_Controller::HTTP_OK);
    }

    public function view_get($userID = 0) 
    {
        $this->getView($userID);
    }

    private function getView($userID) 
    {
        if((int)$userID) {
            $userInfo = $this->user_m->get_user_by_usertype($userID);
            $this->pluckInfo();
            $this->basicInfo($userInfo);
            $this->attendanceInfo($userInfo);
            $this->salaryInfo($userInfo);
            $this->paymentInfo($userInfo);
            $this->documentInfo($userInfo);

            if(count($userInfo)) {
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
        $this->retdata['usertypes'] = pluck($this->usertype_m->get_usertype(), 'usertype', 'usertypeID' );
    }

    private function basicInfo($userInfo) 
    {
        if(count($userInfo)) {
            $this->retdata['profile'] = $userInfo;
        } else {
            $this->retdata['profile'] = [];
        }
    }

    private function dayattendance($id = null, $usertypeID = null)
    {
        $schoolyearID       = $this->session->userdata('defaultschoolyearID');
        $attendances       = $this->uattendance_m->get_order_by_uattendance(array("userID" => $id, 'schoolyearID' => $schoolyearID));
        $attendances        = pluck($attendances,'obj','monthyear');
        $schoolYearMonths   = $this->schoolYearMonth($this->data['schoolyearsessionobj']);
        $holidays           = $this->getHolidaysSession();
        $weekends           = $this->getWeekendDaysSession();
        $leaves             = $this->leaveApplicationsDateListByUser($id, $schoolyearID, $usertypeID);

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

    private function leaveApplicationsDateListByUser($studentID, $schoolyearID, $usertypeID) 
    {
        $leaveapplications = $this->leaveapplication_m->get_order_by_leaveapplication(array('create_userID'=>$studentID,'create_usertypeID' => $usertypeID ,'schoolyearID' => $schoolyearID, 'status' => 1));
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

    public function attendanceInfo($userInfo) 
    {
        if(count($userInfo)) {
            $userID         = $userInfo->userID;
            $usertypeID     = $userInfo->usertypeID;
            $attendance = $this->dayattendance($userID, $usertypeID);
            $this->retdata['attendancesmonths'] = $this->schoolYearMonth($this->data['schoolyearsessionobj'], true);
            $this->retdata['attendance'] = $attendance['attendance'];
            $this->retdata['totalcount'] = $attendance['totalcount'];
        } else {
            $this->retdata['attendance'] = [];
            $this->retdata['totalcount'] = [];
        }
    }

    private function salaryInfo($userInfo) 
    {
        if(count($userInfo)) {
            $manageSalary = $this->manage_salary_m->get_single_manage_salary(array('usertypeID' => $userInfo->usertypeID, 'userID' => $userInfo->userID));
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
                        $grosssalarylist = array();
                        $totaldeductionlist = array();

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

    private function paymentInfo($userInfo) 
    {
        $schoolyearID = $this->session->userdata('defaultschoolyearID');
        if(count($userInfo)) {
            $this->retdata['make_payments'] = $this->make_payment_m->get_order_by_make_payment(array('usertypeID' => $userInfo->usertypeID, 'userID' => $userInfo->userID, 'schoolyearID' => $schoolyearID));
        } else {
            $this->retdata['make_payments'] = [];
        }
    }

    private function documentInfo($userInfo) 
    {
        if(count($userInfo)) {
            $this->retdata['documents'] = $this->document_m->get_order_by_document(array('userID' => $userInfo->userID,'usertypeID' => $userInfo->usertypeID));
        } else {
            $this->retdata['documents'] = [];
        }
    }
}
