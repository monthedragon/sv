<table >
<tr class='tr_header'>
	<td>Agent</td>
	<?foreach($times as $time){?>
		<td><?=$time?></td>
	<?}?>
		<td>total</td>
</tr>
<?foreach($sph as $agent_id=>$sph){?>
	<tr>
		<td><?=$agentArr[strtolower($agent_id)]?></td>
		<?	$running_total = 0;
			foreach($times as $k=>$time){
				$ctr = isset($sph[$k]) ? $sph[$k] : 0 ;
		?>
			<td class="<?=($ctr ? 'cursor_red_bold' : '' )?>"><?=$ctr?></td>
		<?
				$running_total += $ctr;
			}
		?>

		<td class="cursor_red_bold"><?=$running_total?></td>
	</tr>
<?}?>
</table>