<?php
require_once 'db_connect.php';

session_start();

if(!isset($_SESSION['userID'])){
	echo '<script type="text/javascript">location.href = "../login.html";</script>'; 
}

if(isset($_POST['userID'])){
	$id = filter_input(INPUT_POST, 'userID', FILTER_SANITIZE_STRING);
	$del = "1";

	$get_stmt = $db->prepare("SELECT * FROM airport WHERE id=?");
	$get_stmt->bind_param('s', $id);
	$get_stmt->execute();
			
	$result = $get_stmt->get_result();
						
	if ($row = $result->fetch_assoc()) {
		$airport_name = $row['airport_name'];
	}
			
	$get_stmt->close();

	if ($stmt2 = $db->prepare("DELETE FROM airport WHERE id=?")) {
		$stmt2->bind_param('s', $id);
		
		if($stmt2->execute()){
			$stmt2->close();

			$name = $_SESSION['name'];
			$userId = $_SESSION['userID'];
			$today = date("Y-m-d H:i:s");
			
			$action = "User : ".$name." Delete Airport : ".$airport_name." in airport table!";
			
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
    	            "message"=> "Deleted"
    	        )
    	    );
		} else{
		    echo json_encode(
    	        array(
    	            "status"=> "failed", 
    	            "message"=> $stmt2->error
    	        )
    	    );
		}
	} 
	else{
	    echo json_encode(
	        array(
	            "status"=> "failed", 
	            "message"=> "Somthings wrong"
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
