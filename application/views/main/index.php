<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f6f9;
            margin: 0;
            padding: 20px;
        }

        h1 {
            font-size: 24px;
            margin-bottom: 15px;
            color: #333;
        }

        /* Top bar */
        .filter-bar {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 20px;
        }

        .filter-bar input {
            padding: 8px;
            border-radius: 6px;
            border: 1px solid #ccc;
            font-size: 14px;
        }

        .filter-bar button {
            padding: 8px 15px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }

        .filter-bar button:hover {
            background-color: #45a049;
        }

        /* Cards */
        .cards-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
            margin-bottom: 25px;
        }

        .card {
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            text-align: center;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 120px;
            transition: transform 0.2s ease;
        }

        .card:hover {
            transform: translateY(-3px);
        }

        .card h2 {
            font-size: 32px;
            font-weight: bold;
            margin: 0;
            color: #333;
            line-height: 1.2;
        }

        .card p {
            font-size: 16px;
            color: #777;
            margin-top: 8px;
        }

        /* Table */
        #tbl_dashboard {
            width: 100%;
            border-collapse: collapse;
            background-color: white;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            border-radius: 10px;
            overflow: hidden;
        }

        #tbl_dashboard th,
        #tbl_dashboard td {
            padding: 12px 15px;
            text-align: center;
            font-size: 14px;
        }

        #tbl_dashboard th {
            background-color: #4CAF50;
            color: white;
            text-transform: uppercase;
        }

        #tbl_dashboard tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        #tbl_dashboard tr:hover {
            background-color: #f1f1f1;
        }

        /* Alerts */
        .alerts {
            margin-top: 25px;
            padding: 15px;
            background-color: #fff4e5;
            border: 1px solid #ffe1b3;
            border-radius: 8px;
            color: #b56d00;
            font-size: 14px;
        }

        .alerts a {
            color: #d35400;
            font-weight: bold;
            text-decoration: none;
        }
    </style>
</head>
<body>


<!-- Filter Bar -->
<div class="filter-bar">
    <form id='frm-search' action='<?=base_url('main')?>' method='POST'>
        <?=input('calldate','calldate','date','date',$calldate);?>
        <button type='submit' value='search' id='btn-search'> Search</button>
    </form>
</div>

<!-- Summary Cards -->
<div class="cards-container">
    <div class="card">
        <h2><?=$salesSummary['total_sales']?></h2>
        <p>Total Sales</p>
    </div>
    <div class="card">
        <h2><?=$salesSummary['verified_sales']?></h2>
        <p>Verified Sales</p>
    </div>
    <div class="card">
        <h2><?=$salesSummary['new_sales']?></h2>
        <p>Pending Verification</p>
    </div>

    <?php
        if($alerts){
            $totalAlert = 0;
            foreach($alerts as $type => $details){
                $totalAlert += $details['count'];
                echo "<div class='card'>
                        <h2>{$details['count']}</h2>
                        <p>".ucwords(strtolower($type))."</p>
                    </div>";
            }
        }
    ?>
</div>

<!-- Table -->
<table id="tbl_dashboard">
    <thead>
    <tr>
        <th>Campaign</th>
        <th>New Sales</th>
        <th>Verified Sales</th>
    </tr>
    </thead>
    <tbody>

    <?foreach($crms as $details){?>
        <tr>
            <td>
                <a href='<?=base_url('sales/list_sale/'.$details['id'].'/'.$calldate)?>'><?=$details['name']?></a>
            </td>
            <td><?=$details['new_sales']?></td>
            <td><?=$details['verified_sales']?></td>
        </tr>
    <?}?>
    </tbody>
</table>
<!-- Alerts -->
<?php
if($alerts){
    reset($alerts);
    $firstKey = key($alerts);
    $alertInfo = $alerts[$firstKey];

    echo '<div class="alerts">
               <strong>Alert/s:</strong> '.$totalAlert.' sale/s to review.
               <a href="'.base_url('sales/list_sale/'.$alertInfo['crm_id'].'/'.substr($alertInfo['calldate'],0,10)).'">Review Now</a>
          </div>';
}?>

<script>
    $(function(){
        var url = '<?=base_url('sales/list_sale')?>';
        init_search_fx(url);
    })

</script>
