<?php
require_once "db_connect.php";

session_start();

if(isset($_POST['salesID'])){
	$id = filter_input(INPUT_POST, 'salesID', FILTER_SANITIZE_STRING);

    if ($update_stmt = $db->prepare("SELECT sales_cart.flyers, sales_cart.id FROM sales, sales_cart WHERE sales.id = sales_cart.sale_id AND sales.id=?")) {
        $update_stmt->bind_param('s', $id);
        
        // Execute the prepared query.
        if (! $update_stmt->execute()) {
            echo json_encode(
                array(
                    "status" => "failed",
                    "message" => "Something went wrong"
                )); 
        }
        else{
            $result = $update_stmt->get_result();
            $message = array();
            
            if ($row = $result->fetch_assoc()) {
                $message['flyers'] = $row['flyers'];
                $message['id'] = $row['id'];
            }
            
            echo json_encode(
                array(
                    "status" => "success",
                    "message" => $message
                ));   
        }
    }
}
else{
    echo json_encode(
        array(
            "status" => "failed",
            "message" => "Missing Attribute"
            )); 
}
?>