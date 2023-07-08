<!-------REMARKS VIEW-------->
<!--<div style='position: fixed;width:500px;float:right;top: 25%; right: 10px'>-->
<div style='position: relative;width:500px;float:right;height:100px;'>
	<div style='position:absolute;right:0px;cursor:pointer' id='div-toggle-remarks'>
		T o g g l e &nbsp; R e m a r k s
	</div>
	<div id='div-remarks' style='width: 80%;position: absolute;top: 15%;right: 0px;background-color:white;padding:10px;max-height:200px;overflow:auto'>
	</div>
</div>
<!-------------------------->

<form>

    <!--HIDDEN FIELDS START HERE-->
        <input type='hidden' name='hidden[crm_id]' value='<?=$crm_id?>'>
    <!--HIDDEN FIELDS ENDS HERE-->
<?
    $html = '<table width=70%>';
//table TD width
    $html .= '<tr>
                <td width=2%></td>
                <td width=1%></td>
                <td width=1%></td>
                <td width=1%></td>
            </tr>';
    //loop thru field_mappings according to the set tables
    foreach($field_mappings as $table_id=>$fields){


        $table_name = strtoupper((isset($table_details[$table_id]) ? $table_details[$table_id]['foreign_mask_name'] : TABLE_MASK));
        $html .= "<tr class='tr_header'><td colspan=5> {$table_name} </td></tr>";

        //loop thru sales details per table
        if(!isset($sales[$table_id])){
            $html .= "<tr><td style='text-align:center' colspan=5><span class=warning>NO DETAILS</span></td></tr>";
            continue;
        }


        $saleTotalCtr = count($sales[$table_id]);
        $saleCtr =0;

        foreach($sales[$table_id] as $foreign_table_id=>$details){

            foreach($fields as $fieldDetails){

                if(!isset($details[$fieldDetails['field_name']])) continue;

                $saleDetails = $details[$fieldDetails['field_name']];
                $html .= "<tr class=><td class=>";
                $html .= '<b>'.(!empty($fieldDetails['mask_name']) ? $fieldDetails['mask_name'] : $fieldDetails['field_name']) .'</b>';
                $html .='</td><td>';
				$originalValue = '';
				if($fieldDetails['field_type']=='select'){

                    if(isset($lookup[$fieldDetails['lu_cat']])){
                        $options = $lookup[$fieldDetails['lu_cat']];
                        $originalValue = (isset($options[$saleDetails['original_value']]) ? $options[$saleDetails['original_value']] : '');
                    }
					
				}else{
					$originalValue = $saleDetails['original_value'];
				}
                $html .= $originalValue;
                $html .='</td><td>';


                //set the foreign_table_name as the array name for each field this is to identify where table to be saved!
                $foreign_table_name = (isset($table_details[$table_id]['foreign_table_name']) ? $table_details[$table_id]['foreign_table_name'] : 'main');

                //comment for the meantime if foreign is 0 then set it as is!!
                //if foreign_id is 0 then this is for main_table then we need to get the table_recid from sales/system_db else foreign table!
//                $foreign_id = ($saleDetails['foreign_table_id']==0) ? $sale_main_details['table_recid'] : $saleDetails['foreign_table_id'];

                //if foreign_id is 0 then this is for main_table then we need to set it 0 else foreign table!
                //this is very important to identify what rows to be updated on the main or foreign table!!!
                $foreign_id = $saleDetails['foreign_table_id'];

                //fieldName is set according to the foreign_table_id[baserecid/recid][field_name] this will be render on the save part!
                $fieldName = "{$table_id}[{$foreign_id}][{$fieldDetails['field_name']}]";

                //check if required if set then show default value!
                $default = (($fieldDetails['is_required']==1) ? $saleDetails['original_value'] : '');

                //new Value
                $newValue = $saleDetails['new_value'];

                //is set to empty meaning the value of the field is forced to empty
                //empty value has speacial tagging!
                $is_set_empty = 0;
                $readonly = '';
                if($newValue == EMPTY_TAG){
                    //if set to empty then readonly the field
                    $is_set_empty = 1;
                    $readonly =' readonly';
                }

                //set required if set as 1
                //required-conditional is a class used if change the status to pending then remove all required under this class!
                $required = (($fieldDetails['is_required']==1) ? 'required required-conditional' : '');

                //consolidate classes here maybe in the future I can set multiple clas
                $class = $required;

                //consolidate all misc here maybe in the futire i can set multiple misc
                $misc = $readonly;

                //set the object's ID
                $objID = $fieldDetails['field_name'].'_'.$table_id.'_'.$foreign_id;


                if($fieldDetails['field_type']=='input'){

                    //DRAW INPUT
                    $html .= input($objID,$fieldName,'input',$class,$saleDetails['new_value'],$default,$misc,50,200,$view_only);

                }elseif($fieldDetails['field_type']=='select'){

                    if(isset($lookup[$fieldDetails['lu_cat']])){
                        $options = $lookup[$fieldDetails['lu_cat']];
                        $noOpt = 0;
                    }else{
                        $options =array(); //if not set then show empty options! error catcher!!
                        $noOpt = 1;
                    }

                    //DRAW SELECT
                    //readonly is not applicable for select! so no matter even we include that in misc!
                    $html .= select($objID,$fieldName,$class,$saleDetails['new_value'],$default,$misc,$options,1,$view_only);

                    if($noOpt)
                        $html .= " &nbsp; <span class=warning> {$fieldDetails['lu_cat']}  is not set! ";

                }else{

                    //DRAW VALUE
                    $html .= $saleDetails['new_value'];
                }


                //DRAW EMPTY BUTTON

                $html .= '</td><td>';
                if(!$view_only)
                    $html .= "<span class='cursor-pointer spn-empty' obj_type='{$fieldDetails['field_type']}' obj_id ='{$objID}' is_empty='$is_set_empty'>[empty]</span>";

                $html .= '</td></tr>';

            }

            if($saleCtr!=$saleTotalCtr-1)
                $html .= "<tr class='tr_separator'><td colspan=5> </td></tr>";

            $saleCtr++;
        }

        $html .= "<tr class='tr_footer'><td colspan=5>  <br> </td></tr>";
    }

