<form id='frm-search' action='<?=base_url('sales/list_sale/'.$crm_id)?>' method='POST'>
	<table class='tbl-search'>
		<tr>
			<td>
				Calldate:
				<?=input('calldate','calldate','date','date',$calldate);?>
				<input type='submit' value='search' id='btn-search'>
			</td>
			<td>
				<!----DRAW ALERT---->
				<?=alert_html($alerts,$crms,$lu);?>
				<!----END  ALERT---->
				
			</td>
		</tr>
	</table>
</form>
	
<table width=100% class='tbl_sale'>
	<thead>
	
	<tr class='header'>
		<td width=1%>&nbsp;</td>
		<td>customer</td>
		<td>calldate</td>
		<td>status</td>
		<td>agent</td>
		<td>sv</td>
		<td width=1>actions</td>
		<td width=1>
			<?if(has_access(180)){?>
				<input type='checkbox' id='chk-all'>
			<?}?>
		</td>
		<td width=1>
			
			<?if(has_access(181)){?>
				<!--TODO generate bulk SF-->
			<?}?>
			
		</td>
	</tr>
	</thead>
	<tbody>
    <!--NEW TABLE-->
    <?
	$saleCtr = 1;
	if(count($sales['NEW']) > 0){?>
        <tr class='tr-header'>
            <td colspan=9 class='divider'>
                New
            </td>
        </tr>

        <?foreach($sales['NEW'] as $details){?>
            <tr>
				<td><?=$saleCtr++?></td>
                <td>
					<?$href = ((has_access(3)) ? base_url('sales/init_sale/'.$crm_id.'/'.$details['id'].'/'.$calldate) : '#');?>
					<a href='<?=$href?>'>
                        <?=$details['firstname']?>
                        <?=$details['lastname']?>
                    </a>
                </td>
				<td colspan=8>
					AVAILABLE
				</td>
            </tr>
        <?}?>
   <?}?>

    <!--VERIFIED TABLE-->
    <?if(count($sales['VERIFIED']) > 0){?>
        <tr class=''>
            <td colspan=9 class='divider'>
                Verified
            </td>
       </tr>

        <?foreach($sales['VERIFIED'] as $details){?>
            <tr class='tr_alert_<?=$details['alert']?>'>
				<td><?=$saleCtr++?></td>
                <td>
					<?$href = ((has_access(3)) ? base_url('sales/init_sale/'.$crm_id.'/'.$details['table_recid'].'/'.$calldate) : '#');?>
										<a href='<?=$href?>'>
                        <?=$details['firstname']?>
                        <?=$details['lastname']?>
                    </a>
                </td>
				<td><?=render_date($details['calldate'],5)?></td>
                <td><?=$lu['ol_status'][$details['status']]?></td>
                <td><?=$details['agent_name']?></td>
                <!--
                    TODO
                    ADD PRIVS HERE TO IDENTIFY if SV_CODE or NAME will be dislayed!
                    ADD COLOR CODING based on the status!!!
                -->
                <td><?=$details['sv_code']?></td>
                <td>
					<a href='<?=base_url('sales/view_sale_details/'.$crm_id.'/'.$details['id'].'/'.$calldate)?>'>details</a>
					|
					<a href='<?=base_url('remarks/view_remarks/'.$details['id'].'/'.$calldate)?>'>remarks</a>
				</td>
				<?if(has_access(180)){?>
					<td><input type='checkbox' class='chk-locked' target_id='<?=$details['id']?>' <?=($details['is_locked'] ? 'checked' : '')?>></td>
				<?}?>
					
				<?if(has_access(181)){?>
					<td>
					 <?if($details['status'] === '1'){?>
						 <span class='cursor_blue' onclick=window.open('<?=base_url('reports/generate_xls_report/'.$crm_id.'/'.$details['table_recid'])?>')>S F</span>
					 <?}?>
					</td>
				<?}?>
			</tr>
        <?}?>
    <?}?>
</tbody>
</table>

<script>
	$(function(){
		var url = '<?=base_url('sales/list_sale')?>';
		init_search_fx(url);
		
		$('.chk-locked').click(function(){
			var val = $(this).prop('checked');
			var sale_id = $(this).attr('target_id');
			var url = "<?=base_url('sales/locked')?>/"+sale_id+'/'+val;
			do_ajax(url);
		})
		
		$('#chk-all').click(function(){
			var val = $(this).prop('checked');
			var url = "<?=base_url('sales/locked_all/'.$crm_id.'/'.$calldate)?>/"+val;
			do_ajax(url);
			
			
			$('.chk-locked').prop('checked',val);
		})
		
	})
</script>