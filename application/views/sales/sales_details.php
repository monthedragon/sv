<!-------REMARKS VIEW-------->
<!--<div style='position: fixed;width:500px;float:right;top: 25%; right: 10px'>-->
<div id="divRemarks"
     style="position:fixed; top:135px; right:20px; width:500px;
            background:#fff; border:1px solid #ccc; box-shadow:0 2px 6px rgba(0,0,0,.2);
            border-radius:6px; padding:10px; transition:top .2s ease;">
    <div id="div-toggle-remarks" style="cursor:pointer; text-align:right; font-weight:bold;">
        Toggle Remarks
    </div>
    <div id="div-remarks" style="margin-top:10px; max-height:400px; overflow:auto;">
        <!-- remarks go here -->
    </div>
</div>
<!-------------------------->
<div style="float: left; width: 65%; padding: 10px;">
    <form>
        <!--HIDDEN FIELDS START HERE-->
            <input type='hidden' name='hidden[crm_id]' value='<?=$crm_id?>'>
        <!--HIDDEN FIELDS ENDS HERE-->
    <?
        $html = '<table width=100%>';
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
                    $html .="</td><td id='td_{$fieldDetails['field_name']}'>";
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
        echo "<input type = 'button' value = 'Generate FAMU' onclick='generateErequest();'>";
        echo "<div id='challenger_div'></div>";
    }

    //Standard e-request div
    echo "<span id='e_request_div'></span>";

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
                echo "<input type='submit' value='Save and Generate FAMU' id='btn-submit-form' class='btn_sumbmit' action_type='pamu'> <br>";
            }elseif($crm_id == 10) {
                echo "<input type='submit' value='Save and Generate E-REQUEST' id='btn-submit-form' class='btn_sumbmit' action_type='e_request_save'> <br>";
            }else{
                echo "<input type='submit' value='save' id='btn-submit-form' class='btn_sumbmit'>";
            }
            ?>

            <input type='button' value='back' class='btn-back-to-list'>
            <span style='color:red; font-weight:bold' id='spn_loader'></span>

        <?}?>
    </form>
</div>
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
							if(functionCall == 'pamu' || functionCall == 'e_request_save'){
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

    // Clone the element and remove <hr> before copying
    var content = $(element).clone();
    content.find("hr").replaceWith("\n");

    $("body").append($temp);
    $temp.val(content.html().replace(brRegex, "\r\n")).select();
    document.execCommand("copy");
    $temp.remove();
}

function closeModal(){
	$.modal.close();
}

