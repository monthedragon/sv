<table >
    <tr class='tr_header'>
        <td>Lead Identity</td>
        <td>Callresult</td>
        <td>Manila</td>
        <td>Provincial</td>
        <td>Total Count</td>

    </tr>
    <?
    $total_tally = array();
    $prev_li = '';

    $dmc = array('TOTAL'=>0);
    $contacts =  array('TOTAL'=>0);
    $total_contacts =  array('TOTAL'=>0);

    foreach($result as $details){
        $callresult_ctr 	= $details['CTR'];
        $cr 				= $details['callresult'];
        $lead_identity 		= $details['lead_identity'];
        $li 				= $details['lead_identity'];
        $callresult_name 	= $cr;
        $mla_ctr 			= $details['MLA'];
        $prov_ctr 			= $details['PROV'];

        if($prev_li != $lead_identity){
            $prev_li  = $lead_identity;
        }else{
            //if same as previous then set it blank
            $lead_identity = '';
        }
        ?>
        <?if($lead_identity != ''){
            //oa = overall agreement
            $ag_mla_oa 	= ($ag_res[$li]['AG_MLA']) ? $ag_res[$li]['AG_MLA'] : '0';
            $ag_prov_oa = ($ag_res[$li]['AG_PROV']) ? $ag_res[$li]['AG_PROV'] : '0';

            //ws = web shoper
            $ag_mla_ws 	= ($ag_res[$li]['WEB_SHOP_MLA']) ? $ag_res[$li]['WEB_SHOP_MLA'] : '0';
            $ag_prov_ws = ($ag_res[$li]['WEB_SHOP_PROV']) ? $ag_res[$li]['WEB_SHOP_PROV'] : '0';
            ?>

            <tr>
                <td><?=$lead_identity?></td>
                <td>OVERALL AGREEMENT</td>
                <td><?=$ag_mla_oa?></td>
                <td><?=$ag_prov_oa?></td>
                <td><?=$ag_mla_oa+$ag_prov_oa?></td>
            </tr>
            <tr>
                <td></td>
                <td>WEB SHOPPER</td>
                <td><?=$ag_mla_ws?></td>
                <td><?=$ag_prov_ws?></td>
                <td><?=$ag_mla_ws+$ag_prov_ws?></td>
            </tr>

            <?

            $total_tally['OVERALL AGREEMENT']['MLA'] 	+= $ag_mla_oa;
            $total_tally['OVERALL AGREEMENT']['PROV'] 	+= $ag_prov_oa;
            $total_tally['OVERALL AGREEMENT']['TOTAL'] 	+= $ag_mla_oa+$ag_prov_oa;

            $total_tally['WEB SHOPPER']['MLA'] 		+= $ag_mla_ws;
            $total_tally['WEB SHOPPER']['PROV'] 	+= $ag_prov_ws;
            $total_tally['WEB SHOPPER']['TOTAL'] 	+= $ag_mla_ws+$ag_prov_ws;

        }?>
        <?php

        $is_dmc = false;
        if($cr == ''){
            $callresult_name = 'Rehased';
        }elseif($cr == 'CB'){
            if(isset($cb_lookup[$details['FINAL_DISPO']])){
                $callresult_name = '(CB)'.$cb_lookup[$details['FINAL_DISPO']];
            }

            //2; //for CONTACTS
            $is_dmc = $details['FINAL_DISPO'] == 'cpna' ? 2 : 1;

        }elseif($cr == 'NI'){
            $is_dmc = 1;
            if(isset($ni_lookup[$details['FINAL_DISPO']])){
                $callresult_name = '(NI)'.$ni_lookup[$details['FINAL_DISPO']];
            }
        }elseif($cr == 'AG' ){
            $is_dmc = 1;
            if($details['FINAL_DISPO'] == 'SALE_UNVERIFIED')
                $callresult_name = '(AG)'.'SALE_UNVERIFIED';
            else
                $callresult_name = '(AG)'.$details['FINAL_DISPO'];
        }else{
            if(isset($callresult_lookup[$details['FINAL_DISPO']])){
                $callresult_name = $callresult_lookup[$details['FINAL_DISPO']];
            }
        }

        if(!isset($total_tally[$callresult_name])){
            $total_tally[$callresult_name]['MLA'] = 0;
            $total_tally[$callresult_name]['PROV'] = 0;
            $total_tally[$callresult_name]['TOTAL'] = 0;
        }

        $total_tally[$callresult_name]['MLA'] 	+= $mla_ctr;
        $total_tally[$callresult_name]['PROV'] 	+= $prov_ctr;
        $total_tally[$callresult_name]['TOTAL'] += $callresult_ctr;

        ?>
        <tr>
            <td></td>
            <td><?=$callresult_name?></td>
            <td><?=$mla_ctr?></td>
            <td><?=$prov_ctr?></td>
            <td><?=$callresult_ctr?></td>
        </tr>


    <?
        //DMC/CONTACT display
        if($is_dmc){
            if($is_dmc == 1 ){
                $dmc['MLA'] += $mla_ctr;
                $dmc['PROV'] += $prov_ctr;
                $dmc['TOTAL'] += $callresult_ctr;
            }
            //CONTACTS = DMC+CPNA
            $contacts['MLA'] += $mla_ctr;
            $contacts['PROV'] += $prov_ctr;
            $contacts['TOTAL'] += $callresult_ctr;
        }

        //CONTACTS + non-DMC
        $total_contacts['MLA'] += $mla_ctr;
        $total_contacts['PROV'] += $prov_ctr;
        $total_contacts['TOTAL'] += $callresult_ctr;

    }?>

    <tr class='tr_header_red tr_header_green'>
        <td colspan=5>TOTAL</td>

    </tr>

    <?
    foreach($total_tally as $cr=>$tally_details){
        echo "
				<tr>
					<td></td>
					<td>{$cr}</td>
					<td>{$tally_details['MLA']}</td>
					<td>{$tally_details['PROV']}</td>
					<td>{$tally_details['TOTAL']}</td>
				</tr>
			";
    }

    //Tally for DMC/CONTACT display
    $column_arr = ['MLA','PROV'];
    ?>

    <tr class='tr_header'>
        <td colspan = <?=count($column_arr)+3?>></td>

    </tr>
    <tr>
        <td></td>
        <td>DMC</td>
        <? draw_total($column_arr, $dmc);?>
    </tr>

    <tr>
        <td></td>
        <td>CONTACTS</td>
        <? draw_total($column_arr, $contacts);?>
    </tr>
    <tr>
        <td></td>
        <td>TOTAL CONTACTS</td>
        <? draw_total($column_arr, $total_contacts);?>
    </tr>


    <tr>
        <td></td>
        <td>DMC RATE</td>
        <? draw_rate_html($column_arr, $dmc, $contacts);?>
    </tr>

    <tr>
        <td></td>
        <td>CONTACT RATE</td>
        <?  draw_rate_html($column_arr, $contacts, $total_contacts);?>
    </tr>
</table>