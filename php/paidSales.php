<?php
require_once "db_connect.php";

session_start();

if(!isset($_SESSION['userID'])){
    echo '<script type="text/javascript">';
    echo 'window.location.href = "../login.html";</script>';
}

if(isset($_POST['salesID'])){
    $salesID = filter_input(INPUT_POST, 'salesID', FILTER_SANITIZE_STRING);
    $paid_datetime = date("Y-m-d H:i:s");
    $today = date("Y-m-d 00:00:00");
    $uid = $_SESSION['userID'];

    if ($select_stmt = $db->prepare("SELECT COUNT(*) FROM sales WHERE sales_no IS NOT NULL AND created_datetime >= ?")) {
        $select_stmt->bind_param('s', $today);
        
        // Execute the prepared query.
        if (! $select_stmt->execute()) {
            echo json_encode(
                array(
                    "status" => "failed",
                    "message" => "Failed to get latest Sales Order Number"
                )
            ); 
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

            if ($update_stmt = $db->prepare("UPDATE sales SET sales_no=?, paid_datetime=? WHERE id=?")) {
                $update_stmt->bind_param('sss', $firstChar, $paid_datetime, $salesID);
                
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

                                if ($insert_stmt2 = $db->prepare("INSERT INTO job (sales_cart_id, created_by) VALUES (?, ?)")) {
                                    $insert_stmt2->bind_param('ss', $sales_cart_id, $uid);
                                    // Execute the prepared query.
                                    if (! $insert_stmt2->execute()) {
                                        $success = false;
                                    }
                                }
                            }

                            if($success){
                                $select_stmt2->close();
                                $db->close();

                                echo json_encode(
                                    array(
                                        "status"=> "success", 
                                        "message"=> "Job Created!!" 
                                    )
                                );
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
                    else{
                        echo json_encode(
                            array(
                                "status"=> "failed", 
                                "message"=> "failed to prepare jobs"
                            )
                        );
                    }
                }
            }
        }
    }
    else{
        echo json_encode(
            array(
                "status" => "failed",
                "message" => "Failed to get Sales Order Count"
            )
        );
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