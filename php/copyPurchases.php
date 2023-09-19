<?php
require_once "db_connect.php";

session_start();

if(isset($_POST['salesID'])){
	$id = filter_input(INPUT_POST, 'salesID', FILTER_SANITIZE_STRING);

    if ($get_stmt = $db->prepare("SELECT * FROM sales WHERE id = ?"))
    {
        $get_stmt->bind_param('s', $id);
        
        // Execute the prepared query.
        if (! $get_stmt->execute()) {
            echo json_encode(
                array(
                    "status" => "failed",
                    "message" => "Id not available"
                )); 
        }
        else{
            $result = $get_stmt->get_result();
            $message = array();

            $salesCartId=0;
            if ($row = $result->fetch_assoc()) {
                $shipmentType = $row['shipment_type'];
                $totalAmount = $row['total_amount'];
                $customerNotes = $row['customer_notes'];  
            }
            else{
                echo json_encode(
                    array(
                        "status" => "failed",
                        "message" =>  "Id not available"
                    ));  

            }
        }
    }

    if ($get_stmt = $db->prepare("SELECT * FROM sales_cart WHERE sale_id = ?"))
    {
        $get_stmt->bind_param('s', $id);
        
        // Execute the prepared query.
        if (! $get_stmt->execute()) {
            echo json_encode(
                array(
                    "status" => "failed",
                    "message" => "Id not available"
                )); 
        }
        else{
            $result = $get_stmt->get_result();
            $message = array();

            if ($row = $result->fetch_assoc()) {
                $deliveryAddress = $row['delivery_address'];
                $pickUpAddress = $row['pickup_address'];
                $cargoReadyTime = $row['cargo_ready_time'];

                $route = json_decode($row['route'], true);
                    
                
                $routeInfo="";

                for($i=0; $i<count($route); $i++){
                    $date = date_create_from_format('Y-m-d H:i:s', $route[$i]['depatureTime']);
                    $adate = date_create_from_format('Y-m-d H:i:s', $route[$i]['arrivalTime']);

                    $routeInfo .= $date->format('d')." ".$date->format('M').": ".$route[$i]['flightNo']." ".$route[$i]['departure']
                    ."-".$route[$i]['arrival']." ".$date->format('H').$date->format('i')."-".$adate->format('H').$adate->format('i')."\n";
                }


                echo json_encode(
                    array(
                        "status" => "success",
                        "deliveryAddress" => $deliveryAddress,
                        "pickUpAddress" => $pickUpAddress,
                        "cargoReadyTime" => $cargoReadyTime,
                        "shipmentType" => $shipmentType,
                        "totalAmount" => $totalAmount,
                        "customerNotes" => $customerNotes,
                        "routeInfo" => $routeInfo,
                    ));   
            }
            else{
                echo json_encode(
                    array(
                        "status" => "failed",
                        "message" =>  "Id not available"
                    ));  

            }
        }
    }
}
else{
    echo json_encode(
        array(
            "status" => "failed",
            "message" => "Id not available"
            )); 
}
?>