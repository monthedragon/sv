<?php
Class Reports extends Auth_Controller{
	var $data = array(); 
	
	function __construct(){
		parent::__construct();
		$this->load->model('reports_model');
		$this->load->model('sales_model');
		$this->load->helper('url');
		$this->load->library('session');    
		$this->data = $this->get_privs();
		$this->load->library('MY_utils');
	}
	
	public function sales(){
		$this->set_header_data(PROJECT_NAME,'Sales Per Hour');
		$crms = $this->my_utils->get_all_crms();
		$newCrms = array();
		foreach($crms as $details){
			$newCrms[$details['id']] = $details['name'];
		}
		
		$data['start_calldate'] = isset($pdata['start_date']) ? $pdata['start_calldate'] : date('Y-m-d');
		$data['end_calldate'] = isset($pdata['end_calldate']) ? $pdata['end_calldate'] : date('Y-m-d');
		$data['crms'] = $newCrms;
		
		$this->load->view('reports/sale_per_hour',$data);
		$this->load->view('templates/footer');
	}
	
	public function sph(){
		$pdata = $this->input->post();
		$crmDetails = $this->my_utils->get_crm_by_id($pdata['crm_id']);
		$newconn = $this->my_utils->change_conn($crmDetails);
		$selectFields = "{$crmDetails['agent_field']} as agent_id,SUBSTR(TIME({$crmDetails['calldate_field']}),1,2) AS TIME,COUNT(*) as SALE";
		$whereArr = array($crmDetails['calldate_field'].' >='=>$pdata['start_calldate'] . ' 00:00:00',
						  $crmDetails['calldate_field'].' <='=>$pdata['end_calldate'] . ' 23:59:59',
						  $crmDetails['callresult_field']=>$crmDetails['sale_tag']);
		
		$result = $newconn->select($selectFields,false)
				->from($crmDetails['main_table'])
				->where($whereArr)
				->order_by("TIME")
				->group_by("TIME,{$crmDetails['agent_field']}")->get()->result_array();
		//echo $newconn->last_query();
		
		$retValArr = array();
		foreach($result as $details){
			$retValArr[$details['agent_id']][$details['TIME']] = $details['SALE'];
		}
		
		
		$times = array();
		for($i=1;$i<=24;$i++){
			$key = (($i<=9) ? 0 : '' ) . $i;
			//$kw = (($i<=9) ? 'AM' : 'PM' ) ;
			$times[$key]=$i.':00';
		}
		$data['times'] = $times;
		$data['sph'] = $retValArr;
		$data['agentArr'] =  $this->sales_model->get_agent_list_in_main_table($newconn,$crmDetails);
		$this->load->view('reports/sph',$data);
	}
	
	
	public function verify(){
		$this->set_header_data(PROJECT_NAME,'Verify Per Hour');
		$crms = $this->my_utils->get_all_crms();
		$newCrms = array();
		foreach($crms as $details){
			$newCrms[$details['id']] = $details['name'];
		}
		
		$data['start_calldate'] = isset($pdata['start_date']) ? $pdata['start_calldate'] : date('Y-m-d');
		$data['end_calldate'] = isset($pdata['end_calldate']) ? $pdata['end_calldate'] : date('Y-m-d');
		$data['crms'] = $newCrms;
		
		$this->load->view('reports/verify_per_hour',$data);
		$this->load->view('templates/footer');
	}
	/**
	* 2020-11-14
	* Verify Per Hour 
	**/
	public function vph(){
		$pdata = $this->input->post();
		$selectFields = "user_id ,SUBSTR(TIME(sales.verified_date),1,2) AS TIME,COUNT(*) as VERIFIED";
		$whereArr = array('sales.verified_date >='=>$pdata['start_calldate'] . ' 00:00:00',
						  'sales.verified_date <='=>$pdata['end_calldate'] . ' 23:59:59',);
						  
		if($pdata['crm_id']){
			$whereArr['crm.id'] = $pdata['crm_id'];
		}
		
		$result = $this->db->select($selectFields,false)
				->from('sales')
				->join('crm', ' crm.crm_code =  sales.crm_code')
				->where($whereArr)
				->order_by("TIME")
				->group_by("TIME,user_id")
				->get()
				->result_array();
		// echo $this->db->last_query();
		
		$retValArr = array();
		foreach($result as $details){
			$retValArr[strtolower($details['user_id'])][$details['TIME']] = $details['VERIFIED'];
		}
		
		
		$times = array();
		for($i=1;$i<=24;$i++){
			$key = (($i<=9) ? 0 : '' ) . $i;
			//$kw = (($i<=9) ? 'AM' : 'PM' ) ;
			$times[$key]=$i.':00';
		}
		$data['times'] = $times;
		$data['vph'] = $retValArr;
		$data['svArr'] =  $this->sales_model->get_all_sv();
		$this->load->view('reports/vph',$data);
	}
	
	
	public function recurring_leads($crm_id = '', $do_old_version = 0){
		//2020-03-07
		//$do_old_version is only applicable for CHALLENGER this is to have an option to generate the report from previous version
		
		$this->set_header_data(PROJECT_NAME,' Recurring Leads');
		$crms = $this->my_utils->get_all_crms();
		$newCrms = array();
		foreach($crms as $details){
            if(!$details['is_active']) continue;
			$newCrms[$details['id']] = $details['name'];
		}
		
		$lead_identity_arr = array();
		$data['p_crm_id'] = $crm_id;
		if($crm_id){
			$crmDetails = $this->my_utils->get_crm_by_id($crm_id);		
			$newconn = $this->my_utils->change_conn($crmDetails);
			$result = $newconn->select('*')
								->from('leads_details')
								->where('is_active',1)
								->get()
								->result_array();
			foreach($result as $details){
				$lead_identity_arr[$details['lead_identity']]  = $details['lead_identity'];
			}
		}
		
		$data['lead_identity_arr'] = $lead_identity_arr;
		
		$data['start_calldate'] = isset($pdata['start_date']) ? $pdata['start_calldate'] : date('Y-m-d');
		$data['end_calldate'] = isset($pdata['end_calldate']) ? $pdata['end_calldate'] : date('Y-m-d');
		$data['crms'] = $newCrms;
		$data['do_old_version'] = $do_old_version;
		
		$this->load->view('reports/recurring_leads',$data);
		$this->load->view('templates/footer');
	}
	
	public function recurring_leads_generate($do_old_version = 0){

		$pdata = $this->input->post();		
		
		$this->reports_model->crm_id = $pdata['crm_id'];
		$data['cb_lookup'] = $this->reports_model->getLookup('cb',1);
		$data['ni_lookup'] = $this->reports_model->getLookup('ni',1);
		$data['callresult_lookup'] = $this->reports_model->getLookup('callresult',1);

		$crmDetails = $this->my_utils->get_crm_by_id($pdata['crm_id']);
		$newconn = $this->my_utils->change_conn($crmDetails);
		
		$is_challenger = ($this->reports_model->crm_id == 9);

        if($is_challenger){
			//challenger
			$selectFields = "	
								ch_campaign,
								callresult,
								IF(callresult IN ('NI', 'CB'), sub_callresult,
									IF (callresult = 'AG', 'SALES', callresult)
								) AS 'FINAL_DISPO', 
							    SUM(IF(callresult = 'AG', REPLACE(c_loan_amount, ',', ''), 0)) AS LOAN_AMOUNT,
								COUNT(*) AS CTR ";
		}else{
            //default query

            $customSelect = "   IF(callresult IN ('NI', 'CB'), sub_callresult,
									IF (callresult = 'AG', 'SALES', callresult)
								) AS 'FINAL_DISPO',
            ";

            if($this->reports_model->crm_id == 1){

                $customSelect = "IF(callresult IN ('NI', 'CB'), sub_callresult,
								    IF (callresult = 'AG' AND sv_card_request_by_client IS NOT NULL, (sv_card_request_by_client),
										IF (callresult = 'AG' AND sv_card_request_by_client IS NULL, 'SALE_UNVERIFIED' ,callresult)
									)
								) AS 'FINAL_DISPO',";
                $customSelect .= "COUNT(CASE WHEN c_is_web_shopper = 1 AND CALLRESULT =  'AG' THEN 1 ELSE NULL END) AS 'WEB_SHOPPER_AG_CTR',";

            }

            $selectFields = "
                            lead_identity,
							callresult,
							{$customSelect}
							COUNT(CASE WHEN c_location = 'MANILA' THEN 1 ELSE NULL END) AS 'MLA',
							COUNT(CASE WHEN c_location = 'PROVINCIAL' THEN 1 ELSE NULL END) AS 'PROV',
							COUNT(CASE WHEN c_location NOT IN ('PROVINCIAL','MANILA') THEN 1 ELSE NULL END) AS 'UNTAG_LOC',
							COUNT(*) AS CTR";
        }

		$whereArr = array($crmDetails['calldate_field'].' >='=>$pdata['start_calldate'] . ' 00:00:00',
						  $crmDetails['calldate_field'].' <='=>$pdata['end_calldate'] . ' 23:59:59');
		
		$newconn->select($selectFields,false)
				->from($crmDetails['main_table'])
				->where($whereArr);

		if($pdata['lead_identity']){
			$newconn->where_in('lead_identity',$pdata['lead_identity']);
		}
		 
		if($is_challenger){
			$newconn->where("ch_campaign != ''");
			$data['result'] = $newconn->order_by("ch_campaign,callresult,FINAL_DISPO")
									->group_by("ch_campaign,FINAL_DISPO")->get()->result_array();
		}else{
			$data['result'] = $newconn->order_by("lead_identity,callresult,FINAL_DISPO")
									->group_by("lead_identity,FINAL_DISPO")->get()->result_array();
		}			
		

		//echo '<pre>'.$newconn->last_query();
		$data['ag_res'] = $this->getOnlyAGResult($newconn,$crmDetails,$pdata);
		// echo '<pre>';
		// var_dump($data['only_AG_result']);
		// exit();
		// echo $newconn->last_query();
		// exit();
		
		if($is_challenger){
			//rl = recurring leads
			$trgt_view = 'rl_challenger';
		}else{
			$trgt_view = 'recurring_leads_generate';
		}
		$this->load->view('reports/'.$trgt_view,$data);
	}
	
	function getOnlyAGResult($newconn,$crmDetails,$pdata){

        $customSelect = '';
        if($this->reports_model->crm_id == 1){
            $customSelect = ",COUNT(CASE WHEN c_is_web_shopper = 1 AND c_location = 'MANILA' THEN 1 ELSE NULL END) AS 'WEB_SHOP_MLA',
						     COUNT(CASE WHEN c_is_web_shopper = 1 AND c_location = 'PROVINCIAL' THEN 1 ELSE NULL END) AS 'WEB_SHOP_PROV'";
        }
		$selectFields = "  lead_identity, 
						   COUNT(CASE WHEN c_location = 'MANILA' THEN 1 ELSE NULL END) AS 'AG_MLA', 
						   COUNT(CASE WHEN c_location = 'PROVINCIAL' THEN 1 ELSE NULL END) AS 'AG_PROV'
						   {$customSelect}
						   ";
		
		$whereArr = array($crmDetails['calldate_field'].' >='=>$pdata['start_calldate'] . ' 00:00:00',
						  $crmDetails['calldate_field'].' <='=>$pdata['end_calldate'] . ' 23:59:59');
		
		$newconn->select($selectFields,false)
				->from($crmDetails['main_table'])
				->where($whereArr);
				
		//ONLY AG to be gathered
		$newconn->where('callresult','AG');
		
		if($pdata['lead_identity']){
			$newconn->where_in('lead_identity',$pdata['lead_identity']);
		}
		  				
		$result = $newconn->order_by("lead_identity")->group_by("lead_identity")->get()->result_array();
		
		// echo $newconn->last_query();
		// exit();
		
		$all_ag_arr  = array(); 
		foreach($result as $details){
			$all_ag_arr[$details['lead_identity']] = $details;
		}
		
		return $all_ag_arr;
	}
	
	//ADDED 2019-05-04
	//MAIN entry point in creating CONVERSION RATE table
	public function conversion_rate($crm_id=''){
		$pdata = $this->input->post();
		
		$this->set_header_data(PROJECT_NAME,' Conversion Rate');
		$crms = $this->my_utils->get_all_crms();
		$newCrms = array();
		foreach($crms as $details){
			$newCrms[$details['id']] = $details['name'];
		}
		
		$data['p_crm_id'] = $crm_id;
		if($crm_id){
			$this->reports_model->crm_id = $crm_id;
			$data['cb_lookup'] = $this->reports_model->getLookup('cb',1);
			$data['ni_lookup'] = $this->reports_model->getLookup('ni',1);
			$data['callresult_lookup'] = $this->reports_model->getLookup('callresult',1);
			$data['conversion_rate_lu'] = $this->reports_model->getLookup('conversion_rate_lu',1);
		}
		
		$data['start_calldate'] = isset($pdata['start_date']) ? $pdata['start_calldate'] : date('Y-m-d');
		$data['end_calldate'] = isset($pdata['end_calldate']) ? $pdata['end_calldate'] : date('Y-m-d');
		$data['crms'] = $newCrms;
		
		$this->load->view('reports/conversion_rate',$data);
		$this->load->view('templates/footer');
	}
	
	//ADDED 2019-05-04
	//Main
	public function conversion_rate_generate(){
		
		$pdata = $this->input->post();
		$this->reports_model->saveDMCConfig(); //save the currently selected DMC config so that later the those CR will be selected automatically
		
		$this->reports_model->crm_id = $pdata['crm_id'];
		$data['cb_lookup'] = $this->reports_model->getLookup('cb',1);
		$data['ni_lookup'] = $this->reports_model->getLookup('ni',1);
		$data['callresult_lookup'] = $this->reports_model->getLookup('callresult',1); //GET the previously saved DMC setup to be used on the SCREEN to make CR auto selected
		$data['agent_list'] = $this->reports_model->get_agent_list_in_main_table();

		$crmDetails = $this->my_utils->get_crm_by_id($pdata['crm_id']);
		$newconn = $this->my_utils->change_conn($crmDetails);
		$selectFields = "   agent,
							COUNT(*) AS DMC,
							SUM(CASE WHEN callresult = 'AG' THEN 1 ELSE 0 END) AS SALE";
		$whereArr = array($crmDetails['calldate_field'].' >='=>$pdata['start_calldate'] . ' 00:00:00',
						  $crmDetails['calldate_field'].' <='=>$pdata['end_calldate'] . ' 23:59:59');
						  
		
		$data['result'] = $newconn->select($selectFields,false)
				->from($crmDetails['main_table'])
				->where($whereArr);

		$callresult_where_str = " callresult = 'AG' ";
		
		if(isset($pdata['CB_cr'])){ //set where query for CB
			$sub_callresult_str = implode("','",$pdata['CB_cr']);
			$callresult_where_str .= "  OR (callresult = 'CB' AND sub_callresult IN ('{$sub_callresult_str}')) ";
		}
		
		if(isset($pdata['NI_cr'])){ //set where query for NI
			$sub_callresult_str = implode("','",$pdata['NI_cr']);
			$callresult_where_str .= "  OR   (callresult = 'NI' AND sub_callresult IN ('{$sub_callresult_str}')) ";
		}
		
		if(isset($pdata['cr'])){ //set where query for NON-NI and NON-CB
			$sub_callresult_str = implode("','",$pdata['cr']);
			$callresult_where_str .= "  OR  (callresult IN ('{$sub_callresult_str}')) ";
		}
		
		$newconn->where("($callresult_where_str)",NULL,FALSE);
				
		$data['result'] = $newconn->group_by($crmDetails['agent_field'])->get()->result_array();
		// echo $newconn->last_query();
		// exit();
		
		$this->load->view('reports/conversion_rate_generate',$data);
	}
	
	/*************************************
	*************************************
	* START OF GENERATING SALESFILE (SF)
	*************************************
	*************************************/
	
	public function generate_xls_report($crm_id,$table_recid='',$calldate=''){
		$this->reports_model->generate_report($crm_id,$table_recid);
		$data = $this->input->get();
		
		$data['crm_id'] = $crm_id;
		$data['table_recid'] = $table_recid;
		$data['ownership'] = $this->reports_model->getLookup('ownership');
		$data['car_ownership'] = $this->reports_model->getLookup('car_ownership');
		$data['education_attainment'] = $this->reports_model->getLookup('education_attainment');
		$data['yesno'] = $this->reports_model->getLookup('yesno');
		$data['yesnoLU'] = $this->reports_model->getLookup('yesno',1);
		$data['yesnoneLU'] = array(1=>'YES',2=>'NO');
		$data['billingaddress'] = $this->reports_model->getLookup('billingaddress');
		$data['employment'] = $this->reports_model->getLookup('employment',1);
		$data['relationship'] = $this->reports_model->getLookup('relationship',1);
		$data['amf_product'] = $this->reports_model->getLookup('amf_product',1);
		$data['nationalityLU'] = $this->reports_model->getLookup('nationality',1);
		$data['card_requestLUp'] = $this->reports_model->getLookup('CardRequest',1);
		$data['sofLU'] = $this->reports_model->getLookup('sof',1);
		
		
		$data['details'] = $this->reports_model->agDetails;
		$data['cards'] = $this->reports_model->cardDetails;
		$data['supple'] = $this->reports_model->suppleDetails;
		
		$data['last_sv_remarks'] = $this->reports_model->getLastSVRemarks($table_recid);
		
		
		//$this->load->view('reports/generated_report',$data);	
		
/*		For the preparation of changing the template of template_1 */
		if($crm_id == 1){ //only for template 1
		
			$sess = $this->session->all_userdata();
			$data['username'] = $sess['username'];
			$this->load->view('reports/generated_report_temp_1',$data);
		}else{
			$this->load->view('reports/generated_report',$data);	
		}
		

	}
	
	
	//ADDED 2020-09-26
	//MAIN entry point in creating OUTBOUND REPORT
	public function outbound_report($crm_id=''){
		$pdata = $this->input->post();
		
		$this->set_header_data(PROJECT_NAME,' Outbound Report');
		$data = array();
		
		// include_once("./include/Excel/Excel_Perf.php");
		// $template_path =  './template/september_2020.xlsx';
        // $this->excel_xml = new Excel_Perf($template_path);
		// $this->excel_xml->setFileName('Semptember');
        // $this->createExcel();
		
		$crms = $this->my_utils->get_all_crms();
		$newCrms = array();
		foreach($crms as $details){
			$newCrms[$details['id']] = $details['name'];
		}
		
		$data['p_crm_id'] = $crm_id;
		
		//For now only challenger will be the target of this report
		$data['crms'][9] = $newCrms[9];
		
		$data['target_months'] = array('09'=>'September');
		
		$this->load->view('reports/outbound_report',$data);
		$this->load->view('templates/footer');
	}
	
	/**
	* *************************
	* START OF REPORT`
	* *************************
	**/
	
	function generate_obr_rpt(){
		$pdata = $this->input->get();
		$crm_id = $pdata['crm_id'];
        $this->setConfig();
		
		$crmDetails = $this->my_utils->get_crm_by_id($crm_id);		
		$this->conn_obj = $this->my_utils->change_conn($crmDetails);
		
        $this->getData();
		
		$this->template_path =  'template/september_2020.xlsx';
        $this->initExcel('September');
        $this->writeBody();
        $this->downloadExcel();
		
	}
	
    function getData(){
        $start_date = date("Y-{$this->target_report_month}-01");
        $end_date = date("Y-{$this->target_report_month}-t 23:59:59");

        $sql = "SELECT
                  id,
                  IF(callresult IN ('CB','NI') , sub_callresult,
                    IF(callresult = 'AG', ag_type, callresult)) AS final_dispo,
                  DATE(calldate) AS calldate,
                  agent,
                  lead_identity
              FROM contact_list
                WHERE calldate >= '{$start_date}'
                AND calldate <= '{$end_date}'";
        $result = $this->conn_obj->query($sql)->result_array();
		
        $this->data_row = array();
        foreach($result as $row) {
            $cd = $row['calldate'];
            $cr = $row['final_dispo'];

            if(!isset($this->data_row[$cd][$cr])){
                $this->data_row[$cd][$cr] = 0;
            }
            $this->data_row[$cd][$cr] += 1;
        }
    }

    function setConfig(){
		$pdata = $this->input->get();

        $this->target_rows = array(
                                //Legacy CR
                                54=>array('AG1','AG2','AG3','AG4','AG5','AG6','AG7','AG8'),
                                62=>array('duplicate'),
                                64=>array('cancelledcard'),
                                67=>array('cpna'),
                                68=>array('undecided'),
                                69=>array('forconfirmation'),
                                70=>array('notimetoobusy'),

                                //CR mapping
                                73=>array('Invalid6'),
                                74=>array('lowcredit'),
                                75=>array('LostBlockCard'),
                                76=>array('no_code_found'),
                                77=>array('no_code_found'),
                                78=>array('no_code_found'),
                                79=>array('no_code_found'),
                                80=>array('InvalidBE'),
                                81=>array('NOTQUAL'),
                                82=>array('Invalid'),
                                83=>array('INVfraud'),
                                84=>array('NQMOB'),
                                85=>array('NQPAY'),
                                86=>array('UC3'),
                                87=>array('Incomelow'),
                                88=>array('DC'),
                                89=>array('MILITARYORPNP'),
                                90=>array('underBSP'),
                                91=>array('INVPYMTRATIO'),
                                92=>array('INVALIDWHOLESALE'),
                                93=>array('INVHIRISK3'),
                                94=>array('INVhirisk'),
                                95=>array('INVHIRISK5'),
                                96=>array('INVHIRISK2'),
                                97=>array('NOB'),
                                98=>array('INVNOREc'),
                                99=>array('IN'),
                                100=>array('no_code_found'),
                                103=>array('AvailableLow'),
                                104=>array('no_code_found'),
                                105=>array('Dontlikedebts'),
                                106=>array('HIGHRATE'),
                                107=>array('no_code_found'),
                                108=>array('no_code_found'),
                                109=>array('Noneedcash'),
                                110=>array('WithExisting'),
                                111=>array('noreason'),
                                112=>array('totallyrefused'),
                                113=>array('planningtoclose'),
                                114=>array('DONOTCALL'),
                                115=>array('RCBCIssue'),
                                116=>array('languagebarrier'),
                                117=>array('NOcardforBT'),
                                118=>array('Delwithothercard'),
                                121=>array('ringing'),
                                122=>array('busy'),
                                123=>array('unattended'),
                                124=>array('invalid_no'),
                                125=>array('voicemail'),
                                126=>array('wrong_number'),
                                127=>array('hang_up'),
                                128=>array('fax_tone'),
                                129=>array('MO'),
                                130=>array('out_of_the_country'),
        );

        $this->col_start = 10; //LETTER J
        $this->col_end = 45; //LETTER AS
		$this->target_report_month = $pdata['target_month'];
        $this->date_start_report = '2020-08-29';
    }


    function initExcel($filename=''){
        include_once("include/Excel/Excel_Perf.php");
        $this->excel_xml = new Excel_Perf($this->template_path);
        $this->excel_xml->setFileName($filename);
    }

    function writeBody(){

        $target_cd = $this->date_start_report;

        for($i = $this->col_start; $i <= $this->col_end; $i++){
            $excelColumn = $this->excel_xml->util->intToColumn($i);

            foreach($this->target_rows AS $row_no=>$callresult_arr){
                $row_value = 0;
                $day_of_week = date('w',$date_str);

                //dont set value on SUNDAY
                //TODO make it dynamic later
                if($day_of_week == 0) continue;

                foreach($callresult_arr as $cr){
                    if(isset($this->data_row[$target_cd][$cr])){
                        $row_value += $this->data_row[$target_cd][$cr];
                    }
                }

                $this->excel_xml->setCellValue($excelColumn.$row_no, $row_value,'n');
            }

            $date_str = strtotime($target_cd.' +1 day');
            $target_cd = date('Y-m-d',$date_str);

        }

        $this->excel_xml->addRows(1,130);
    }

    function downloadExcel(){
	   	$this->excel_xml->download();
    }


	/**
	 * method for writing the header
	 */
	function writeHeader(){
		$cur_date = date('Y年m月d日 （H:i）');
		$this->excel_xml->setCellValue('B1',$cur_date);
        $this->excel_xml->addRows(1,200);
	}
	
	function los($crm_id=''){
		
		$pdata = $this->input->post();
		
		$this->set_header_data(PROJECT_NAME,' LOS Report');
		$data = array();
		
		
		$crms = $this->my_utils->get_all_crms();
		$newCrms = array();
		foreach($crms as $details){
			$newCrms[$details['id']] = $details['name'];
		}
		
		$data['p_crm_id'] = $crm_id;
		
		//For now only Template 1 will be the target of this report
		$data['crms'][1] = $newCrms[1];
		
		$data['user_list'] = $this->reports_model->getLosSVlist();
		
		$this->load->view('reports/los',$data);
		$this->load->view('templates/footer');
	}
	
	
	/**
	* *************************
	* START OF LOS REPORT`
	* *************************
	**/
	
	function generate_los_rpt(){
		$pdata = $this->input->get();
		$crm_id = $pdata['crm_id'];
		$this->crm_id = $crm_id;
		
        $this->reports_model->getLOSData();
		
		$this->template_path =  'template/los.xlsx';
		$sd	= $this->formatRptDate($pdata['start_calldate']);
		$ed	= $this->formatRptDate($pdata['end_calldate']);
		$filename = 'LOS_'. $sd . '~' .$ed;
        $this->initExcel($filename);
        $this->writeLOSBody();
        $this->downloadExcel();
		
	}
	
	function formatRptDate($date){
		$date = DATE('MdY',strtotime($date));
		return $date;
	}
	

    function writeLOSBody(){
		$los_data = $this->reports_model->los_data;
		
		//Draw HEADER
		$this->excel_xml->addRows(1,1);
		
		$row = 2;
		$cur_date = strtoupper(date('F d, Y', strtotime(date('Y-m-d'))));
		
		//set lookups 
		$amf_product = $this->reports_model->getLookup('amf_product',1);
		$card_requestLUp = $this->reports_model->getLookup('CardRequest',1);
		$yesnoneLU = array(1=>'YES',2=>'NO');
		
		foreach($los_data as $d){
			
			//added 2021-07-03
			//As per request change the value if QA inputted on the field from SV
			$mobile_no = $d['mobileno'];
			if($d['sv_sv_mobile_no']){
				$mobile_no = $d['sv_sv_mobile_no'];
			}
			
			$home_no = $d['telno'];
			if($d['sv_sv_home_no']){
				$home_no = $d['sv_sv_home_no'];
			}
			
			$card_request = $d['sv_sv_card_request_by_client'];
			if(isset($card_requestLUp[$d['sv_sv_card_request_by_client']])){
				$card_request = $card_requestLUp[$d['sv_sv_card_request_by_client']];
			}
			
			$this->excel_xml->setCellValue('A'.$row, $d['cust_nbr']);
			$this->excel_xml->setCellValue('B'.$row, $cur_date);
			$this->excel_xml->setCellValue('C'.$row, $card_request .'/' . $d['2nd_card_credit_limit']);
			$this->excel_xml->setCellValue('D'.$row, $d['sv_sv_source_code']);
			//2021-07-18 changed from sv_sv_prep_p_code to sv_sv_card_request_by_client
			//Since sv_sv_card_request_by_client is now a PULLDOWN
			$this->excel_xml->setCellValue('E'.$row, $d['sv_sv_card_request_by_client']); //$amf_product[$d['amf_product']] //changed to sv_prep_p_code as per requested @2021-07-03
			$this->excel_xml->setCellValue('F'.$row, $d['pd_name']);
			$this->excel_xml->setCellValue('G'.$row, strtoupper(str_replace('.','',$d['sv_sv_title']))); //added 2021-07-03
			$this->excel_xml->setCellValue('H'.$row, $d['c_lastname']);
			$this->excel_xml->setCellValue('I'.$row, $d['c_firstname']);
			$this->excel_xml->setCellValue('J'.$row, $d['c_middlename']);
			$this->excel_xml->setCellValue('K'.$row, $d['pd_name']);
			$this->excel_xml->setCellValue('L'.$row, $d['c_name_in_card']);
			$this->excel_xml->setCellValue('M'.$row, $d['c_nationality']);
			$this->excel_xml->setCellValue('N'.$row, $d['c_us_tin']);
			$this->excel_xml->setCellValue('O'.$row, $mobile_no);
			$this->excel_xml->setCellValue('P'.$row, $this->setLOSMobileNo($d['officeno'],$home_no));
			$this->excel_xml->setCellValue('Q'.$row, $d['c_sss_gsis']);
			$this->excel_xml->setCellValue('R'.$row, $d['tin']);
			$this->excel_xml->setCellValue('S'.$row, $d['c_tin']);
			$this->excel_xml->setCellValue('T'.$row, $d['email_addr']);
			$this->excel_xml->setCellValue('U'.$row, $d['c_esoa']);
			$this->excel_xml->setCellValue('V'.$row, $yesnoneLU[$d['c_is_web_shopper']]);
			$this->excel_xml->setCellValue('BC'.$row,$yesnoneLU[$d['c_with_personal_changes']]);
			$this->excel_xml->setCellValue('BE'.$row, $d['sv_last_remark']);
			
			//added 2021-07-03
			//BILLING ADDRESS CHANNEL
			$this->excel_xml->setCellValue('Z'.$row, $d['sv_sv_bill_add_ch']);
			//CARD DELIVERY CHANNEL
			$this->excel_xml->setCellValue('AA'.$row, $d['sv_sv_card_del_ch']);
			//On Authority to Disclose:(YES/NO)
			$this->excel_xml->setCellValue('BD'.$row, $yesnoneLU[$d['sv_sv_on_auth_to_disc']]);
			
			$this->excel_xml->addRows($row,1);
		}
		
    }
	
	//set value base on the value available for officeno and telno
	public function setLOSMobileNo($officeno, $telno){
		$officeno = $this->cleanNumber($officeno);
		$telno = $this->cleanNumber($telno);
		
		$landline = $officeno;
		if(!empty($telno) && !empty($officeno)){
			$landline .= '/'; //add slash
		}
		$landline .= $telno;
		
		if(empty($landline)){
			$landline = 'NA';
		}
		
		return $landline;
	}
	
	//remove specified special characters
	public function cleanNumber($number){
		$number = str_replace(' ', '', $number); 
		$number = str_replace('-', '', $number); 

		return preg_replace('/[^0-9\-]/', '', $number); // Removes special chars
	}

    public function swVerifiedReport(){
        $this->set_header_data(PROJECT_NAME,'Verified Report');
        $crms = $this->my_utils->get_all_crms();
        $newCrms = array();

        foreach($crms as $details){
            $newCrms[$details['id']] = $details['name'];
        }

        $data['start_calldate'] = isset($pdata['start_date']) ? $pdata['start_calldate'] : date('Y-m-01');
        $data['end_calldate'] = isset($pdata['end_calldate']) ? $pdata['end_calldate'] : date('Y-m-d');
        $data['crms'] = $newCrms;
        $data['rptTypes'] = $this->getLookupBatch(array('report_type'))['report_type'];

        $this->load->view('reports/swVerifiedReport',$data);
        $this->load->view('templates/footer');
    }


    public function generateVR(){
        $pdata = $this->input->post();
        $this->reports_model->crm_id = $pdata['crm_id'];
        $data['agentList'] = $this->reports_model->get_agent_list_in_main_table();
        $data['type'] = $pdata['type'];
        list($data['dataList'], $data['columnArr']) = $this->reports_model->getVerifiedRecords();
        $this->load->view('reports/verifiedReport',$data);
    }

}
?>
