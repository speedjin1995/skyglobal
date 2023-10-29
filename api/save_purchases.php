<?php
require_once 'db_connect.php';
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

session_start();
$post = json_decode(file_get_contents('php://input'), true);

if (isset($post['id'], $post['claims'])) {
    $jobNo = $post['id'];
    $log = $post['claims'];
    $currentDateTime = date('Y-m-d H:i:s');
        
    // Select data from the purchases table based on the jobNo
    $select_stmt = $db->prepare("SELECT id, items, total FROM purchases WHERE jobNo = ?");
    $select_stmt->bind_param('s', $jobNo);
    $select_stmt->execute();
    $result = $select_stmt->get_result();
    $purchasesData = $result->fetch_assoc();
	$claimList = array();
    
    if (empty($purchasesData)) {
        $totalPrice = 0.00;
		$index = 1;

		foreach ($log as $claim) {
			$claimList[] = array(
				'purchaseId'=>(string)$index,
				'itemName'=>$claim['item'],
				'itemPrice'=>$claim['price'],
				'images'=>$claim['images']
			);

			$totalPrice += (float)$claim['price'];
			$index++;
		}
		

        $insert_stmt = $db->prepare("INSERT INTO purchases (jobNo, items, date, total) VALUES (?, ?, ?, ?)");
		$data = json_encode($claimList);
        $insert_stmt->bind_param('ssss', $jobNo, $data, $currentDateTime, $totalPrice);
        if ($insert_stmt->execute()) {
            echo json_encode(
                array(
                    "status" => "success",
                    "message" => "Inserted Successfully!!"
                )
            );
        } else {
            echo json_encode(
                array(
                    "status" => "failed",
                    "message" => $insert_stmt->error
                )
            );
        }
    } 
	else {
		$items = json_decode($purchasesData['items'], true);
		$totalPrice = (float)$purchasesData['total'];
		$index = count($items);

		foreach ($log as $claim) {
			$totalPrice += (float)$claim['price'];

			array_push($items, array(
				'purchaseId'=>(string)$index,
				'itemName'=>$claim['item'],
				'itemPrice'=>$claim['price'],
				'images'=>$claim['images']
			));

			$index++;
		}

        $id = $purchasesData['id'];
        $update_stmt = $db->prepare("UPDATE purchases SET items=?, date=?, total=? WHERE id=?");
		$data = json_encode($items);
        $update_stmt->bind_param('ssss', $data, $currentDateTime, $totalPrice, $id);

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
else {
    echo json_encode(
        array(
            "status" => "failed",
            "message" => "Please fill in all the fields"
        )
    );
}
?>
