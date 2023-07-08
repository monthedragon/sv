<?php
class Manage_model extends CI_Model {
	var $retVal = array();

	public function __construct()
	{
		$this->load->database();
	}
	
	public function search_customer(){
		$pdata = $this->input->post();
		$crmDetails = $this->my_utils->get_crm_by_id($pdata['crm_id']);
		
        $newconn = $this->my_utils->change_conn($crmDetails);
		
		$fnameField = $crmDetails['firstname_field'];
		$lnameField = $crmDetails['lastname_field'];
		$calldateField = $crmDetails['calldate_field'];
		$callresultField = $crmDetails['callresult_field'];
		
		$newconn->select("id,{$fnameField},{$lnameField},{$calldateField},{$callresultField}")
				->from($crmDetails['main_table']);
				
		if(!empty($pdata['start_calldate']))
			$newconn->where('calldate >=',$pdata['start_calldate'] . ' 00:00:00');
		
		if(!empty($pdata['end_calldate']))
			$newconn->where('calldate <=',$pdata['end_calldate'] . ' 23:59:59');

		if(!empty($pdata['firstname']))
			$newconn->like($fnameField,$pdata['firstname']);
			
		if(!empty($pdata['lastname']))
			$newconn->like($lnameField,$pdata['lastname']);
			
		$result = $newconn->limit(20)->order_by($calldateField,'DESC')->get()->result_array();
		// echo $newconn->last_query();
		
		return $result;
	}
	
	//for the meantime update the calldate!
	public function update_main($crm_id){
		$pdata = $this->input->post();
		$whereArr = array('id'=>$pdata['id']);
		$crmDetails = $this->my_utils->get_crm_by_id($crm_id);
		
        $newconn = $this->my_utils->change_conn($crmDetails);
		
		$newconn->update($crmDetails['main_table'],$pdata,$whereArr);
		echo $newconn->last_query();
	}
	
	public function getCampaignLookUp(){
		$pdata = $this->input->post();
		$crmDetails = $this->my_utils->get_crm_by_id($pdata['crm_id']);
        $newconn = $this->my_utils->change_conn($crmDetails);
		$lu_arr_raw = $newconn->select('*')->from('lookup')->where('lu_cat','callresult')->get()->result_array();
		
		$lu_arr = array();
		foreach($lu_arr_raw as $details){
			$lu_arr[$details['lu_code']] = $details['lu_desc'];
		}
		
		return $lu_arr;
		
	}
}