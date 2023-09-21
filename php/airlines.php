<?php
require_once "db_connect.php";

session_start();

if(!isset($_SESSION['userID'])){
    echo '<script type="text/javascript">';
    echo 'window.location.href = "../login.html";</script>';
}
else{
    $userId = $_SESSION['userID'];
}

if(isset($_POST['name'], $_POST['iata'])){
    $iata = filter_input(INPUT_POST, 'iata', FILTER_SANITIZE_STRING);
    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);

    if($_POST['id'] != null && $_POST['id'] != ''){
        if ($update_stmt = $db->prepare("UPDATE airlines SET iata=?, name=? WHERE id=?")) {
            $update_stmt->bind_param('sss', $iata, $name, $_POST['id']);
            
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
                                             
                $get_stmt = $db->prepare("SELECT * FROM airlines WHERE id=?");
                $get_stmt->bind_param('s', $id);
                $get_stmt->execute();
                
                $result = $get_stmt->get_result();
                            
                if ($row = $result->fetch_assoc()) {
                    $airline_name = $row['name'];
                }
                
                $get_stmt->close();
                
                
                $action = "User : ".$name." Update Airline : ".$airline_name." in airlines table!";
                
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
                        "message"=> "Updated Successfully!!" 
                    )
                );
            }
        }
    }
    else{
        if ($insert_stmt = $db->prepare("INSERT INTO airlines (iata, name) VALUES (?, ?)")) {
            $insert_stmt->bind_param('ss', $iata, $name);
            
            // Execute the prepared query.
            if (! $insert_stmt->execute()) {
                echo json_encode(
                    array(
                        "status"=> "failed", 
                        "message"=> $insert_stmt->error
                    )
                );
            }
            else{
                $insert_stmt->close();
                
                $name = $_SESSION['name'];
                $userId = $_SESSION['userID'];
                $today = date("Y-m-d H:i:s");
                                             
                $get_stmt = $db->prepare("SELECT * FROM airlines WHERE id=?");
                $get_stmt->bind_param('s', $id);
                $get_stmt->execute();
                
                $result = $get_stmt->get_result();
                            
                if ($row = $result->fetch_assoc()) {
                    $airline_name = $row['name'];
                }
                
                $get_stmt->close();
                
                
                $action = "User : ".$name." Add Airline : ".$airline_name." in airlines table!";
                
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
                        "message"=> "Added Successfully!!" 
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