<!-- tables [0] = main, [1] = supple or etc, [2] = card or etc-->
<?foreach($field_tables as $table_id=>$field_arr){?>
<table width=100%>	
	<tr class=''>
		<td colspan=8 class='divider'><?=($table_id == 0 ) ? 'Main Table' : $table_info[$table_id]['foreign_mask_name']?></td>
	</tr>
</table>

<div class='div-mapping-holder'>
	<table width=100%>
		<tr class='tr_header'>
			<td>&nbsp;</td>
			<td>db field</td>
			<td>mask name</td>
			<td>type</td>
			<td>lookup category</td>
			<td>required</td>
			<td>order</td>
		</tr>
		<!-- $field_mappings = data from field_mappings/sv table -->
		<?foreach($field_arr as $field_name){?>	
			<?
				//id has 5 '_' this will be used to split the object, first key is the id/field_mapping, second key is the field_name/field_mapping
				$objID = $table_id.'_____'.$field_name;
				$fieldSet  = ((isset($field_mappings[$table_id][$field_name])) ? 1 : 0 );
				$fieldDetails = ($fieldSet ? $field_mappings[$table_id][$field_name] : '');
				
				if(!empty($fieldDetails) && $fieldDetails['is_active']==1){
					$isActive = 1;
				}else{
					$isActive = 0;
				}
			?>
			<a href='#' id='{$objID}'></a>
			<tr>
				<td>
					<?
						$is_active = (($fieldSet) ? (($fieldDetails['is_active']) ? 'checked=checked' : '') : '' );
						echo "<input type='checkbox' class='obj_on_change' id='{$objID}' name='is_active' {$is_active}>";
					?>
				</td>
				<td><?=$field_name?></td>
				<td>
					
					<?
						if($isActive){
							$mask_name = (($fieldSet) ? $fieldDetails['mask_name'] : '' );
							echo input($objID,'mask_name','input','obj_on_change',$mask_name);
						}
					?>
				</td>
				<td>
					<?
						if($isActive){
							$field_type = (($fieldSet) ? $fieldDetails['field_type'] : '' );
							$options = array('input'=>'INPUT','select'=>'SELECT');
							echo select($objID,'field_type','obj_on_change',$field_type,null,null,$options);
						}
					?>
				</td>
				<td>
					<?
						if($isActive){
							$lu_cat = (($fieldSet) ? $fieldDetails['lu_cat'] : '' );
							echo input($objID,'lu_cat','input','obj_on_change',$lu_cat);
						}
					?>
				</td>
				<td>
					<?
						if($isActive){
							$required = (($fieldSet) ? (($fieldDetails['is_required']) ? 'checked=checked' : '') : '' );
							echo "<input type='checkbox' class='obj_on_change' id='{$objID}' name='is_required' {$required}>";
						}
					?>
				</td>
				<td>
					<?
						if($isActive){
							$orderBy = (($fieldSet) ? $fieldDetails['order_by'] : '' );
							echo input($objID,'order_by','input','obj_on_change',$orderBy,'','',5,5);
						}
					?>
				</td>
			</tr>
		<?}?>
	</table>
</div>
<?}?>


<script>
	$(function(){
	
		$('.obj_on_change').change(function(){
	
			var url = "<?=base_url('setting/update_field_mapping')?>";
			var type = $(this).prop('type');
			var val = $(this).val();
			var id = $(this).prop('id');
			var name = $(this).prop('name');
			var data = {};
			
			if(type=='checkbox'){
				val = $(this).prop('checked');
				if(val == true)
					val = 1;
				else
					val = 0;
			}
			
			data['id'] = id;
			data['val'] = val;
			data['target_field'] = name;
			data['crm_code'] = '<?=$crmDetails["crm_code"]?>';
			
			$.ajax({
				url:url,
				data:data,
				type:'POST',
				complete:function(){
					if(name=='is_active'){
						//window.location = '<?=base_url('setting/mapping/'.$crm_id)?>';
					}
				}
			})
		})
	})

</script>