<?php
class Remarks_model extends CI_Model {
	var $retVal = array();
	
	public function __construct()
	{
		$this->load->database();
	}
	
	public function get_remarks($sale_id){
		$saleDetails = $this->sales_model->get_sale_main_details($sale_id);
		
		$whereArr = array(
				'crm_code'=>$saleDetails['crm_code'],
				'table_recid'=>$saleDetails['table_recid']
			);
		$remarks = $this->db->from('remarks')
							->where($whereArr)
							->order_by('time_stamp','DESC')
							->get()
							->result_array();
		
		return $remarks;
	}
	
    

}