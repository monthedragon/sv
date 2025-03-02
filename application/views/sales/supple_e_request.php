<?php
$message = '';
foreach($sales as $relatedTableId => $baseRecIdArr){

    if($relatedTableId  == 0){
        continue; //this is the main table
    }

    foreach($baseRecIdArr as $baseRecid => $suppleDetails){
        $detail_arr = globalGetValues($suppleDetails);
        $message .= $detail_arr['lastname'] . "<br>";
        $message .= $detail_arr['firstname'] . "<br>";
        $message .= $detail_arr['middlename'] . "<br>";
        $message .= $detail_arr['embossed_name'] . "<br>";
        $message .= getLookUpVal($lookup['relationship'], $detail_arr['relationship']) . "<br>";
        $message .= date("m/d/Y", strtotime($detail_arr['dob'])) . "<br>"; // DOB (M/D/Y)
        $message .= getLookUpVal($lookup['gender'], $detail_arr['gender']) . "<br>";
        $message .= (!empty($detail_arr['civil_status']) ? ucfirst($detail_arr['civil_status']) : "-") . "<br>";
        $message .= (!empty($detail_arr['email_add']) ? $detail_arr['email_add'] : "-") . "<br>";
        $message .= (!empty($detail_arr['mobile_no']) ? $detail_arr['mobile_no'] : "-") . "<br>";
        $message .= number_format($detail_arr['assigned_spend_limit'], 2) . "<br>"; // Spend limit with 2 decimal places

        $message .= getLookUpVal($lookup['employment_info'], $detail_arr['employment']). "<br>";
        $message .= (!empty($detail_arr['present_add']) ? $detail_arr['present_add'] : "-") . "<br>";
        $message .= $detail_arr['place_of_birth'] . "<br>";
        $message .= $detail_arr['nationality'] . "<br>";
        $message .= "LEXTSI" . "<br>";
        $message .= "TELEPRIME SOLUTIONS" . "<br>";
        $message .=  getLookUpVal($lookup['submitted_id'], $detail_arr['submitted_id']). "<br>";
        $message .= "<hr class='record-separator'>";
    }
}


echo "<span style='float:left;cursor:pointer;' onclick=closeModal() > PRESS `ESC` to EXIT</span>";
echo "<input type = 'button' value = ' COPY ' onclick=copyToClipboard('#e_req_msg') style='float:right; border:1px solid red;'>";
echo '<br><br><hr class=separator><br>';
echo "<div id='e_req_msg'>{$message}</div>";
?>