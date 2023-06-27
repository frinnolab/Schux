<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Marksetting_m extends MY_Model {

	protected $_table_name     = 'marksetting';
	protected $_primary_key    = 'marksettingID';
	protected $_primary_filter = 'intval';
	protected $_order_by       = "marksettingID";

	function __construct() {
		parent::__construct();
		$this->load->model('exam_m');
		$this->load->model('classes_m');
		$this->load->model('subject_m');
		$this->load->model('markpercentage_m');
	}

	public function get_marksetting($array=NULL, $single=FALSE) {
		return parent::get($array, $single);
	}

	public function get_order_by_marksetting($array=NULL) {
		return parent::get_order_by($array);
	}

	public function get_single_marksetting($array=NULL) {
		return parent::get_single($array);
	}

	public function insert_marksetting($array) {
		return parent::insert($array);
	}

	public function insert_batch_marksetting($array) {
		return parent::insert_batch($array);
	}

	public function update_marksetting($data, $id = NULL) {
		parent::update($data, $id);
		return $id;
	}

	public function delete_marksetting($id){
		return parent::delete($id);
	}

	public function delete_marksetting_by_array($array=[]) {
		if(count($array)) {
			$this->db->where($array);
			return $this->db->delete($this->_table_name);
		} 
		return FALSE;
	}

	public function get_marksetting_with_marksettingrelation($array=[]) {
		$this->db->select('*');
		$this->db->from('marksetting');
		$this->db->join('marksettingrelation', 'marksetting.marksettingID=marksettingrelation.marksettingID');
		if(count($array)) {
			foreach ($array as $key=>$value) {
				$this->db->where("marksetting.$key", $value);
			}
		}
		$this->db->order_by('marksetting.marksettingID DESC');
		$query = $this->db->get();
		return $query->result();
	}

	public function get_exam($marktypeID= '', $classesID=0) {
		if($marktypeID == 4) {
			return $this->exam_m->get_exam();
		} elseif(($marktypeID == 5) || ($marktypeID == 6)) {
			if((int)$classesID) {
				$this->db->select('marksetting.*, exam.exam');
				$this->db->from('marksetting');
				$this->db->join('exam', 'marksetting.examID=exam.examID');
				$this->db->where('marksetting.marktypeID', $marktypeID);
				$this->db->where('marksetting.classesID', $classesID);
				$query = $this->db->get();
				return $query->result();
			} 
			return [];
		} else {
			$this->db->select('marksetting.*, exam.exam');
			$this->db->from('marksetting');
			$this->db->join('exam', 'marksetting.examID=exam.examID');
			$this->db->where('marksetting.marktypeID', $marktypeID);
			$query = $this->db->get();
			return $query->result();
		}
	}

    public function get_exam_with_class( $classesID = 0 )
    {
        $exams      = [];
        $marktypeID = $this->data['siteinfos']->marktypeID;
        if ( $marktypeID == 4 ) {
            $exams = $this->exam_m->get_exam();
        } elseif ( ( $marktypeID == 5 ) || ( $marktypeID == 6 ) ) {
            if ( (int) $classesID ) {
                $this->db->select('marksetting.*, exam.exam');
                $this->db->from('marksetting');
                $this->db->join('exam', 'marksetting.examID=exam.examID');
                $this->db->where('marksetting.marktypeID', $marktypeID);
                $this->db->where('marksetting.classesID', $classesID);
                $query = $this->db->get();
                $exams =  $query->result();
            }
        } else {
            $this->db->select('marksetting.*, exam.exam');
            $this->db->from('marksetting');
            $this->db->join('exam', 'marksetting.examID=exam.examID');
            $this->db->where('marksetting.marktypeID', $marktypeID);
            $query = $this->db->get();
            $exams = $query->result();
        }

        if(count($exams)) {
            $exams = pluck($exams, 'obj', 'examID');
            return $exams;
        }
        return [];
    }

	public function get_marksetting_markpercentages_add($array) {
		extract($array);

		$finalmark = 100;
		if(count($subject)) {
			$finalmark = $subject->finalmark;
		}

		$queryArray['marktypeID']   = (int)$marktypeID;
		
		if(($marktypeID == 2) || ($marktypeID == 3) || ($marktypeID == 5) || ($marktypeID == 6)) {
			$queryArray['examID']   = (int)$examID;
		}
		
		if(($marktypeID == 1) || ($marktypeID == 4) || ($marktypeID == 5) || ($marktypeID == 6)) {
			$queryArray['classesID']= (int)$classesID;
		}
		if(($marktypeID == 4) || ($marktypeID == 6)) {
			$queryArray['subjectID']= (int)$subjectID;
		}
		$marksettingArr       = pluck($this->get_marksetting_with_marksettingrelation($queryArray), 'markpercentageID', 'markpercentageID');

		$markpercentages  = $this->markpercentage_m->get_markpercentage();
		$retMarkpercentages = [];
		if(count($markpercentages)) {
			foreach ($markpercentages as $markpercentage) {
				if(in_array($markpercentage->markpercentageID, $marksettingArr)) {
					$markpercentage->percentage = convertMarkpercentage($markpercentage->percentage, $finalmark);
					$retMarkpercentages[$markpercentage->markpercentageID] = $markpercentage;
				}
			}
		}
		return $retMarkpercentages;
	}

	public function get_marksetting_markpercentages() {
		$marktypeID = (int)$this->data['siteinfos']->marktypeID;
		$exclassID  = (int)$this->data['siteinfos']->ex_class;

		$classes    = $this->classes_m->get_order_by_classes(['classesID !='=> $exclassID]);
		$exams      = $this->exam_m->get_exam();
		$subjects   = pluck_multi_array($this->subject_m->get_subject(), 'obj', 'classesID');
		
		$marksettingrelations          = $this->get_marksetting_with_marksettingrelation();
		$retglobalmarksettingArr       = [];
		$retclasswisemarksettingArr    = [];
		$retsubjectwisemarksettingArr  = [];
		$retclassexamwisemarksettingArr= [];
		$retclassexamsubjectsettingArr = [];

		if(count($marksettingrelations)) {
			foreach ($marksettingrelations as $marksettingrelation) {
				if($marksettingrelation->marktypeID != $marktypeID) {
					continue;
				}
				$retglobalmarksettingArr[$marksettingrelation->examID][$marksettingrelation->markpercentageID] = $marksettingrelation->markpercentageID;
				$retclasswisemarksettingArr[$marksettingrelation->examID][$marksettingrelation->classesID][$marksettingrelation->markpercentageID] = (int)$marksettingrelation->markpercentageID;
				$retsubjectwisemarksettingArr[$marksettingrelation->classesID][$marksettingrelation->subjectID][$marksettingrelation->markpercentageID] = (int)$marksettingrelation->markpercentageID;
				$retclassexamwisemarksettingArr[$marksettingrelation->classesID][$marksettingrelation->examID][$marksettingrelation->markpercentageID] = (int)$marksettingrelation->markpercentageID;
				$retclassexamsubjectsettingArr[$marksettingrelation->classesID][$marksettingrelation->examID][$marksettingrelation->subjectID][$marksettingrelation->markpercentageID] = (int)$marksettingrelation->markpercentageID;
						
			}
		}

		$retMarkpercentages = [];
		if(count($classes)) {
			foreach ($classes as $class) {
				if($marktypeID == 0) {
					if(count($exams)) {
						foreach($exams as $exam) {
							if(isset($retglobalmarksettingArr[$exam->examID])) {
								$subjectsArr          = isset($subjects[$class->classesID]) ? $subjects[$class->classesID] : [];
								$retmarkpercentageArr = $retglobalmarksettingArr[$exam->examID];
								asort($retmarkpercentageArr);
								if(count($subjectsArr)) {
									foreach ($subjectsArr as $subject) {
										$retMarkpercentages[$class->classesID][$exam->examID][$subject->subjectID]['own'] = $retmarkpercentageArr;
									}
								}
							}
						}
					}
				} else if($marktypeID == 1) {
					if(count($exams)) {
						foreach($exams as $exam) {
							if(isset($retclasswisemarksettingArr[$exam->examID])) {
								$subjectsArr               = isset($subjects[$class->classesID]) ? $subjects[$class->classesID] : [];
								$retclassmarkpercentageArr = isset($retclasswisemarksettingArr[$exam->examID][$class->classesID]) ? $retclasswisemarksettingArr[$exam->examID][$class->classesID] : [];
								asort($retclassmarkpercentageArr);
								if(count($subjectsArr)) {
									foreach ($subjectsArr as $subject) {
										$retMarkpercentages[$class->classesID][$exam->examID][$subject->subjectID]['own'] = $retclassmarkpercentageArr;
									}
								}
							}
						}
					}
				} else if($marktypeID == 2) {
					if(count($exams)) {
						foreach($exams as $exam) {
							if(isset($retglobalmarksettingArr[$exam->examID])) {
								$subjectsArr          = isset($subjects[$class->classesID]) ? $subjects[$class->classesID] : [];
								$retmarkpercentageArr = $retglobalmarksettingArr[$exam->examID];
								asort($retmarkpercentageArr);
								if(count($subjectsArr)) {
									foreach ($subjectsArr as $subject) {
										$retMarkpercentages[$class->classesID][$exam->examID][$subject->subjectID]['own'] = $retmarkpercentageArr;
									}
								}
							}
						}
					}
				} else if($marktypeID == 3) {
					if(count($exams)) {
						foreach($exams as $exam) {
							if(isset($retglobalmarksettingArr[$exam->examID])) {
								$subjectsArr          = isset($subjects[$class->classesID]) ? $subjects[$class->classesID] : [];
								$retmarkpercentageArr = $retglobalmarksettingArr[$exam->examID];
								asort($retmarkpercentageArr);
								if(count($subjectsArr)) {
									foreach ($subjectsArr as $subject) {
										$retMarkpercentages[$class->classesID][$exam->examID][$subject->subjectID]['own'] = $retmarkpercentageArr;
									}
								}
							}
						}
					}
				} else if($marktypeID == 4) {
					if(count($exams)) {
						foreach($exams as $exam) {
							$subjectsArr         = isset($subjects[$class->classesID]) ? $subjects[$class->classesID] : [];
							$uniquePercentageArr = [];
							if(count($subjectsArr)) {
								foreach ($subjectsArr as $subject) {
									$retmarkpercentageArr    = isset($retsubjectwisemarksettingArr[$class->classesID][$subject->subjectID]) ? $retsubjectwisemarksettingArr[$class->classesID][$subject->subjectID] : [];
									asort($retmarkpercentageArr);
									$retMarkpercentages[$class->classesID][$exam->examID][$subject->subjectID]['own'] = $retmarkpercentageArr;

									if(count($retmarkpercentageArr)) {
										foreach ($retmarkpercentageArr as $markpercentageID) {
											if(!isset($uniquePercentageArr[$markpercentageID])) {
												$uniquePercentageArr[$markpercentageID] = $markpercentageID; 
											}
										}
									}
								}
							}

							asort($uniquePercentageArr);
							if(count($subjectsArr)) {
								foreach ($subjectsArr as $subject) {
									$retMarkpercentages[$class->classesID][$exam->examID][$subject->subjectID]['unique'] = $uniquePercentageArr;
								}
							}
						}
					}
				} else if($marktypeID == 5) {
					if(count($exams)) {
						foreach($exams as $exam) {
							if(isset($retclassexamwisemarksettingArr[$class->classesID][$exam->examID])) {
								$retmarkpercentageArr    = $retclassexamwisemarksettingArr[$class->classesID][$exam->examID];
								asort($retmarkpercentageArr);
								$subjectsArr  = isset($subjects[$class->classesID]) ? $subjects[$class->classesID] : [];
								if(count($subjectsArr)) {
									foreach ($subjectsArr as $subject) {
										$retMarkpercentages[$class->classesID][$exam->examID][$subject->subjectID]['own'] = $retmarkpercentageArr;
									}
								}
							}
						}
					}
				} else if($marktypeID == 6) {
					if(count($exams)) {
						foreach($exams as $exam) {
							if(isset($retclassexamsubjectsettingArr[$class->classesID][$exam->examID])) {
								$subjectsArr         = isset($subjects[$class->classesID]) ? $subjects[$class->classesID] : [];
								$uniquePercentageArr = [];
								if(count($subjectsArr)) {
									foreach ($subjectsArr as $subject) {
										if(isset($retclassexamsubjectsettingArr[$class->classesID][$exam->examID][$subject->subjectID])) {

											$retmarkpercentageArr    = $retclassexamsubjectsettingArr[$class->classesID][$exam->examID][$subject->subjectID];
											asort($retmarkpercentageArr);
											$retMarkpercentages[$class->classesID][$exam->examID][$subject->subjectID]['own'] = $retmarkpercentageArr;

											if(count($retmarkpercentageArr)) {
												foreach ($retmarkpercentageArr as $markpercentageID) {
													if(!isset($uniquePercentageArr[$markpercentageID])) {
														$uniquePercentageArr[$markpercentageID] = $markpercentageID; 
													}
												}
											}

										}
									}

								}

								asort($uniquePercentageArr);
								if(count($subjectsArr)) {
									foreach ($subjectsArr as $subject) {
										if(isset($retclassexamsubjectsettingArr[$class->classesID][$exam->examID][$subject->subjectID])) {
											$retMarkpercentages[$class->classesID][$exam->examID][$subject->subjectID]['unique'] = $uniquePercentageArr;
										}
									}
								}
							}
						}
					}
				}
			}
		}
		return $retMarkpercentages;
	}
}