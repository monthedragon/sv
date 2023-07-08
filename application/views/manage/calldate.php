<form id='frm-search' action='<?=base_url('main')?>' method='POST'>
<table>
	<tr>
		<td>Campaign:</td>
		<td><?=select('crm_id','crm_id','required','',null,null,$crms)?></td>
	</tr>
	<tr>
		<td>Firstname</td>
		<td><?=input('firstname','firstname','input');?></td>
	</tr>
	<tr>
		<td>Lastname</td>
		<td><?=input('lastname','lastname','input');?></td>
		
	</tr>
	<tr>
		<td>Calldate:</td>
		<td colspan=3>
				<?=input('start_calldate','start_calldate','input','date',$start_calldate);?>
				<?=input('end_calldate','end_calldate','input','date',$end_calldate);?>
		</td>
	</tr>
	
	<tr>
		<td colspan=3>
				<input type='submit' value='search' id='btn-search'>
		</td>
	</tr>
	
	<tr>
		<td colspan=3>
			<span class='warning note'>Only first 20 records will be displayed, <br /> Please use the search fields to filter the result.</span>
		</td>
	</tr>
</table>
</form>

<div id='div-customer-list'></div>

<script>
	$(function(){
		$('form').submit(function(){
		
			if($(this).valid()){
				var url = "<?=base_url('manage/search_customer')?>";
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
						$('#div-customer-list').html(data);
					},
					complete:function(){
						$('#btn-search').removeProp('disabled').val('search');;
					},
				});	
		
			}
			return false;
		})
		
		$('.date').datepicker({'dateFormat':'yy-mm-dd'});
	})
</script>