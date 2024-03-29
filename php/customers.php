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

if(isset($_POST['name'], $_POST['address'], $_POST['phone'], $_POST['email'])){
    $address = filter_input(INPUT_POST, 'address', FILTER_SANITIZE_STRING);
    $phone = filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_STRING);
    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
    $pic = null;

    if(isset($_POST['pic']) && $_POST['pic'] != null && $_POST['pic'] != ''){
        $pic = filter_input(INPUT_POST, 'pic', FILTER_SANITIZE_STRING);
    }

    if(filter_var($email, FILTER_VALIDATE_EMAIL)){
        if($_POST['id'] != null && $_POST['id'] != ''){
            if ($update_stmt = $db->prepare("UPDATE customers SET customer_name=?, customer_address=?, customer_phone=?, customer_email=?, pic=? WHERE id=?")) {
                $update_stmt->bind_param('ssssss', $name, $address, $phone, $email, $pic, $_POST['id']);
                
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
                                                 
                    $get_stmt = $db->prepare("SELECT * FROM customers WHERE id=?");
                    $get_stmt->bind_param('s', $id);
                    $get_stmt->execute();
                    
                    $result = $get_stmt->get_result();
                                
                    if ($row = $result->fetch_assoc()) {
                        $customer_name = $row['customer_name'];
                    }
                    
                    $get_stmt->close();
                    
                    
                    $action = "User : ".$name." Update Customer : ".$customer_name." in customers table!";
                    
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
            if ($insert_stmt = $db->prepare("INSERT INTO customers (customer_name, customer_address, customer_phone, customer_email, pic) VALUES (?, ?, ?, ?, ?)")) {
                $insert_stmt->bind_param('sssss', $name, $address, $phone, $email, $pic);
                
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
                                                 
                    $get_stmt = $db->prepare("SELECT * FROM customers WHERE id=?");
                    $get_stmt->bind_param('s', $id);
                    $get_stmt->execute();
                    
                    $result = $get_stmt->get_result();
                                
                    if ($row = $result->fetch_assoc()) {
                        $customer_name = $row['customer_name'];
                    }
                    
                    $get_stmt->close();
                    
                    
                    $action = "User : ".$name." Add Customer : ".$customer_name." in customers table!";
                    
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
                "message"=> "Please enter a valid email address"
            )
        );
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