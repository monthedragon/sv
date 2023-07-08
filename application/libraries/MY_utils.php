<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class my_utils extends CI_Model {
	protected $data = array();
    public $crmDetails = array();

	public  function __construct(){ 
        $this->crmDetails =array();
	}

    public function change_conn($cd){
        $config['hostname'] = $cd['db_ip'];
        $config['username'] = $cd['db_username'];
        $config['password'] = $cd['db_password'];
        $config['database'] = $cd['db_name'];
        $config['dbdriver'] = "mysql";
        $config['dbprefix'] = "";
        $config['pconnect'] = FALSE;
        $config['db_debug'] = TRUE;

        return $this->load->database($config,true);
    }

    public function get_crm_by_id($id,$forced_get_to_db=0){
        if(!isset($this->crmDetails[$id]) || $forced_get_to_db == 1) {
            $result =  $this->db->from('crm')->where('is_active',1)->where('id',$id)->get()->result_array();
            $this->crmDetails[$result[0]['id']] = $result[0]; //set two keys id and crm_code
			$this->crmDetails[$result[0]['crm_code']] = $result[0];
            return $result[0];
        }else{
            return $this->crmDetails[$id];
        }
    }
	
	public function get_crm_by_code($crm_code,$forced_get_to_db=0){
        if(!isset($this->crmDetails[$crm_code])  || $forced_get_to_db == 1) {
            $result =  $this->db->from('crm')->where('is_active',1)->where('crm_code',$crm_code)->get()->result_array();
            $this->crmDetails[$result[0]['id']] = $result[0]; //set two keys id and crm_code
			$this->crmDetails[$result[0]['crm_code']] = $result[0];
            return $result[0];
        }else{
            return $this->crmDetails[$crm_code];
        }
    }

	public function get_all_crms(){
		$retValArr = array();
		$result = $this->db->from('crm')->get()->result_array();
		
		foreach($result  as $details){
			$retValArr[$details['crm_code']] = $details;
		}
		
		return $retValArr;
	}
}

/* End of file MY_utils.php */
/* Location: ./application/libraries/MY_utils.php */ 
?>