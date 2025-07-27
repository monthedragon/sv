<table style="width:100%; margin-top: 10px;">
<tr class='tr_header'>
	<td>Agent</td>
	<?foreach($times as $time){?>
		<td><?=$time?></td>
	<?}?>
		<td>total</td>
</tr>
<?
$totalArr = array();
foreach($sph as $agent_id=>$sph){?>
	<tr>
		<td><?=$agentArr[strtolower($agent_id)]?></td>
		<?	$running_total = 0;
			foreach($times as $k=>$time){
				$ctr = isset($sph[$k]) ? $sph[$k] : 0 ;
		?>
			<td class="<?=($ctr ? 'cursor_red_bold' : '' )?>"><?=number_format($ctr,0)?></td>
		<?
                if(!isset($totalArr[$k])) $totalArr[$k] = 0;
                $totalArr[$k] += $ctr;

				$running_total += $ctr;
			}
		?>

		<td class="cursor_red_bold"><?=number_format($running_total,0)?></td>
	</tr>
<?}

// Grand total row
echo "<tr class='tr_separator'>";
echo "<td style='padding-top:15px;'></td>";

$grandTotal = 0;

foreach ($times as $k => $time) {
    $val = isset($totalArr[$k]) ? $totalArr[$k] : 0;
    $grandTotal += $val;
    $cls = $val ? 'cursor_red_bold' : '';
    echo "<td class='{$cls}'>".number_format($val,0)."</td>";
}

echo "<td class='cursor_red_bold'>".number_format($grandTotal,0)."</td>";
echo "</tr>";
?>
</table>