<form id='frm-search' action='' method='POST' autocomplete="off">
<table>
	<tr >
		<td>Campaign:</td>
		<td><?=select('crm_id','crm_id','required','',$p_crm_id,null,$crms)?></td>
	</tr>
	<tr >
		<td>SV:</td>
		<td><?=select('user_id','user_id','required','',$p_user_id,null,$user_list)?></td>
	</tr>
	
	<tr>
		<td>Target Date:</td>
		<td>
			<?=input('start_calldate','start_calldate','input','date required',$start_calldate);?>
			<?=input('end_calldate','end_calldate','input','date required',$end_calldate);?>
		</td>
	</tr>
	<tr>
		<td colspan=3>
				<input type='submit' value='generate' id='btn-search'>
		</td>
	</tr>
</table>
</form>

<div id='div-report-list'></div>

<script>
	$(function(){
		
		$(".date").datepicker({'dateFormat':'yy-mm-dd'});
		
		$('form').submit(function(){
		
			if($(this).valid()){
				var url = "<?=base_url('reports/generate_los_rpt')?>";
				var data = $(this).serialize();
				var type ='POST';
				
				window.location  =(url+'?'+data);
		
			}
			return false;
		})
		
	})
</script>