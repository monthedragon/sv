<?php //include_once('Spreadsheet/Excel/Writer.php');//include_once('Spreadsheet/Excel/Reader/reader.php');Class Main extends Auth_Controller  { 	var $data = array();	//use `Auth_Controller` if you want the page to be validated if the user is logged in or not, if you want to disable this then use `CI_Controller` instead	public  function __construct(){		parent::__construct();		$this->load->model('main_model');		$this->load->model('sales_model');		$this->load->helper('url');		$this->load->library('session');  		$this->load->helper(array('form', 'url'));  		$this->has_permission(178);		$this->data = $this->get_privs();		$this->load->library('MY_utils');	} 		public function index(){		$pdata = $this->input->post();		$this->load->helper('form');		$this->set_header_data(PROJECT_NAME,'Campaign List');				$calldate =  date('Y-m-d');		if(isset($pdata['calldate']))			$calldate =  $pdata['calldate'];					$arrOfLuCode = array('ol_alert');        $data['lu'] = $this->getLookupBatch($arrOfLuCode);		$data['alerts'] = $this->sales_model->get_alerts();		$data['session'] = $this->session->all_userdata();		$data['calldate'] = $calldate;		$data['privs'] = $this->data;		$crm = $this->main_model->get_crm(); //original detail of the crm		$crm = $this->main_model->construct_crm_dashboard($crm,$calldate); //added count of new and verified sales		$data['crms'] = $crm;		$this->load->view('main/index',$data);				$this->load->view('templates/footer'); 		}}?>