function populateSourceCode(){
	
	//As of June 18, 2021
	//Auto populate "Source Code" base on the "Acct B Score"
    //Additional condition based on the selected amf_product (April 06, 2025)
    let amf_product = $("[id^='amf_product']").first().val();
	var acct_b_score = parseInt($('#td_acct_b_score').html());
	var source_code = 'BLANK'; //if nothing matched then set "BLANK" as word

    // "1315003005895" IF selected is "AMF WAIVED FOR LIFE", ELSE apply old logic:
	// "1315003005495" IF account_b_score within 630 TO 639
	// "1315003005595" IF account_b_score within 640 TO 649 
	// "1315003005695" IF account_b_score within 650 HIGH 	

    if(amf_product == '1'){
        source_code = '1315003005895';
    }else{
        if(acct_b_score >= 630 && acct_b_score <= 639){
            source_code = '1315003005495';
        }else if(acct_b_score >= 640 && acct_b_score <= 649){
            source_code = '1315003005595';
        }else if(acct_b_score >= 650){
            source_code = '1315003005695';
        }
    }

	$('#sv_source_code_0_0').val(source_code);
	
}


	//2021-07-31 FOR Challenger
	//set automatically the value for MID
	function calculateMID(){
		var product = $('#c_product_0_0').val();
		if(product == ''){
			product = $('#td_c_product').html();
		}
		product_small = product;
		product = product.toUpperCase();
				
		var ch_portfolio = $('#td_ch_portfolio').html().toUpperCase();
		
		var ch_type = $('#c_type_of_ch_holder_0_0').val().toUpperCase();
		if(ch_type == ''){
			ch_type = $('#td_c_type_of_ch_holder').html().toUpperCase();;
		}
		
		var c_other_bank = $('#c_other_bank_0_0').val().toUpperCase();
		if(c_other_bank == ''){
			//IMPORTANT: when adding LOOKUP for c_other_bank
			//Make it sure the lu_code and lu_desc is the same when all spaces turns to '_' underscore
			c_other_bank = $('#td_c_other_bank').html().replace(/ /g,'_').toUpperCase();;
		}
		
		// alert(product + '_' + ch_portfolio + '_' + ch_type + '_' + c_other_bank);
		
		if(product_small == 'balance_transfer'){
			//Balance transfer has it own mapping
			var key = ch_type + '_' + c_other_bank;
			var mid_set = {
				'TRANSACTOR_BT_FROM_CITIBANK' : ['888094398'],
				'TRANSACTOR_BT_FROM_SCB' : ['888094406'],
				'TRANSACTOR_BT_FROM_BDO' : ['888094414'],
				'TRANSACTOR_BT_FROM_METROBANK' : ['888094422'],
				'TRANSACTOR_BT_FROM_EASTWEST' : ['888094430'],
				'TRANSACTOR_BT_FROM_BPI' : ['888094448'],
				'TRANSACTOR_BT_FROM_ALLIED_BANK' : ['888094455'],
				'TRANSACTOR_BT_FROM_UNION_BANK' : ['888094463'],
				'TRANSACTOR_BT_FROM_EQUICOM' : ['888094471'],
				'TRANSACTOR_BT_FROM_PNB' : ['888094489'],
				'TRANSACTOR_BT_FROM_HSBC' : ['888094497'],
				'TRANSACTOR_BT_FROM_MAYBANK' : ['888094505'],
				'TRANSACTOR_BT_FROM_CHINABANK' : ['888094513'],
				'TRANSACTOR_BT_FROM_BANK_OF_COMMERCE' : ['888094521'],
				'TRANSACTOR_BT_FROM_SECURITY_BANK' : ['888094539'],
				'TRANSACTOR_BT_FROM_LANDBANK_VISA' : ['888094547'],
				'TRANSACTOR_BT_FROM_LANDBANK_MASTERCARD' : ['888094554'],
				'TRANSACTOR_BT_FROM_ROBINSONS_BANK' : ['888098837'],
				
				'REVOLVER_BT_FROM_CITIBANK' : ['888094752'],
				'REVOLVER_BT_FROM_SCB' : ['888094760'],
				'REVOLVER_BT_FROM_BDO' : ['888094778'],
				'REVOLVER_BT_FROM_METROBANK' : ['888094786'],
				'REVOLVER_BT_FROM_EASTWEST' : ['888094794'],
				'REVOLVER_BT_FROM_BPI' : ['888094802'],
				'REVOLVER_BT_FROM_ALLIED_BANK' : ['880948187'],
				'REVOLVER_BT_FROM_UNION_BANK' : ['888094828'],
				'REVOLVER_BT_FROM_EQUICOM' : ['888094836'],
				'REVOLVER_BT_FROM_PNB' : ['888094844'],
				'REVOLVER_BT_FROM_HSBC' : ['888094851'],
				'REVOLVER_BT_FROM_MAYBANK' : ['888094869'],
				'REVOLVER_BT_FROM_CHINABANK' : ['888094877'],
				'REVOLVER_BT_FROM_BANK_OF_COMMERCE' : ['888094885'],
				'REVOLVER_BT_FROM_SECURITY_BANK' : ['888094893'],
				'REVOLVER_BT_FROM_LANDBANK_VISA' : ['888094901'],
				'REVOLVER_BT_FROM_LANDBANK_MASTERCARD' : ['888094919'],
				'REVOLVER_BT_FROM_ASIA_UNITED_BANK' : ['888094927'],
				'REVOLVER_BT_FROM_ROBINSONS_BANK' : ['88098845'],

			};
		}else{
			
			var key = product + '_' + ch_portfolio + '_' + ch_type;
			var mid_set = {
				'CASH_LOAN_LOW_TRANSACTOR' : ['888100716'],
				'CASH_LOAN_MEDIUM_TRANSACTOR' : ['888100724'],			
				'CASH_LOAN_HIGH_TRANSACTOR' : ['888100732'],

				'CASH_LOAN_LOW_REVOLVER' : ['888100740'],	
				'CASH_LOAN_MEDIUM_REVOLVER' : ['888100757'],					
				'CASH_LOAN_HIGH_REVOLVER' : ['888100765'],			

				'YOUR_CASH_LOW_TRANSACTOR' : ['888100641'],			
				'YOUR_CASH_MEDIUM_TRANSACTOR' : ['888100658'],					
				'YOUR_CASH_HIGH_TRANSACTOR' : ['888100666'],				

				'YOUR_CASH_LOW_REVOLVER' : ['888100674'],			
				'YOUR_CASH_MEDIUM_REVOLVER' : ['888100682'],					
				'YOUR_CASH_HIGH_REVOLVER' : ['888100690'],
			};
		}
	
		var mid = '';
		if (key in mid_set){
			mid = mid_set[key][0];
		}
			
		$('#c_mid_0_0').val(mid);
		
	}

    $(function(){
        set_empty_to_dom('<?=EMPTY_TAG?>');
		

        $('form').submit(function(){
			var btn_submit = $(this).find("input[type=submit]:focus" );

            if($(this).valid()){
				
				if('<?=$crm_id?>' == 1){
					var old_home_no = $('#sv_home_no_0_0').closest('td').prev('td').html();
					var old_mobile_no = $('#sv_mobile_no_0_0').closest('td').prev('td').html();
					
					var change_in_home = $('#sv_home_no_0_0').val();
					var change_in_mobile = $('#sv_mobile_no_0_0').val();
					
					if(change_in_home != old_home_no || change_in_mobile != old_mobile_no){
						if(!confirm("Changes found on mobile or home! \r\nDo you want to proceed?")){
							return false;
						}
					}
				}

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

                        }else if('<?=$crm_id?>' == 10){
                            var a_t = btn_submit.attr('action_type');
                            var modal_url = '';

                            //generate e_request
                            modal_url = '<?=base_url('sales/supple_e_request/'.$sale_id.'/'.$crm_id)?>';

                            if($('#sv-status').val() == '1'){
                                do_modal(modal_url,'e_request_div', a_t,400,850);
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
		
		if('<?=$crm_id?>' == 1){
			//As of June 6, 2021 
			//Auto populate the sv-remarks when e-soa = yes 
			
			
			//LIMITED ONLY to TEMPLATE 1
			$('#c_esoa_0_0').keyup(function(){
				var val = $(this).val();
				
				var sv_remark = '';
				if(val.toUpperCase() == 'YES'){
					sv_remark = ('AGREED TO ENROLL TO E-SOA ');
				}
				$('#sv-remarks').val(sv_remark)
			})
			
			$('#c_esoa_0_0').keyup();
			
			populateSourceCode();
			
		
		}
		
		//2021-07-31 Challenger
		if('<?=$crm_id?>' == 9){
			$('#c_product_0_0').change(function(){
				calculateMID();
			});
			
			$('#c_type_of_ch_holder_0_0').change(function(){
				calculateMID();
			});
			
			$('#c_other_bank_0_0').change(function(){
				calculateMID();
			});
			
			
		}

        if('<?=$crm_id?>' == 1){
            $("[id^='amf_product']").on('change', function() {
                populateSourceCode();
            });
        }

        var initialTop = 135;
        $(window).on("scroll", function() {
            if ($(window).scrollTop() > 0) {
                $("#divRemarks").css("top", "5px");  // stick to very top
            } else {
                $("#divRemarks").css("top", initialTop + "px");  // restore gap
            }
        });

    })
</script>