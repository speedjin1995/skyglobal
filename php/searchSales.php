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
$searchQuery = " WHERE customers.id = sales.customer_name AND users.id = sales.handled_by AND sales_cart.sale_id = sales.id";
//if($searchValue != ''){
  //$searchQuery = " and (customer_name like '%".$searchValue."%' or customer_code like '%".$searchValue."%')";
//}


if(isset($_POST['inputName']) && $_POST['inputName']!=null){
  $searchQuery.= " AND (sales.customer_name like '%".$_POST['inputName']."%')";
}

if(isset($_POST['inputStartTime']) && $_POST['inputStartTime']!=null){
  $searchQuery.= " AND (sales.created_datetime >= '".$_POST['inputStartTime']."')";
}

if(isset($_POST['inputEndTime']) && $_POST['inputEndTime']!=null){
  $searchQuery.= " AND (sales.created_datetime <= '".$_POST['inputEndTime']."')";
}

## Total number of records without filtering
$sel = mysqli_query($db,"select count(*) as allcount from sales");
$records = mysqli_fetch_assoc($sel);
$totalRecords = $records['allcount'];

## Total number of record with filtering
$sel = mysqli_query($db,"select count(*) as allcount from sales, customers, users, sales_cart".$searchQuery);
$records = mysqli_fetch_assoc($sel);
$totalRecordwithFilter = $records['allcount'];

## Fetch records
$empQuery = "select sales.id, sales.quotation_no, sales.sales_no, customers.customer_name, sales.contact_no, sales.email, customers.customer_address, 
sales.total_amount, sales.customer_notes, sales.internal_notes, sales.shipment_type, sales.created_by, sales.created_datetime, sales.updated_by, 
sales.updated_datetime, users.name, sales.quoted_datetime, sales.paid_datetime, sales.shipped_datetime, sales.completed_datetime, sales.cancelled_datetime, 
sales_cart.departure_airport, sales_cart.destination_airport, sales_cart.pickup_charge, sales_cart.export_clearances, sales_cart.air_ticket, sales_cart.flyers_fee,
sales_cart.import_clearance, sales_cart.delivery_charges, sales_cart.total_amount, sales_cart.extra_charges from sales, customers, users, sales_cart".$searchQuery." limit ".$row.",".$rowperpage;
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
      "cancelled_datetime"=>$row['cancelled_datetime'],
      "departure_airport"=>$row['departure_airport'],
      "destination_airport"=>$row['destination_airport'],
      "pickup_charge"=>$row['pickup_charge'],
      "export_clearances"=>$row['export_clearances'],
      "air_ticket"=>$row['air_ticket'],
      "flyers_fee"=>$row['flyers_fee'],
      "import_clearance"=>$row['import_clearance'],
      "delivery_charges"=>$row['delivery_charges'],
      "extra_charges"=>$row['extra_charges'],
      "total_amount"=>$row['total_amount']
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