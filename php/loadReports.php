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
$searchQuery = "";
//if($searchValue != ''){
  //$searchQuery = " and (customer_name like '%".$searchValue."%' or customer_code like '%".$searchValue."%')";
//}

## Total number of records without filtering
$sel = mysqli_query($db,"select count(*) as allcount from job");
$records = mysqli_fetch_assoc($sel);
$totalRecords = $records['allcount'];

## Total number of record with filtering
$sel = mysqli_query($db,"select count(*) as allcount from job".$searchQuery);
$records = mysqli_fetch_assoc($sel);
$totalRecordwithFilter = $records['allcount'];

## Fetch records
$empQuery = "select * from job".$searchQuery." limit ".$row.",".$rowperpage;
$empRecords = mysqli_query($db, $empQuery);
$data = array();

while($row = mysqli_fetch_assoc($empRecords)) {
  $purchases = array();
  $sales = array();
  $totalSales = 0;
  $totalPurchases = 0;

  // Sales Retrieval
  if($row['sales_cart_id']!=null && $row['sales_cart_id']!=''){
    $id = $row['sales_cart_id'];

    if ($update_stmt = $db->prepare("SELECT sales_cart.*, sales.total_amount FROM sales_cart, sales WHERE sales.id = sales_cart.sale_id AND sales_cart.id=?")) {
      $update_stmt->bind_param('s', $id);
      
      // Execute the prepared query.
      if ($update_stmt->execute()) {
        $result1 = $update_stmt->get_result();
        
        if ($row1 = $result1->fetch_assoc()) {
          $sales = json_decode($row1['extra_charges'], true);
          $totalSales = $row1['total_amount'];

          if($row1['pickup_charge'] != null && $row1['pickup_charge'] != '' && $row1['pickup_charge'] != '0.00'){
            $sales[] = array(
              'extraChargesName' => 'Pickup Charge',
              'extraChargesAmount' => $row1['pickup_charge'],
            );
          }

          if($row1['export_clearances'] != null && $row1['export_clearances'] != '' && $row1['export_clearances'] != '0.00'){
            $sales[] = array(
              'extraChargesName' => 'Export Clearances',
              'extraChargesAmount' => $row1['export_clearances'],
            );
          }

          if($row1['air_ticket'] != null && $row1['air_ticket'] != '' && $row1['air_ticket'] != '0.00'){
            $sales[] = array(
              'extraChargesName' => 'Air Ticket',
              'extraChargesAmount' => $row1['air_ticket'],
            );
          }

          if($row1['flyers_fee'] != null && $row1['flyers_fee'] != '' && $row1['flyers_fee'] != '0.00'){
            $sales[] = array(
              'extraChargesName' => 'Flyers Fees',
              'extraChargesAmount' => $row1['flyers_fee'],
            );
          }


          if($row1['import_clearance'] != null && $row1['import_clearance'] != '' && $row1['import_clearance'] != '0.00'){
            $sales[] = array(
              'extraChargesName' => 'Import Clearance',
              'extraChargesAmount' => $row1['import_clearance'],
            );
          }

          if($row1['delivery_charges'] != null && $row1['delivery_charges'] != '' && $row1['delivery_charges'] != '0.00'){
            $sales[] = array(
              'extraChargesName' => 'Delivery Charges',
              'extraChargesAmount' => $row1['delivery_charges'],
            );
          }
        }
      }
    }
  }

  // Purchases Retrieval
  if($row['job_no']!=null && $row['job_no']!=''){
    $id = $row['job_no'];

    if ($update_stmt2 = $db->prepare("SELECT * FROM purchases WHERE jobNo=?")) {
      $update_stmt2->bind_param('s', $id);
      
      // Execute the prepared query.
      if ($update_stmt2->execute()) {
        $result2 = $update_stmt2->get_result();
        
        if ($row2 = $result2->fetch_assoc()) {
          $purchases = json_decode($row2['items'], true);
          $totalPurchases = $row2['total'];
        }
      }
    }
  }

  $data[] = array( 
    "id"=>$row['id'],
    "job_no"=>$row['job_no'],
    "sales"=>$sales,
    "totalSales"=>$totalSales,
    "purchases"=>$purchases,
    "totalPurchases"=>$totalPurchases,
    "profit"=>number_format(((float)$totalSales - (float)$totalPurchases), 2, '.', '')
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