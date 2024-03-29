<?php
require_once "db_connect.php";

session_start();

if(isset($_POST['userID'])){
	$id = filter_input(INPUT_POST, 'userID', FILTER_SANITIZE_STRING);

    if ($update_stmt = $db->prepare("SELECT * FROM suppliers WHERE id=?")) {
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
            
            while ($row = $result->fetch_assoc()) {
                $message['id'] = $row['id'];
                $message['supplier_name'] = $row['supplier_name'];
                $message['last_name'] = $row['last_name'];
                $message['dob'] = $row['dob'];
                $message['supplier_address'] = $row['supplier_address'];
                $message['supplier_phone'] = $row['supplier_phone'];
                $message['supplier_email'] = $row['supplier_email'];
                $message['passport'] = $row['passport'];
                $message['passport_expiry_date'] = $row['passport_expiry_date'];
                $message['picture'] = $row['picture'];
                $message['passport_pic'] = $row['passport_pic'];
                $message['visa_pic'] = $row['visa_pic'];
                $message['supplier_phone2'] = $row['supplier_phone2'];
                $message['vaccination_status'] = $row['vaccination_status'];
                $message['nationality'] = $row['nationality'];
                $message['station_country'] = $row['station_country'];
                $message['username'] = $row['username'];
                $message['remark'] = $row['remark'];
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