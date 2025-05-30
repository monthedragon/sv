<?php
$message = '';
foreach($sales as $relatedTableId => $baseRecIdArr){

    if($relatedTableId  == 0){
        continue; //this is the main table
    }

    foreach($baseRecIdArr as $baseRecid => $suppleDetails){
        $detail_arr = globalGetValues($suppleDetails);
        $message .= 'Place of Birth: '. $detail_arr['place_of_birth'] . "<br>";
        $message .= 'Present Address: '. (!empty($detail_arr['present_add']) ? $detail_arr['present_add'] : "-") . "<br>";
        $message .= 'Employment: '. getLookUpVal($lookup['employment_info'], $detail_arr['employment']). "<br>";
        $message .= 'Company Name: '. $detail_arr['comp_name'] . "<br>";
        $message .= 'Nature of Business: '. $detail_arr['nature_of_bus'] . "<br>";
        $message .= 'Company Address: '. $detail_arr['comp_add'] . "<br>";
        $message .= 'Occupaition / Position: '. $detail_arr['occupation_pos'] . "<br>";
        $message .= 'Office No: '. $detail_arr['office_no'] . "<br>";
        $message .= 'Annual Income: '. $detail_arr['annual_income'] . "<br>";
        $message .= 'Spend Limit: '. number_format($detail_arr['assigned_spend_limit'], 2) . "<br>"; // Spend limit with 2 decimal places
        $message .=  'Submitted ID: '. getLookUpVal($lookup['submitted_id'], $detail_arr['submitted_id']). "<br>";
        $message .= "<hr class='record-separator'>";
    }
}


echo "<span style='float:left;cursor:pointer;' onclick=closeModal() > PRESS `ESC` to EXIT</span>";
echo "<input type = 'button' value = ' COPY ' onclick=copyToClipboard('#e_req_msg') style='float:right; border:1px solid red;'>";
echo '<br><br><hr class=separator><br>';
echo "<div id='e_req_msg'>{$message}</div>";
?>