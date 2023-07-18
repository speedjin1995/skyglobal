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
    $uid = $_SESSION['userID'];
    $today = date("Y-m-d 00:00:00");

    if ($select_stmt2 = $db->prepare("SELECT * FROM sales_cart WHERE sale_id = ?")) {
        $select_stmt2->bind_param('s', $salesID);
    
        if (! $select_stmt2->execute()) {
            echo json_encode(
                array(
                    "status"=> "failed", 
                    "message"=> $select_stmt2->error
                )
            );
        }
        else{
            $result2 = $select_stmt2->get_result();
            $success = true;
            
            while ($row2 = $result2->fetch_assoc()) {
                $sales_cart_id = $row2['id'];
                if ($select_stmt = $db->prepare("SELECT COUNT(*) FROM job WHERE created_datetime >= ?")) {
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
                        $count = 0;
                        $firstChar = 'J'.date("Ymd");
                        
                        if ($row = $result->fetch_assoc()) {
                            $count = (int)$row['COUNT(*)'] + 1;
                            $select_stmt->close();
                        }

                        $charSize = strlen(strval($count));

                        for($i=0; $i<(4-(int)$charSize); $i++){
                            $firstChar.='0';  // S0000
                        }
                
                        $firstChar .= strval($count);  //S00009

                        if ($insert_stmt2 = $db->prepare("INSERT INTO job (job_no, sales_cart_id, created_by) VALUES (?, ?, ?)")) {
                            $insert_stmt2->bind_param('sss', $firstChar, $sales_cart_id, $uid);
                            // Execute the prepared query.
                            if (! $insert_stmt2->execute()) {
                                $success = false;
                            }
                        }
                    }
                }
            }
    
            if($success){
                $select_stmt2->close();
    
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
                                "message"=> "Job Created & Start Shipping!!" 
                            )
                        );
                    }
                }
            }
            else{
                echo json_encode(
                    array(
                        "status"=> "failed", 
                        "message"=> "failed to created jobs"
                    )
                );
            }
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