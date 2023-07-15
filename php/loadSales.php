<?php
## Database configuration
require_once 'db_connect.php';

## Read value
$draw = $_POST['draw'];
$row = $_POST['start'];
$rowperpage = $_POST['length']; // Rows display per page
//$columnIndex = $_POST['order'][0]['column']; // Column index
//$columnName = $_POST['columns'][$columnIndex]['data']; // Column name
//$columnSortOrder = $_POST['order'][0]['dir']; // asc or desc
$searchValue = mysqli_real_escape_string($db,$_POST['search']['value']); // Search value

## Search 
$searchQuery = " WHERE customers.id = sales.customer_name AND users.id = sales.handled_by";
//if($searchValue != ''){
  //$searchQuery = " and (customer_name like '%".$searchValue."%' or customer_code like '%".$searchValue."%')";
//}

## Total number of records without filtering
$sel = mysqli_query($db,"select count(*) as allcount from sales");
$records = mysqli_fetch_assoc($sel);
$totalRecords = $records['allcount'];

## Total number of record with filtering
$sel = mysqli_query($db,"select count(*) as allcount from sales, customers, users".$searchQuery);
$records = mysqli_fetch_assoc($sel);
$totalRecordwithFilter = $records['allcount'];

## Fetch records
$empQuery = "select sales.id, sales.quotation_no, sales.sales_no, customers.customer_name, sales.contact_no, sales.email, customers.customer_address, 
sales.total_amount, sales.customer_notes, sales.internal_notes, sales.shipment_type, sales.created_by, sales.created_datetime, sales.updated_by, 
sales.updated_datetime, users.name, sales.quoted_datetime, sales.paid_datetime, sales.shipped_datetime, sales.completed_datetime, 
sales.cancelled_datetime from sales, customers, users".$searchQuery." limit ".$row.",".$rowperpage;
$empRecords = mysqli_query($db, $empQuery);
$data = array();

while($row = mysqli_fetch_assoc($empRecords)) {
    $data[] = array( 
      "id"=>$row['id'],
      "quotation_no"=>$row['quotation_no'],
      "sales_no"=>$row['sales_no'],
      "customer_name"=>$row['customer_name'],
      "contact_no"=>$row['contact_no'],
      "email"=>$row['email'],
      "customer_address"=>$row['customer_address'],
      "total_amount"=>$row['total_amount'],
      "customer_notes"=>$row['customer_notes'],
      "internal_notes"=>$row['internal_notes'],
      "shipment_type"=>$row['shipment_type'],
      "created_by"=>$row['created_by'],
      "created_datetime"=>$row['created_datetime'],
      "updated_by"=>$row['updated_by'],
      "updated_datetime"=>$row['updated_datetime'],
      "handled_by"=>$row['name'],
      "quoted_datetime"=>$row['quoted_datetime'],
      "paid_datetime"=>$row['paid_datetime'],
      "shipped_datetime"=>$row['shipped_datetime'],
      "completed_datetime"=>$row['completed_datetime'],
      "cancelled_datetime"=>$row['cancelled_datetime']
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