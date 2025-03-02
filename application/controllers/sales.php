<?php
Class Sales extends Auth_Controller  {
    var $data = array();
    //use `Auth_Controller` if you want the page to be validated if the user is logged in or not, if you want to disable this then use `CI_Controller` instead
    public  function __construct(){
        parent::__construct();
        $this->load->model('sales_model');
        $this->load->model('users_model');
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->helper(array('form', 'url'));
        $this->data = $this->get_privs();
        $this->load->library('MY_utils');
    }

    public function list_sale($crm_id,$calldate=''){
        $pdata = $this->input->post();

        $data = $pdata;
        if(empty($calldate))
            $calldate = (isset($pdata['calldate']) ? $pdata['calldate'] : date('Y-m-d'));

        $data['calldate'] = $calldate;
        $arrOfLuCode = array('ol_status','ol_alert');
        $data['lu'] = $this->getLookupBatch($arrOfLuCode);

        $data['privs'] = $this->data;
        $data['sales'] = $this->sales_model->get_sales($crm_id,$calldate);
        $data['alerts'] = $this->sales_model->get_alerts();
        $data['crmDetails'] = $this->my_utils->get_crm_by_id($crm_id);
        $data['crms'] = $this->my_utils->get_all_crms();
        $data['crm_id'] = $crm_id;
        $data['users'] = $this->users_model->get_all_users();

        $this->set_header_data(PROJECT_NAME,'Sales List ' . $data['crmDetails']['name']);
        $this->load->view('sales/list_sale',$data);

        $this->load->view('templates/footer');
    }

    /*
     * 1. check if the sale is new then save the details from the corresponding tables
     * 2. check if the sale is already in the sales/system_db if yes then update the corresponding details
     *
     * */
    public function init_sale($crm_id,$main_table_id,$calldate){

        $sale_id = $this->sales_model->init_sale($crm_id,$main_table_id,$calldate);
        $saleMainDetails = $this->sales_model->get_sale_main_details($sale_id); //get it from sales/system_db
        $return = $this->sales_model->get_sales_details_by_id($sale_id,$crm_id);
        $lookup = $this->sales_model->get_lookup_from_main_table($crm_id);
        //lookup from SV table
        $arrOfLuCode = array('sv_status','ol_alert');
        $data['lu'] = $this->getLookupBatch($arrOfLuCode);

        $data['crm_id'] = $crm_id;
        $data['sale_id'] = $sale_id;
        //details came from sales/system_db
        $data['sale_main_details'] = $saleMainDetails;
        //details came from sales_details/system_db
        $data['sales'] = $return['SALES'];
        $data['field_mappings'] = $return['FIELD_MAPPINGS'];
        $data['table_details'] = $return['TABLE_DETAILS'];
        $data['lookup'] = $lookup;
        $data['view_only']=false;

        $customer = $saleMainDetails['firstname'] . ' ' . $saleMainDetails['lastname'];

        $calldate = ((!empty($calldate)) ? $calldate : date('Y-m-d'));
        $data['calldate'] = $calldate;

        if(isset($this->test_sale)){
            $this->set_header_data(PROJECT_NAME,'<font color=red>DEVELOPMENT</font> Sale\'s Details :: '.strtoupper($customer) );
            $this->load->view('sales/sales_details_test',$data);
        }else{
            $this->set_header_data(PROJECT_NAME,'Sale\'s Details :: '.strtoupper($customer) );
            $this->load->view('sales/sales_details',$data);
        }
        $this->load->view('templates/footer');
    }

    public function init_test_sale($crm_id,$main_table_id,$calldate){
        $this->test_sale = true;

        $this->init_sale($crm_id,$main_table_id,$calldate);
    }

    public function view_sale_details($crm_id,$sale_id,$calldate=''){
        $saleMainDetails = $this->sales_model->get_sale_main_details($sale_id); //get it from sales/system_db
        $return = $this->sales_model->get_sales_details_by_id($sale_id,$crm_id);
        $lookup = $this->sales_model->get_lookup_from_main_table($crm_id);

        $calldate = ((!empty($calldate)) ? $calldate : date('Y-m-d'));
        $data['calldate'] = $calldate;
        $data['crm_id'] = $crm_id;
        $data['sale_id'] = $sale_id;
        //details came from sales/system_db
        $data['sale_main_details'] = $saleMainDetails;
        //details came from sales_details/system_db
        $data['sales'] = $return['SALES'];
        $data['field_mappings'] = $return['FIELD_MAPPINGS'];
        $data['table_details'] = $return['TABLE_DETAILS'];
        $data['lookup'] = $lookup;
        $data['view_only']=true;

        $customer = $saleMainDetails['firstname'] . ' ' . $saleMainDetails['lastname'];
        $this->set_header_data(PROJECT_NAME,'Sale\'s Details :: '.strtoupper($customer) );
        $this->load->view('sales/sales_details',$data);
        $this->load->view('templates/footer');

    }

    public function save_sales($sale_id){
        $this->sales_model->save_sales($sale_id);
    }

    //locked = true or false
    public function locked($sale_id,$locked){
        $updateArr = array('is_locked'=>(($locked=='true') ? 1 : 0));
        $whereArr = array('id'=>$sale_id);
        $this->db->update('sales',$updateArr,$whereArr);
    }
    public function locked_all($crm_id,$calldate,$locked){
        $coverDate =date('Y-m-d',strtotime('+1 day'.$calldate));
        $crmDetails = $this->my_utils->get_crm_by_id($crm_id);

        $updateArr = array('is_locked'=>(($locked=='true') ? 1 : 0));
        $whereArr = array(
            'crm_code'=>$crmDetails['crm_code'],
            'calldate >='=>$calldate,
            'calldate <'=>$coverDate
        );
        $this->db->update('sales',$updateArr,$whereArr);
    }

    public function challenger_e_request($sale_id, $crm_id, $is_pamu = false){
        $saleMainDetails = $this->sales_model->get_sale_main_details($sale_id); //get it from sales/system_db
        $return = $this->sales_model->get_sales_details_by_id($sale_id,$crm_id);
        $lookup = $this->sales_model->get_lookup_from_main_table($crm_id);

        $crmDetails = $this->my_utils->get_crm_by_id($crm_id);
        $newconn = $this->my_utils->change_conn($crmDetails);
        $data['agent_fname_list'] = $this->sales_model->get_agent_list_in_main_table($newconn,$crmDetails,1,1);

        $data['users'] = $this->users_model->get_all_users_info();
        $data['sale_main_details'] = $saleMainDetails;
        $data['crm_id'] = $crm_id;
        $data['sale_id'] = $sale_id;
        $data['sales'] = $return['SALES'];
        $data['lookup'] = $lookup;
        $data['is_pamu'] = $is_pamu;
        $this->load->view('sales/challenger_e_request',$data);

    }

    public function supple_e_request($sale_id, $crm_id){
        $saleMainDetails = $this->sales_model->get_sale_main_details($sale_id); //get it from sales/system_db
        $return = $this->sales_model->get_sales_details_by_id($sale_id,$crm_id);
        $lookup = $this->sales_model->get_lookup_from_main_table($crm_id);

        $crmDetails = $this->my_utils->get_crm_by_id($crm_id);
        $newconn = $this->my_utils->change_conn($crmDetails);

        $data = array(
            'agent_fname_list'   => $this->sales_model->get_agent_list_in_main_table($newconn, $crmDetails, 1, 1),
            'users'              => $this->users_model->get_all_users_info(),
            'sale_main_details'  => $saleMainDetails,
            'crm_id'             => $crm_id,
            'sale_id'            => $sale_id,
            'sales'              => $return['SALES'],
            'lookup'             => $lookup,
        );

        //add this setting if needed
        $eRequestPage = array(
            '10' => 'supple_e_request'
        );

        $this->load->view('sales/'. $eRequestPage[$crm_id], $data);
    }
}
?>