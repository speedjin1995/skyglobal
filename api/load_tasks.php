<?php
require_once 'db_connect.php';

$post = json_decode(file_get_contents('php://input'), true);

$userId=$post['userId'];

//$stmt = $db->prepare("SELECT * from weighing WHERE created_datetime >= ?");
$stmt = $db->prepare("SELECT job.id, job.accepted_by, job.handled_by, job.flight_no, job.log, job.created_datetime, sales.quotation_no, sales.customer_notes, 
sales.internal_notes, sales.shipment_type, customers.customer_name, sales_cart.departure_airport, sales_cart.destination_airport, sales_cart.number_of_carton, 
sales_cart.pickup_address, sales_cart.pickup_pic, sales_cart.pickup_contact, sales_cart.pickup_email, sales_cart.delivery_address, sales_cart.delivery_pic, 
sales_cart.delivery_contact, sales_cart.delivery_email, sales_cart.route from customers, job, sales, sales_cart WHERE customers.id = sales.customer_name AND 
sales_cart.flyers = ? AND sales.completed_datetime IS NULL AND sales.cancelled_datetime IS NULL AND job.sales_cart_id = sales_cart.id AND sales_cart.sale_id = sales.id");
$stmt->bind_param('s', $userId);
$stmt->execute();
$result = $stmt->get_result();
$message = array();

while($row = $result->fetch_assoc()){
	$message[] = array( 
        'id'=>$row['id'],
        'accepted_by'=>$row['accepted_by'],
        'handled_by'=>$row['handled_by'],
        'flight_no'=>$row['flight_no'],
        'log'=>json_decode($row['log'], true),
        'created_datetime'=>$row['created_datetime'],
        'quotation_no'=>$row['quotation_no'],
        'customer_notes'=>$row['customer_notes'],
        'internal_notes'=>$row['internal_notes'],
        'shipment_type'=>$row['shipment_type'],
        'customer_name'=>$row['customer_name'],
        'departure_airport'=>$row['departure_airport'],
        'destination_airport'=>$row['destination_airport'],
        'number_of_carton'=>$row['number_of_carton'],
        'pickup_address'=>$row['pickup_address'],
        'pickup_pic'=>$row['pickup_pic'],
        'pickup_contact'=>$row['pickup_contact'],
        'pickup_email'=>$row['pickup_email'],
        'delivery_address'=>$row['delivery_address'],
        'delivery_pic'=>$row['delivery_pic'],
        'delivery_contact'=>$row['delivery_contact'],
        'delivery_email'=>$row['delivery_email'],
        'route'=>json_decode($row['route'], true)
    );
}

$stmt->close();
$db->close();

echo json_encode(
    array(
        "status"=> "success", 
        "message"=> $message
    )
);
?>