$html .= '</table>';

//final render here!
echo $html;

if($crm_id == 9){
	echo "<input type = 'button' value = 'Generate PAMU' onclick='generateErequest();'>";
	echo "<div id='challenger_div'></div>";
}


?>
    <?if($view_only){?>
        <input type='button' value='back' class='btn-back-to-list'>
    <?}else{?>

        <!--SV FORM for verification!-->
        <table width=100%>
            <tr class='tr_header_green'><td colspan=5> SV REMARKS </td></tr>
            <tr>
                <td valign=top>Remarks</td>
                <td><?=textarea('sv-remarks','SV[remarks]','')?></td>
            </tr>

            <tr>
                <td valign=top>Status</td>
                <td><?=select('sv-status','SV[status]','required','',null,null,$lu['sv_status'])?></td>
            </tr>

            <tr>
                <td valign=top>Alert</td>
                <td><?=select('sv-alert','SV[alert]','','',null,null,$lu['ol_alert'])?></td>
            </tr>
        </table>

		<?php
		if($crm_id == 9) {
			echo "<input type='submit' value='Save and Generate E-REQUEST' id='btn-submit-form' class='btn_sumbmit' action_type='e_request'> <br>";
			
			echo "<input type='submit' value='Save and Generate PAMU' id='btn-submit-form' class='btn_sumbmit' action_type='pamu'> <br>";
		}else{
			echo "<input type='submit' value='save' id='btn-submit-form' class='btn_sumbmit'>";
		}		
		?>
        
		<input type='button' value='back' class='btn-back-to-list'>
		<span style='color:red; font-weight:bold' id='spn_loader'></span>

    <?}?>
</form>

<script>

