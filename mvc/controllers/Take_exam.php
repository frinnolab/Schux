<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

Class Take_exam extends Admin_Controller {
    /*
    | -----------------------------------------------------
    | PRODUCT NAME: 	INILABS SCHOOL MANAGEMENT SYSTEM
    | -----------------------------------------------------
    | AUTHOR:			INILABS TEAM
    | -----------------------------------------------------
    | EMAIL:			info@inilabs.net
    | -----------------------------------------------------
    | COPYRIGHT:		RESERVED BY INILABS IT
    | -----------------------------------------------------
    | WEBSITE:			http://inilabs.net
    | -----------------------------------------------------
    */

    function __construct() {
        parent::__construct();
        $this->load->model('online_exam_m');
        $this->load->model('online_exam_question_m');
        $this->load->model('instruction_m');
        $this->load->model('question_bank_m');
        $this->load->model('question_option_m');
        $this->load->model('question_answer_m');
        $this->load->model('online_exam_user_answer_m');
        $this->load->model('online_exam_user_status_m');
        $this->load->model('online_exam_user_answer_option_m');
        $this->load->model('student_m');
        $this->load->model('classes_m');
        $this->load->model('section_m');
        $this->load->model('subject_m');
        $this->load->model('studentrelation_m');

        $language = $this->session->userdata('lang');
        $this->lang->load('take_exam', $language);
    }

    public function index() {
        $this->data['headerassets'] = array(
            'css' => array(
                'assets/select2/css/select2.css',
                'assets/select2/css/select2-bootstrap.css'
            ),
            'js' => array(
                'assets/select2/select2.js'
            )
        );

        $usertypeID   = $this->session->userdata('usertypeID');
        $loginuserID  = $this->session->userdata('loginuserID');
        $schoolyearID = $this->session->userdata('defaultschoolyearID');
        $this->data['usertypeID']   = $usertypeID;
        $this->data['userSubjectPluck'] = pluck($this->subject_m->get_order_by_subject(array('type' => 1)), 'subjectID', 'subjectID');
        if($usertypeID == '3') {
            $this->data['student'] = $this->studentrelation_m->get_single_student(array('srstudentID' => $loginuserID, 'srschoolyearID' => $schoolyearID));
            $optionalSubject = $this->subject_m->get_single_subject(array('type' => 0, 'subjectID' => $this->data['student']->sroptionalsubjectID));
            if(count($optionalSubject)) {
                $this->data['userSubjectPluck'][$optionalSubject->subjectID] = $optionalSubject->subjectID;
            }
        }

        $this->data['examStatus'] = pluck($this->online_exam_user_status_m->get_order_by_online_exam_user_status(array('userID'=>$loginuserID)),'obj','onlineExamID');
        $this->data['onlineExams'] = $this->online_exam_m->get_order_by_online_exam(array('schoolYearID' => $schoolyearID, 'usertypeID' => $usertypeID, 'published'=>1));
        $this->data["subview"] = "online_exam/take_exam/index";
        $this->load->view('_layout_main', $this->data);
    }

    public function show() {
        $this->data['headerassets'] = array(
            'css' => array(
                'assets/checkbox/checkbox.css',
                'assets/inilabs/form/fuelux.min.css'
            )
        );
        $this->data['footerassets'] = array(
            'js' => array(
                'assets/inilabs/form/fuelux.min.js'
            )
        );

        $userID = $this->session->userdata("loginuserID");
        $onlineExamID = htmlentities(escapeString($this->uri->segment(3)));

        $examGivenStatus = FALSE;
        $examGivenDataStatus = FALSE;
        $examExpireStatus = FALSE;
        $examSubjectStatus = FALSE;
        if((int)$onlineExamID) {
            $schoolyearID = $this->session->userdata('defaultschoolyearID');
            $onlineExam = $this->online_exam_m->get_single_online_exam(['onlineExamID' => $onlineExamID, 'schoolYearID' => $schoolyearID]);
            if(count($onlineExam)) {
                $this->data['student'] = $this->studentrelation_m->get_single_student(array('srstudentID' => $userID, 'srschoolyearID' => $schoolyearID));
                if(count($this->data['student'])) {
                    $array['classesID'] = $this->data['student']->srclassesID;
                    $array['sectionID'] = $this->data['student']->srsectionID;
                    $array['studentgroupID'] = $this->data['student']->srstudentgroupID;
                    $array['onlineExamID'] = $onlineExamID;
                    $array['schoolYearID'] = $schoolyearID;
                    $online_exam = $this->online_exam_m->get_online_exam_by_student($array);

                    $userExamCheck = $this->online_exam_user_status_m->get_order_by_online_exam_user_status(array('userID' => $userID, 'classesID' => $array['classesID'], 'sectionID' => $array['sectionID'], 'onlineExamID' => $onlineExamID));
                    if(count($online_exam)) {
                        $DDonlineExam = $online_exam;

                        $currentdate = 0;
                        if($DDonlineExam->examTypeNumber == '4') {
                            $presentDate = strtotime(date('Y-m-d'));
                            $examStartDate = strtotime($DDonlineExam->startDateTime);
                            $examEndDate = strtotime($DDonlineExam->endDateTime);
                        } elseif($DDonlineExam->examTypeNumber == '5') {
                            $presentDate = strtotime(date('Y-m-d H:i:s'));
                            $examStartDate = strtotime($DDonlineExam->startDateTime);
                            $examEndDate = strtotime($DDonlineExam->endDateTime);
                        }

                        if($DDonlineExam->examTypeNumber == '4' || $DDonlineExam->examTypeNumber == '5') {
                            if($presentDate >= $examStartDate && $presentDate <= $examEndDate) {
                                $examGivenStatus = TRUE;
                            } elseif($presentDate > $examStartDate && $presentDate > $examEndDate) {
                                $examExpireStatus = TRUE;
                            }
                        } else {
                            $examGivenStatus = TRUE;
                        }

                        if($examGivenStatus) {
                            $examGivenStatus = FALSE;
                            if($DDonlineExam->examStatus == 2) {
                                $examGivenStatus = TRUE;
                            } else {
                                $userExamCheck = pluck($userExamCheck,'obj','onlineExamID');
                                if(isset($userExamCheck[$DDonlineExam->onlineExamID])) {
                                    $examGivenDataStatus = TRUE;
                                } else {
                                    $examGivenStatus = TRUE;
                                }
                            }
                        }

                        if($examGivenStatus) {
                            if($DDonlineExam->subjectID > 0) {
                                $examGivenStatus = FALSE;
                                $userSubjectPluck = pluck($this->subject_m->get_order_by_subject(array('type' => 1)), 'subjectID', 'subjectID');
                                $optionalSubject = $this->subject_m->get_single_subject(array('type' => 0, 'subjectID' => $this->data['student']->sroptionalsubjectID));
                                if(count($optionalSubject)) {
                                    $userSubjectPluck[$optionalSubject->subjectID] = $optionalSubject->subjectID;
                                }

                                if(in_array($DDonlineExam->subjectID, $userSubjectPluck)) {
                                    $examGivenStatus = TRUE;
                                } else {
                                    $examSubjectStatus = FALSE;
                                }
                            } else {
                                $examSubjectStatus = TRUE;
                            }
                        } else {
                            $examSubjectStatus = TRUE;
                        }
                    }
                }
                
                if(count($this->data['student'])) {
                    $this->data['class'] = $this->classes_m->get_single_classes(array('classesID' => $this->data['student']->srclassesID));
                    $this->data['section'] = $this->section_m->get_single_section(array('sectionID' => $this->data['student']->srsectionID));
                } else {
                    $this->data['class'] = [];
                    $this->data['section'] = [];
                }

                $this->data['onlineExam'] = $this->online_exam_m->get_single_online_exam(['onlineExamID' => $onlineExamID, 'schoolYearID' => $schoolyearID]);
                $onlineExamQuestions = $this->online_exam_question_m->get_order_by_online_exam_question(['onlineExamID' => $onlineExamID]);

                $allOnlineExamQuestions = $onlineExamQuestions;
                
                if(count($this->data['onlineExam'])) {
                    if($this->data['onlineExam']->random != 0) {
                        $onlineExamQuestions = $this->randAssociativeArray($onlineExamQuestions, $this->data['onlineExam']->random);
                    }
                }

                $this->data['onlineExamQuestions'] = $onlineExamQuestions;
                $onlineExamQuestions = pluck($onlineExamQuestions, 'obj', 'questionID');
                $questionsBank = pluck($this->question_bank_m->get_order_by_question_bank(), 'obj', 'questionBankID');


                $this->data['questions'] = $questionsBank;

                $options = [];
                $answers = [];
                $allOptions = [];
                $allAnswers = [];

                if(count($allOnlineExamQuestions)) {
                    $pluckOnlineExamQuestions = pluck($allOnlineExamQuestions, 'questionID');
                    $allOptions = $this->question_option_m->get_where_in_question_option($pluckOnlineExamQuestions, 'questionID');
                    foreach ($allOptions as $option) {
                        if($option->name == "" && $option->img == "") continue;
                        $options[$option->questionID][] = $option;
                    }
                    $this->data['options'] = $options;
                    
                    $allAnswers = $this->question_answer_m->get_where_in_question_answer($pluckOnlineExamQuestions, 'questionID');
                    foreach ($allAnswers as $answer) {
                        $answers[$answer->questionID][] = $answer;
                    }
                    $this->data['answers'] = $answers;
                } else {
                    $this->data['options'] = $options;
                    $this->data['answers'] = $answers;
                }

                if($_POST) {
                    $time = date("Y-m-d H:i:s");
                    $mainQuestionAnswer = [];
                    $userAnswer = $this->input->post('answer');

                    foreach ($allAnswers as $answer) {
                        if($answer->typeNumber == 3) {
                            $mainQuestionAnswer[$answer->typeNumber][$answer->questionID][$answer->answerID] = $answer->text;
                        } else {
                            $mainQuestionAnswer[$answer->typeNumber][$answer->questionID][] = $answer->optionID;
                        }
                    }

                    $questionStatus = [];
                    $correctAnswer = 0;
                    $totalQuestionMark = 0;
                    $totalCorrectMark = 0;
                    $visited = [];
                    
                    $totalAnswer = 0;
                    if(count($userAnswer)) {
                        foreach ($userAnswer as $userAnswerKey => $uA) {
                            $totalAnswer += count($uA);
                        }
                    }

                    if(count($allOnlineExamQuestions)) {
                        foreach ($allOnlineExamQuestions as $aoeq) {
                            if(isset($questionsBank[$aoeq->questionID])) {
                                $totalQuestionMark += $questionsBank[$aoeq->questionID]->mark; 
                            }
                        }
                    }

                    $f = 0;
                    foreach ($mainQuestionAnswer as $typeID => $questions) {
                        if(!isset($userAnswer[$typeID])) continue;
                        foreach ($questions as $questionID => $options) {
                            if(isset($onlineExamQuestions[$questionID])) {
                                $onlineExamQuestionID = $onlineExamQuestions[$questionID]->onlineExamQuestionID;
                                $onlineExamUserAnswerID = $this->online_exam_user_answer_m->insert([
                                    'onlineExamQuestionID' => $onlineExamQuestionID,
                                    'userID' => $userID
                                ]);
                            }
                            if(isset($userAnswer[$typeID][$questionID])) {
                                $totalCorrectMark += isset($questionsBank[$questionID]) ? $questionsBank[$questionID]->mark : 0;

                                $questionStatus[$questionID] = 1;
                                $correctAnswer++;
                                $f = 1;
                                if($typeID == 3) {
                                    foreach ($options as $answerID => $answer) {
                                        $takeAnswer = strtolower($answer);
                                        $getAnswer = isset($userAnswer[$typeID][$questionID][$answerID]) ? strtolower($userAnswer[$typeID][$questionID][$answerID]) : '';
                                        $this->online_exam_user_answer_option_m->insert([
                                            'questionID' => $questionID,
                                            'typeID' => $typeID,
                                            'text' => $getAnswer,
                                            'time' => $time
                                        ]);
                                        if($getAnswer != $takeAnswer) {
                                            $f = 0;
                                        }
                                    }
                                } elseif($typeID == 1 || $typeID == 2) {
                                    if(count($options) != count($userAnswer[$typeID][$questionID])) {
                                        $f = 0;
                                    } else {
                                        if(!isset($visited[$typeID][$questionID])) {
                                            foreach ($userAnswer[$typeID][$questionID] as $userOption) {
                                                $this->online_exam_user_answer_option_m->insert([
                                                    'questionID' => $questionID,
                                                    'optionID' => $userOption,
                                                    'typeID' => $typeID,
                                                    'time' => $time
                                                ]);
                                            }
                                            $visited[$typeID][$questionID] = 1;
                                        }
                                        foreach ($options as $answerID => $answer) {
                                            if(!in_array($answer, $userAnswer[$typeID][$questionID])) {
                                                $f = 0;
                                                break;
                                            }
                                        }
                                    }
                                }

                                if(!$f) {
                                    $questionStatus[$questionID] = 0;
                                    $correctAnswer--;
                                    $totalCorrectMark -= $questionsBank[$questionID]->mark;
                                }
                            }
                        }
                    }

                    $examtime = $this->online_exam_user_status_m->get_single_online_exam_user_status(array('userID' => $userID, 'onlineExamID' => $onlineExamID));

                    $examTimeCounter = 1;
                    if(count($examtime)) {
                        $examTimeCounter = $examtime->examtimeID;
                        $examTimeCounter++;
                    }


                    $statusID = 10;
                    if(count($this->data['onlineExam'])) {
                        if($this->data['onlineExam']->markType == 5) {

                            $percentage = 0;
                            if($totalCorrectMark > 0 && $totalQuestionMark > 0) {
                                $percentage = (($totalCorrectMark/$totalQuestionMark)*100);
                            } 

                            if($percentage >= $this->data['onlineExam']->percentage) {
                                $statusID = 5;
                            } else {
                                $statusID = 10;
                            }
                        } elseif($this->data['onlineExam']->markType == 10) {
                            if($totalCorrectMark >= $this->data['onlineExam']->percentage) {
                                $statusID = 5;
                            } else {
                                $statusID = 10;
                            }
                        }
                    } 


                    $this->online_exam_user_status_m->insert([
                        'onlineExamID' => $this->data['onlineExam']->onlineExamID,
                        'time' => $time,
                        'totalQuestion' => count($onlineExamQuestions),
                        'totalAnswer' => $totalAnswer,
                        'nagetiveMark' => $this->data['onlineExam']->negativeMark,
                        'duration' => $this->data['onlineExam']->duration,
                        'score' => $correctAnswer,
                        'userID' => $userID,
                        'classesID' => count($this->data['class']) ? $this->data['class']->classesID : 0,
                        'sectionID' => count($this->data['section']) ? $this->data['section']->sectionID : 0,
                        'examtimeID' => $examTimeCounter,
                        'totalCurrectAnswer' => $correctAnswer,
                        'totalMark' => $totalQuestionMark,
                        'totalObtainedMark' => $totalCorrectMark,
                        'totalPercentage' => sprintf("%.2f", (($totalCorrectMark > 0 && $totalQuestionMark > 0) ? (($totalCorrectMark/$totalQuestionMark)*100) : 0)),
                        'statusID' => $statusID,
                    ]);

                    $this->data['fail'] = $f;
                    $this->data['questionStatus'] = $questionStatus;
                    $this->data['totalAnswer'] = $totalAnswer;
                    $this->data['correctAnswer'] = $correctAnswer;
                    $this->data['totalCorrectMark'] = $totalCorrectMark;
                    $this->data['totalQuestionMark'] = $totalQuestionMark;
                    $this->data['userExamCheck'] = $userExamCheck;
                    $this->data["subview"] = "online_exam/take_exam/result";
                    return $this->load->view('_layout_main', $this->data);
                }

                if($examGivenStatus) {
                    $this->data["subview"] = "online_exam/take_exam/question";
                    return $this->load->view('_layout_main', $this->data);
                } else {
                    if($examGivenDataStatus) {
                        $this->data['online_exam'] = $online_exam;
                        $userExamCheck = pluck($userExamCheck,'obj','onlineExamID');
                        $this->data['userExamCheck'] = isset($userExamCheck[$onlineExamID]) ? $userExamCheck[$onlineExamID] : [];
                        $this->data["subview"] = "online_exam/take_exam/checkexam";
                        return $this->load->view('_layout_main', $this->data);
                    } else {
                        if($examExpireStatus) {
                            $this->data['examsubjectstatus'] = $examSubjectStatus;
                            $this->data['expirestatus'] = $examExpireStatus;
                            $this->data['upcomingstatus'] = FALSE;
                            $this->data['online_exam'] = $online_exam;
                            $this->data["subview"] = "online_exam/take_exam/expireandupcoming";
                            return $this->load->view('_layout_main', $this->data);
                        } else {
                            $this->data['examsubjectstatus'] = $examSubjectStatus;
                            $this->data['expirestatus'] = $examExpireStatus;
                            $this->data['upcomingstatus'] = TRUE;
                            $this->data['online_exam'] = $online_exam;
                            $this->data["subview"] = "online_exam/take_exam/expireandupcoming";
                            return $this->load->view('_layout_main', $this->data);
                        }
                    }
                }
            } else {
                $this->data["subview"] = "error";
                $this->load->view('_layout_main', $this->data);
            }
        } else {
            $this->data["subview"] = "error";
            $this->load->view('_layout_main', $this->data);
        }
    }

    public function instruction()
    {
        $onlineExamID = htmlentities(escapeString($this->uri->segment(3)));
        if((int) $onlineExamID) {
            $instructions = pluck($this->instruction_m->get_order_by_instruction(), 'obj', 'instructionID');
            $onlineExam = $this->online_exam_m->get_single_online_exam(['onlineExamID' => $onlineExamID]);
            $this->data['onlineExam'] = $onlineExam;
            if(!isset($instructions[$onlineExam->instructionID])) {
                redirect(base_url('take_exam/show/'.$onlineExamID));
            }
            $this->data['instruction'] = $instructions[$onlineExam->instructionID];
            $this->data["subview"] = "online_exam/take_exam/instruction";
            return $this->load->view('_layout_main', $this->data);
        } else {
            $this->data["subview"] = "error";
            $this->load->view('_layout_main', $this->data);
        }
    }

    public function randAssociativeArray($array, $number = 0) {
        $returnArray = [];
        $countArray = count($array);
        $number = $countArray;

        if($countArray == 1) {
            $randomKey[] = 0;
        } else {
            if(count($array)) {
                $randomKey = array_rand($array, $number);
            } else {
                $randomKey = [];
            }
        }

        if(is_array($randomKey)) {
            shuffle($randomKey);
        }

        if(count($randomKey)) {
            foreach ($randomKey as $key) {
                $returnArray[] = $array[$key];
            }
            return $returnArray;
        } else {
            return $array;
        }
    }
}
