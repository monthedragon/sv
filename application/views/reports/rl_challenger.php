<?php
	
	$column_arr = array();
	$final_result = array();
	$cr_arr = array();
	foreach($result as $details){
		$column_arr[$details['ch_campaign']] = $details['ch_campaign'];
		$final_result[$details['ch_campaign']][$details['FINAL_DISPO']] = $details;
		$cr_arr[$details['callresult']][$details['FINAL_DISPO']] = $details['FINAL_DISPO'];
	}
?>	
<table >
<tr class='tr_header'>
	<td>Callresult</td>
	<?php
		foreach($column_arr as $ch_campaign){
			echo "<td>{$ch_campaign}</td>";
		}
	?>
	
	<td>Total Count</td>
	
</tr>
<?
// echo '<pre>';
// print_r($cr_arr);
$dmc = array('TOTAL'=>0);
$contacts =  array('TOTAL'=>0);
$total_contacts =  array('TOTAL'=>0);
$loan_arr =  array('TOTAL'=>0);

foreach($cr_arr as $cr=>$sub_cr){
	
	foreach($sub_cr as $final_dispo){
		$is_dmc = 0; 
		$callresult_name = '';
		if($cr == ''){	
			$callresult_name = 'Rehased';
		}elseif($cr == 'CB'){	
			if(isset($cb_lookup[$final_dispo])){
				$callresult_name = '(CB)'.$cb_lookup[$final_dispo];
			}
			if($final_dispo == 'cpna'){
				$is_dmc = 2; //for CONTACTS
			}else{
				$is_dmc = 1;
			}
			
		}elseif($cr == 'NI'){
			$is_dmc = 1;
			if(isset($ni_lookup[$final_dispo])){
				$callresult_name = '(NI)'.$ni_lookup[$final_dispo];
			}
		}elseif($cr == 'AG' ){
			$is_dmc = 1;
			$callresult_name = '(AG)'.$final_dispo;	
		}else{
			if(isset($callresult_lookup[$final_dispo])){
				$callresult_name = $callresult_lookup[$final_dispo];
			}
		}
		
		echo "<tr>";
		echo "<td>{$callresult_name}</td>";
		$total_per_dispo = 0;
		foreach($column_arr as $ch_campaign){
			
			$val = 0;
			if(isset($final_result[$ch_campaign][$final_dispo])){
				$val = $final_result[$ch_campaign][$final_dispo]['CTR'];
				
				if($cr == 'AG' ){
					$loan_amount = $final_result[$ch_campaign][$final_dispo]['LOAN_AMOUNT'];
					
					if(!isset($loan_arr[$ch_campaign])) $loan_arr[$ch_campaign] = 0;
					$loan_arr[$ch_campaign] += $loan_amount;	
				}
			}
			echo "<td>{$val}</td>";
			
			
			if($is_dmc){
				
				if($is_dmc == 1 ){
					if(!isset($dmc[$ch_campaign])) $dmc[$ch_campaign] = 0;
					$dmc[$ch_campaign] += $val;	
				}
				//CONTACTS = DMC+CPNA
				if(!isset($contacts[$ch_campaign])) $contacts[$ch_campaign] = 0;
				$contacts[$ch_campaign] += $val;
				
			}
			
			if(!isset($total_contacts[$ch_campaign])) $total_contacts[$ch_campaign] = 0;
			$total_contacts[$ch_campaign] += $val;
			
			$total_per_dispo += $val;
			
		}
		echo "<td>{$total_per_dispo}</td>";
		echo "</tr>";
	}	
}
?>
<tr class='tr_header'>
	<td colspan = <?=count($column_arr)+2?>></td>
	
</tr>
<tr><td>DMC</td>
<? draw_total($column_arr, $dmc);?> 
</tr>

<tr><td>CONTACTS</td>
<? draw_total($column_arr, $contacts);?> 
</tr>

<tr><td>TOTAL CONTACTS</td>
<?draw_total($column_arr, $total_contacts);?> 
</tr>

<tr><td>DMC RATE</td>
<? draw_rate_html($column_arr, $dmc, $contacts);?> 
</tr>

<tr><td>CONTACT RATE</td>
<?  draw_rate_html($column_arr, $contacts, $total_contacts);?> 
</tr>

<!-- TO FOLLOW REPLAC(', ') has issue  -->
<!--tr><td>LOAN AMOUNT</td>
<!--?  draw_total($column_arr, $loan_arr,2);?> 
</tr-->
</table>