<?php
    echo "<table border='1' style='margin-top:5px'>";
    echo "<tr class='tr_header'><td>Agent</td>";

    foreach ($columnArr as $date) {
        $dateView = $date;

        if($type == 'daily'){
            $dateView = date('M-d', strtotime($date));
        }elseif($type == 'monthly'){
            $dateView = date('M', strtotime($date.'-01'));
        }

        echo "<td>{$dateView}</td>";
    }
    echo "</tr>";

    foreach ($dataList as $agent => $dateData) {
        $agentName = isset($agentList[$agent]) ? $agentList[$agent] : $agent;
        echo "<tr class='tr-list'><td>{$agentName}</td>";
        foreach ($columnArr as $date) {
            $value = isset($dateData[$date]) ? $dateData[$date] : 0;
            echo "<td>{$value}</td>";
        }
        echo "</tr>";
    }

    echo "</table>";
?>


<script>
    $(function(){
        hover_out_tr_fx('tr-list');
    })
</script>