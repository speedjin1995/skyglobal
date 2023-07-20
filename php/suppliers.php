<?php
require_once "db_connect.php";

session_start();
$allowed = array("jpg" => "image/jpg", "jpeg" => "image/jpeg", "gif" => "image/gif", "png" => "image/png");
$path = '../assets/';
$path2 = '../assets/';
$path3 = '../assets/';

if(!isset($_SESSION['userID'])){
    echo '<script type="text/javascript">';
    echo 'window.location.href = "../login.html";</script>';
}
else{
    $userId = $_SESSION['userID'];
}

if(isset($_POST['username'], $_POST['firstName'], $_POST['lastName'], $_POST['address'], $_POST['phone'], $_POST['email'], 
$_POST['passport'], $_POST['passportExpiry'], $_POST['stationCountry'], $_POST['nationality'], $_POST['dob'])){
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
    $firstName = filter_input(INPUT_POST, 'firstName', FILTER_SANITIZE_STRING);
    $lastName = filter_input(INPUT_POST, 'lastName', FILTER_SANITIZE_STRING);
	$address = filter_input(INPUT_POST, 'address', FILTER_SANITIZE_STRING);
    $phone = filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_STRING);
    $passport = filter_input(INPUT_POST, 'passport', FILTER_SANITIZE_STRING);
    $passportExpiry = filter_input(INPUT_POST, 'passportExpiry', FILTER_SANITIZE_STRING);
    $stationCountry = filter_input(INPUT_POST, 'stationCountry', FILTER_SANITIZE_STRING);
    $nationality = filter_input(INPUT_POST, 'nationality', FILTER_SANITIZE_STRING);
    $dob = filter_input(INPUT_POST, 'dob', FILTER_SANITIZE_STRING);
    $remark = null;

    if($_POST['remark'] != null && $_POST['remark'] != ""){
        $remark = filter_input(INPUT_POST, 'remark', FILTER_SANITIZE_STRING);
    }

    $path = $path.'profile/';
    $filePath = 'profile/';
    $uploadOk = 0;
    $path2 = $path2.'visa/';
    $filePath2 = 'visa/';
    $uploadOk2 = 0;
    $path3 = $path3.'passport/';
    $filePath3 = 'passport/';
    $uploadOk3 = 0;

    $random_salt = hash('sha512', uniqid(openssl_random_pseudo_bytes(16), TRUE));
    $password = '123456';
    $password = hash('sha512', $password . $random_salt);

    if(isset($_FILES["image-upload"]) && $_FILES["image-upload"]["error"] == 0){
        $filename = $_FILES["image-upload"]["name"];
        $filetype = $_FILES["image-upload"]["type"];
        $filesize = $_FILES["image-upload"]["size"];
    
        // Verify file extension
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        if(!array_key_exists($ext, $allowed)){
            echo json_encode(
                array(
                    "status" => "failed",
                    "message" => "Please select a valid file format."
                )
            );
        }
    
        // Verify file size - 5MB maximum
        $maxsize = 5 * 1024 * 1024;
        if($filesize > $maxsize){
            echo json_encode(
                array(
                    "status" => "failed",
                    "message" => "File size is larger than the allowed limit."
                )
            );
        }
    
        // Verify MYME type of the file
        if(in_array($filetype, $allowed)){
            $temp = explode(".", $_FILES["image-upload"]["name"]);
            $newfilename = round(microtime(true)) . '.' . end($temp);

            // Check whether file exists before uploading it
            if(file_exists($path.$newfilename)){
                echo json_encode(
                    array(
                        "status" => "failed",
                        "message" => $newfilename." is already exists."
                    )
                );
            } 
            else{
                if (move_uploaded_file($_FILES["image-upload"]["tmp_name"], $path.$newfilename)) {
                    $filePath = $filePath.$newfilename;
                    $uploadOk = 1;
                } 
                else {
                    echo json_encode(
                        array(
                            "status" => "failed",
                            "message" => "Sorry, there was an error uploading your file."
                        )
                    );
                }
            } 
        } 
        else{
            echo json_encode(
                array(
                    "status" => "failed",
                    "message" => "Sorry, there was an error uploading your file."
                )
            );
        }
    }

    if(isset($_FILES["image-upload2"]) && $_FILES["image-upload2"]["error"] == 0){
        $filename = $_FILES["image-upload2"]["name"];
        $filetype = $_FILES["image-upload2"]["type"];
        $filesize = $_FILES["image-upload2"]["size"];
    
        // Verify file extension
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        if(!array_key_exists($ext, $allowed)){
            echo json_encode(
                array(
                    "status" => "failed",
                    "message" => "Please select a valid file format."
                )
            );
        }
    
        // Verify file size - 5MB maximum
        $maxsize = 5 * 1024 * 1024;
        if($filesize > $maxsize){
            echo json_encode(
                array(
                    "status" => "failed",
                    "message" => "File size is larger than the allowed limit."
                )
            );
        }
    
        // Verify MYME type of the file
        if(in_array($filetype, $allowed)){
            $temp = explode(".", $_FILES["image-upload2"]["name"]);
            $newfilename = round(microtime(true)) . '.' . end($temp);

            // Check whether file exists before uploading it
            if(file_exists($path2.$newfilename)){
                echo json_encode(
                    array(
                        "status" => "failed",
                        "message" => $newfilename." is already exists."
                    )
                );
            } 
            else{
                if (move_uploaded_file($_FILES["image-upload2"]["tmp_name"], $path2.$newfilename)) {
                    $filePath2 = $filePath2.$newfilename;
                    $uploadOk2 = 1;
                } 
                else {
                    echo json_encode(
                        array(
                            "status" => "failed",
                            "message" => "Sorry, there was an error uploading your file."
                        )
                    );
                }
            } 
        } 
        else{
            echo json_encode(
                array(
                    "status" => "failed",
                    "message" => "Sorry, there was an error uploading your file."
                )
            );
        }
    }

    if(isset($_FILES["image-upload3"]) && $_FILES["image-upload3"]["error"] == 0){
        $filename = $_FILES["image-upload3"]["name"];
        $filetype = $_FILES["image-upload3"]["type"];
        $filesize = $_FILES["image-upload3"]["size"];
    
        // Verify file extension
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        if(!array_key_exists($ext, $allowed)){
            echo json_encode(
                array(
                    "status" => "failed",
                    "message" => "Please select a valid file format."
                )
            );
        }
    
        // Verify file size - 5MB maximum
        $maxsize = 5 * 1024 * 1024;
        if($filesize > $maxsize){
            echo json_encode(
                array(
                    "status" => "failed",
                    "message" => "File size is larger than the allowed limit."
                )
            );
        }
    
        // Verify MYME type of the file
        if(in_array($filetype, $allowed)){
            $temp = explode(".", $_FILES["image-upload3"]["name"]);
            $newfilename = round(microtime(true)) . '.' . end($temp);

            // Check whether file exists before uploading it
            if(file_exists($path3.$newfilename)){
                echo json_encode(
                    array(
                        "status" => "failed",
                        "message" => $newfilename." is already exists."
                    )
                );
            } 
            else{
                if (move_uploaded_file($_FILES["image-upload3"]["tmp_name"], $path3.$newfilename)) {
                    $filePath3 = $filePath3.$newfilename;
                    $uploadOk3 = 1;
                } 
                else {
                    echo json_encode(
                        array(
                            "status" => "failed",
                            "message" => "Sorry, there was an error uploading your file."
                        )
                    );
                }
            } 
        } 
        else{
            echo json_encode(
                array(
                    "status" => "failed",
                    "message" => "Sorry, there was an error uploading your file."
                )
            );
        }
    }

    if(filter_var($email, FILTER_VALIDATE_EMAIL)){
        if($_POST['id'] != null && $_POST['id'] != ''){
            if($uploadOk == 1 && $uploadOk2 == 1 && $uploadOk3 == 1){
                if ($update_stmt = $db->prepare("UPDATE suppliers SET username=?, supplier_name=?, last_name=?, supplier_address=?, supplier_phone=?, supplier_email=?, station_country=?, nationality=?, dob=?, remark=?, passport_pic=?, passport=?, picture=?, passport_expiry_date=?, visa_pic=? WHERE id=?")) {
                    $update_stmt->bind_param('ssssssssssssssss', $username, $firstName, $lastName, $address, $phone, $email, $stationCountry, $nationality, $dob, $remark, $filePath3, $passport, $filePath, $passportExpiry, $filePath2, $_POST['id']);
                    
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
                if ($update_stmt = $db->prepare("UPDATE suppliers SET username=?, supplier_name=?, last_name=?, supplier_address=?, supplier_phone=?, supplier_email=?, station_country=?, nationality=?, dob=?, remark=?, passport=?, passport_expiry_date=? WHERE id=?")) {
                    $update_stmt->bind_param('ssssssssssssssss', $username, $firstName, $lastName, $address, $phone, $email, $stationCountry, $nationality, $dob, $remark, $passport, $passportExpiry, $_POST['id']);
                    
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
        }
        else{
            if($uploadOk == 1 && $uploadOk2 == 1 && $uploadOk3 == 1){
                if ($insert_stmt = $db->prepare("INSERT INTO suppliers (username, password, salt, supplier_name, last_name, supplier_address, supplier_phone, supplier_email, station_country, nationality, dob, remark, passport_pic, passport, picture, passport_expiry_date, visa_pic) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)")) {
                    $insert_stmt->bind_param('sssssssssssssssss', $username, $password, $random_salt, $firstName, $lastName, $address, $phone, $email, $stationCountry, $nationality, $dob, $remark, $filePath3, $passport, $filePath, $passportExpiry, $filePath2);
                    
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
            else{
                if ($insert_stmt = $db->prepare("INSERT INTO suppliers (username, password, salt, supplier_name, last_name, supplier_address, supplier_phone, supplier_email, station_country, nationality, dob, remark, passport, passport_expiry_date) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)")) {
                    $insert_stmt->bind_param('ssssssssssssss', $username, $password, $random_salt, $firstName, $lastName, $address, $phone, $email, $stationCountry, $nationality, $dob, $remark, $passport, $passportExpiry);
                    
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