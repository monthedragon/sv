<table >
<tr class='tr_header'>
	<td>Verifier</td>
	<?foreach($times as $time){?>
		<td><?=$time?></td>
	<?}?>
		<td>total</td>
</tr>
<?foreach($vph as $sv_id=>$vph_det){?>
	<tr>
		<td>
		<?
			$sv_name = $svArr[strtolower($sv_id)]['full_name'];
			if($sv_name == '') $sv_name = $sv_id;
			
			echo $sv_name;
		?>
		</td>
		<?	$running_total = 0;
			foreach($times as $k=>$time){
				$ctr = isset($vph_det[$k]) ? $vph_det[$k] : 0 ;
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