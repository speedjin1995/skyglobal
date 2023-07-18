<?php
require_once "db_connect.php";

session_start();

if(!isset($_SESSION['userID'])){
    echo '<script type="text/javascript">';
    echo 'window.location.href = "../login.html";</script>';
}

if(isset($_POST['inputHandler'], $_POST['inputCustomerName'], $_POST['inputContactNum'], $_POST['inputEmail'], $_POST['inputShipmentType'], $_POST['inputPickupAddress'],
$_POST['inputPickupContactNum'], $_POST['inputPickupEmail'], $_POST['inputDeliveryAddress'], $_POST['inputDeliveryName'], $_POST['inputDeliveryContactNum'], $_POST['inputDeliveryEmail'], 
$_POST['inputDimensionW'], $_POST['inputDimensionL'], $_POST['inputDimensionH'], $_POST['inputUnit'], $_POST['inputVolumetricWeight'],
$_POST['inputNumberofCarton'], $_POST['inputCartonPiecesWeight'], $_POST['inputTotalCartonWeight'], $_POST['cargoReadyTime'], $_POST['inputPickupName'], 
$_POST['inputTotalCharges'])){
    $inputHandler = filter_input(INPUT_POST, 'inputHandler', FILTER_SANITIZE_STRING);
    $inputCustomerName = filter_input(INPUT_POST, 'inputCustomerName', FILTER_SANITIZE_STRING);
    $inputContactNum = filter_input(INPUT_POST, 'inputContactNum', FILTER_SANITIZE_STRING);
    $inputEmail = filter_input(INPUT_POST, 'inputEmail', FILTER_SANITIZE_STRING);
    $inputShipmentType = filter_input(INPUT_POST, 'inputShipmentType', FILTER_SANITIZE_STRING);
    $inputPickupAddress = filter_input(INPUT_POST, 'inputPickupAddress', FILTER_SANITIZE_STRING);
    $inputPickupName = filter_input(INPUT_POST, 'inputPickupName', FILTER_SANITIZE_STRING);
    $inputPickupContactNum = filter_input(INPUT_POST, 'inputPickupContactNum', FILTER_SANITIZE_STRING);
    $inputPickupEmail = filter_input(INPUT_POST, 'inputPickupEmail', FILTER_SANITIZE_STRING);
    $inputDeliveryAddress = filter_input(INPUT_POST, 'inputDeliveryAddress', FILTER_SANITIZE_STRING);
    $inputDeliveryName = filter_input(INPUT_POST, 'inputDeliveryName', FILTER_SANITIZE_STRING);
    $inputDeliveryContactNum = filter_input(INPUT_POST, 'inputDeliveryContactNum', FILTER_SANITIZE_STRING);
    $inputDeliveryEmail = filter_input(INPUT_POST, 'inputDeliveryEmail', FILTER_SANITIZE_STRING);
    $cargoReadyTime = filter_input(INPUT_POST, 'cargoReadyTime', FILTER_SANITIZE_STRING);
    $inputDepAirport = null;
    $inputDesAirport = null;
    $inputUnit = filter_input(INPUT_POST, 'inputUnit', FILTER_SANITIZE_STRING);
    $inputVolumetricWeight = filter_input(INPUT_POST, 'inputVolumetricWeight', FILTER_SANITIZE_STRING);
    $inputNumberofCarton = filter_input(INPUT_POST, 'inputNumberofCarton', FILTER_SANITIZE_STRING);
    $inputTotalCartonWeight = filter_input(INPUT_POST, 'inputTotalCartonWeight', FILTER_SANITIZE_STRING);
    $inputTotalCharges = filter_input(INPUT_POST, 'inputTotalCharges', FILTER_SANITIZE_STRING);
    $inputNotesInternal = "";
    $inputNotestoCustomer = "";
    $checkboxPickup = "NO";
    $checkboxExport = "NO";
    $checkboxAir = "NO";
    $checkboxFlyers = "NO";
    $checkboxImport = "NO";
    $checkboxDelivery = "NO";
    $user = $_SESSION['userID'];
    $deleted = array();
    $deletedShip = array();
    $message = array();
    $message2 = array();

    if(isset($_POST['inputDepAirport']) && $_POST['inputDepAirport'] != null && $_POST['inputDepAirport'] != ""){
        $inputDepAirport = filter_input(INPUT_POST, 'inputDepAirport', FILTER_SANITIZE_STRING);
    }

    if(isset($_POST['inputDesAirport']) && $_POST['inputDesAirport'] != null && $_POST['inputDesAirport'] != ""){
        $inputDesAirport = filter_input(INPUT_POST, 'inputDesAirport', FILTER_SANITIZE_STRING);
    }

    if($_POST['inputNotesInternal'] != null && $_POST['inputNotesInternal'] != ""){
        $inputNotesInternal = filter_input(INPUT_POST, 'inputNotesInternal', FILTER_SANITIZE_STRING);
    }
    
    if($_POST['inputNotestoCustomer'] != null && $_POST['inputNotestoCustomer'] != ""){
        $inputNotestoCustomer = filter_input(INPUT_POST, 'inputNotestoCustomer', FILTER_SANITIZE_STRING);
    }

    if(isset($_POST['checkboxPickup']) && $_POST['checkboxPickup'] == "on"){
        $checkboxPickup = "YES";
    }

    if(isset($_POST['checkboxExport']) && $_POST['checkboxExport'] == "on"){
        $checkboxExport = "YES";
    }

    if(isset($_POST['checkboxAir']) && $_POST['checkboxAir'] == "on"){
        $checkboxAir = "YES";
    }

    if(isset($_POST['checkboxFlyers']) && $_POST['checkboxFlyers'] == "on"){
        $checkboxFlyers = "YES";
    }

    if(isset($_POST['checkboxImport'])&& $_POST['checkboxImport'] == "on"){
        $checkboxImport = "YES";
    }

    if(isset($_POST['checkboxDelivery']) && $_POST['checkboxDelivery'] == "on"){
        $checkboxDelivery = "YES";
    }

    if(isset($_POST['inputPickupCharge']) && $_POST['inputPickupCharge'] != null && $_POST['inputPickupCharge'] != ""){
        $inputPickupCharge = filter_input(INPUT_POST, 'inputPickupCharge', FILTER_SANITIZE_STRING);
    }

    if(isset($_POST['inputExportClearances']) && $_POST['inputExportClearances'] != null && $_POST['inputExportClearances'] != ""){
        $inputExportClearances = filter_input(INPUT_POST, 'inputExportClearances', FILTER_SANITIZE_STRING);
    }

    if(isset($_POST['inputAirTicket']) && $_POST['inputAirTicket'] != null && $_POST['inputAirTicket'] != ""){
        $inputAirTicket = filter_input(INPUT_POST, 'inputAirTicket', FILTER_SANITIZE_STRING);
    }

    if(isset($_POST['inputFlyersFee']) && $_POST['inputFlyersFee'] != null && $_POST['inputFlyersFee'] != ""){
        $inputFlyersFee = filter_input(INPUT_POST, 'inputFlyersFee', FILTER_SANITIZE_STRING);
    }

    if(isset($_POST['inputImportClearance']) && $_POST['inputImportClearance'] != null && $_POST['inputImportClearance'] != ""){
        $inputExportClearances = filter_input(INPUT_POST, 'inputImportClearance', FILTER_SANITIZE_STRING);
    }

    if(isset($_POST['inputDeliveryCharges']) && $_POST['inputDeliveryCharges'] != null && $_POST['inputDeliveryCharges'] != ""){
        $inputDeliveryCharges = filter_input(INPUT_POST, 'inputDeliveryCharges', FILTER_SANITIZE_STRING);
    }

    // Arrays
    $inputDimensionW = $_POST['inputDimensionW'];
    $inputDimensionL = $_POST['inputDimensionL'];
    $inputDimensionH = $_POST['inputDimensionH'];
    $inputCartonPiecesWeight = $_POST['inputCartonPiecesWeight'];
    
    $success = true;
    $today = date("Y-m-d 00:00:00");

    if(isset($_POST['deleted']) && $_POST['deleted'] != null){
        $deleted = $_POST['deleted'];
        $deleted = array_map('intval', $deleted);
    }

    if(isset($_POST['deletedShip']) && $_POST['deletedShip'] != null){
        $deletedShip = $_POST['deletedShip'];
        $deletedShip = array_map('intval', $deletedShip);
    }

    if(isset($_POST['route']) && $_POST['route']){
        $route = $_POST['route'];
        $departure = $_POST['departure']; 
        $depatureTime = $_POST['depatureTime'];
        $arrival = $_POST['arrival'];
        $arrivalTime = $_POST['arrivalTime'];

        for($i=0; $i<count($route); $i++){
            if(!in_array($i, $deleted)){
                $message[] = array( 
                    'route' => $route[$i],
                    'departure' => $departure[$i],
                    'depatureTime' => $depatureTime[$i],
                    'arrival' => $arrival[$i],
                    'arrivalTime' => $arrivalTime[$i]
                );

                if($i == 0){
                    if(!isset($_POST['inputDepAirport']) || $_POST['inputDepAirport'] == null || $_POST['inputDepAirport'] == ""){
                        $inputDepAirport = $departure[$i];
                    }
                }

                if($i == (count($route) - 1)){
                    if(!isset($_POST['inputDesAirport']) || $_POST['inputDesAirport'] != null || $_POST['inputDesAirport'] != ""){
                        $inputDesAirport = $arrival[$i];
                    }
                }
            }
        }
    }
    

    for($i=0; $i<count($inputDimensionW); $i++){
        if(!in_array($i, $deletedShip)){
            $message2[] = array( 
                'W' => $inputDimensionW[$i],
                'L' => $inputDimensionL[$i],
                'H' => $inputDimensionH[$i],
                'Weight' => $inputCartonPiecesWeight[$i],
                'Unit' => $inputUnit
            );
        }
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
        if ($select_stmt = $db->prepare("SELECT COUNT(*) FROM sales WHERE sales_no IS NULL AND created_datetime >= ?")) {
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

                if ($insert_stmt = $db->prepare("INSERT INTO sales (quotation_no, customer_name, contact_no, email, total_amount, customer_notes, internal_notes, shipment_type, created_by, updated_by, handled_by) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)")) {
                    $insert_stmt->bind_param('sssssssssss', $firstChar, $inputCustomerName, $inputContactNum, $inputEmail, $inputTotalCharges, $inputNotestoCustomer, $inputNotesInternal, $inputShipmentType, $user, $user, $inputHandler);
                    
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
                        $message = json_encode($message);
                        $message2 = json_encode($message2);

                        if ($insert_stmt2 = $db->prepare("INSERT INTO sales_cart (sale_id, departure_airport, destination_airport, weight_data, volumetric_weight, number_of_carton, total_cargo_weight, cargo_ready_time, pickup_address, pickup_pic, pickup_contact, pickup_email, delivery_address, delivery_pic, delivery_contact, delivery_email, route, pickup_charge, export_clearances, air_ticket, flyers_fee, import_clearance, delivery_charges, total_amount, pickup_charge_chk, export_clearances_chk, air_ticket_chk, flyers_fee_chk, import_clearance_chk, delivery_charges_chk) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)")) {
                            $insert_stmt2->bind_param('ssssssssssssssssssssssssssssss', $id, $inputDepAirport, $inputDesAirport, $message2, $inputVolumetricWeight, $inputNumberofCarton, $inputTotalCartonWeight, $cargoReadyTime, $inputPickupAddress, $inputPickupName, $inputPickupContactNum, $inputPickupEmail, $inputDeliveryAddress, $inputDeliveryName, $inputDeliveryContactNum, $inputDeliveryEmail, $message, $inputPickupCharge, $inputExportClearances, $inputAirTicket, $inputFlyersFee, $inputImportClearance, $inputDeliveryCharges, $inputTotalCharges, $checkboxPickup, $checkboxExport, $checkboxAir, $checkboxFlyers, $checkboxImport, $checkboxDelivery);
                            // Execute the prepared query.
                            if (! $insert_stmt2->execute()) {
                                echo json_encode(
                                    array(
                                        "status"=> "failed", 
                                        "message"=> "Failed to created sales cart records due to ".$insert_stmt2->error 
                                    )
                                );
                            }
                            else{
                                $insert_stmt2->close();
                                $db->close();
    
                                echo json_encode(
                                    array(
                                        "status"=> "success", 
                                        "message"=> "Added Successfully!!"
                                    )
                                );
                            }
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