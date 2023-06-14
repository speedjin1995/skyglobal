<?php
require_once "db_connect.php";

session_start();

if(!isset($_SESSION['userID'])){
    echo '<script type="text/javascript">';
    echo 'window.location.href = "../login.html";</script>';
}

if(isset($_POST['inputHandler'], $_POST['inputCustomerName'], $_POST['inputContactNum'], $_POST['inputEmail'], $_POST['inputShipmentType'], $_POST['totalPrice'],
$_POST['inputAddress'], $_POST['cargoReadyTime'], $_POST['inputPickupAddress'], $_POST['inputDimension'], $_POST['inputNumberofCarton'], $_POST['inputWeightofCarton'], 
$_POST['inputPickupCharge'], $_POST['inputExportClearances'], $_POST['inputAirTicket'], $_POST['inputFlyersFee'], $_POST['inputImportClearance'], 
$_POST['inputDeliveryCharges'], $_POST['inputTotalCharges'])){
    $inputHandler = filter_input(INPUT_POST, 'inputHandler', FILTER_SANITIZE_STRING);
    $inputCustomerName = filter_input(INPUT_POST, 'inputCustomerName', FILTER_SANITIZE_STRING);
    $inputContactNum = filter_input(INPUT_POST, 'inputContactNum', FILTER_SANITIZE_STRING);
    $inputEmail = filter_input(INPUT_POST, 'inputEmail', FILTER_SANITIZE_STRING);
    $inputShipmentType = filter_input(INPUT_POST, 'inputShipmentType', FILTER_SANITIZE_STRING);
    $totalPrice = filter_input(INPUT_POST, 'totalPrice', FILTER_SANITIZE_STRING);
    $inputAddress = filter_input(INPUT_POST, 'inputAddress', FILTER_SANITIZE_STRING);
    $inputNotesInternal = "";
    $inputNotestoCustomer = "";
    $user = $_SESSION['userID'];
    $deleted = array();

    if($_POST['inputNotesInternal'] != null && $_POST['inputNotesInternal'] != ""){
        $inputNotesInternal = filter_input(INPUT_POST, 'inputNotesInternal', FILTER_SANITIZE_STRING);
    }
    
    if($_POST['inputNotestoCustomer'] != null && $_POST['inputNotestoCustomer'] != ""){
        $inputNotestoCustomer = filter_input(INPUT_POST, 'inputNotestoCustomer', FILTER_SANITIZE_STRING);
    }
    
    // Arrays
    $cargoReadyTime=$_POST['cargoReadyTime'];
    $inputPickupAddress=$_POST['inputPickupAddress'];
    $inputDimension=$_POST['inputDimension'];
    $inputNumberofCarton=$_POST['inputNumberofCarton'];
    $inputWeightofCarton=$_POST['inputWeightofCarton'];
    $inputPickupCharge=$_POST['inputPickupCharge'];
    $inputExportClearances=$_POST['inputExportClearances'];
    $inputAirTicket=$_POST['inputAirTicket'];
    $inputFlyersFee=$_POST['inputFlyersFee'];
    $inputImportClearance=$_POST['inputImportClearance'];
    $inputDeliveryCharges=$_POST['inputDeliveryCharges'];
    $inputTotalPrice=$_POST['inputTotalCharges'];
    
    $success = true;
    $today = date("Y-m-d 00:00:00");

    if(isset($_POST['deleted']) && $_POST['deleted'] != null){
        $deleted = $_POST['deleted'];
        $deleted = array_map('intval', $deleted);
    }

    if(isset($_POST['id']) && $_POST['id'] != null && $_POST['id'] != ''){
        if ($update_stmt = $db->prepare("UPDATE weighing SET item_types=?, lot_no=?, tray_weight=?, tray_no=?, grading_net_weight=?, grade, pieces, grading_gross_weight, grading_net_weight, moisture_after_grading=? WHERE id=?")) {
            $update_stmt->bind_param('ssssssss', $itemType, $grossWeight, $lotNo, $bTrayWeight, $bTrayNo, $netWeight, $moistureValue, $_POST['id']);
            
            // Execute the prepared query.
            if (! $update_stmt->execute()) {
                echo json_encode(
                    array(
                        "status"=> "failed", 
                        "message"=> $update_stmt->error
                    )
                );
            }
            else{

                $action = "User : ".$name."Update Tray No : ".$bTrayNo." in grades table!";

                if ($log_insert_stmt = $db->prepare("INSERT INTO log (userId , userName, action) VALUES (?, ?, ?)")) {
                    $log_insert_stmt->bind_param('sss', $userID, $name, $action);
                

                    if (! $log_insert_stmt->execute()) {
                        echo json_encode(
                            array(
                                "status"=> "failed", 
                                "message"=> $log_insert_stmt->error 
                            )
                        );
                    }
                    else{
                        $log_insert_stmt->close();
                    }
                }

                $update_stmt->close();
                $db->close();
                
                echo json_encode(
                    array(
                        "status"=> "success", 
                        "message"=> "Updated Successfully!!" 
                    )
                );
            }
        }
    }
    else{
        if ($select_stmt = $db->prepare("SELECT COUNT(*) FROM sales WHERE created_datetime >= ?")) {
            $select_stmt->bind_param('s', $today);
            
            // Execute the prepared query.
            if (! $select_stmt->execute()) {
                echo json_encode(
                    array(
                        "status" => "failed",
                        "message" => "Failed to get latest count"
                    )); 
            }
            else{
                $result = $select_stmt->get_result();
                $count = 1;
                $firstChar = 'Q'.date("Ymd");
                
                if ($row = $result->fetch_assoc()) {
                    $count = (int)$row['COUNT(*)'] + 1;
                    $select_stmt->close();
                }

                $charSize = strlen(strval($count));

                for($i=0; $i<(4-(int)$charSize); $i++){
                    $firstChar.='0';  // S0000
                }
        
                $firstChar .= strval($count);  //S00009

                if ($insert_stmt = $db->prepare("INSERT INTO sales (quotation_no, customer_name, contact_no, email, customer_address, total_amount, customer_notes, internal_notes, shipment_type, created_by, updated_by, handled_by) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)")) {
                    $insert_stmt->bind_param('ssssssssssss', $firstChar, $inputCustomerName, $inputContactNum, $inputEmail, $inputAddress, $totalPrice, $inputNotestoCustomer, $inputNotesInternal, $inputShipmentType, $user, $user, $inputHandler);
                    
                    // Execute the prepared query.
                    if (! $insert_stmt->execute()) {
                        echo json_encode(
                            array(
                                "status"=> "failed", 
                                "message"=> "Failed to created purchase records due to ".$insert_stmt->error
                            )
                        );
                    }
                    else{
                        $id = $insert_stmt->insert_id;;
                        $insert_stmt->close();

                        for($i=0; $i<sizeof($cargoReadyTime); $i++){
                            if($cargoReadyTime[$i] != null && !in_array($i,$deleted)){
                                if ($insert_stmt2 = $db->prepare("INSERT INTO sales_cart (sale_id, dimension, number_of_carton, weight_of_cargo, cargo_ready_time, pickup_address, pickup_charge, export_clearances, air_ticket, flyers_fee, import_clearance, delivery_charges, total_amount) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)")) {
                                    $insert_stmt2->bind_param('sssssssssssss', $id, $inputDimension[$i], $inputNumberofCarton[$i], $inputWeightofCarton[$i], $cargoReadyTime[$i], $inputPickupAddress[$i], $inputPickupCharge[$i] , $inputExportClearances[$i], $inputAirTicket[$i], $inputFlyersFee[$i], $inputImportClearance[$i], $inputDeliveryCharges[$i], $inputTotalPrice[$i]);
                                    // Execute the prepared query.
                                    if (! $insert_stmt2->execute()) {
                                        $success = false;
                                    }
                                }
                            }
                        }

                        if($success){
                            $insert_stmt2->close();
                            $db->close();

                            echo json_encode(
                                array(
                                    "status"=> "success", 
                                    "message"=> "Added Successfully!!"
                                )
                            );
                        }
                        else{
                            $insert_stmt2->close();
                            $db->close();

                            echo json_encode(
                                array(
                                    "status"=> "failed", 
                                    "message"=> "Failed to created sales cart records due to ".$insert_stmt2->error 
                                )
                            );
                        }
                    }
                }
            }
        }
    }
}
else{
    echo json_encode(
        array(
            "status"=> "failed", 
            "message"=> "Please fill in all the fields"
        )
    );
}
?>