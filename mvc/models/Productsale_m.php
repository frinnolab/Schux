<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Productsale_m extends MY_Model {

    protected $_table_name = 'productsale';
    protected $_primary_key = 'productsaleID';
    protected $_primary_filter = 'intval';
    protected $_order_by = "productsaleID desc";

    function __construct() {
        parent::__construct();
    }

    function get_productsale($array=NULL, $signal=FALSE) {
        $query = parent::get($array, $signal);
        return $query;
    }

    function get_single_productsale($array) {
        $query = parent::get_single($array);
        return $query;
    }

    function get_order_by_productsale($array=NULL) {
        $query = parent::get_order_by($array);
        return $query;
    }

    function insert_productsale($array) {
        $id = parent::insert($array);
        return $id;
    }

    function update_productsale($data, $id = NULL) {
        parent::update($data, $id);
        return $id;
    }

    public function delete_productsale($id){
        parent::delete($id);
    }

    public function get_all_productsale_for_report($queryArray) {

        $schoolyearID = $this->session->userdata('defaultschoolyearID');

        $this->db->select('productsale.*,productsaleitem.*, product.productname');
        $this->db->from('productsale');
        $this->db->join('productsaleitem', 'productsale.productsaleID = productsaleitem.productsaleID');
        $this->db->join('product', 'product.productID = productsaleitem.productID');

        if(isset($queryArray['productsalecustomertypeID']) && $queryArray['productsalecustomertypeID'] != 0) {
            $this->db->where('productsale.productsalecustomertypeID', $queryArray['productsalecustomertypeID']);
        }

        if(isset($queryArray['productsalecustomerID']) && $queryArray['productsalecustomerID'] != 0) {
            $this->db->where('productsale.productsalecustomerID', $queryArray['productsalecustomerID']);
        }

        if(!empty($queryArray['reference_no']) && $queryArray['reference_no'] != '0') {
            $this->db->where('productsale.productsalereferenceno', $queryArray['reference_no']);
        }

        if(isset($queryArray['statusID']) && $queryArray['statusID'] != 0) {
            if($queryArray['statusID'] == '4') {
                $this->db->where('productsale.productsalerefund', 1);
            } else {
                $this->db->where('productsale.productsalestatus', $queryArray['statusID']);
                $this->db->where('productsale.productsalerefund !=', 1);
            }
        } else {
            $this->db->where('productsale.productsalerefund !=', 1);
        }

        if((isset($queryArray['fromdate']) && $queryArray['fromdate'] != 0) && (isset($queryArray['todate']) && $queryArray['todate'] != 0)) {
            $fromdate = date('Y-m-d', strtotime($queryArray['fromdate']));
            $todate = date('Y-m-d', strtotime($queryArray['todate']));
            $this->db->where('productsaledate >=', $fromdate);
            $this->db->where('productsaledate <=', $todate);
        }
        $this->db->where('productsale.schoolyearID',$schoolyearID);
        $this->db->order_by('productsale.productsaleID','DESC');
        $query = $this->db->get();
        return $query->result();
    }
}
