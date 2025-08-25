<form id='frm-search' action='<?=base_url('reports/generateVR')?>' method='POST'>
<table>
	<tr>
		<td>Campaign:</td>
		<td><?=select('crm_id','crm_id','required','',null,null,$crms)?></td>
	</tr>

    <tr>
        <td>Calldate:</td>
        <td colspan=3>
            <?=input('start_calldate','start_calldate','date','date required',$start_calldate);?>
            <?=input('end_calldate','end_calldate','date','date required',$end_calldate);?>
        </td>
    </tr>

    <tr>
        <td>Type:</td>
        <td><?=select('type','type',' required ','','daily',null,$rptTypes)?></td>
    </tr>
	
	<tr>
		<td colspan=3>
				<input type='submit' value='search' id='btn-search'>
		</td>
	</tr>
</table>
</form>

<div id='div-report-list'></div>

<script>
	$(function(){

		$('form').submit(function(){

			if($(this).valid()){
                let action = $(this).prop('action');
				var url = action;
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
	})
</script>