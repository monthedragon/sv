<?if(isset($editable) && $editable == true ){?>
	<div style=float:left>
		<input type='button' value='back to list' id='btn-back-to-list'>
	</div>
	<?if(has_access(5)  && !$saleDetails['is_locked']){?>
		<div style=float:right>
			<a href='#' class='link a-create-response'>Create Response</a>
		</div>
		<div id='div-remark-form'></div>
	<?}else{?>
		<div style=float:right>
			<span class='warning'>Creating response is not available</span>
		</div>
	<?}?>
<?}?>

<?if(count($remarks)==0){?>
	<div class='div-center'>
		<span class='warning'>No remarks</span>
	</div>
<?}?>

<table width=100% >
<tr class='trdashlineGray'>
	<td>details</td>
	<td>remarks</td>
</tr>
<?
$tr=1;
foreach($remarks as $details){
?>
	<tr class='<?=(!$tr?'tr-odd':'tr-even')?>'>
		<td valign=top width=15%>
			<!---TODO ADD HERE IF SV MAASK NAME OR NOTE--->
			<?=(!empty($details['mask_name']) ? $details['mask_name'] : $users[$details['user_id']])?>
			<br>
			<?=render_date($details['time_stamp'],1)?>

			<?if($details['alert'] != 0){?>
				<br>
				<?=$lu['ol_alert'][$details['alert']]?>
			<?}?>

			<?if($details['status'] != 0){?>
				<br>
				<?=$lu['ol_status'][$details['status']]?>
			<?}?>
		</td>
		<td valign=top>
			<?=nl2br($details['remarks'])?>
		</td>

	</tr>
<?
$tr = !$tr;
}?>
</table>

<script>
	$(function(){

		var url ='<?=base_url('remarks/create_response/'.$sale_id)?>';
		do_ajax(url,'POST','','div-remark-form');

		$('#btn-back-to-list').click(function(){
			window.location = '<?=$return_url?>'
		})

		$('.a-create-response').click(function(){
			$('#div-remark-form').toggle('medium');
		})
	})
</script>