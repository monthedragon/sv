<?php
Class Reports_model extends CI_Model{ 

	var $contactIDs = array();
	var $suppleDetails = array();//arr of detail for supple
	var $cardDetails = array();//arr of dtails for card details
	var $agDetails = array(); //agreement details
	
	public function __construct()
	{
		$this->load->database();

	}
	 
	
	public function generate_report($crm_id,$table_recid='',$calldate=''){
		$this->crm_id = $crm_id;
		
		$crmDetails = $this->my_utils->get_crm_by_id($crm_id);		
		$newconn = $this->my_utils->change_conn($crmDetails);
		
		$pdata = $this->input->get();
		$retVal = array();
		$newconn->select('*')
						->from($crmDetails['main_table'])
						->where($crmDetails['callresult_field'],$crmDetails['sale_tag']);
		
		if(!empty($table_recid)){
			$newconn->where('id',$table_recid);
		}		

		if(!empty($calldate) && !empty($calldate)){
			$newconn->where($crmDetails['calldate_field'].' >= ' , "{$calldate} 00:00:00")
							->where($crmDetails['calldate_field'].' <= ' , "{$calldate} 23:59:59");
		}

		$result = $newconn->get()->result_array();
		
		foreach($result as $details){
			$this->contactIDs[$details['id']] =$details['id'];
			$this->agDetails[$details['id']] = $details;
		}

		if(count($result)<=0){
			echo "No RECORD FOUND!";
		}else{
			$this->get_supple();
			$this->get_cards();
		}

	}
	 
	
	function get_supple(){
		$crmDetails = $this->my_utils->get_crm_by_id($this->crm_id);		
		$newconn = $this->my_utils->change_conn($crmDetails);
		$result = $newconn->select('*')
					->from('supplementary')
					->where_in('baserecid',$this->contactIDs)
					->get()
					->result_array();
		foreach($result as $details){
			$this->suppleDetails[$details['baserecid']][$details['id']] = $details;
		}
	}
	
	function get_cards(){
		$crmDetails = $this->my_utils->get_crm_by_id($this->crm_id);		
		$newconn = $this->my_utils->change_conn($crmDetails);
		$result = $newconn->select('*')
					->from('card_details')
					->where_in('baserecid',$this->contactIDs)
					->order_by('issuer')
					->get()
					->result_array();
		
		foreach($result as $details){
			$this->cardDetails[$details['baserecid']][$details['issuer']][$details['id']] = $details;
		}
	}
	
	
	
	//iden =0 regular array; iden = 1 index of lu_code
	//all = 1 get all even its not active
	function getLookup($lu_cat,$iden=0,$all=1){
		static $newconn;
		
		if(!$newconn){
			$crmDetails = $this->my_utils->get_crm_by_id($this->crm_id);		
			$newconn = $this->my_utils->change_conn($crmDetails);
		}
		
		$data = array();
		$newconn->select('*')->from('lookup')
					->where('lu_cat',$lu_cat);
		
		if(!$all) //if not all then get only all active
			$newconn->where('is_active',1);
		
		$result = $newconn->order_by('order_by')
					->get()
					->result_array();
					
		if($iden == 0)		
			$data = $result;
		else{
			
			foreach($result as $d)
				$data[$d['lu_code']] = $d['lu_desc'];
				
		}
		
		return $data;
	}
	
	public function getLastSVRemarks($table_recid){
		
        $result = $this->db->select('*')
				->from('remarks')
				->where('table_recid',$table_recid)
				->order_by('time_stamp','DESC')
				->limit('1')
				->get()
				->result_array();
				
		if($result){
			return $result[0]['remarks'];
		}
		
		return $false;
	}
	
	//save the currently selected DMC config so that later the those CR will be selected automatically
	public function saveDMCConfig(){
		
		$pdata = $this->input->post();
		$crmDetails = $this->my_utils->get_crm_by_id($pdata['crm_id']);
		$newconn = $this->my_utils->change_conn($crmDetails);
		$newconn->query("DELETE FROM lookup WHERE lu_cat = 'conversion_rate_lu'");
		
		$target_pdata_index = array('CB_cr','NI_cr','cr');
		
		foreach($target_pdata_index as $pdata_key){
			if(isset($pdata[$pdata_key])){
				foreach($pdata[$pdata_key] as $code){
					$insertData = array(
											'lu_code' 	=> $code,
											'lu_desc' 	=> $code,
											'lu_cat' 	=> 'conversion_rate_lu',
											'is_active' => 1,
										);
					$newconn->insert('lookup',$insertData);
				}
			}
		}
	}
	
	public function get_agent_list_in_main_table(){
		$crmDetails = $this->my_utils->get_crm_by_id($this->reports_model->crm_id);
		$newconn = $this->my_utils->change_conn($crmDetails);
		
        $agengList = array();

        //display even old or inactive agent!
        $result = $newconn->from($crmDetails['user_table'])->get()->result_array();

        foreach($result as $details){
            //user_name and user_password shall be used to all CAMPAIGN
				
			$username = ($details['user_name']);
			
            if($lowerusername){
				$username = strtolower($details['user_name']);
			}
			
            $agengList[$username] = $details['firstname']. ' ' . $details['lastname'];
        } 
		
        return $agengList;

    }
	
	public function getLOSData(){
		
		$pdata = $this->input->get();
		$start_date = $pdata['start_calldate'];
		$end_date 	= $pdata['end_calldate'];
		$crm_id 	= $pdata['crm_id'];
		$crmDetails = $this->my_utils->get_crm_by_id($crm_id);		
		$crm_code = $crmDetails['crm_code'];
		$sv_user_id	= $pdata['user_id'];
		
		$user_id_sql_where = '';
		if($sv_user_id != 'all'){
			$user_id_sql_where = " AND sales.user_id = '{$sv_user_id}' ";
		}
		
		$query = "
					SELECT 
						sales.* ,
						sales_details.sales_id ,
						sales_details.original_value,
						sales_details.new_value,
						sales_details.field_name
					FROM sales
					INNER JOIN sales_details ON sales_id = sales.id
					WHERE sales.status = 1
					AND sales.crm_code = '{$crm_code}'
					AND sales.calldate  >= '{$start_date} 00:00:00'
					AND sales.calldate  <= '{$end_date} 23:59:59'
					{$user_id_sql_where}
					ORDER BY lastname, firstname";
		$result = $this->db->query($query)->result_array();
		// echo '<pre>';
		// print_r($this->db->last_query());
		
		$this->los_data = array();
		
		foreach($result as $data){
			$recid 		= $data['table_recid'];
			$sales_id 	= $data['sales_id'];
			$field_name = 'sv_'.$data['field_name']; //appened SV so that it wont mix up on the origina contact_list field
			$value 		= (trim($data['new_value'])) ? $data['new_value'] : $data['original_value'];
			
			if(!isset($this->los_data[$recid])){
				$this->los_data[$recid] = array();
			}
			$this->los_data[$recid][$field_name] = trim($value);
		}
		
		$this->LOSmainData();
		$this->getLastSVRemarkByBulk(array_keys($this->los_data));
		// exit();
	}
	
	public function LOSmainData(){
		$pdata 		= $this->input->get();
		$crm_id 	= $pdata['crm_id'];
		$crmDetails = $this->my_utils->get_crm_by_id($crm_id);	
		$conn_obj = $this->my_utils->change_conn($crmDetails);
		$recid_str = "('".implode("','",array_keys($this->los_data))."')";
		
		//SET ONLY necessary fields to avoid any memorry issue
		$query = "SELECT 
							id, 
							amf_product,
							c_esoa,
							c_lastname,
							c_firstname,
							c_middlename,
							c_is_web_shopper,
							c_name_in_card,
							c_nationality,
							c_sss_gsis,
							c_tin,
							c_us_tin,
							c_with_personal_changes,
							cust_nbr,
							email_addr,
							mobileno,
							officeno,
							telno,
							pd_name,
							tin,
							2nd_card_credit_limit
						FROM contact_list WHERE id IN {$recid_str}";
		$result = $conn_obj->query($query)->result_array();
		
		foreach($result as $data){
			$recid = $data['id'];
			
			//append the information from contact_list
			$this->los_data[$recid] += $data;
		}
			
	}
	
	//get the latest SV remarks via BULK process
	//$rec_id_arr should be ARRAY of table_recid
	public function getLastSVRemarkByBulk($rec_id_arr){
		$recid_str = "('".implode("','",$rec_id_arr)."')";
		
		$query = "SELECT table_recid,remarks FROM remarks
					WHERE table_recid IN {$recid_str}
					ORDER BY table_recid,time_stamp DESC";
		
		$result = $this->db->query($query)->result_array();
		
		foreach($result as $data){
			$recid = $data['table_recid'];
			
			if(!isset($this->los_data[$recid]['sv_remark'])){
				//If there will many remarks from ONE SALE
				//Get only the LATEST one since the query is SORTED via time_stamp DESC
				//Then the very first remarks to be set will be the LATEST 
				$this->los_data[$recid]['sv_last_remark'] = $data['remarks'];
			}
		}			
	}
	
	//2021-07-03
	//get sv list to be used on the pulldown on LOS reports_model-
	//If the user is non-admin then only his account will be available on the list
	public function getLosSVlist(){
		$user_type = $this->session->userdata['user_type'];
		
		$sv_arr = array();
		$show_all_sv = has_access(185); //rights on displaying ALL SV from the pulldown even for non-admin
		if($show_all_sv){
			$sv_arr['all'] = 'All SV';
			$sv_arr += $this->get_all_users();
		}else{
			$username = $this->session->userdata['username'];
			$full_name = $this->session->userdata['full_name'];
			$sv_arr[$username] = $full_name;
		}
		
		return $sv_arr;
		
	}
	
	public function get_all_users(){
		$result = $this->db->select("users.*, CONCAT(users.firstname, ' ' , users.lastname) AS full_name",false)
							->from('users')
							->where('is_active',1)
							->order_by('firstname')
							->get()
							->result_array();
			
		$retVal = array();
		foreach($result as $details){
			$retVal[$details['user_name']] = $details['full_name'];
		}
		
		return $retVal;
	}

    public function getVerifiedRecords(){

        $pdata = $this->input->post();
        $crmDetails = $this->my_utils->get_crm_by_id($pdata['crm_id']);

        $targetRecId = $this->getSVVerifiedRecords();
        $contactList = $this->getRecordsFromMainDB($targetRecId, $crmDetails);
        return $this->reconContactList($contactList, $crmDetails);
    }

    public function getSVVerifiedRecords(){
        $pdata = $this->input->post();
        $whereArr = array(  'sales.verified_date >='=>$pdata['start_calldate'] . ' 00:00:00',
                            'sales.verified_date <='=>$pdata['end_calldate'] . ' 23:59:59',);

        $whereArr['crm.id'] = $pdata['crm_id'];

        $result = $this->db->select('table_recid',false)
            ->from('sales')
            ->join('crm', ' crm.crm_code =  sales.crm_code')
            ->where($whereArr)
            ->get()
            ->result_array();
         //echo $this->db->last_query();

        return $result;
    }

    public function getRecordsFromMainDB($recordIdArr, $crmDetails){

        $pdata = $this->input->post();

        $newconn = $this->my_utils->change_conn($crmDetails);
        $mainTable = $crmDetails['main_table'];
        $calldateField = $crmDetails['calldate_field'];
        $agentField = $crmDetails['agent_field'];
        $crField = $crmDetails['callresult_field'];
        $saleTag  = $crmDetails['sale_tag'];
        $castedCalldate = '';

        $whereArr = array(  "{$calldateField} >="=>$pdata['start_calldate'] . ' 00:00:00',
                            "{$calldateField} <="=>$pdata['end_calldate'] . ' 23:59:59');

        // Determine date format and grouping based on report type
        if ($pdata['type'] == 'daily') {
            $castedCalldate = "DATE({$calldateField}) AS report_date";
        } elseif ($pdata['type'] == 'monthly') {
            $castedCalldate = "DATE_FORMAT({$calldateField}, '%Y-%m') AS report_date";
        } else { // yearly
            $castedCalldate = "YEAR({$calldateField}) AS report_date";
        }

        $result = $newconn->select("{$agentField}, {$castedCalldate}, COUNT(*) AS total", false)
            ->from($mainTable)
            ->where($whereArr)
            ->group_by("{$agentField}, report_date")
            ->order_by('report_date', 'ASC')
            ->get()
            ->result_array();
        //echo $newconn->last_query();

        //echo "<pre>";
        //print_r($result);
        return $result;
    }

    public function reconContactList($result, $crmDetails){
        $pivotData = [];
        $agentField = $crmDetails['agent_field'];
        $columns = [];

        foreach ($result as $row) {
            $agent = $row[$agentField];
            $date = $row['report_date'];
            $pivotData[$agent][$date] = $row['total'];
            $columns[$date] = $date;
        }

//        echo "<pre>";
//        print_r($pivotData);
        return [$pivotData, $columns];
    }
}
?>