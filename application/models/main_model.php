<?php
class Main_model extends CI_Model {
	var $retVal = array();

	public function __construct()
	{
		$this->load->database();
	}
	
	public function change_conn($cd){
		$config['hostname'] = $cd['hostname'];
		$config['username'] = $cd['username'];
		$config['password'] = $cd['password'];
		$config['database'] = $cd['campaign_db'];
		$config['dbdriver'] = "mysql";
		$config['dbprefix'] = "";
		$config['pconnect'] = FALSE;
		$config['db_debug'] = TRUE;

		return $this->load->database($config,true);
	}

    public function get_crm(){
        return $this->db->from('crm')->where('is_active',1)->get()->result_array();
    }

	public function construct_crm_dashboard($crms,$calldate=''){
		$retValArr = array();
		//set this as 1 just to get the count and not the details of the sales
		
		$this->sales_model->show_less_field = 1;
		
		if(empty($calldate))
			$calldate = date('Y-m-d');
		
		foreach($crms as $k=>$details){
			
			$salesDetails = $this->sales_model->get_sales($details['id'],$calldate);
			
			//include the number of new and veriifed sales in the details of the crm
			$details['new_sales'] = count($salesDetails['NEW']);
			$details['verified_sales'] = count($salesDetails['VERIFIED']);
			$retValArr[$details['crm_code']] = $details;
		}
		
		return $retValArr;
	}
	

}