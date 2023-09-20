<?php
## Database configuration
require_once 'db_connect.php';

## Read value
$draw = $_POST['draw'];
$row = $_POST['start'];
$rowperpage = $_POST['length']; // Rows display per page
$columnIndex = $_POST['order'][0]['column']; // Column index
$columnName = $_POST['columns'][$columnIndex]['data']; // Column name
$columnSortOrder = $_POST['order'][0]['dir']; // asc or desc
//$searchValue = mysqli_real_escape_string($db,$_POST['search']['value']); // Search value

## Search 
$searchQuery = " WHERE id != '0'";

if(isset($_POST['inputName']) && $_POST['inputName']!=null){
  $searchQuery.= " AND (userId like '%".$_POST['inputName']."%')";
}

if(isset($_POST['inputStartTime']) && $_POST['inputStartTime']!=null){
  $searchQuery.= " AND (created_dateTime >= '".$_POST['inputStartTime']."')";
}

if(isset($_POST['inputEndTime']) && $_POST['inputEndTime']!=null){
  $searchQuery.= " AND (created_dateTime <= '".$_POST['inputEndTime']."')";
}

## Total number of records without filtering
$sel = mysqli_query($db,"select count(*) as logs from log");
$records = mysqli_fetch_assoc($sel);
$totalRecords = $records['logs'];

## Total number of record with filtering
$sel = mysqli_query($db,"select count(*) as logs from log".$searchQuery);
$records = mysqli_fetch_assoc($sel);
$totalRecordwithFilter = $records['logs'];

## Fetch records
$empQuery = "select * from log".$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;
//$empQuery = "select * from log WHERE id != '0'".$searchQuery." limit ".$row.",".$rowperpage;
$empRecords = mysqli_query($db, $empQuery);
$data = array();

while($row = mysqli_fetch_assoc($empRecords)) {
  $data[] = array( 
    "id"=>$row['id'],
    "userName"=>$row['userName'],
    "created_dateTime"=>$row['created_dateTime'],
    "action"=>$row['action']
  );
}

## Response
$response = array(
"draw" => intval($draw),
"iTotalRecords" => $totalRecords,
"iTotalDisplayRecords" => $totalRecordwithFilter,
"aaData" => $data,
"query" => $empQuery
);

echo json_encode($response);

?>