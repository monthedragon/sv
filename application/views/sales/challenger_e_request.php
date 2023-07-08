<?php
function getValues($main_details){
	$detail_arr = array();
	foreach($main_details as $col=>$details){
		$value = $details['original_value'];
		
		if(!empty($details['new_value'])){
			$value = $details['new_value'];
		}
		
		$detail_arr[$col] = $value;
	}
	
	return $detail_arr;
}

if(isset($sales[0][0])){
	$main_details = $sales[0][0];
	$detail_arr = getValues($main_details);
	extract($detail_arr);
	
	$sv_fname = '';
	if(isset($users[$sale_main_details['user_id']])){
		$sv_fname = $users[$sale_main_details['user_id']]['firstname'];
		$sv_fname = strtoupper($sv_fname);
	}
	
	$product 	= $lookup['product'][$c_product];
	$moca 		= $lookup['mode_of_cash_out'][$c_mode_of_cashout];
	$pid 		= $lookup['pid'][$c_pid];
	
	if($c_mode_of_cashout == 'delivery'){
		$del_addr = $c_billing_address;
	}else{
		$bank_name = isset($lookup['bank_name'][$c_bank_name]) ? $lookup['bank_name'][$c_bank_name] : '';
		$del_addr = $bank_name . ' ' . $c_account_number .' '. $c_account_name . ' ' .  $c_branch;
	}
	
	//2021-07-31
	$p_fee = 350;
	if($c_mode_of_cashout == 'crediting'){
		$p_fee = 250;
	}
	
	$ch_campaign = trim(str_replace('Challenger 2','',$ch_campaign));
	
	//as of 2020-03-14 [COVID] add dynamically the calldate YEAR and MONTh 
	$calldate_str = date('F Y',strtotime($calldate));
	$ch_campaign .= $calldate_str;
	
	//as of 2021-08-09
	$ch_remarks = trim($ch_remarks);
	//{$ch_campaign} removed and changed by {$ch_remarks}
	
	// echo '<pre>';
	// var_dump($agent_fname_list);
	
	$message = '';
	$message .=  "{$c_cc_no}.{$c_fullname}<br>";
	$message .=  "OB.TSI.{$sv_fname}.{$ch_remarks}-SALES COMPLETED<br>";
	if($is_pamu){
		$message .=  "APPLICATION ID {$sv_reference_no}<br>";
	}
	
	$l_amount = $c_loan_amount;
	if($c_product == 'balance_transfer'){
		//Added 2020-11-07 requested by Ms A
		$l_amount = $c_amount_to_transfer;
	}
	
	$c_monhtly_amortization = ($c_monhtly_amortization);
	$message .= "CH AVAIL {$product} {$l_amount}/{$c_tenor}MOS({$c_rates}%) / MONTHLY AMORT {$c_monhtly_amortization} / MID#{$sv_mid}<br>";
	
	$bt_added_note = '';
	if($c_product == 'balance_transfer'){
		//Added 2020-11-07 requested by Ms A
		$bank_name_bt = isset($lookup['bank_name'][$c_bank_name_bt]) ? $lookup['bank_name'][$c_bank_name_bt] : '';
		$message .= "OTHER BANK, {$bank_name_bt} {$c_cc_no_bt} {$c_name_on_cc} <br>";
		$message .= "INFORMED POL AND PROCESSING FEE(250) <br>";
		$bt_added_note = 'CH OK TO CHANGE BT AMOUNT <br>';
	}else{
		//2021-08-07 change the wordings of {$moca}
		$message .= "REQ {$moca}, {$del_addr}<br>";
		$message .= "INFORMED POL AND PROCESSING FEE($p_fee)<br>";
	}
	
	$message .= "PASSED PID: {$pid}";
	$message .= "<br>";
	
	//Moved here and added condition for your_cash (2022-02-18)
	if($c_product == 'your_cash'){
		$message .= "YES TO DISCLOSURE 1-5 <br>";
	}else{
		$message .= "YES TO DISCLOSURE 1-4 <br>";
	}
		
	//Added 2020-11-07
	if($c_if_ok_to_change_amount == 1){

		$message .= $bt_added_note;
		
		if($c_product == 'cash_loan'){
			$message .= "CH OK TO CHANGE LOAN AMOUNT <br>";
		}
	}else{
		
		$message .= $bt_added_note;
		
		if($c_product == 'cash_loan'){
			$message .= "DO NOT PROCESS IF LOAN AMOUNT APPLIED IS NOT GRANTED <br>";
		}
	}
	
	$contacts = '';
	if($c_mobileno_tag == 'contacted') $contacts .= 'MOBILE NO.'.$c_contact_mobileno . ' - CONTACTED';
	if($c_officeno_tag == 'contacted') $contacts .= (($contacts !='' ) ? '<br>' : '') . 'OFFICE NO.'.$c_contact_officeno. ' - CONTACTED';
	if($c_homeno_tag == 'contacted') $contacts .= (($contacts !='' ) ? '<br>' : '') . 'HOME NO.'. $c_contact_telno. ' - CONTACTED';
	
	if($contacts){
		$message .= $contacts;
	}
	
	//2021-08-07 ADDED
	if($c_source_of_ag && isset($lookup['source_of_ag'][$c_source_of_ag])){
		$message .= '<br>' . $lookup['source_of_ag'][$c_source_of_ag];
	}
	
	//MAKE all caps
	$message = strtoupper($message);
}

echo "<span style='float:left;cursor:pointer;' onclick=closeModal() > PRESS `ESC` to EXIT</span>";
echo "<input type = 'button' value = ' COPY ' onclick=copyToClipboard('#p_msg') style='float:right; border:1px solid red;'>";
echo '<br><br><hr class=separator>';
echo "<div style='color:black;font-size:15px;weight:bold'><p id='p_msg'>{$message}</p> </div>";

// echo '<pre>';
// print_R($detail_arr);
?>