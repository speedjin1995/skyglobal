<?php
## Database configuration
require_once 'db_connect.php';

## Read value
$draw = $_POST['draw'];
$row = $_POST['start'];
$rowperpage = $_POST['length']; 


## Fetch records
$sel = mysqli_query($db,"select count(*) as allcount from purchases");
$records = mysqli_fetch_assoc($sel);
$totalRecordwithFilter= $totalRecords = $records['allcount'];

$empQuery = "SELECT * FROM purchases limit ".$row.",".$rowperpage;
$empRecords = mysqli_query($db, $empQuery);
$data = array();

while($row = mysqli_fetch_assoc($empRecords)) {
    $data[] = array( 
      "id"=>$row['id'],
      "jobNo"=>$row['jobNo'],
      "total"=>$row['total'],
      "date"=>$row['date'],
      "items"=>$row['items'],
      "created_datetime"=>$row['created_datetime'],
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