<?php
require_once "db_connect.php";

session_start();

if(isset($_POST['jobId'])){
	$id = filter_input(INPUT_POST, 'jobId', FILTER_SANITIZE_STRING);

    if ($update_stmt = $db->prepare("SELECT * FROM job WHERE job_no = ?"))
    {
        $update_stmt->bind_param('s', $id);
        
        // Execute the prepared query.
        if (! $update_stmt->execute()) {
            echo json_encode(
                array(
                    "status" => "failed",
                    "message" => "Job Number not available"
                )); 
        }
        else{
            $result = $update_stmt->get_result();
            $message = array();

            $salesCartId=0;
            if ($row = $result->fetch_assoc()) {
                $salesCartId = $row['sales_cart_id'];

                $get_stmt = $db->prepare("SELECT * FROM sales_cart WHERE id = ?");
                $get_stmt->bind_param('i', $salesCartId);

                $get_stmt->execute();
                $getResult = $get_stmt->get_result();

                if ($row = $getResult->fetch_assoc()) {
                 $message = $row['extra_charges'];

                 echo json_encode(
                    array(
                        "status" => "success",
                        "message" => $message
                    ));   
                }
                else{
                  echo json_encode(
                    array(
                        "status" => "failed",
                        "message" => "Sales Cart Id not available"
                        )); 
                }
            }
            else{
                echo json_encode(
                    array(
                        "status" => "failed",
                        "message" =>  "Job Number not available"
                    ));  

            }
        }
    }
}
else{
    echo json_encode(
        array(
            "status" => "failed",
            "message" => "Job Number not available"
            )); 
}
?>