function generateErequest(){
	
	var url = '<?=base_url('sales/challenger_e_request/'.$sale_id.'/'.$crm_id.'/1')?>';
	do_modal(url,'challenger_div', 'v',400,850);
}


function do_modal(url,objModal,functionCall,height,width){
	$.ajax({
		url:url,
		success:function(data){
			$("#"+objModal).modal({
				containerCss: { height:height,width: width},
				onOpen:function(dialog){  
						dialog.overlay.fadeIn('fast', function () {
							dialog.container.slideDown('slow', function () {
								dialog.data.fadeIn('slow');
							});
						});
					},
					onClose: function(dialog){
						$("#"+objModal).html(''); 
						dialog.container.slideUp('slow', function () { 
							if(functionCall == 'pamu'){
								backToList();
							}
							$.modal.close(); // must call this! 		
						}); 
				}
			});
			$("#"+objModal).html(data);
		}
		
	})
}

function backToList(){
	window.location = "<?=base_url('sales/list_sale/'.$crm_id.'/'.$calldate)?>";
}

function copyToClipboard(element) {
  var $temp = $("<textarea>");
  var brRegex = /<br\s*[\/]?>/gi;
  $("body").append($temp);
  $temp.val($(element).html().replace(brRegex, "\r\n")).select();
  document.execCommand("copy");
  $temp.remove();
  //$.modal.close();
}

function closeModal(){
	$.modal.close();
}


    $(function(){
        set_empty_to_dom('<?=EMPTY_TAG?>');
		

        $('form').submit(function(){
			var btn_submit = $(this).find("input[type=submit]:focus" );

            if($(this).valid()){

                var data = $(this).serialize();
                var url = '<?=base_url('sales/save_sales/'.$sale_id)?>';
                $.ajax({
                    url:url,
                    data:data,
                    type:'POST',
                    beforeSend:function(){
						$(".btn_sumbmit").prop('disabled',true);
						$('#spn_loader').html('Please wait . . . ');
					},
                    success:function(data){
						
		
                        if('<?=isset($_REQUEST['debug'])?>'){
							
                            alert('DEBUG INFO: '+data);
							
                        }else if('<?=$crm_id?>' == 9){
							var a_t = btn_submit.attr('action_type');
							var modal_url = '';
							
							if(a_t == 'e_request'){
								//generate e_request
								modal_url = '<?=base_url('sales/challenger_e_request/'.$sale_id.'/'.$crm_id)?>';
							}else if(a_t == 'pamu'){
								//generate PAMU	
								modal_url = '<?=base_url('sales/challenger_e_request/'.$sale_id.'/'.$crm_id.'/1')?>';
							}
							
							if($('#sv-status').val() == '1'){
								do_modal(modal_url,'challenger_div', a_t,400,850);
							}else{
								backToList();
							}
							
						}else{
													
							if($('#sv-status').val() == '1'){
								window.open('<?=base_url('reports/generate_xls_report/'.$crm_id.'/'.$sale_main_details['table_recid'])?>')
							}
													
                            alert('saved!');
							backToList();
                        }

                    },
                    complete:function(){
                        $('.btn_sumbmit').removeProp('disabled');
						$('#spn_loader').html('');
                    },
					error:function(data){
						alert(data);
					}
                })

            }else{
                alert('Please check the required fields');
            }

            return false;
        })

        $('.btn-back-to-list').click(function(){
			backToList();
        })

        $('#sv-status').change(function(){
            var status = $(this).val();
            //if pending then set the remarks as required
            if(status == '1'){
                $('.required-conditional').addClass('required');
                $('#sv-remarks').removeClass('required');
            }else{
                $('.required-conditional').removeClass('required');
				$('#sv-remarks').addClass('required');
            }
        })

		$('#div-toggle-remarks').click(function(){
			$('#div-remarks').toggle();
		})

		//LOAD Remarks
		var url = '<?=base_url('remarks/view_remarks_ajax/'.$sale_id)?>';
		do_ajax(url,'POST','','div-remarks');
    })
</script>