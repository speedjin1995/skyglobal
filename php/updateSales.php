<?php
require_once "db_connect.php";

session_start();

if(!isset($_SESSION['userID'])){
    echo '<script type="text/javascript">';
    echo 'window.location.href = "../login.html";</script>';
}

if(isset($_POST['inputHandler'], $_POST['inputCustomerName'], $_POST['inputContactNum'], $_POST['inputEmail'], $_POST['inputShipmentType'], $_POST['inputPickupAddress'],
$_POST['inputPickupContactNum'], $_POST['inputPickupEmail'], $_POST['inputDeliveryAddress'], $_POST['inputDeliveryName'], $_POST['inputDeliveryContactNum'], $_POST['inputDeliveryEmail'], 
$_POST['inputDimensionW'], $_POST['inputDimensionL'], $_POST['inputDimensionH'], $_POST['inputVolumetricWeight'],
$_POST['inputNumberofCarton'], $_POST['inputTotalCartonWeight'], $_POST['cargoReadyTime'], $_POST['inputPickupName'], 
$_POST['inputTotalCharges'], $_POST['inputFlyerName'])){
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
    $inputVolumetricWeight = filter_input(INPUT_POST, 'inputVolumetricWeight', FILTER_SANITIZE_STRING);
    $inputNumberofCarton = filter_input(INPUT_POST, 'inputNumberofCarton', FILTER_SANITIZE_STRING);
    $inputTotalCartonWeight = filter_input(INPUT_POST, 'inputTotalCartonWeight', FILTER_SANITIZE_STRING);
    $inputTotalCharges = filter_input(INPUT_POST, 'inputTotalCharges', FILTER_SANITIZE_STRING);
    $inputFlyerName = filter_input(INPUT_POST, 'inputFlyerName', FILTER_SANITIZE_STRING);
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
    $message3 = array();

    if($_POST['inputNotesInternal'] != null && $_POST['inputNotesInternal'] != ""){
        $inputNotesInternal = filter_input(INPUT_POST, 'inputNotesInternal', FILTER_SANITIZE_STRING);
    }
    
    if($_POST['inputNotestoCustomer'] != null && $_POST['inputNotestoCustomer'] != ""){
        $inputNotestoCustomer = filter_input(INPUT_POST, 'inputNotestoCustomer', FILTER_SANITIZE_STRING);
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

    if(isset($_POST['route']) && $_POST['route']){
        $route = $_POST['route'];
        $departure = $_POST['departure']; 
        $depatureTime = $_POST['depatureTime'];
        $arrival = $_POST['arrival'];
        $arrivalTime = $_POST['arrivalTime'];
        $flightNo = $_POST['flightNo'];

        for($i=0; $i<count($route); $i++){
            if($flightNo[$i] != null){
                $message[] = array( 
                    'route' => $route[$i],
                    'departure' => $departure[$i],
                    'depatureTime' => $depatureTime[$i],
                    'arrival' => $arrival[$i],
                    'arrivalTime' => $arrivalTime[$i],
                    'flightNo' => $flightNo[$i]
                );
            }
            else{
                $message[] = array( 
                    'route' => $route[$i],
                    'departure' => $departure[$i],
                    'depatureTime' => $depatureTime[$i],
                    'arrival' => $arrival[$i],
                    'arrivalTime' => $arrivalTime[$i],
                    'flightNo' => ''
                );
            }

            if($i == 0){
                $inputDepAirport = $departure[$i];
            }

            if($i == (count($route) - 1)){
                $inputDesAirport = $arrival[$i];
            }
        }
    }
    
    for($i=0; $i<count($inputDimensionW); $i++){
        $message2[] = array( 
            'W' => $inputDimensionW[$i],
            'L' => $inputDimensionL[$i],
            'H' => $inputDimensionH[$i],
            'Weight' => $inputCartonPiecesWeight[$i],
            'Unit' => 'CM'
        );
    }

    if(isset($_POST['extraChargesName']) && $_POST['extraChargesName']){
        $extraChargesName = $_POST['extraChargesName'];
        $extraChargesAmount = $_POST['extraChargesAmount'];

        for($i=0; $i<count($extraChargesName); $i++){
            $message3[] = array( 
                'extraChargesName' => $extraChargesName[$i],
                'extraChargesAmount' => $extraChargesAmount[$i]
            );
        }
    }

    if(isset($_POST['id']) && $_POST['id'] != null && $_POST['id'] != ''){
        if ($update_stmt = $db->prepare("UPDATE sales SET customer_name = ?, contact_no = ?, email = ?, total_amount = ?, customer_notes = ?, internal_notes = ?, shipment_type = ?, updated_by = ?, handled_by = ? WHERE id=?")) {
            $update_stmt->bind_param('ssssssssss', $inputCustomerName, $inputContactNum, $inputEmail, $inputTotalCharges, $inputNotestoCustomer, $inputNotesInternal, $inputShipmentType, $user, $inputHandler, $_POST['saleId']);
            
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
                $update_stmt->close();
                $message = json_encode($message);
                $message2 = json_encode($message2);
                $message3 = json_encode($message3);
                
                if ($update_stmt2 = $db->prepare("UPDATE sales_cart SET flyers = ?, departure_airport = ?, destination_airport = ?, weight_data = ?, volumetric_weight = ?, number_of_carton = ?, total_cargo_weight = ?, cargo_ready_time = ?, pickup_address = ?, pickup_pic = ?, pickup_contact = ?, pickup_email = ?, delivery_address = ?, delivery_pic = ?, delivery_contact = ?, delivery_email = ?, route = ?, pickup_charge = ?, export_clearances = ?, air_ticket = ?, flyers_fee = ?, import_clearance = ?, delivery_charges = ?, extra_charges = ?, total_amount = ?  WHERE id=?")) {
                    $update_stmt2->bind_param('ssssssssssssssssssssssssss', $inputFlyerName, $inputDepAirport, $inputDesAirport, $message2, $inputVolumetricWeight, $inputNumberofCarton, $inputTotalCartonWeight, $cargoReadyTime, $inputPickupAddress, $inputPickupName, $inputPickupContactNum, $inputPickupEmail, $inputDeliveryAddress, $inputDeliveryName, $inputDeliveryContactNum, $inputDeliveryEmail, $message, $inputPickupCharge, $inputExportClearances, $inputAirTicket, $inputFlyersFee, $inputImportClearance, $inputDeliveryCharges, $message3, $inputTotalCharges, $_POST['id']);
                
                    if (! $update_stmt2->execute()) {
                        echo json_encode(
                            array(
                                "status"=> "failed", 
                                "message"=> $update_stmt2->error
                            )
                        );
                    }
                    else{
                        $update_stmt2->close();
                        $db->close();

                        echo json_encode(
                            array(
                                "status"=> "success", 
                                "message"=> "Updated Successfully!!" 
                            )
                        );
                    }
                }
                else{
                    echo json_encode(
                        array(
                            "status"=> "failed", 
                            "message"=> "Failed to update sales cart table"
                        )
                    );
                }

                
            }
        }
    }
    else{
        echo json_encode(
            array(
                "status"=> "failed", 
                "message"=> "Failed to update sales table"
            )
        );
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