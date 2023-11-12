<?php
require_once "db_connect.php";

session_start();

if(!isset($_SESSION['userID'])){
    echo '<script type="text/javascript">';
    echo 'window.location.href = "../login.html";</script>';
}

if(isset($_POST['inputJobNo'], $_POST['inputDate'], $_POST['purchaseId'], $_POST['itemName'], $_POST['itemPrice'])){
    $inputJobNo = filter_input(INPUT_POST, 'inputJobNo', FILTER_SANITIZE_STRING);
    $inputDate = filter_input(INPUT_POST, 'inputDate', FILTER_SANITIZE_STRING);
    $total=0;

    $user = $_SESSION['userID'];
    
    $message = array();
    $message2 = array();
    
    $success = true;
    $today = date("Y-m-d H:i:s");


    if(isset($_POST['purchaseId']) && $_POST['purchaseId']){
        $purchaseId = $_POST['purchaseId'];
        $itemName = $_POST['itemName']; 
        $itemPrice = $_POST['itemPrice'];

        for($i=0; $i<count($purchaseId); $i++){
            $message[] = array( 
                'purchaseId' => $purchaseId[$i],
                'itemName' => $itemName[$i],
                'itemPrice' => $itemPrice[$i],
                'images' => array()
            );
            $total+= $itemPrice[$i];
        }
    }

    $select_stmt = $db->prepare("SELECT id, items, total FROM purchases WHERE jobNo = ?");
    $select_stmt->bind_param('s', $inputJobNo);
    $select_stmt->execute();
    $result = $select_stmt->get_result();
    $purchasesData = $result->fetch_assoc();
	$claimList = array();
    
    if (empty($purchasesData)) {
        if ($insert_stmt = $db->prepare("INSERT INTO purchases (jobNo , date, items, total, created_datetime) VALUES (?, ?, ?, ?, ?)")) {
            $msg = json_encode($message);
            $insert_stmt->bind_param('sssis', $inputJobNo, $inputDate, $msg, $total, $today);
    
            if (! $insert_stmt->execute()) {
                echo json_encode(
                    array(
                        "status"=> "failed", 
                        "message"=> $insert_stmt->error 
                    )
                );
            }
            else{
                $purchase_id = $insert_stmt->insert_id;;
                $insert_stmt->close();
                $name = $_SESSION['name'];
                $userId = $_SESSION['userID'];
                $today = date("Y-m-d H:i:s");
                
                $action = "User : ".$name." Add Purchase id : ".$purchase_id." in customers table!";
                
                if ($log_insert_stmt = $db->prepare("INSERT INTO log (userId, userName, created_dateTime, action) VALUES (?,?,?,?)")) {
                    $log_insert_stmt->bind_param('ssss', $userId, $name, $today, $action);
                            
                    if (! $log_insert_stmt->execute()) {
                        /*echo json_encode(
                            array(
                                "status"=> "failed", 
                                "message"=> $log_insert_stmt->error 
                            )
                        );*/
                    }
                }
    
              echo json_encode(
                array(
                    "status"=> "success", 
                    "message"=> "Inserted Successfully!" 
                )
              );
            }
            
            //$insert_stmt->close();
            $db->close();
            
        }
    } 
	else {
		$id = $purchasesData['id'];
        $msg = json_encode($message);
        $update_stmt = $db->prepare("UPDATE purchases SET items=?, total=? WHERE id=?");
        $update_stmt->bind_param('sss', $msg, $total, $id);

        if ($update_stmt->execute()) {
            echo json_encode(
                array(
                    "status" => "success",
                    "message" => "Updated Successfully!!"
                )
            );
        } else {
            echo json_encode(
                array(
                    "status" => "failed",
                    "message" => $update_stmt->error
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