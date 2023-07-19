<?php
require_once "db_connect.php";

session_start();

if(isset($_POST['userID'])){
	$id = filter_input(INPUT_POST, 'userID', FILTER_SANITIZE_STRING);

    if ($update_stmt = $db->prepare("SELECT sales.quotation_no, sales.sales_no, sales.customer_name, sales.contact_no, sales.email, sales.total_amount, sales.customer_notes, 
    sales.internal_notes, sales.shipment_type, sales.handled_by, sales_cart.sale_id, sales_cart.departure_airport, sales_cart.destination_airport, sales_cart.weight_data, 
    sales_cart.number_of_carton, sales_cart.volumetric_weight, sales_cart.total_cargo_weight, sales_cart.cargo_ready_time, sales_cart.pickup_address, 
    sales_cart.pickup_pic, sales_cart.pickup_contact, sales_cart.pickup_email, sales_cart.delivery_address, sales_cart.delivery_pic, sales_cart.delivery_contact,
    sales_cart.delivery_email, sales_cart.route, sales_cart.pickup_charge, sales_cart.export_clearances, sales_cart.air_ticket, sales_cart.flyers_fee, 
    sales_cart.import_clearance, sales_cart.delivery_charges, sales_cart.total_amount, sales_cart.id, sales_cart.extra_charges, sales_cart.flyers FROM sales, sales_cart 
    WHERE sales.id = sales_cart.sale_id AND sales.id=?")) {
        $update_stmt->bind_param('s', $id);
        
        // Execute the prepared query.
        if (! $update_stmt->execute()) {
            echo json_encode(
                array(
                    "status" => "failed",
                    "message" => "Something went wrong"
                )); 
        }
        else{
            $result = $update_stmt->get_result();
            $message = array();
            
            if ($row = $result->fetch_assoc()) {
                $message['quotation_no'] = $row['quotation_no'];
                $message['sales_no'] = $row['sales_no'];
                $message['customer_name'] = $row['customer_name'];
                $message['contact_no'] = $row['contact_no'];
                $message['email'] = $row['email'];
                $message['handled_by'] = $row['handled_by'];
                $message['customer_notes'] = $row['customer_notes'];
                $message['internal_notes'] = $row['internal_notes'];
                $message['shipment_type'] = $row['shipment_type'];
                $message['sale_id'] = $row['sale_id'];
                $message['departure_airport'] = $row['departure_airport'];
                $message['destination_airport'] = $row['destination_airport'];
                $message['weight_data'] = $row['weight_data'];
                $message['number_of_carton'] = $row['number_of_carton'];
                $message['volumetric_weight'] = $row['volumetric_weight'];
                $message['total_cargo_weight'] = $row['total_cargo_weight'];
                $message['cargo_ready_time'] = $row['cargo_ready_time'];
                $message['pickup_address'] = $row['pickup_address'];
                $message['pickup_pic'] = $row['pickup_pic'];
                $message['pickup_contact'] = $row['pickup_contact'];
                $message['pickup_email'] = $row['pickup_email'];
                $message['delivery_address'] = $row['delivery_address'];
                $message['delivery_pic'] = $row['delivery_pic'];
                $message['delivery_contact'] = $row['delivery_contact'];
                $message['delivery_email'] = $row['delivery_email'];
                $message['route'] = $row['route'];
                $message['pickup_charge'] = $row['pickup_charge'];
                $message['export_clearances'] = $row['export_clearances'];
                $message['air_ticket'] = $row['air_ticket'];
                $message['flyers_fee'] = $row['flyers_fee'];
                $message['import_clearance'] = $row['import_clearance'];
                $message['delivery_charges'] = $row['delivery_charges'];
                $message['extra_charges'] = $row['extra_charges'];
                $message['total_amount'] = $row['total_amount'];
                $message['id'] = $row['id'];
                $message['flyers'] = $row['flyers'];
            }
            
            echo json_encode(
                array(
                    "status" => "success",
                    "message" => $message
                ));   
        }
    }
}
else{
    echo json_encode(
        array(
            "status" => "failed",
            "message" => "Missing Attribute"
            )); 
}
?>