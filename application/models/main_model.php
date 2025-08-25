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
        // Default date
        if (empty($calldate)) {
            $calldate = date('Y-m-d');
        }

        // Always initialize properly
        $retValArr = [];
        $salesSummary = [
            'total_sales'   => 0,
            'new_sales'     => 0,
            'verified_sales'=> 0
        ];

        // Optimize by minimizing fields fetched from sales_model
        $this->sales_model->show_less_field = 1;

        foreach ($crms as $k => $details) {
            // Fetch sales details for this CRM
            $salesDetails = $this->sales_model->get_sales($details['id'], $calldate);

            // Count new and verified sales
            $newSales      = isset($salesDetails['NEW']) ? count($salesDetails['NEW']) : 0;
            $verifiedSales = isset($salesDetails['VERIFIED']) ? count($salesDetails['VERIFIED']) : 0;

            // Add to CRM details
            $details['new_sales']     = $newSales;
            $details['verified_sales'] = $verifiedSales;

            // Add to final CRM list
            $retValArr[$details['crm_code']] = $details;

            // Update totals
            $salesSummary['new_sales']      += $newSales;
            $salesSummary['verified_sales'] += $verifiedSales;
            $salesSummary['total_sales']    += ($newSales + $verifiedSales);
        }

        // Get overall alerts summary
        $alerts = $this->getAlertSummary();

        // Return structured data
        return [
            'crms'         => $retValArr,
            'salesSummary' => $salesSummary,
            'alerts'       => $alerts
        ];
	}

    public function getAlertSummary(){

        $controller =& get_instance();
        $olAlert = $controller->getLookup('ol_alert', 1);

        $alerts = $this->sales_model->get_alerts();
        $alertSummary = [];
        foreach($alerts as $type => $dataArr){
            $alertLbl = $olAlert[$type];
            reset($dataArr);
            $firstKey = key($dataArr);

            // Get the first alert info for redirection purposes only in the screen
            $alertSummary[$alertLbl] = array_merge(['count' => count($dataArr)],$dataArr[$firstKey]);

        }

        //var_dump($alertSummary);

        return $alertSummary;
    }
	

}