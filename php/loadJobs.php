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
$searchQuery = " WHERE job.sales_cart_id = sales_cart.id ";
//if($searchValue != ''){
  //$searchQuery = " and (customer_name like '%".$searchValue."%' or customer_code like '%".$searchValue."%')";
//}

## Total number of records without filtering
$sel = mysqli_query($db,"select count(*) as allcount from job");
$records = mysqli_fetch_assoc($sel);
$totalRecords = $records['allcount'];

## Total number of record with filtering
$sel = mysqli_query($db,"select count(*) as allcount from job, sales_cart".$searchQuery);
$records = mysqli_fetch_assoc($sel);
$totalRecordwithFilter = $records['allcount'];

## Fetch records
$empQuery = "select job.id, job.sales_cart_id, sales_cart.sale_id, sales_cart.departure_airport, sales_cart.destination_airport, sales_cart.weight_data, 
sales_cart.number_of_carton, sales_cart.volumetric_weight, sales_cart.total_cargo_weight, sales_cart.cargo_ready_time, sales_cart.pickup_address, 
sales_cart.pickup_pic, sales_cart.pickup_contact, sales_cart.pickup_email, sales_cart.delivery_address, sales_cart.delivery_pic, sales_cart.delivery_contact,
sales_cart.delivery_email, sales_cart.route, sales_cart.pickup_charge, sales_cart.export_clearances, sales_cart.air_ticket, sales_cart.flyers_fee, 
sales_cart.import_clearance, sales_cart.delivery_charges, sales_cart.total_amount, job.created_datetime from job, sales_cart".$searchQuery." limit ".$row.",".$rowperpage;
$empRecords = mysqli_query($db, $empQuery);
$data = array();

while($row = mysqli_fetch_assoc($empRecords)) {
    $data[] = array( 
      "id"=>$row['id'],
      "sales_cart_id"=>$row['sales_cart_id'],
      "sale_id"=>$row['sale_id'],
      "departure_airport"=>$row['departure_airport'],
      "destination_airport"=>$row['destination_airport'],
      "weight_data"=>$row['weight_data'],
      "number_of_carton"=>$row['number_of_carton'],
      "volumetric_weight"=>$row['volumetric_weight'],
      "total_cargo_weight"=>$row['total_cargo_weight'],
      "cargo_ready_time"=>$row['cargo_ready_time'],
      "pickup_address"=>$row['pickup_address'],
      "pickup_pic"=>$row['pickup_pic'],
      "pickup_contact"=>$row['pickup_contact'],
      "pickup_email"=>$row['pickup_email'],
      "delivery_address"=>$row['delivery_address'],
      "delivery_pic"=>$row['delivery_pic'],
      "delivery_contact"=>$row['delivery_contact'],
      "delivery_email"=>$row['delivery_email'],
      "route"=>$row['route'],
      "pickup_charge"=>$row['pickup_charge'],
      "export_clearances"=>$row['export_clearances'],
      "air_ticket"=>$row['air_ticket'],
      "flyers_fee"=>$row['flyers_fee'],
      "import_clearance"=>$row['import_clearance'],
      "delivery_charges"=>$row['delivery_charges'],
      "total_amount"=>$row['total_amount'],
      "created_datetime"=>$row['created_datetime']
    );
}

## Response
$response = array(
  "draw" => intval($draw),
  "iTotalRecords" => $totalRecords,
  "iTotalDisplayRecords" => $totalRecordwithFilter,
  "aaData" => $data
);

echo json_encode($response);

?>