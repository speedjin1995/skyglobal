<?php
require_once "db_connect.php";

session_start();

if(!isset($_SESSION['userID'])){
    echo '<script type="text/javascript">';
    echo 'window.location.href = "../login.html";</script>';
}

if(isset($_POST['jobID'], $_POST['jobStatus'])){
    $jobID = filter_input(INPUT_POST, 'jobID', FILTER_SANITIZE_STRING);
    $jobStatus = filter_input(INPUT_POST, 'jobStatus', FILTER_SANITIZE_STRING);
    $jobLog = array();

    if ($select_stmt = $db->prepare("SELECT log FROM job WHERE id=?")) {
        $select_stmt->bind_param('s', $jobID);
        
        // Execute the prepared query.
        if (! $select_stmt->execute()) {
            echo json_encode(
                array(
                    "status"=> "failed", 
                    "message"=> $select_stmt->error
                )
            );
        }
        else{
            $result = $select_stmt->get_result();

            if ($row = $result->fetch_assoc()) {
                $jobLog = json_decode($row['log'], true);
            }

            $select_stmt->close();
            array_push($jobLog, array(
                'status' => $jobStatus,
                'timestamp' => date("Y-m-d H:i:s")
            ));
            $jobLog = json_encode($jobLog);
            
            if ($update_stmt2 = $db->prepare("UPDATE job SET log = ? WHERE id=?")) {
                $update_stmt2->bind_param('ss', $jobLog, $jobID);
            
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
            "message"=> "Please fill in all the fields"
        )
    );
}
?>