<?php
require_once "db_connect.php";

session_start();

if(!isset($_SESSION['userID'])){
    echo '<script type="text/javascript">';
    echo 'window.location.href = "../login.html";</script>';
}

if(isset($_POST['inputHandler'], $_POST['inputCustomerName'], $_POST['inputContactNum'], $_POST['inputEmail'], $_POST['inputShipmentType'],
$_POST['inputAddress'], $_POST['cargoReadyTime'], $_POST['inputPickupAddress'], $_POST['inputDimension'], $_POST['inputNumberofCarton'], $_POST['inputWeightofCarton'], 
$_POST['inputPickupCharge'], $_POST['inputExportClearances'], $_POST['inputAirTicket'], $_POST['inputFlyersFee'], $_POST['inputImportClearance'], 
$_POST['inputDeliveryCharges'], $_POST['inputTotalCharges'])){
    $inputHandler = filter_input(INPUT_POST, 'inputHandler', FILTER_SANITIZE_STRING);
    $inputCustomerName = filter_input(INPUT_POST, 'inputCustomerName', FILTER_SANITIZE_STRING);
    $inputContactNum = filter_input(INPUT_POST, 'inputContactNum', FILTER_SANITIZE_STRING);
    $inputEmail = filter_input(INPUT_POST, 'inputEmail', FILTER_SANITIZE_STRING);
    $inputShipmentType = filter_input(INPUT_POST, 'inputShipmentType', FILTER_SANITIZE_STRING);
    $inputAddress = filter_input(INPUT_POST, 'inputAddress', FILTER_SANITIZE_STRING);
    $cargoReadyTime = filter_input(INPUT_POST, 'cargoReadyTime', FILTER_SANITIZE_STRING);
    $inputPickupAddress = filter_input(INPUT_POST, 'inputPickupAddress', FILTER_SANITIZE_STRING);
    $inputDimension = filter_input(INPUT_POST, 'inputDimension', FILTER_SANITIZE_STRING);
    $inputNumberofCarton = filter_input(INPUT_POST, 'inputNumberofCarton', FILTER_SANITIZE_STRING);
    $inputWeightofCarton = filter_input(INPUT_POST, 'inputWeightofCarton', FILTER_SANITIZE_STRING);
    $inputPickupCharge = filter_input(INPUT_POST, 'inputPickupCharge', FILTER_SANITIZE_STRING);
    $inputExportClearances = filter_input(INPUT_POST, 'inputExportClearances', FILTER_SANITIZE_STRING);
    $inputAirTicket = filter_input(INPUT_POST, 'inputAirTicket', FILTER_SANITIZE_STRING);
    $inputFlyersFee = filter_input(INPUT_POST, 'inputFlyersFee', FILTER_SANITIZE_STRING);
    $inputImportClearance = filter_input(INPUT_POST, 'inputImportClearance', FILTER_SANITIZE_STRING);
    $inputDeliveryCharges = filter_input(INPUT_POST, 'inputDeliveryCharges', FILTER_SANITIZE_STRING);
    $inputTotalCharges = filter_input(INPUT_POST, 'inputTotalCharges', FILTER_SANITIZE_STRING);

    $totalPrice = $inputTotalCharges;
    $inputNotesInternal = "";
    $inputNotestoCustomer = "";
    $user = $_SESSION['userID'];
    $paid_datetime = date("Y-m-d H:i:s");
    $today = date("Y-m-d 00:00:00");

    if($_POST['inputNotesInternal'] != null && $_POST['inputNotesInternal'] != ""){
        $inputNotesInternal = filter_input(INPUT_POST, 'inputNotesInternal', FILTER_SANITIZE_STRING);
    }
    
    if($_POST['inputNotestoCustomer'] != null && $_POST['inputNotestoCustomer'] != ""){
        $inputNotestoCustomer = filter_input(INPUT_POST, 'inputNotestoCustomer', FILTER_SANITIZE_STRING);
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

                  
                $name = $_SESSION['name'];
                $userId = $_SESSION['userID'];
                $today = date("Y-m-d H:i:s");
    
    
                $action = "User : ".$name." Modify weighing with Id : ".$_POST['id']."!";
    
                    if ($log_insert_stmt = $db->prepare("INSERT INTO log (userId, userName, created_dateTime, action) VALUES (?,?,?,?)")) {
                        $log_insert_stmt->bind_param('ssss', $userId, $name, $today, $action);
                    
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
        if ($select_stmt = $db->prepare("SELECT COUNT(*) FROM sales WHERE sales_no IS NOT NULL AND created_datetime >= ?")) {
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
                $firstChar = 'S'.date("Ymd");
                
                if ($row = $result->fetch_assoc()) {
                    $count = (int)$row['COUNT(*)'] + 1;
                    $select_stmt->close();
                }

                $charSize = strlen(strval($count));

                for($i=0; $i<(4-(int)$charSize); $i++){
                    $firstChar.='0';  // S0000
                }
        
                $firstChar .= strval($count);  //S00009

                if ($insert_stmt = $db->prepare("INSERT INTO sales (sales_no, customer_name, contact_no, email, customer_address, total_amount, customer_notes, internal_notes, shipment_type, created_by, updated_by, handled_by, paid_datetime) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)")) {
                    $insert_stmt->bind_param('sssssssssssss', $firstChar, $inputCustomerName, $inputContactNum, $inputEmail, $inputAddress, $totalPrice, $inputNotestoCustomer, $inputNotesInternal, $inputShipmentType, $user, $user, $inputHandler, $paid_datetime);
                    
                    // Execute the prepared query.
                    if (! $insert_stmt->execute()) {
                        echo json_encode(
                            array(
                                "status"=> "failed", 
                                "message"=> "Failed to created sales records due to ".$insert_stmt->error
                            )
                        );
                    }
                    else{
                        $id = $insert_stmt->insert_id;;
                        $insert_stmt->close();

                        if ($insert_stmt2 = $db->prepare("INSERT INTO sales_cart (sale_id, dimension, number_of_carton, weight_of_cargo, cargo_ready_time, pickup_address, pickup_charge, export_clearances, air_ticket, flyers_fee, import_clearance, delivery_charges, total_amount) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)")) {
                            $insert_stmt2->bind_param('sssssssssssss', $id, $inputDimension, $inputNumberofCarton, $inputWeightofCarton, $cargoReadyTime, $inputPickupAddress, $inputPickupCharge, $inputExportClearances, $inputAirTicket, $inputFlyersFee, $inputImportClearance, $inputDeliveryCharges, $inputTotalCharges);
                            // Execute the prepared query.
                            if (! $insert_stmt2->execute()) {
                                $insert_stmt2->close();
                                $db->close();

                                echo json_encode(
                                    array(
                                        "status"=> "failed", 
                                        "message"=> "Failed to created sales cart records due to ".$insert_stmt2->error 
                                    )
                                );
                            }
                            else{
                                $sales_cart_id = $insert_stmt2->insert_id;;
                                $insert_stmt2->close();

                                if ($insert_stmt3 = $db->prepare("INSERT INTO job (sales_cart_id, created_by) VALUES (?, ?)")) {
                                    $insert_stmt3->bind_param('ss', $sales_cart_id, $user);
                                    // Execute the prepared query.
                                    if (! $insert_stmt3->execute()) {
                                        $insert_stmt3->close();
                                        $db->close();

                                        echo json_encode(
                                            array(
                                                "status"=> "failed", 
                                                "message"=> "Failed to created job due to ".$insert_stmt3->error 
                                            )
                                        );
                                    }
                                    else{
                                        $insert_stmt3->close();
  
                                        $name = $_SESSION['name'];
                                        $userId = $_SESSION['userID'];
                                        $today = date("Y-m-d H:i:s");
                            
                            
                                        $action = "User : ".$name." Add job with Id : ".$sales_cart_id."!";
                            
                                            if ($log_insert_stmt = $db->prepare("INSERT INTO log (userId, userName, created_dateTime, action) VALUES (?,?,?,?)")) {
                                                $log_insert_stmt->bind_param('ssss', $userId, $name, $today, $action);
                                            
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

                                        $db->close();

                                        echo json_encode(
                                            array(
                                                "status"=> "success", 
                                                "message"=> "Added Successfully!!"
                                            )
                                        );
                                    }
                                }
                                else{
                                    echo json_encode(
                                        array(
                                            "status"=> "failed", 
                                            "message"=> "Failed to created job"
                                        )
                                    );
                                }
                            }
                        }
                        else{
                            echo json_encode(
                                array(
                                    "status"=> "failed", 
                                    "message"=> "Failed to created sales cart records"
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