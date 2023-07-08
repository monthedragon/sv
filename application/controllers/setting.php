<?php
Class Setting extends Auth_Controller{
	
	function __construct(){
		parent::__construct();
		$this->load->model('setting_model');
		$this->load->model('sales_model');
		$this->load->helper('url');
		$this->load->library('session');    
		$this->data = $this->get_privs();
		$this->load->library('MY_utils');
	}
	
	function set_related_table_fields($crmDetails){
		$retValArr = array();
		$relatedTables = $this->sales_model->get_related_tables($crmDetails);
		
		foreach($relatedTables as $details){
			$fields = $this->setting_model->get_fields($crmDetails,$details['foreign_table_name']);
			
			$retValArr[$details['id']] = $fields;
		}
		
		return $retValArr;
	}
	
	function set_field_mappings($crmDetails){
		//set the inital value as main(0)
		$related_table_id_arr = array(0);
		$relatedTables = $this->sales_model->get_related_tables($crmDetails);
		
		foreach($relatedTables as $details){
			$related_table_id_arr[] = $details['id'];
		}
		
		foreach($related_table_id_arr as $related_table_id){
			$field_arr_set = array();
			$field_arr = $this->sales_model->get_field_mappings($crmDetails['crm_code'],$related_table_id);
			
			foreach($field_arr as $details){
				$field_arr_set[$details['field_name']] = $details;
			}			
			$select_fields_arr[$related_table_id] = $field_arr_set;
		}
		
		return $select_fields_arr;
		
	}
	
	function set_related_tables($crmDetails){
		$retValArr = array();
		$relatedTables = $this->sales_model->get_related_tables($crmDetails);
		
		foreach($relatedTables as $details){
			$retValArr[$details['id']] = $details;
		}
		
		return $retValArr;
		
	}
	
	function mapping($crm_id){
		$this->set_header_data(PROJECT_NAME,'Mapping');
		$data = array();
		$crmDetails = $this->my_utils->get_crm_by_id($crm_id);
		
		$field_tables = $this->set_related_table_fields($crmDetails);
		//0 is for the main_fields/table
		$field_tables[0] = $this->setting_model->get_fields($crmDetails,$crmDetails['main_table']);
		ksort($field_tables);
		$data['field_tables'] = $field_tables;
		$data['field_mappings'] = $this->set_field_mappings($crmDetails);
		$data['crmDetails'] = $crmDetails;
		$data['crm_id'] = $crm_id;
		$data['table_info'] = $this->set_related_tables($crmDetails);
		
		$this->load->view('setting/mapping',$data);
		$this->load->view('templates/footer'); 	
	}
	
	function update_field_mapping(){
		$this->setting_model->update_field_mapping();
	}
	
}
?>
