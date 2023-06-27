<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mark
{
	protected $ci;
	protected $studentID;
	protected $classesID;
	protected $schoolyearID;
	protected $data;
	protected $retdata;


	public function __construct($params=[])
	{
        $this->ci           = & get_instance();
        $this->studentID    = $params['studentID'];
        $this->classesID    = $params['classesID'];
        $this->schoolyearID = $params['schoolyearID'];
        $this->data         = $params['data'];

        $this->ci->load->model('studentrelation_m');
        $this->ci->load->model('markpercentage_m');
        $this->ci->load->model('marksetting_m');
        $this->ci->load->model('mark_m');

        $language = $this->ci->session->userdata('lang');
		$this->ci->lang->load('mark', $language);
	}


	private function _mark() {
		$CI           = $this->ci;
		$studentID    = $this->studentID;
		$classesID    = $this->classesID;
		$schoolyearID = $CI->session->userdata('defaultschoolyearID');

		if((int)$studentID && (int)$classesID) {
			$student      = $CI->studentrelation_m->get_single_student(array('srstudentID' => $studentID, 'srclassesID' => $classesID, 'srschoolyearID' => $schoolyearID));
			$classes      = $CI->classes_m->get_single_classes(array('classesID' => $classesID));
			if(count($student) && count($classes)) {
				$queryArray = [
					'classesID'    => $student->srclassesID,
					'sectionID'    => $student->srsectionID,
					'studentID'    => $student->srstudentID, 
					'schoolyearID' => $schoolyearID, 
				];

				$exams             = pluck($CI->exam_m->get_exam(), 'exam', 'examID');
				$grades            = $CI->grade_m->get_grade();
				$marks             = $CI->mark_m->student_all_mark_array($queryArray);
				$markpercentages   = $CI->markpercentage_m->get_markpercentage();

				$subjects          = $CI->subject_m->general_get_order_by_subject(array('classesID' => $classesID));
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
						$retMark[$mark->examID][$mark->subjectID][$mark->markpercentageID] = $mark->mark;
					}
				}

				$allStudentMarks = $CI->mark_m->student_all_mark_array(array('classesID' => $classesID, 'schoolyearID' => $schoolyearID));
				$highestMarks    = [];
				foreach ($allStudentMarks as $allStudentMark) {
					if(!isset($highestMarks[$allStudentMark->examID][$allStudentMark->subjectID][$allStudentMark->markpercentageID])) {
						$highestMarks[$allStudentMark->examID][$allStudentMark->subjectID][$allStudentMark->markpercentageID] = -1;
					}
					$highestMarks[$allStudentMark->examID][$allStudentMark->subjectID][$allStudentMark->markpercentageID] = max($allStudentMark->mark, $highestMarks[$allStudentMark->examID][$allStudentMark->subjectID][$allStudentMark->markpercentageID]);
				}
				$marksettings  = $CI->marksetting_m->get_marksetting_markpercentages();

				$this->retdata['settingmarktypeID'] = $this->data->marktypeID;
				$this->retdata['subjects']          = $subjectArr;
				$this->retdata['exams']             = $exams;
				$this->retdata['grades']            = $grades;
				$this->retdata['markpercentages']   = pluck($markpercentages, 'obj', 'markpercentageID');
				$this->retdata['optionalsubjectArr']= $optionalsubjectArr;
				$this->retdata['marks']             = $retMark;
				$this->retdata['highestmarks']      = $highestMarks;
				$this->retdata['student']           = $student;
				$this->retdata['marksettings']      = isset($marksettings[$classesID]) ? $marksettings[$classesID] : [];

			}
		}

	}

	public function mail() {
		$CI    = $this->ci;
		$this->_mark();
		extract($this->retdata);

        $optionalsubjectID = $student->sroptionalsubjectID;
        if(count($marksettings)) {
            $retStr = '';
            foreach ($marksettings as $examID => $marksetting) {
                $retStr .= '<div style="border:1px solid #23292F; margin-top: 25px;">';
                    $retStr .= '<div style="background-color:#FFFFFF">';
                        $retStr .= '<h3 style="color:#23292F; padding:10px; margin:0px;">'; 
                            $retStr .= (isset($exams[$examID]) ? $exams[$examID] : '');
                        $retStr .= '</h3>';
                    $retStr .= '</div>';

                    $retStr .= '<div style="border-top:1px solid #23292F; padding:10px;">';
                        $retStr .= "<table style='width:100% !important; border-collapse: collapse'>";
                            $retStr .= "<thead>";
                                $retStr .= "<tr>";
                                    $retStr .= "<th rowspan='2' style='background-color:#395C7F;color:#fff; border: 1px solid #ddd; padding: 5px'>";
                                        $retStr .= $CI->lang->line("mark_subject");
                                    $retStr .= "</th>";

                                    foreach ($marksetting as $subjectID => $markpercentageArr) {
                                        foreach ($markpercentageArr[(($settingmarktypeID==4) || ($settingmarktypeID==6)) ? 'unique' : 'own'] as $markpercentageID) {
                                            $markpercentagetypelabel = isset($markpercentages[$markpercentageID]) ? $markpercentages[$markpercentageID]->markpercentagetype : '';
                                            $retStr .= "<th colspan='2' style='background-color:#395C7F;color:#fff; border: 1px solid #ddd; padding: 5px'>";
                                                $retStr .= $markpercentagetypelabel;
                                            $retStr .= "</th>";
                                        }
                                        break;
                                    }
                                    $retStr .= "<th colspan='3' style='background-color:#395C7F;color:#fff; border: 1px solid #ddd; padding: 5px'>";
                                        $retStr .= $CI->lang->line("mark_total");
                                    $retStr .= "</th>";
                                $retStr .= "</tr>";
                                foreach ($marksetting as $subjectID => $markpercentageArr) {
                                    $retStr .= "<tr>";
                                        foreach ($markpercentageArr[(($settingmarktypeID==4) || ($settingmarktypeID==6)) ? 'unique' : 'own'] as $markpercentageID) {
                                            $retStr .= "<th style='border: 1px solid #ddd; padding: 5px'>";
                                                $retStr .= $CI->lang->line("mark_obtained_mark");
                                            $retStr .= "</th>";

                                            $retStr .= "<th style='border: 1px solid #ddd; padding: 5px'>";
                                                $retStr .= $CI->lang->line("mark_highest_mark");
                                            $retStr .= "</th>";
                                        }
                                        $retStr .= "<th style='border: 1px solid #ddd; padding: 5px'>";
                                            $retStr .= $CI->lang->line("mark_mark");
                                        $retStr .= "</th>";
                                        $retStr .= "<th style='border: 1px solid #ddd; padding: 5px'>";
                                            $retStr .= $CI->lang->line("mark_point");
                                        $retStr .= "</th>";
                                        $retStr .= "<th style='border: 1px solid #ddd; padding: 5px'>";
                                            $retStr .= $CI->lang->line("mark_grade");
                                        $retStr .= "</th>";
                                    $retStr .= "</tr>";
                                    break;
                                }
                            $retStr .= "</thead>";
                            $retStr .= "<tbody>";
                            $totalMark           = 0;
                            $totalFinalMark      = 0;
                            $totalSubject        = 0;
                            $averagePoint        = 0;
                            $opmarkpercentageArr = [];
                            foreach ($marksetting as $subjectID => $markpercentageArr) {
                                if($subjectID == $optionalsubjectID) {
                                    $opmarkpercentageArr = $markpercentageArr;
                                }
                                if(!in_array($subjectID, $optionalsubjectArr)) {
                                    $totalSubject++;
                                    $retStr .= "<tr>";
                                        $retStr .= "<td style='border: 1px solid #ddd; padding: 5px'>";
                                             $retStr .= isset($subjects[$subjectID]) ? $subjects[$subjectID]->subject : '';
                                        $retStr .= "</td>";

                                        $subjectfinalmark = isset($subjects[$subjectID]) ? (int)$subjects[$subjectID]->finalmark : 0;
                                        $totalSubjectMark = 0;
                                        $percentageMark   = 0;
                                        foreach ($markpercentageArr[(($settingmarktypeID==4) || ($settingmarktypeID==6)) ? 'unique' : 'own'] as $markpercentageID) {

                                            $f = false;
                                            if(isset($markpercentageArr['own']) && in_array($markpercentageID, $markpercentageArr['own'])) {
                                                $f = true;
                                                $percentageMark   += (isset($markpercentages[$markpercentageID]) ? $markpercentages[$markpercentageID]->percentage : 0);
                                            }

                                            $retStr .= "<td style='border: 1px solid #ddd; padding: 5px'>";
                                                if(isset($marks[$examID][$subjectID][$markpercentageID]) && $f) {
                                                    $retStr .= $marks[$examID][$subjectID][$markpercentageID];
                                                    $totalSubjectMark += $marks[$examID][$subjectID][$markpercentageID];
                                                } else {
                                                    if($f) {
                                                        $retStr .= 'N/A';
                                                    }
                                                }
                                            $retStr .= "</td>";

                                            $retStr .= "<td style='border: 1px solid #ddd; padding: 5px'>";
                                                if(isset($highestmarks[$examID][$subjectID][$markpercentageID]) && ($highestmarks[$examID][$subjectID][$markpercentageID] != -1) && $f) {
                                                    $retStr .= $highestmarks[$examID][$subjectID][$markpercentageID];
                                                } else {
                                                     if($f) {
                                                        $retStr .= 'N/A';
                                                    }
                                                }
                                            $retStr .= "</td>";
                                        }
                                        $finalpercentageMark = convertMarkpercentage($percentageMark, $subjectfinalmark);


                                        $retStr .= "<td style='border: 1px solid #ddd; padding: 5px'>";
                                            $retStr .= $totalSubjectMark;
                                            $totalMark        += $totalSubjectMark;
                                            $totalFinalMark   += $finalpercentageMark;
                                            $totalSubjectMark  = markCalculationView($totalSubjectMark, $subjectfinalmark, $percentageMark);
                                        $retStr .= "</td>";
                                        
                                        if(count($grades)) {
                                            foreach ($grades as $grade) {
                                                if(($grade->gradefrom <= $totalSubjectMark) && ($grade->gradeupto >= $totalSubjectMark)) {
                                                    $retStr .= "<td style='border: 1px solid #ddd; padding: 5px'>";
                                                        $retStr .= $grade->point;
                                                        $averagePoint += $grade->point;
                                                    $retStr .= "</td>";
                                                    $retStr .= "<td style='border: 1px solid #ddd; padding: 5px'>";
                                                        $retStr .= $grade->grade;
                                                    $retStr .= "</td>";
                                                }
                                            }
                                        } else {
                                            $retStr .= "<td style='border: 1px solid #ddd; padding: 5px'>";
                                                $retStr .= 'N/A';
                                            $retStr .= '</td>';
                                            $retStr .= "<td style='border: 1px solid #ddd; padding: 5px'>";
                                                $retStr .= 'N/A';
                                            $retStr .= '</td>';
                                        }
                                    $retStr .= "</tr>";
                                }
                            }

                            if(($optionalsubjectID > 0) && count($opmarkpercentageArr)) {
                                $totalSubject++;
                                $retStr .= "<tr>";
                                    $retStr .= "<td style='border: 1px solid #ddd; padding: 5px'>";
                                         $retStr .= isset($subjects[$optionalsubjectID]) ? $subjects[$optionalsubjectID]->subject : '';
                                    $retStr .= "</td>";
                                    $subjectfinalmark  = isset($subjects[$optionalsubjectID]) ? $subjects[$optionalsubjectID]->finalmark : 0;

                                    $totalSubjectMark = 0;
                                    $percentageMark   = 0;
                                    foreach ($opmarkpercentageArr[(($settingmarktypeID==4) || ($settingmarktypeID==6)) ? 'unique' : 'own'] as $markpercentageID) {

                                        $f = false;
                                        if(isset($opmarkpercentageArr['own']) && in_array($markpercentageID, $opmarkpercentageArr['own'])) {
                                            $f = true;
                                            $percentageMark   += (isset($markpercentages[$markpercentageID]) ? $markpercentages[$markpercentageID]->percentage : 0);
                                        } 

                                        $retStr .= "<td style='border: 1px solid #ddd; padding: 5px'>";
                                            if(isset($marks[$examID][$optionalsubjectID][$markpercentageID]) && $f) {
                                                $retStr .= $marks[$examID][$optionalsubjectID][$markpercentageID];
                                                $totalSubjectMark += $marks[$examID][$optionalsubjectID][$markpercentageID];
                                            } else {
                                                if($f) {
                                                    $retStr .= 'N/A';
                                                }
                                            }
                                        $retStr .= "</td>";

                                        $retStr .= "<td style='border: 1px solid #ddd; padding: 5px'>";
                                            if(isset($highestmarks[$examID][$optionalsubjectID][$markpercentageID]) && ($highestmarks[$examID][$optionalsubjectID][$markpercentageID] != -1) && $f) {
                                                $retStr .= $highestmarks[$examID][$optionalsubjectID][$markpercentageID];
                                            } else {
                                                if($f) {
                                                    $retStr .= 'N/A';
                                                }
                                            }
                                        $retStr .= "</td>";
                                    }
                                    $finalpercentageMark = convertMarkpercentage($percentageMark, $subjectfinalmark);

                                    $retStr .= "<td style='border: 1px solid #ddd; padding: 5px'>";
                                        $retStr .= $totalSubjectMark;
                                        $totalMark        += $totalSubjectMark;
                                        $totalFinalMark   += $finalpercentageMark;

                                        $totalSubjectMark  = markCalculationView($totalSubjectMark, $subjectfinalmark, $percentageMark);
                                    $retStr .= "</td>";
                                    
                                    if(count($grades)) {
                                        foreach ($grades as $grade) {
                                            if(($grade->gradefrom <= $totalSubjectMark) && ($grade->gradeupto >= $totalSubjectMark)) {
                                                $retStr .= "<td style='border: 1px solid #ddd; padding: 5px'>";
                                                    $retStr .= $grade->point;
                                                    $averagePoint += $grade->point;
                                                $retStr .= "</td>";
                                                $retStr .= "<td style='border: 1px solid #ddd; padding: 5px'>";
                                                    $retStr .= $grade->grade;
                                                $retStr .= "</td>";
                                            }
                                        }
                                    } else {
                                        $retStr .= "<td style='border: 1px solid #ddd; padding: 5px'>";
                                            $retStr .= 'N/A';
                                        $retStr .= '</td>';
                                        $retStr .= "<td style='border: 1px solid #ddd; padding: 5px'>";
                                            $retStr .= 'N/A';
                                        $retStr .= '</td>';
                                    }
                                $retStr .= "</tr>";
                            }
                            $retStr .= "</tbody>";
                        $retStr .= "</table>";

                        $retStr .= '<p style="margin-bottom: 5px">'. $CI->lang->line('mark_total_marks').' : <span style="font-weight: bold; color: #f56954 !important">'. ini_round($totalFinalMark).'</span>';
                        $retStr .= '&nbsp;&nbsp;&nbsp;&nbsp;'.$CI->lang->line('mark_total_obtained_marks').' : <span style="font-weight: bold; color: #f56954 !important">'. ini_round($totalMark).'</span>';
                        $totalAverageMark = $totalMark / $totalSubject;
                        $retStr .= '&nbsp;&nbsp;&nbsp;&nbsp;'.$CI->lang->line('mark_total_average_marks').' : <span style="font-weight: bold; color: #f56954 !important">'. ini_round($totalAverageMark).'</span>';

                        $totalmarkpercentage  = markCalculationView($totalMark, $totalFinalMark);
                        $retStr .= '&nbsp;&nbsp;&nbsp;&nbsp;'.$CI->lang->line('mark_total_average_marks_percetage').' : <span style="font-weight: bold; color: #f56954 !important">'. ini_round($totalmarkpercentage) .'</span>';

                        $gpaAveragePoint = $averagePoint / $totalSubject;
                        $retStr .= '&nbsp;&nbsp;&nbsp;&nbsp;'.$CI->lang->line('mark_gpa').' : <span style="font-weight: bold; color: #f56954 !important">'. ini_round($gpaAveragePoint) .'</span>';
                        $retStr .= '</p>';
                    $retStr .= '</div>';  
                $retStr .= "</div>";
            }
            return $retStr;
        }
	}

	public function sms() {
		$CI    = $this->ci;
		$this->_mark();
		extract($this->retdata);

        $optionalsubjectID = $student->sroptionalsubjectID;
        if(count($marksettings)) {
        	$k = 1;
            $retStr = '';
            foreach ($marksettings as $examID => $marksetting) {
                $retStr .= (isset($exams[$examID]) ? $exams[$examID] : '');

                $totalSubject        = 0;
                $averagePoint        = 0;
                $opmarkpercentageArr = [];
                
                foreach ($marksetting as $subjectID => $markpercentageArr) {
                    if($subjectID == $optionalsubjectID) {
                        $opmarkpercentageArr = $markpercentageArr;
                    }
                    if(!in_array($subjectID, $optionalsubjectArr)) {
                        $totalSubject++;

                        $subjectfinalmark = isset($subjects[$subjectID]) ? (int)$subjects[$subjectID]->finalmark : 0;
                        $percentageMark   = 0;
                        $totalSubjectMark = 0;
                        foreach ($markpercentageArr[(($settingmarktypeID==4) || ($settingmarktypeID==6)) ? 'unique' : 'own'] as $markpercentageID) {
                            $f = false;
                            if(isset($markpercentageArr['own']) && in_array($markpercentageID, $markpercentageArr['own'])) {
                                $f = true;
                                $percentageMark   += (isset($markpercentages[$markpercentageID]) ? $markpercentages[$markpercentageID]->percentage : 0);
                            }

                            if(isset($marks[$examID][$subjectID][$markpercentageID]) && $f) {
                                $totalSubjectMark += $marks[$examID][$subjectID][$markpercentageID];
                            }
                        }

                        $totalSubjectMark  = markCalculationView($totalSubjectMark, $subjectfinalmark, $percentageMark);
                        if(count($grades)) {
                            foreach ($grades as $grade) {
                                if(($grade->gradefrom <= $totalSubjectMark) && ($grade->gradeupto >= $totalSubjectMark)) {
                                    $averagePoint += $grade->point;
                                }
                            }
                        }
                    }
                }

                if(($optionalsubjectID > 0) && count($opmarkpercentageArr)) {
                    $totalSubject++;
                    $subjectfinalmark  = isset($subjects[$optionalsubjectID]) ? $subjects[$optionalsubjectID]->finalmark : 0;

                    $totalSubjectMark = 0;
                    $percentageMark   = 0;
                    foreach ($opmarkpercentageArr[(($settingmarktypeID==4) || ($settingmarktypeID==6)) ? 'unique' : 'own'] as $markpercentageID) {

                        $f = false;
                        if(isset($opmarkpercentageArr['own']) && in_array($markpercentageID, $opmarkpercentageArr['own'])) {
                            $f = true;
                            $percentageMark   += (isset($markpercentages[$markpercentageID]) ? $markpercentages[$markpercentageID]->percentage : 0);
                        } 

                        if(isset($marks[$examID][$optionalsubjectID][$markpercentageID]) && $f) {
                            $totalSubjectMark += $marks[$examID][$optionalsubjectID][$markpercentageID];
                        }
                    }

                    $totalSubjectMark  = markCalculationView($totalSubjectMark, $subjectfinalmark, $percentageMark);
                    if(count($grades)) {
                        foreach ($grades as $grade) {
                            if(($grade->gradefrom <= $totalSubjectMark) && ($grade->gradeupto >= $totalSubjectMark)) {
                                $averagePoint += $grade->point;
                            }
                        }
                    }
                }

                $gpaAveragePoint = $averagePoint / $totalSubject;
                
                if($k < count($marksettings)) {
                	$retStr .= '&nbsp; - &nbsp;'.$CI->lang->line('mark_gpa').' : '. ini_round($gpaAveragePoint).' , ';
                } else {
                	$retStr .= '&nbsp; - &nbsp;'.$CI->lang->line('mark_gpa').' : '. ini_round($gpaAveragePoint).' .';
                }
                $k++;
            }
            return strip_tags($retStr);
        }
	}

}

/* End of file Mark.php */
/* Location: .//var/www/html/schoolupdate42/mvc/libraries/Mark.php */
