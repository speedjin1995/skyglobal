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

if(isset($_POST['jobID'], $_POST['jobStatus'])){
    $ds = DIRECTORY_SEPARATOR;  //1 
    $storeFolder = '../jobs';   //2
    $filename = filter_input(INPUT_POST, 'filename', FILTER_SANITIZE_STRING);
    $jobID = filter_input(INPUT_POST, 'jobID', FILTER_SANITIZE_STRING);
    $jobStatus = filter_input(INPUT_POST, 'jobStatus', FILTER_SANITIZE_STRING);
    $jobLog = array();
    
    if (!empty($_FILES)) {
        $tempFile = $_FILES['file']['tmp_name'];
        $temp = explode(".", $_FILES["file"]["name"]);
        $newfilename = $filename . '.' . end($temp);
        $targetPath = dirname( __FILE__ ).$ds.$storeFolder.$ds;
        $targetFile = $targetPath.$newfilename;
        move_uploaded_file($tempFile,$targetFile);

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
                    if($row['log'] != null && $row['log'] != ''){
                        $jobLog = json_decode($row['log'], true);
                    }
                }
    
                $select_stmt->close();

                for($i=0; $i<count($jobLog); $i++){
                    if($jobLog[$i]['status'] == $jobStatus){
                        array_push($jobLog[$i]['images'], $newfilename);
                    }
                }

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
                            "message"=> "Failed to update job log"
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
                "message"=> "No file received"
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