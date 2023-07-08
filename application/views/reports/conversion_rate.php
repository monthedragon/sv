<form id='frm-search' action='<?=base_url('reports/sph')?>' method='POST'>
<table>
	<tr >
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
		<td valign=top>DMC setup:</td>
		<td colspan=3>
			<div id='div_dmc_setup_control' style='cursor:pointer; color:red'>TOGGLE DMC CONFIG VIEW</div>
			<i><span class='note' >previous choosen DMC CONFIG will be retained PER campaign</span></i>
			<div id='div_dmc_setup' style='display:none'>
				<table width=100%>
					<tr  class='tr_header'>
						<td>CALLBACK</td>
						<td>NI</td>
						<td>CALLRESULT</td>
					</tr>
					<tr>
						<td valign=top>
							<?
								foreach($cb_lookup as $code=>$desc){
									$is_checked = isset($conversion_rate_lu[$code]) ? 'checked' : '';
									echo "<label style='cursor:pointer'><input name='CB_cr[]' type='checkbox' id='{$code}'  value='{$code}' {$is_checked}>{$desc}</label><br>";
								}
								
							?>
						</td>
						<td valign=top>
							<?
								foreach($ni_lookup as $code=>$desc){
									$is_checked = isset($conversion_rate_lu[$code]) ? 'checked' : '';
									echo "<label  style='cursor:pointer'><input name='NI_cr[]' type='checkbox' id='{$code}'  value='{$code}' {$is_checked}>{$desc}</label><br>";
								}
								
							?>
						</td>
						<td valign=top>
							<?
								foreach($callresult_lookup as $code=>$desc){
									if($code == 'AG' || $code == 'CB' || $code == 'NI') continue; //dont display the parent CR
									$is_checked = isset($conversion_rate_lu[$code]) ? 'checked' : '';
									echo "<label  style='cursor:pointer'><input name='cr[]' type='checkbox' id='{$code}'  value='{$code}' {$is_checked}>{$desc}</label><br>";
								}
								
							?>
						</td>
					</tr>
				</table>
			</div>
		</td>
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
				var url = "<?=base_url('reports/conversion_rate_generate')?>";
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
			window.location = "<?=base_url('reports/conversion_rate/')?>/"+crm_id;
		})
		
		$('#div_dmc_setup_control').click(function(){
			$('#div_dmc_setup').toggle("slow");
		})
	})
</script>