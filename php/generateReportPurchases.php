<?php

require_once "db_connect.php";

session_start();

if(!isset($_SESSION['userID'])){
    echo '<script type="text/javascript">';
    echo 'window.location.href = "../login.html";</script>';
}

if(isset($_POST['purchasesID'])){
    $id = filter_input(INPUT_POST, 'purchasesID', FILTER_SANITIZE_STRING);



    if ($select_stmt = $db->prepare("SELECT * FROM purchases WHERE id=?")) {
        $select_stmt->bind_param('s', $id);

        // Execute the prepared query.
        if (! $select_stmt->execute()) {
            echo json_encode(
                array(
                    "status" => "failed",
                    "message" => "Something went wrong on the query"
                )); 
        }
        else{
            $result = $select_stmt->get_result();
                
            if ($result!=null) {
				$message = "<style> table, th, td { border: 1px solid black; }
				@media print {
					@page {
						margin-left: 0.5in;
						margin-right: 0.5in;
						margin-top: 0.1in;
						margin-bottom: 0.1in;
					}
					
				} 
						
				table {
					width: 100%;
					border-collapse: collapse;
					
				} 
				
				.table th, .table td {
					padding: 0.70rem;
					vertical-align: top;
					border-top: 1px solid #dee2e6;
					
				} 
				
				.table-bordered {
					border: 1px solid #000000;
					
				} 
				
				.table-bordered th, .table-bordered td {
					border: 1px solid #000000;
					font-family: sans-serif;
					font-size: 12px;
					
				} 
				
				.row {
					display: flex;
					flex-wrap: wrap;
					margin-top: 20px;
					margin-right: -15px;
					margin-left: -15px;
					
				} 
				
				.col-md-4{
					position: relative;
					width: 33.333333%;
				}
				
				.center {
					display: block;
					margin-left: auto;
					margin-right: auto;
				}
			   </style>";

			   $items; 
			   $createdDateTime; 
			   $voucher_no;
			   $total;

			   while ($row = $result->fetch_assoc()) {
				$createdDateTime= date("d/m/Y", strtotime($row['created_datetime']));
				$items= json_decode($row['items'],true);
				$voucher_no= $row['jobNo'];
				$total=floatval($row['total']);
			   }

			   $count=0;
		       $page=sizeof($items)/6;


			   for($a=0 ; $a<=(int)$page ; $a++){
				$message.="<h1>SKY GLOBAL TIME CRITICAL SOLUTIONS SDN BHD</h1>
				<h2>PURCHASE</h2>
				<div style='width: 100%;'>
				<div style='display: inline-block;'>PAY TO: ______________________</div>
				<div style='padding-left:5%; display: inline-block;'>DATE: ".$createdDateTime."</div>
				<div style='padding-top:3%;'>
					<div style='float: left; width: 60%;'>
						<table class='table-bordered' style='width:100%'>
							<thead>
								<tr>
									<th style='width: 70%;'>PARTICULAR</th>
									<th>BILL NO.</th>
									<th>USD</th>
									<th>CTS</th>
								</tr>
							</thead>
							<tbody>";
							
							$totalStringfront="";
							$totalStringback="00";
							$locationofDotTotal;
	
							for($c=$count; $c<sizeof($items);){
								
								$front="";
								$back="00";
								$locationofDot = strpos($items[$c]['itemPrice'],".");
	
								if($locationofDot!=null){
									$front= substr(sprintf("%.2f", $items[$c]['itemPrice']), 0, $locationofDot);
									$back= substr(sprintf("%.2f", $items[$c]['itemPrice']), $locationofDot+1);
								} 
								else{
									$front= $items[$c]['itemPrice'];
								}
								
								$message.="<tr>
								<td>".$items[$c]['itemName']."</td>
								<td>-</td>
								<td>".$front."</td>
								<td>".$back."</td>
							    </tr>";
	
							 $c++;
							 $remain= $c % 6;
							 if($remain==0){
								$count = $c;
								break;
							 }
							}
	
							$locationofDotTotal = strpos(sprintf("%.2f", $total),".");
	
							$totalStringfront= substr(sprintf("%.2f", $total), 0, $locationofDotTotal);
							$totalStringback= substr(sprintf("%.2f", $total), $locationofDotTotal+1);
	
					$message.="<tr>
									<td>PURCHASE NO: </td>
									<td>".$voucher_no."</td>
									<td></td>
									<td></td>
								</tr>
								<tr>
									<td style='visibility: hidden'></td>
									<td>Total USD: </td>
									<td>".$totalStringfront."</td>
									<td>".$totalStringback."</td>
								</tr>
							</tbody>
						</table>
						<div style='padding-top:3%;'>THE SUM OF DOLLARS:___________________________________________</div>
						<div style='padding-top:5%'>
							<div style='display: inline-block;'>
								<div>______________</div>
								<div>APPROVED BY</div>
							</div>
							<div style='padding-left:45%; display: inline-block;'>
								<div>_____________</div>
								<div>RECEIVED BY</div>
							</div>
						</div>
					</div>
					<div style='float: left; padding-left:5%;'>
						<div>BALANCE PETTY CASH</div>
						<table style='width:100%'>
							<tbody>
								<tr>
									<td style='width:40%'>100 USD</td>
									<td></td>
								</tr>
								<tr>
									<td>50 USD</td>
									<td></td>
		
								</tr>
								<tr>
									<td>20 USD</td>
									<td></td>
		
								</tr>
								<tr>
									<td>10 USD</td>
									<td></td>
		
								</tr>
								<tr>
									<td>5 USD</td>
									<td></td>
		
								</tr>
								<tr>
									<td>1 USD</td>
									<td></td>
								</tr>
								<tr>
									<td>COIN</td>
									<td></td>
								</tr>
								<tr>
									<td>TOTAL</td>
									<td></td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
				</div>";
	
				if((int)$page!=0 && $a<(int)$page){
					$message.='
					<p style="page-break-after: always;">&nbsp;</p>
					<p style="page-break-before: always;">&nbsp;</p>';
				}
			   }
				
                echo json_encode(
                    array(
                        "status" => "success",
                        "message" => $message
                    )
                );
            }
            else{
                echo json_encode(
                    array(
                        "status" => "failed",
                        "message" => 'Unable to read data'
                    )
                );
            }
            
            
        }
    }
    else{
        echo json_encode(
            array(
                "status" => "failed",
                "message" => "Something Goes Wrong on database reading"
            ));
    }
}
else{
    echo json_encode(
        array(
            "status"=> "failed", 
            "message"=> "Id not detected"
        )
    ); 
}

?>