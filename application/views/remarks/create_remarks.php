<form >
<table>
	<tr>
		<td valign=top>Remarks</td>
		<td><?=textarea('remarks','remarks','required')?></td>
	</tr>

	<tr>
		<td valign=top>Alert</td>
		<td><?=select('alert','alert','','',null,null,$lu['ol_alert'])?></td>
	</tr>
	
	<tr>
		<td valign=top>Action</td>
		<td><?=select('action','action','','',null,null,$lu['actions'])?></td>
	</tr>
	<tr>
		<td>
			<div class='div_hide'>Calldate:</div>
		</td>
		<td>
			<div class='div_hide'>
				<input type='input' class='date' name='moved_date' id='txt-moved-date'>
			</div>
		</td>
	</tr>
		
	<tr>
		<td colspan=2 style='text-align:right'><input type='submit' value='save'></td>
	</tr>
</table>

	
		
	

	
</form>

<script>
	$(function(){
		$(".date").datepicker({'dateFormat':'yy-mm-dd'});
		
		$('#action').change(function(){
			var action  = $(this).val();
			if(action == '1'){
				$('.div_hide').show();
				$('#txt-moved-date').addClass('required');
			}else{
				$('.div_hide').hide();
				$('#txt-moved-date').removeClass('required');
			}
		})
		
		$('form').submit(function(){
			
			if($(this).valid()){
				
				$.ajax({
                    url:"<?=base_url('remarks/save_remarks/'.$sale_id)?>",
					data:$(this).serialize(),
					type:'POST',
					complete:function(){
						alert('Saved!');
						window.location = '<?=base_url('remarks/view_remarks/'.$sale_id)?>';
					},
					success:function(data){
						//alert(data);
					}
                })
				
								
			}else{
				alert('Please check required fields');
			}
			
			return false;
		})
	})
</script>