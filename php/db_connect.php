<?php
date_default_timezone_set('Asia/Kuala_Lumpur');
$db = mysqli_connect("sql909.main-hosting.eu", "root", "root", "skyglobal");

if(mysqli_connect_errno()){
    echo 'Database connection failed with following errors: ' . mysqli_connect_error();
    die();
}
?>