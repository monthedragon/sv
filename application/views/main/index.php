<form id='frm-search' action='<?=base_url('main')?>' method='POST'>
	<table class='tbl-search'>
		<tr>
			<td>
				Calldate:
				<?=input('calldate','calldate','input','date',$calldate);?>
				<input type='submit' value='search' id='btn-search'>
			</td>
			<td>
				<!----DRAW ALERT---->
				<?=alert_html($alerts,$crms,$lu);?>
				<!----END  ALERT---->
				
			</td>
		</tr>
	</table>
</form>

<table>
<tr class=tr_header>
	<td>Campaign</td>
	<td>New Sales</td>
	<td>Verified Sales</td>
</tr>
<?foreach($crms as $details){?>
    <tr class='tr-list'>
        <td>
             <a href='<?=base_url('sales/list_sale/'.$details['id'].'/'.$calldate)?>'><?=$details['name']?></a>
        </td>
		<td><?=$details['new_sales']?></td>
		<td><?=$details['verified_sales']?></td>
    </tr>
<?}?>

</table>

<script>
	$(function(){
		var url = '<?=base_url('sales/list_sale')?>';
		init_search_fx(url);
		
		hover_out_tr_fx('tr-list');
	})
	
</script>