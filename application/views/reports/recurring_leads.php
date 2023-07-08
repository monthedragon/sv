<form id='frm-search' action='<?=base_url('reports/sph')?>' method='POST'>
<table>
	<tr>
		<td>Campaign:</td>
		<td><?=select('crm_id','crm_id','required','',$p_crm_id,null,$crms)?></td>
	</tr>
	
	<tr>
		<td>Calldate:</td>
		<td colspan=3>
				<?=input('start_calldate','start_calldate','input','date',$start_calldate);?>
				<?=input('end_calldate','end_calldate','input','date',$end_calldate);?>
		</td>
	</tr>
	
	<tr>
		<td>Lead identity:</td>
		<td>
			<select multiple name='lead_identity[]' style="height:300px;width:400px;">
				<?foreach($lead_identity_arr as $li_name=>$li_info){?>
				<option value="<?=$li_name?>"><?=$li_info?>
					<?}?>
			</select>
			
		</td>
		
	</tr>
	<tr>
		<td colspan=3>
				<input type='submit' value='search' id='btn-search' >
		</td>
	</tr>
</table>
</form>

<div id='div-report-list'></div>

<script>
	$(function(){
		$('form').submit(function(){
		
			if($(this).valid()){
				var url = "<?=base_url('reports/recurring_leads_generate/'.$do_old_version)?>";
				var data = $(this).serialize();
				var type ='POST';
				
				$.ajax({
					url:url,
					data:data,
					type:type,
					crossDomain: true,
					beforeSend:function(){
						$('#btn-search').val('please wait. . . ').prop('disabled');
					},
					success:function(data){
						$('#div-report-list').html(data);
					},
					complete:function(){
						$('#btn-search').removeProp('disabled').val('search');;
					},
				});	
		
			}
			return false;
		})
		
		
		$('#crm_id').change(function(){
			var crm_id  = $(this).val();
			window.location = "<?=base_url('reports/recurring_leads/')?>/"+crm_id;
		})
	})
</script>