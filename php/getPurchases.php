<?php
require_once "db_connect.php";

session_start();

if(isset($_POST['jobId'])){
	$id = filter_input(INPUT_POST, 'jobId', FILTER_SANITIZE_STRING);

    if ($update_stmt = $db->prepare("SELECT * FROM job WHERE job_no = ?"))
    {
        $update_stmt->bind_param('s', $id);
        
        // Execute the prepared query.
        if (! $update_stmt->execute()) {
            echo json_encode(
                array(
                    "status" => "failed",
                    "message" => "Job Number not available"
                )); 
        }
        else{
            $result = $update_stmt->get_result();
            $message = array();

            $salesCartId=0;
            if ($row = $result->fetch_assoc()) {
                $salesCartId = $row['sales_cart_id'];

                $get_stmt = $db->prepare("SELECT * FROM sales_cart WHERE id = ?");
                $get_stmt->bind_param('i', $salesCartId);

                $get_stmt->execute();
                $getResult = $get_stmt->get_result();

                if ($row = $getResult->fetch_assoc()) {
                    $message = json_decode($row['extra_charges'], true);

                    if($row['pickup_charge'] != null && $row['pickup_charge'] != '' && $row['pickup_charge'] != '0.00'){
                        $message[] = array(
                            'extraChargesName' => 'Pickup Charge',
                            'extraChargesAmount' => $row['pickup_charge'],
                        );
                    }

                    if($row['export_clearances'] != null && $row['export_clearances'] != '' && $row['export_clearances'] != '0.00'){
                        $message[] = array(
                            'extraChargesName' => 'Export Clearances',
                            'extraChargesAmount' => $row['export_clearances'],
                        );
                    }

                    if($row['air_ticket'] != null && $row['air_ticket'] != '' && $row['air_ticket'] != '0.00'){
                        $message[] = array(
                            'extraChargesName' => 'Air Ticket',
                            'extraChargesAmount' => $row['air_ticket'],
                        );
                    }

                    if($row['flyers_fee'] != null && $row['flyers_fee'] != '' && $row['flyers_fee'] != '0.00'){
                        $message[] = array(
                            'extraChargesName' => 'Flyers Fees',
                            'extraChargesAmount' => $row['flyers_fee'],
                        );
                    }


                    if($row['import_clearance'] != null && $row['import_clearance'] != '' && $row['import_clearance'] != '0.00'){
                        $message[] = array(
                            'extraChargesName' => 'Import Clearance',
                            'extraChargesAmount' => $row['import_clearance'],
                        );
                    }

                    if($row['delivery_charges'] != null && $row['delivery_charges'] != '' && $row['delivery_charges'] != '0.00'){
                        $message[] = array(
                            'extraChargesName' => 'Delivery Charges',
                            'extraChargesAmount' => $row['delivery_charges'],
                        );
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
                        "message" => "Sales Cart Id not available"
                        )); 
                }
            }
            else{
                echo json_encode(
                    array(
                        "status" => "failed",
                        "message" =>  "Job Number not available"
                    ));  

            }
        }
    }
}
else{
    echo json_encode(
        array(
            "status" => "failed",
            "message" => "Job Number not available"
            )); 
}
?>