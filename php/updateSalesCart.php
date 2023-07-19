<?php
require_once "db_connect.php";

session_start();

if(!isset($_SESSION['userID'])){
    echo '<script type="text/javascript">';
    echo 'window.location.href = "../login.html";</script>';
}

if(isset($_POST['id'], $_POST['inputFlyerNameEdit'])){
    $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_STRING);
    $inputFlyerNameEdit = filter_input(INPUT_POST, 'inputFlyerNameEdit', FILTER_SANITIZE_STRING);

    if ($update_stmt = $db->prepare("UPDATE sales_cart SET flyers = ? WHERE id=?")) {
        $update_stmt->bind_param('ss', $inputFlyerNameEdit, $id);
        
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
                    "message"=> "Updated Successfully!!" 
                )
            );  
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