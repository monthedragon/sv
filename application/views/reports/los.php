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
			<?=input('start_calldate','start_calldate','date','date required',$start_calldate);?>
			<?=input('end_calldate','end_calldate','date','date required',$end_calldate);?>
            <input type='submit' value='generate' id='btn-search' data-rpt-type="old">
            <input type='submit' value='new format' data-rpt-type="new">
		</td>
	</tr>
</table>
</form>

<div id='div-report-list'></div>

<script>
	$(function(){
		$('form').submit(function(){
            const btn = document.activeElement;

            const rptType = btn.dataset.rptType;
            const controller = rptType === 'new'
                ? 'generate_new_los_rpt'
                : 'generate_los_rpt';

			if($(this).valid()){
				var url = "<?=base_url('reports/')?>/" + controller;
				var data = $(this).serialize();
				var type ='POST';
				
				window.location  =(url+'?'+data);
		
			}
			return false;
		})
		
	})
</script>