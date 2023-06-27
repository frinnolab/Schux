<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Schoolyear_m extends MY_Model 
{

	protected $_table_name = 'schoolyear';
	protected $_primary_key = 'schoolyearID';
	protected $_primary_filter = 'intval';
	protected $_order_by = "schoolyear desc";

	function __construct() 
	{
		parent::__construct();
	}

	public function get_schoolyear($array=NULL, $signal=FALSE) 
	{
		$query = parent::get($array, $signal);
		return $query;
	}

	public function get_order_by_schoolyear($array=NULL) 
	{
		$query = parent::get_order_by($array);
		return $query;
	}

	public function get_single_schoolyear($array=NULL) 
	{
		$query = parent::get_single($array);
		return $query;
	}

	public function insert_schoolyear($array) 
	{
		parent::insert($array);
		return TRUE;
	}

	public function update_schoolyear($data, $id = NULL) 
	{
		parent::update($data, $id);
		return $id;
	}

	public function delete_schoolyear($id) 
	{
		parent::delete($id);
	}

	public function get_schoolyear_where($schoolyear) 
	{
		$where = '(schoolyear="'.$schoolyear.'" AND (schoolyeartitle IS NULL OR schoolyeartitle = ""))';
		$this->db->select('*');
		$this->db->from('schoolyear');
       	$this->db->where($where);
       	$query = $this->db->get();
       	return $query->result();

	}

	public function get_schoolyear_where_not($schoolyear, $id) 
	{
		$where = '(schoolyear="'.$schoolyear.'" AND schoolyearID !="'.$id.'" AND (schoolyeartitle IS NULL OR schoolyeartitle = ""))';
		$this->db->select('*');
		$this->db->from('schoolyear');
       	$this->db->where($where);
       	$query = $this->db->get();
       	return $query->result();
	}

	public function get_obj_schoolyear($schoolyearID = 1) 
	{
		$schoolyear = $this->get_single_schoolyear(array('schoolyearID' => $schoolyearID));
		$schoolyearArray = [];
		if(is_object($schoolyear)) {
			$startingDate = explode('-', $schoolyear->startingdate);
			$endingDate   = explode('-', $schoolyear->endingdate);

			$schoolyearArray['schoolyearID'] = $schoolyear->schoolyearID;
			$schoolyearArray['startingday'] = $startingDate[2];
			$schoolyearArray['endingday']   = $endingDate[2];
			$schoolyearArray['startingmonth'] = $startingDate[1];
			$schoolyearArray['endingmonth']   = $endingDate[1];
			$schoolyearArray['startingyear'] = $startingDate[0];
			$schoolyearArray['endingyear']   = $endingDate[0];
			$schoolyearArray['startingdate']   = date('d-m-Y', strtotime($schoolyear->startingdate));
			$schoolyearArray['endingdate']   = date('d-m-Y', strtotime($schoolyear->endingdate));
			$schoolyearArray['schoolyear'] = $schoolyear->schoolyear;
		}
		return (object) $schoolyearArray;
	}
}