<?php

require_once "db_connect.php";

session_start();

if(!isset($_SESSION['userID'])){
    echo '<script type="text/javascript">';
    echo 'window.location.href = "../login.html";</script>';
}

if(isset($_POST['salesID'])){
    $id = filter_input(INPUT_POST, 'salesID', FILTER_SANITIZE_STRING);



    if ($select_stmt = $db->prepare("SELECT * FROM sales WHERE id=?")) {
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

				$message = "<html>
				             <div>
				              <div><img style='float: left;' width='60' height='40' src='images/logo.png'></div>
				              <div style='font-size: 14px; padding-left: 5px;'>
				                   <div style='text-align: center'>
								     <h1 style='display:inline'>SKY GLOBAL TIME CRITICAL SOLUTIONS SDN BHD</h1>
									</div>
									<div style='text-align: center'>10A, Jalan Todak 4, 13700 Seberang Jaya, Pulau Pinang</div>
				                    <div style='text-align: center'>Tel: +604 306 7700, Handphone: +6013 481 1633</div>
				                    <div style='text-align: center'>Email: kokhow_lean@skygls.com &nbsp;&nbsp;&nbsp;&nbsp; Website: www.skygls.com</div>
				              </div>
							 </div>

							<body>	
							<hr>
							<div stye='position: relative;'>";
				
				while ($row = $result->fetch_assoc()) {

					//To get handler name
					$handler="";
			    	$select_stmt2 = $db->prepare("SELECT * FROM users WHERE id=?");
					$select_stmt2->bind_param('i', $row['handled_by']);
					$select_stmt2->execute();
					$result2 = $select_stmt2->get_result();
					if ($result2!=null) {
						while ($row2 = $result2->fetch_assoc()) {
					   		$handler = $row2['name'];
				    	}
					}
					else{
						echo json_encode(
							array(
								"status" => "failed",
								"message" => "Failed to get handler"
							)
						);
					}

					$message.= "<div style='display: flex; justify-content: center;'>
					              <div style='padding-left:30%; font-size: 17px;'><b>QUOTATION</b></div>
	        					  <div style='padding-left:20%; font-size: 13px;  padding-top:4px;'>No. : ".$row['quotation_no']."</div>
								</div>
								<div style='padding-top:1%; display: flex; justify-content: center;'>
								    <div style='width: 50%;'>
									    <div style='font-size: 13px;' >Customer Name: ".$row['customer_name']."</div>
									</div>
									<table style='width: 50%; font-size: 13px;'>
									    <tr><td>Handler</td><td>:</td><td>".$handler."</td></tr>
									    <tr><td>Date</td><td>:</td><td>".$row['created_datetime']."</td></tr>
    									<tr><td>Email</td><td>:</td><td>".$row['email']."</td></tr>
    									<tr><td>Telephone</td><td>:</td><td>".$row['contact_no']."</td></tr>
    									<tr><td>Shipment Type</td><td>:</td><td>".$row['shipment_type']."</td></tr>
									</table>
								</div>";

					$message.= "<table style='padding-top:1%; width:100%'>
					              <tr><td style='border-top: 1px solid; border-bottom: 1px solid;'>Item</td><td style='border-top: 1px solid; border-bottom: 1px solid;'> Total Weight (KG)</td><td style='border-top: 1px solid; border-bottom: 1px solid;'>U/Price Disc (RM)</td></tr>";
								
					//To get sales cart
					$totalamount=0;
			    	$select_stmt3 = $db->prepare("SELECT * FROM sales_cart WHERE sale_id=?");
					$select_stmt3->bind_param('s', $id);
					$select_stmt3->execute();
					$result3 = $select_stmt3->get_result();
					if ($result3!=null) {
						$count=0;
						while ($row3 = $result3->fetch_assoc()) {
							$count++;
							$totalamount+= $row3['total_amount'];
							
							if((float)$row3['volumetric_weight'] > (float)$row3['total_cargo_weight']){
								$message.="<tr><td style='width: 10%;'>".$count."</td><td style='width: 70%;'>Total Volumetric Weight = ".$row3['volumetric_weight']." KG</td><td style='width: 20%; text-align: center;'>".$row3['total_amount']."</td></tr>";
							}
							else{
								$message.="<tr><td style='width: 10%;'>".$count."</td><td style='width: 70%;'>Total Carton Weight = ".$row3['total_cargo_weight']." KG</td><td style='width: 20%; text-align: center;'>".$row3['total_amount']."</td></tr>";
							}
				    	}
					}
					else{
						echo json_encode(
							array(
								"status" => "failed",
								"message" => "Failed to get sales cart"
							)
						);
					}

					$totalamount= number_format($totalamount, 2, '.', '');
					$message.="</table>
							   <div style='position: absolute; bottom: 420; width: 100%;'>
					             <hr style='height: 0px; border: none; border-top: 1px solid black;' />
					             <div style='position:absolute; right:50;'><div style=''>Total: ".$totalamount."</div>
							   </div>";
				}
				$message.="</div>
							<div style='position: absolute; '>
							   <div style='padding-top:50%;'><h3 style='display:inline; font-size: 14px;'>TERMS AND CONDITIONS</h3><br>
							   <div style='font-size: 13px;'>By accepting this transaction products, it has confirmed that you have understood and accepted the following terms and conditions -
							   <br><br>
							   1.	Once the road-tax is printed, it is not refundable.
			   				   <br>
							   2.	In accordance with Insurance Act 1996 (Part XII, Section 141 Assumption of Risks, Subsection 4) all refund premiums shall pay directly to the policyholder.
						       <br>
							   3.	 If you intend to cancel the Credit/Debit Card payment after the settlement of transaction, 1.5% charge will be imposed on the refunded amount accordingly.
							   <br><br>
							   Thank you
            				</div>";
				$message.="<div style='text-align: center; padding-top:5%;'>This is a computer generated document. No signature is required.</div>";
				

				$message .= "</body></html>";

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