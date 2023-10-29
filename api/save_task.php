<?php
require_once 'db_connect.php';
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

session_start();
$post = json_decode(file_get_contents('php://input'), true);

if(isset($post['id'], $post['log'])){
	$id = $post['id'];
	$log = $post['log'];

	if(isset($post['id']) && $post['id'] != null && $post['id'] != ''){
		if ($update_stmt = $db->prepare("UPDATE job SET log=? WHERE id=?")){
			$id = $post['id'];
			$data = json_encode($log);
			$update_stmt->bind_param('ss', $data, $id);
		
			// Execute the prepared query.
			if (! $update_stmt->execute()){
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
		else{
			echo json_encode(
				array(
					"status"=> "failed", 
					"message"=> $insert_stmt->error
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