<table>

<?foreach($customers as $details){?>
	<tr main_table_id='<?=$details['id']?>'>
		<td><?=$details[$crmDetails['firstname_field']]?></td>
		<td><?=$details[$crmDetails['lastname_field']]?></td>
		<td><?
				$cr = $details[$crmDetails['callresult_field']];
				echo isset($lu_arr[$cr]) ? $lu_arr[$cr] :  $cr;
			?></td>
		<td><?=input('calldate_'.$details['id'],'calldate','input','date calldate_'.$details['id'],render_date($details[$crmDetails['calldate_field']],6));?></td>
		<td>
			
			<input type='button' value='update' class='btn-update'>
			
		</td>
	</tr>
<?}?>
</table>

<script>
	$(function(){
		$('.btn-update').click(function(){
			var main_table_id = $(this).closest('tr').attr('main_table_id');
			var calldate = $('.calldate_'+main_table_id).val();
			var url = '<?=base_url('manage/update_main/'.$crm_id)?>';
			var data = {};
			data['calldate'] = calldate;
			data['id'] = main_table_id;
			
			do_ajax(url,'POST',data,undefined, 'Saved');
			
		});
		
		$('.date').datepicker({'dateFormat':'yy-mm-dd'});
	})
</script>