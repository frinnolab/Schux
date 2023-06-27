<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Childcare_m extends MY_Model {

	protected $_table_name = 'childcare';
	protected $_primary_key = 'childcareID';
	protected $_primary_filter = 'intval';
	protected $_order_by = "childcareID desc";

	function __construct() {
		parent::__construct();
	}

	public function get_childcare($array=NULL, $signal=FALSE) {
		$query = parent::get($array, $signal);
		return $query;
	}

	public function get_join_childcare_all($schoolyearID = NULL, $id = NULL) {
        $this->db->select('childcare.*,student.name, classes.classes');
        $this->db->from('childcare');
        $this->db->join('classes', 'classes.classesID = childcare.classesID', 'LEFT');
        $this->db->join('student', 'student.studentID = childcare.userID', 'LEFT');
        if ((int)$id) {
            $this->db->where("childcare.childcareID", $id);
        }

        if((int)$schoolyearID) {
        	$this->db->where('childcare.schoolyearID', $schoolyearID);
        }

        $this->db->order_by($this->_order_by);
        $query = $this->db->get();
        return $query->result();
	}

	public function get_order_by_childcare($array=NULL) {
		$query = parent::get_order_by($array);
		return $query;
	}

	public function get_single_childcare($array=NULL) {
		$query = parent::get_single($array);
		return $query;
	}

	public function insert_childcare($array) {
		$id = parent::insert($array);
		return $id;
	}

	public function update_childcare($data, $id = NULL) {
		parent::update($data, $id);
		return $id;
	}

	public function delete_childcare($id){
		parent::delete($id);
	}

	public function upload_document($file) {
		$target_dir = "uploads/documents";
		$target_file = $target_dir . basename($_FILES[$file]["name"]);
		$uploadOk = 1;
		$documentFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
		$retArray = [];
		$retArray['status'] = TRUE;
		// Check if image file is a actual image or fake image
		if(isset($_POST["submit"])) {
			$finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime = finfo_file($finfo, $_FILES[$file]['tmp_name']);
            if ($mime != 'application/pdf') {
				$retArray['status'] = FALSE;
                $retArray['error']['not_pdf'] = 'this is not a PDF file!';
			}
		}

		// Check if file already exists
		if (file_exists($target_file)) {
			$retArray['status'] = FALSE;
			$retArray['error']['file_exist'] = 'File Exist Already!';
		}

		// Check file size
		if ($_FILES[$file]["size"] > 500000) {
			$retArray['status'] = FALSE;
			$retArray['error']['file_large'] = 'File larger than 5MB';
		}

		// Allow certain file formats
		if($documentFileType != "pdf") {
			$retArray['status'] = FALSE;
			$retArray['error']['file_extension'] = 'File not PDF';
		}

		// Check if $uploadOk is set to 0 by an error
		if ($retArray['status']) {
			// if everything is ok, try to upload file
			$retArray['status'] = TRUE;
			if (move_uploaded_file($_FILES[$file]["tmp_name"], $target_file)) {
			} else {
				$retArray['status'] = FALSE;
				$retArray['error']['upload_failure'] = 'File not uploaded. Probably server error';
			}
		}
		return $retArray;
	}
}