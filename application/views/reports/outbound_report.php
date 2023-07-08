<form id='frm-search' action='<?=base_url('reports/sph')?>' method='POST'>
<table>
	<tr >
		<td>Campaign:</td>
		<td><?=select('crm_id','crm_id','required','',$p_crm_id,null,$crms)?></td>
	</tr>
	
	<tr>
		<td>Target Month:</td>
		<td><?=select('target_month','target_month','required','','',null,$target_months)?></td>
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
		$('form').submit(function(){
		
			if($(this).valid()){
				var url = "<?=base_url('reports/generate_obr_rpt')?>";
				var data = $(this).serialize();
				var type ='POST';
				
				window.location  =(url+'?'+data);
		
			}
			return false;
		})
		
	})
</script>