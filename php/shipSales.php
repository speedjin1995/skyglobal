<?php
require_once "db_connect.php";

session_start();

if(!isset($_SESSION['userID'])){
    echo '<script type="text/javascript">';
    echo 'window.location.href = "../login.html";</script>';
}

if(isset($_POST['salesID'])){
    $salesID = filter_input(INPUT_POST, 'salesID', FILTER_SANITIZE_STRING);
    $shipped_datetime = date("Y-m-d H:i:s");

    if ($update_stmt = $db->prepare("UPDATE sales SET shipped_datetime=? WHERE id=?")) {
        $update_stmt->bind_param('ss', $shipped_datetime, $salesID);
        
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