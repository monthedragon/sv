<table >
<tr class='tr_header'>
	<td>AGENT</td>
	<td>DMC</td>
	<td>SALE</td>
	<td>CONVERSION RATE</td>
	
</tr>
<?
foreach($result as $details){
	$agent_name = isset($agent_list[$details['agent']]) ? $agent_list[$details['agent']] : $details['agent'];
?>
	<tr>
		<td><?=$agent_name?></td>
		<td><?=$details['DMC']?></td>
		<td><?=$details['SALE']?></td>
		<td>
			<?
				$sale = $details['SALE'];
				$dmc  = $details['DMC'];
				
				if(!$sale || !$dmc){
					$con_rate = 0;
				}else{
					$con_rate = number_format(($sale/$dmc)*100);
				}
				
				echo $con_rate.'%';
			?>
		</td>
	</tr>
<?}?>
</table>