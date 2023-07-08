<?php
Class Setting_model extends CI_Model{ 
	public function __construct()
	{
		$this->load->database();
	}

	public function get_fields($crmDetails,$tableName){
		$retVal = array();
		$newconn = $this->my_utils->change_conn($crmDetails);
		
		$fields = $newconn->list_fields($tableName);

		foreach ($fields as $field)
		{
		   $retVal[] =  $field ;
		}
		
		return $retVal;
	}
	
	public function update_field_mapping(){
		$pdata = $this->input->post();
		$keyData = explode('_____',$pdata['id']);
		$table_id =  $keyData[0];
		$field_name = $keyData[1];
		$val = $pdata['val'];
		$crmCode = $pdata['crm_code'];
	
		$whereArr = array('related_table_id'=>$table_id,
							'field_name'=>$field_name,
							'crm_code'=>$crmCode
							);
		
		$updateData = $whereArr+array($pdata['target_field']=>$val);
		
		
		$data = $this->db->select('count(*) as CTR')
				->from('field_mapping')
				->where($whereArr)
				->get()->result_array();
		
		//do insert
		if($data[0]['CTR'] == 0){
			$this->db->insert('field_mapping',$updateData);
		}else{ //anything goes here is set as ACTIVE
			$this->db->update('field_mapping',$updateData,$whereArr);
		}
		
		echo $this->db->last_query();		
	}
}
?>