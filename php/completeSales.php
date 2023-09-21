<?php
require_once "db_connect.php";

session_start();

if(!isset($_SESSION['userID'])){
    echo '<script type="text/javascript">';
    echo 'window.location.href = "../login.html";</script>';
}

if(isset($_POST['salesID'])){
    $salesID = filter_input(INPUT_POST, 'salesID', FILTER_SANITIZE_STRING);
    $completed_datetime = date("Y-m-d H:i:s");

    if ($update_stmt = $db->prepare("UPDATE sales SET completed_datetime=? WHERE id=?")) {
        $update_stmt->bind_param('ss', $completed_datetime, $salesID);
        
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

            $name = $_SESSION['name'];
            $userId = $_SESSION['userID'];
            $today = date("Y-m-d H:i:s");

            $get_stmt = $db->prepare("SELECT * FROM sales WHERE id=?");
            $get_stmt->bind_param('s', $salesID);
            $get_stmt->execute();

            $result = $get_stmt->get_result();
            
            if ($row = $result->fetch_assoc()) {
                $quotationId = $row['quotation_no'];
            }

            $get_stmt->close();

            $action = "User : ".$name." Set Complete Status to Sales with Quotation No : ".$quotationId." in sales table!";

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
                    "message"=> "Start Shipping!!" 
                )
            );
        }
    }
}
else{
    echo json_encode(
        array(
            "status" => "failed",
            "message" => "Missing Attribute"
        )
    ); 
}
?>