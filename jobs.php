<?php
require_once 'php/db_connect.php';

session_start();

if(!isset($_SESSION['userID'])){
  echo '<script type="text/javascript">';
  echo 'window.location.href = "login.html";</script>';
}
else{
  $user = $_SESSION['userID'];
  $users = $db->query("SELECT * FROM users WHERE deleted = '0'");
  $customers = $db->query("SELECT * FROM customers WHERE customer_status = '0'");
  $customers2 = $db->query("SELECT * FROM customers WHERE customer_status = '0'");
  $airport = $db->query("SELECT * FROM airport");
  $airport2 = $db->query("SELECT * FROM airport");
}
?>

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
			<div class="col-sm-6">
				<h1 class="m-0 text-dark">Jobs</h1>
			</div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<section class="content">
	<div class="container-fluid">
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <!--div class="row">
                <div class="col-9"></div>
                <div class="col-3">
                  <button type="button" class="btn btn-block bg-gradient-warning btn-sm" id="addOrder">Create New Jobs</button>
                </div>
            </div-->
          </div>
          <div class="card-body">
            <table id="tableforOrder" class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th>Number of Carton</th>
                  <th>Cargo Ready Time</th>
                  <th>Picked Up Address</th>
                  <th>Delivery Address</th>
                  <th>Total Amount</th>
                  <th></th>
                </tr>
              </thead>
            </table>
          </div><!-- /.card-body -->
        </div>
      </div>
    </div>
  </div>
</section><!-- /.content -->

<div class="modal fade" id="orderModal">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <form role="form" id="orderForm">
        <div class="modal-header">
          <h4 class="modal-title">Create New Mission Quotation</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="container-fluid">
            <div class="card card-primary">
              <div class="card-body">
                <input type="hidden" class="form-control" id="id" name="id">
                <input type="hidden" class="form-control" id="saleId" name="saleId">
                <div class="row">
                  <h4>Mission General Informations</h4>
                </div>
                <div class="row">
                  <div class="col-4">
                    <div class="form-group">
                      <label for="inputHandler">Handler</label>
                      <select class="form-control" style="width: 100%;" id="inputHandler" name="inputHandler" readonly>
                        <option value="" selected disabled hidden>Please Select</option>
                        <?php while($usersRow=mysqli_fetch_assoc($users)){ ?>
                          <option value="<?=$usersRow['id'] ?>"><?=$usersRow['name'] ?></option>
                        <?php } ?>
                      </select>
                    </div>
                  </div>
                  <div class="col-4">
                    <div class="form-group">
                      <label for="inputCustomerName">Customer Name</label>
                      <select class="form-control" style="width: 100%;" id="inputCustomerName" name="inputCustomerName" readonly>
                        <option value="" selected disabled hidden>Please Select</option>
                        <?php while($customers2Row=mysqli_fetch_assoc($customers2)){ ?>
                          <option value="<?=$customers2Row['id'] ?>"><?=$customers2Row['customer_name'] ?></option>
                        <?php } ?>
                      </select>
                    </div>
                  </div>
                  <div class="col-4">
                    <div class="form-group">
                      <label for="inputContactNum">Whatsapp Number</label>
                      <input type="text" class="form-control" id="inputContactNum" name="inputContactNum" placeholder="Example: 01X-1234567"
                        data-inputmask='"mask": "999-9999999"' data-mask readonly>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-4">
                    <div class="form-group">
                      <label for="inputEmail">Email</label>
                      <input type="email" class="form-control" id="inputEmail" name="inputEmail" placeholder="Example: dummy@mail.com" readonly>
                    </div>
                  </div>
                  <div class="col-4">
                    <div class="form-group">
                      <label>Shipment Type</label>
                      <select id="inputShipmentType" name="inputShipmentType" class="form-control" readonly>
                        <option value="" selected disabled hidden>Please Select</option>
                        <option value="Airport to airport">Airport to airport</option>
                        <option value="Door to door">Door to door</option>
                        <option value="Door to origin airport">Door to origin airport</option>
                        <option value="Door to destination airport">Door to destination airport</option>
                        <option value="Airport to door">Airport to door</option>
                        <option value="Origin Airport to door">Origin Airport to door</option>
                      </select>
                    </div>
                  </div>
                </div>
                
                <div class="row">
                  <div class="col-6">
                    <div class="form-group">
                      <label>Notes (Internal)</label>
                      <textarea id="inputNotesInternal" name="inputNotesInternal" class="form-control" rows="3"
                        placeholder="Enter Notes (Internal)" readonly></textarea>
                    </div>
                  </div>
                  <div class="col-6">
                    <div class="form-group">
                      <label>Notes to Customer</label>
                      <textarea id="inputNotestoCustomer" name="inputNotestoCustomer" class="form-control" rows="3"
                        placeholder="Enter Notes to Customer" readonly></textarea>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="card card-primary">
              <div class="card-body">
                <div class="row">
                  <h4>Mission Pickup & Delivery Informations</h4>
                </div>
                <div class="row">
                  <div class="col-3">
                    <div class="form-group">
                      <label>Pickup Company Name & Address (Shipper)</label>
                      <textarea id="inputPickupAddress" name="inputPickupAddress" class="form-control" rows="3" placeholder="Enter Address"></textarea>
                    </div>
                  </div>
                  <div class="col-3">
                    <div class="form-group">
                      <label for="inputEmail">Pickup Company PIC</label>
                      <input type="text" class="form-control" id="inputPickupName" name="inputPickupName" placeholder="Example: dummy@mail.com">
                    </div>
                  </div>
                  <div class="col-3">
                    <div class="form-group">
                      <label for="inputContactNum">Pickup Company PIC Phone</label>
                      <input type="text" class="form-control" id="inputPickupContactNum" name="inputPickupContactNum" placeholder="Example: 01X-1234567"
                        data-inputmask='"mask": "999-9999999"' data-mask>
                    </div>
                  </div>
                  <div class="col-3">
                    <div class="form-group">
                      <label for="inputEmail">Pickup Company PIC Email</label>
                      <input type="email" class="form-control" id="inputPickupEmail" name="inputPickupEmail" placeholder="Example: dummy@mail.com">
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-3">
                    <div class="form-group">
                      <label>Delivery Company Name & Address (Consignee)</label>
                      <textarea id="inputDeliveryAddress" name="inputDeliveryAddress" class="form-control" rows="3" placeholder="Enter Address"></textarea>
                    </div>
                  </div>
                  <div class="col-3">
                    <div class="form-group">
                      <label for="inputEmail">Delivery Company PIC</label>
                      <input type="text" class="form-control" id="inputDeliveryName" name="inputDeliveryName" placeholder="Example: dummy@mail.com">
                    </div>
                  </div>
                  <div class="col-3">
                    <div class="form-group">
                      <label for="inputContactNum">Delivery Company PIC Phone</label>
                      <input type="text" class="form-control" id="inputDeliveryContactNum" name="inputDeliveryContactNum" placeholder="Example: 01X-1234567"
                        data-inputmask='"mask": "999-9999999"' data-mask>
                    </div>
                  </div>
                  <div class="col-3">
                    <div class="form-group">
                      <label for="inputEmail">Delivery Company PIC Email</label>
                      <input type="email" class="form-control" id="inputDeliveryEmail" name="inputDeliveryEmail" placeholder="Example: dummy@mail.com">
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="card card-primary">
              <div class="card-body" id="itemList">
                <div class="row">
                  <h4>Mission Shipment Informations</h4>
                  <!--button style="margin-left:auto;margin-right: 25px;" type="button" class="btn btn-primary add-row">Add Shipment</button-->
                </div>
                <div class="row">
                  <div class="col-3">
                    <div class="form-group">
                      <label>Cargo Ready Time</label>
                      <div class="input-group date" id="inputCargoReadyTime" data-target-input="nearest">
                        <input type="text" class="form-control datetimepicker-input" id="cargoReadyTime" name="cargoReadyTime" data-target="#inputCargoReadyTime" />
                        <div class="input-group-append" data-target="#inputCargoReadyTime" data-toggle="datetimepicker">
                          <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-3">
                    <div class="form-group">
                      <label>Number of Carton</label>
                      <input id="inputNumberofCarton" name="inputNumberofCarton" type="number" class="form-control" value="1">
                    </div>
                  </div>
                  <div class="col-3">
                    <button style="margin-left:auto;margin-right: 25px;" type="button" class="btn btn-primary new-pieces" id="newPieces">Add New Pieces</button>
                  </div>
                </div>
                <div class="row">
                  <table style="width: 100%;">
                    <thead>
                      <tr>
                        <th>Piece Densed Weight</th>
                        <th>Dimension Length (cm)</th>
                        <th>Dimension Width (cm)</th>
                        <th>Dimension Height (cm)</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody id="shipmentlist"></tbody>
                  </table>
                </div>
                <div class="row">
                  <div class="col-6">
                    <div class="form-group">
                      <label>Total Volumetric Weight</label>
                      <div class="input-group mb-3">
                        <input id="inputVolumetricWeight" name="inputVolumetricWeight" type="number" class="form-control" value="0.00" readonly>
                        <div class="input-group-append">
                          <span class="input-group-text">kg</span>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-6">
                    <div class="form-group">
                      <label>Total Densed Weight</label>
                      <div class="input-group mb-3">
                        <input id="inputTotalCartonWeight" name="inputTotalCartonWeight" type="number" class="form-control"  value="0.00" readonly>
                        <div class="input-group-append">
                          <span class="input-group-text">kg</span>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="card card-primary">
              <div class="card-body">
                <div class="row">
                  <h4>Job Quotation Route Informations</h4>
                  <button style="margin-left:auto;margin-right: 25px;" type="button" class="btn btn-primary add-row">Add Route</button>
                </div>
                <table style="width: 100%;">
                  <thead>
                    <tr>
                      <th>Route</th>
                      <th>Departure</th>
                      <th>Departure Time</th>
                      <th>Arrival</th>
                      <th>Arrival time</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody id="TableId"></tbody>
                </table>
              </div>
            </div>
            <div class="card card-primary">
              <div class="card-body">
                <div class="row">
                  <div class="col-4">
                    <div class="form-group" id="pickupCharges">
                      <label>
                        Pickup Charge
                      </label>
                      <div class="input-group">
                        <div class="input-group-prepend">
                          <span class="input-group-text">USD</span>
                        </div>
                        <input type="number" class="form-control" id="inputPickupCharge" name="inputPickupCharge" placeholder="Enter Pickup Charges" readonly/>
                      </div>
                    </div>
                  </div>
                  <div class="col-4">
                    <div class="form-group" id="exportClearance">
                      <label>
                        Export Clearances
                      </label>
                      <div class="input-group">
                        <div class="input-group-prepend">
                          <span class="input-group-text">USD</span>
                        </div>
                        <input type="number" class="form-control" id="inputExportClearances" name="inputExportClearances" placeholder="Enter Export Clearances" readonly/>
                      </div>
                    </div>
                  </div>
                  <div class="col-4" id="airTicket">
                    <div class="form-group">
                      <label>
                        Air Ticket
                      </label>
                      <div class="input-group">
                        <div class="input-group-prepend">
                          <span class="input-group-text">USD</span>
                        </div>
                        <input type="number" class="form-control" id="inputAirTicket" name="inputAirTicket" placeholder="Enter Air Ticket" readonly/>
                      </div>
                    </div>
                  </div>
                  <div class="col-4" id="flyersFee">
                    <div class="form-group">
                      <label>
                        Flyers Fee 
                      </label>
                      <div class="input-group">
                        <div class="input-group-prepend">
                          <span class="input-group-text">USD</span>
                        </div>
                        <input type="number" class="form-control" id="inputFlyersFee" name="inputFlyersFee" placeholder="Enter Flyers Fee" readonly/>
                      </div>
                    </div>
                  </div>
                  <div class="col-4" id="importClearance">
                    <div class="form-group">
                      <label>
                        Import Clearance 
                      </label>
                      <div class="input-group">
                        <div class="input-group-prepend">
                          <span class="input-group-text">USD</span>
                        </div>
                        <input type="number" class="form-control" id="inputImportClearance" name="inputImportClearance" placeholder="Enter Import Clearance" readonly/>
                      </div>
                    </div>
                  </div>
                  <div class="col-4" id="deliveryCharges">
                    <div class="form-group">
                      <label>
                        Delivery Charges 
                      </label>
                      <div class="input-group">
                        <div class="input-group-prepend">
                          <span class="input-group-text">USD</span>
                        </div>
                        <input type="number" class="form-control" id="inputDeliveryCharges" name="inputDeliveryCharges" placeholder="Enter Delivery Charges" readonly/>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="card card-primary">
              <div class="card-body">
                <div class="row">
                  <h4>Extra Charges</h4>
                  <button style="margin-left:auto;margin-right: 25px;" type="button" class="btn btn-primary add-charges">Add Charges</button>
                </div>
                <table style="width: 100%;">
                  <thead>
                    <tr>
                      <th>Name</th>
                      <th>Price (USD)</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody id="chargesList"></tbody>
                </table>
              </div>
            </div>
                
            <div class="card card-primary">
              <div class="card-body">
                <div class="row">
                  <div class="col-4">
                    <div class="form-group">
                      <label>Total Charges</label>
                      <div class="input-group">
                        <div class="input-group-prepend">
                          <span class="input-group-text">USD</span>
                        </div>
                        <input type="text" class="form-control" id="inputTotalCharges" name="inputTotalCharges" placeholder="Enter Delivery Charges" readonly>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div><!-- /.container-fluid -->
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary" name="submit" id="submitOrder">Save Change</button>
        </div>
      </form>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>

<script type="text/html" id="addContents">
  <tr class="details">
    <td><input id="route" type="text" class="form-control" readonly></td>
    <td>
      <select id="departure" class="form-control">
        <option value="" selected disabled hidden>Please Select</option>
        <?php while($airportRow2=mysqli_fetch_assoc($airport2)){ ?>
          <option value="<?=$airportRow2['iata'] ?>"><?=$airportRow2['iata'] ?></option>
        <?php } ?>
      </select>
    </td>
    <td>
      <div class="input-group date" id="depatureTimePicker" data-target-input="nearest">
        <input type="text" class="form-control datetimepicker-input" id="depatureTime" data-target="#depatureTimePicker" />
        <div class="input-group-append" data-target="#depatureTimePicker" data-toggle="datetimepicker">
          <div class="input-group-text"><i class="fa fa-calendar"></i></div>
        </div>
      </div>
    </td>
    <td>
      <select id="arrival" class="form-control">
        <option value="" selected disabled hidden>Please Select</option>
        <?php while($airportRow=mysqli_fetch_assoc($airport)){ ?>
          <option value="<?=$airportRow['iata'] ?>"><?=$airportRow['iata'] ?></option>
        <?php } ?>
      </select>
    </td>
    <td>
      <div class="input-group date" id="arrivalTimePicker" data-target-input="nearest">
        <input type="text" class="form-control datetimepicker-input" id="arrivalTime" data-target="#arrivalTimePicker" />
        <div class="input-group-append" data-target="#arrivalTimePicker" data-toggle="datetimepicker">
          <div class="input-group-text"><i class="fa fa-calendar"></i></div>
        </div>
      </div>
    </td>
    <td><button class="btn btn-danger btn-sm" id="remove"><i class="fa fa-times"></i></button></td>
  </tr>
</script>

<script type="text/html" id="addShipments">
  <tr class="details">
    <td>
      <div class="input-group mb-3">
        <input id="inputCartonPiecesWeight" type="number" class="form-control">
        <div class="input-group-append">
          <span class="input-group-text">kg</span>
        </div>
      </div>
    </td>
    <td>
      <input id="inputDimensionW" type="text" class="form-control" placeholder="Enter ...">
    </td>
    <td>
      <input id="inputDimensionL" type="text" class="form-control" placeholder="Enter ...">
    </td>
    <td>
      <input id="inputDimensionH" type="text" class="form-control" placeholder="Enter ...">
    </td>
    <td><button class="btn btn-danger btn-sm" id="remove"><i class="fa fa-times"></i></button></td>
  </tr>
</script>

<script type="text/html" id="addContents3">
  <tr class="details">
    <td>
      <select id="extraChargesName" class="form-control" required>
        <option value="" selected disabled hidden>Please Select</option>
        <option value="Add On Pickup Charges">Add On Pickup Charges</option>
        <option value="Add On Export Clearances">Add On Export Clearances</option>
        <option value="Add On Air Ticket">Add On Air Ticket</option>
        <option value="Add On Flyers Fee">Add On Flyers Fee</option>
        <option value="Add On Import Clearance">Add On Import Clearance</option>
        <option value="Add On Delivery Charges">Add On Delivery Charges</option>
        <option value="Duty and Taxes">Duty and Taxes</option>
        <option value="Wrapping Charges">Wrapping Charges</option>
        <option value="Transportation">Transportation</option>
        <option value="Excess Baggage">Excess Baggage</option>
        <option value="Purchase Suitcase/Luggage Bag">Purchase Suitcase/Luggage Bag</option>
        <option value="Others">Others</option>
      </select>
    </td>
    <td><input type="number" id="extraChargesAmount" type="text" class="form-control" required></td>
    <td><button class="btn btn-danger btn-sm" id="remove"><i class="fa fa-times"></i></button></td>
  </tr>
</script>

<script>
var contentIndex = 0;
var size = $("#TableId").find(".details").length;
var size2 = $("#shipmentlist").find(".details").length;
var size3 = $("#chargesList").find(".details").length;

$(function () {
  var table = $("#tableforOrder").DataTable({
    "responsive": true,
    "autoWidth": false,
    'processing': true,
    'serverSide': true,
    'searching': false,
    'serverMethod': 'post',
    'ordering': false,
    'ajax': {
      'url':'php/loadJobs.php'
    },
    'columns': [
      { data: 'number_of_carton' },
      { data: 'cargo_ready_time' },
      { data: 'pickup_address' },
      { data: 'delivery_address' },
      { data: 'total_amount' },
      { 
        className: 'dt-control',
        orderable: false,
        data: null,
        render: function ( data, type, row ) {
          return '<td class="table-elipse" data-toggle="collapse" data-target="#demo'+row.id+'"><i class="fas fa-angle-down"></i></td>';
        }
      }
    ]       
  });

  $('#tableforOrder tbody').on('click', 'td.dt-control', function () {
    var tr = $(this).closest('tr');
    var row = table.row( tr );

    if ( row.child.isShown() ) {
      // This row is already open - close it
      row.child.hide();
      tr.removeClass('shown');
    }
    else {
      row.child( format(row.data()) ).show();tr.addClass("shown");
    }
  });

  $('#inputCargoReadyTime').datetimepicker({
    icons: { time: 'far fa-clock' },
    format: 'YYYY-MM-DD HH:mm:ss'
  });
  
  $('[data-mask]').inputmask();

  $.validator.setDefaults({
    submitHandler: function () {
      if($('#orderModal').hasClass('show')){
        $('#spinnerLoading').show();
        $.post('php/updateSales.php', $('#orderForm').serialize(), function(data){
          var obj = JSON.parse(data); 
          if(obj.status === 'success'){
            $('#orderModal').modal('hide');
            toastr["success"](obj.message, "Success:");
            $('#tableforOrder').DataTable().ajax.reload();
          }
          else if(obj.status === 'failed'){
            toastr["error"](obj.message, "Failed:");
          }
          else{
            toastr["error"]("Something wrong when edit", "Failed:");
          }

          $('#spinnerLoading').hide();
        });
      }
    }
  });

  $('#addOrder').on('click', function(){
    $('#orderModal').find('#id').val("");
    $('#orderModal').find('#inputHandler').val("<?=$user ?>");
    $('#orderModal').find('#inputCustomerName').val("");
    $('#orderModal').find('#inputContactNum').val("");
    $('#orderModal').find('#inputEmail').val("");
    $('#orderModal').find('#inputShipmentType').val("");
    $('#orderModal').find('#inputAddress').val("");
    $('#orderModal').find('#inputNotesInternal').val("");
    $('#orderModal').find('#inputNotestoCustomer').val("");
    $('#orderModal').find('#cargoReadyTime').val("");
    $('#orderModal').find('#inputPickupAddress').val("");
    $('#orderModal').find('#inputDimension').val("");
    $('#orderModal').find('#inputNumberofCarton').val("");
    $('#orderModal').find('#inputWeightofCarton').val("");
    $('#orderModal').find('#inputPickupCharge').val("0.00");
    $('#orderModal').find('#inputExportClearances').val("0.00");
    $('#orderModal').find('#inputAirTicket').val("0.00");
    $('#orderModal').find('#inputFlyersFee').val("0.00");
    $('#orderModal').find('#inputImportClearance').val("0.00");
    $('#orderModal').find('#inputDeliveryCharges').val("0.00");
    $('#orderModal').find('#inputTotalCharges').val("0.00");
    $('[data-mask]').inputmask();
    $('#orderModal').modal('show');
    
    $('#orderForm').validate({
      errorElement: 'span',
      errorPlacement: function (error, element) {
        error.addClass('invalid-feedback');
        element.closest('.form-group').append(error);
      },
      highlight: function (element, errorClass, validClass) {
        $(element).addClass('is-invalid');
      },
      unhighlight: function (element, errorClass, validClass) {
        $(element).removeClass('is-invalid');
      }
    });
  });

  $(".add-row").click(function(){
    var $addContents = $("#addContents").clone();
    $("#TableId").append($addContents.html());

    $("#TableId").find('.details:last').attr("id", "detail" + size);
    $("#TableId").find('.details:last').attr("data-index", size);
    $("#TableId").find('#remove:last').attr("id", "remove" + size);

    $("#TableId").find('#route:last').attr('name', 'route['+size+']').attr("id", "route" + size).val((size+1).toString());
    $("#TableId").find('#departure:last').attr('name', 'departure['+size+']').attr("id", "departure" + size);
    $("#TableId").find('#depatureTime:last').attr('name', 'depatureTime['+size+']').attr("id", "depatureTime" + size).attr("data-target", "#depatureTimePicker" + size);
    $("#TableId").find('#arrival:last').attr('name', 'arrival['+size+']').attr("id", "arrival" + size);
    $("#TableId").find('#arrivalTime:last').attr('name', 'arrivalTime['+size+']').attr("id", "arrivalTime" + size).attr("data-target", "#arrivalTimePicker" + size);
    $("#TableId").find('#depatureTimePicker:last').attr("id", "depatureTimePicker" + size);
    $("#TableId").find("#depatureTimePicker" + size).find('.input-group-append').attr("data-target", "#depatureTimePicker" + size);
    $("#TableId").find('#arrivalTimePicker:last').attr("id", "arrivalTimePicker" + size);
    $("#TableId").find("#arrivalTimePicker" + size).find('.input-group-append').attr("data-target", "#arrivalTimePicker" + size);

    $("#depatureTimePicker" + size).datetimepicker({
      icons: { time: 'far fa-clock' },
      format: 'YYYY-MM-DD HH:mm:ss'
    });

    $("#arrivalTimePicker" + size).datetimepicker({
      icons: { time: 'far fa-clock' },
      format: 'YYYY-MM-DD HH:mm:ss'
    });

    size++;
  });

  $("#newPieces").click(function(){
    if(parseInt($("#inputNumberofCarton").val()) > size2){
      var $addContents = $("#addShipments").clone();
      $("#shipmentlist").append($addContents.html());

      $("#shipmentlist").find('.details:last').attr("id", "detail" + size2);
      $("#shipmentlist").find('.details:last').attr("data-index", size2);
      $("#shipmentlist").find('#remove:last').attr("id", "remove" + size2);

      $("#shipmentlist").find('#inputCartonPiecesWeight:last').attr('name', 'inputCartonPiecesWeight['+size2+']').attr("id", "inputCartonPiecesWeight" + size2);
      $("#shipmentlist").find('#inputDimensionW:last').attr('name', 'inputDimensionW['+size2+']').attr("id", "inputDimensionW" + size2);
      $("#shipmentlist").find('#inputDimensionL:last').attr('name', 'inputDimensionL['+size2+']').attr("id", "inputDimensionL" + size2);
      $("#shipmentlist").find('#inputDimensionH:last').attr('name', 'inputDimensionH['+size2+']').attr("id", "inputDimensionH" + size2);

      size2++;
    }
  });

  $("#shipmentlist").on('click', 'button[id^="remove"]', function () {
    var index = $(this).parents('.details').attr('data-index');
    //$("#shipmentlist").append('<input type="hidden" name="deletedShip[]" value="'+index+'"/>');
    size2--;
    $(this).parents('.details').remove();
  });

  $("#shipmentlist").on('change', 'input[id^="inputDimensionW"]', function () {
    var inputDimensionW = $(this).parents('.details').find('input[id^="inputDimensionW"]').val() ? $(this).parents('.details').find('input[id^="inputDimensionW"]').val() : 0.00;
    var inputDimensionL = $(this).parents('.details').find('input[id^="inputDimensionL"]').val() ? $(this).parents('.details').find('input[id^="inputDimensionL"]').val() : 0.00;
    var inputDimensionH = $(this).parents('.details').find('input[id^="inputDimensionH"]').val() ? $(this).parents('.details').find('input[id^="inputDimensionH"]').val() : 0.00;
    var inputUnit = $("#inputUnit").val();
    var oldWeight = $("#inputVolumetricWeight").val();

    var inputTotalCharges = calVolumetric(inputDimensionW, inputDimensionL, inputDimensionH, inputUnit);

    if(inputTotalCharges > 0.00){
      var newWeight = parseFloat(oldWeight) + parseFloat(inputTotalCharges);
      $("#inputVolumetricWeight").val(parseFloat(newWeight).toFixed(2));
    }
  });

  $("#shipmentlist").on('change', 'input[id^="inputDimensionL"]', function () {
    var inputDimensionW = $(this).parents('.details').find('input[id^="inputDimensionW"]').val() ? $(this).parents('.details').find('input[id^="inputDimensionW"]').val() : 0.00;
    var inputDimensionL = $(this).parents('.details').find('input[id^="inputDimensionL"]').val() ? $(this).parents('.details').find('input[id^="inputDimensionL"]').val() : 0.00;
    var inputDimensionH = $(this).parents('.details').find('input[id^="inputDimensionH"]').val() ? $(this).parents('.details').find('input[id^="inputDimensionH"]').val() : 0.00;
    var inputUnit = $("#inputUnit").val();
    var oldWeight = $("#inputVolumetricWeight").val();

    var inputTotalCharges = calVolumetric(inputDimensionW, inputDimensionL, inputDimensionH, inputUnit);
    
    if(inputTotalCharges > 0.00){
      var newWeight = parseFloat(oldWeight) + parseFloat(inputTotalCharges);
      $("#inputVolumetricWeight").val(parseFloat(newWeight).toFixed(2));
    }
  });

  $("#shipmentlist").on('change', 'input[id^="inputDimensionH"]', function () {
    var inputDimensionW = $(this).parents('.details').find('input[id^="inputDimensionW"]').val() ? $(this).parents('.details').find('input[id^="inputDimensionW"]').val() : 0.00;
    var inputDimensionL = $(this).parents('.details').find('input[id^="inputDimensionL"]').val() ? $(this).parents('.details').find('input[id^="inputDimensionL"]').val() : 0.00;
    var inputDimensionH = $(this).parents('.details').find('input[id^="inputDimensionH"]').val() ? $(this).parents('.details').find('input[id^="inputDimensionH"]').val() : 0.00;
    var inputUnit = $("#inputUnit").val();
    var oldWeight = $("#inputVolumetricWeight").val();

    var inputTotalCharges = calVolumetric(inputDimensionW, inputDimensionL, inputDimensionH, inputUnit);

    if(inputTotalCharges > 0.00){
      var newWeight = parseFloat(oldWeight) + parseFloat(inputTotalCharges);
      $("#inputVolumetricWeight").val(parseFloat(newWeight).toFixed(2));
    }
  });

  $("#shipmentlist").on('change', 'input[id^="inputCartonPiecesWeight"]', function () {
    var inputCartonPiecesWeight = $(this).parents('.details').find('input[id^="inputCartonPiecesWeight"]').val() ? $(this).parents('.details').find('input[id^="inputCartonPiecesWeight"]').val() : 0.00;
    var inputTotalCharges = $('#inputTotalCartonWeight').val();
    var newWeight = parseFloat(inputTotalCharges) + parseFloat(inputCartonPiecesWeight);
    $("#inputTotalCartonWeight").val(parseFloat(newWeight).toFixed(2));
  });

  $(".add-charges").click(function(){
    var $addContents = $("#addContents3").clone();
    $("#chargesList").append($addContents.html());

    $("#chargesList").find('.details:last').attr("id", "detail" + size3);
    $("#chargesList").find('.details:last').attr("data-index", size3);
    $("#chargesList").find('#remove:last').attr("id", "remove" + size3);

    $("#chargesList").find('#extraChargesName:last').attr('name', 'extraChargesName['+size3+']').attr("id", "extraChargesName" + size3);
    $("#chargesList").find('#extraChargesAmount:last').attr('name', 'extraChargesAmount['+size3+']').attr("id", "extraChargesAmount" + size3);
    size3++;
  });

  $("#chargesList").on('click', 'button[id^="remove"]', function () {
    var index = $(this).parents('.details').attr('data-index');
    //$("#shipmentlist").append('<input type="hidden" name="deletedShip[]" value="'+index+'"/>');
    size3--;
    $(this).parents('.details').remove();
  });

  $("#chargesList").on('change', 'input[id^="extraChargesAmount"]', function () {
    var extraChargesAmount = $(this).parents('.details').find('input[id^="extraChargesAmount"]').val() ? $(this).parents('.details').find('input[id^="extraChargesAmount"]').val() : 0.00;
    var inputTotalCharges = $('#orderModal').find('#inputTotalCharges').val();
    var inputTotalCharges = parseFloat(inputTotalCharges) + parseFloat(extraChargesAmount);
    $('#inputTotalCharges').val(parseFloat(inputTotalCharges).toFixed(2))
  });

  $("#TableId").on('click', 'button[id^="remove"]', function () {
    var index = $(this).parents('.details').attr('data-index');
    size--;
    //$("#TableId").append('<input type="hidden" name="deleted[]" value="'+index+'"/>');
    $(this).parents('.details').remove();
  });

  $("#inputShipmentType").change(function(){
    if($('#inputShipmentType').val() == 'Airport to airport'){
      $('#inputAirTicket').attr('readonly', false);
      $('#inputFlyersFee').attr('readonly', false);
      $("#inputPickupCharge").attr('readonly', true);
      $('#inputExportClearances').attr('readonly', true);
      $('#inputImportClearance').attr('readonly', true);
      $('#inputDeliveryCharges').attr('readonly', true);
    }
    else if($('#inputShipmentType').val() == 'Door to origin airport'){
      $("#inputPickupCharge").attr('readonly', false);
      $('#inputExportClearances').attr('readonly', false);
      $('#inputAirTicket').attr('readonly', true);
      $('#inputFlyersFee').attr('readonly', true);
      $('#inputImportClearance').attr('readonly', true);
      $('#inputDeliveryCharges').attr('readonly', true);
    }
    else if($('#inputShipmentType').val() == 'Door to destination airport'){
      $("#inputPickupCharge").attr('readonly', false);
      $('#inputExportClearances').attr('readonly', false);
      $('#inputAirTicket').attr('readonly', false);
      $('#inputFlyersFee').attr('readonly', false);
      $('#inputImportClearance').attr('readonly', true);
      $('#inputDeliveryCharges').attr('readonly', true);
    }
    else if($('#inputShipmentType').val() == 'Airport to door'){
      $('#inputPickupCharge').attr('readonly', true);
      $('#inputExportClearances').attr('readonly', true);
      $('#inputAirTicket').attr('readonly', true);
      $('#inputFlyersFee').attr('readonly', true);
      $('#inputImportClearance').attr('readonly', false);
      $('#inputDeliveryCharges').attr('readonly', false);
    }
    else if($('#inputShipmentType').val() == 'Origin Airport to door'){
      $('#inputPickupCharge').attr('readonly', true);
      $('#inputExportClearances').attr('readonly', true);
      $('#inputAirTicket').attr('readonly', false);
      $('#inputFlyersFee').attr('readonly', false);
      $('#inputImportClearance').attr('readonly', false);
      $('#inputDeliveryCharges').attr('readonly', false);
    }
  });

  $("#inputPickupCharge").on('change', function () {
    var inputTotalCharges = calTotal($("#inputPickupCharge").val(), $("#inputExportClearances").val(), $("#inputAirTicket").val(), $("#inputFlyersFee").val(), $("#inputImportClearance").val(), $("#inputDeliveryCharges").val());
    $('#orderModal').find('#inputTotalCharges').val(inputTotalCharges);
  });

  $("#inputExportClearances").on('change', function () {
    var inputTotalCharges = calTotal($("#inputPickupCharge").val(), $("#inputExportClearances").val(), $("#inputAirTicket").val(), $("#inputFlyersFee").val(), $("#inputImportClearance").val(), $("#inputDeliveryCharges").val());
    $('#orderModal').find('#inputTotalCharges').val(inputTotalCharges);
  });

  $("#inputAirTicket").on('change', function () {
    var inputTotalCharges = calTotal($("#inputPickupCharge").val(), $("#inputExportClearances").val(), $("#inputAirTicket").val(), $("#inputFlyersFee").val(), $("#inputImportClearance").val(), $("#inputDeliveryCharges").val());
    $('#orderModal').find('#inputTotalCharges').val(inputTotalCharges);
  });

  $("#inputFlyersFee").on('change', function () {
    var inputTotalCharges = calTotal($("#inputPickupCharge").val(), $("#inputExportClearances").val(), $("#inputAirTicket").val(), $("#inputFlyersFee").val(), $("#inputImportClearance").val(), $("#inputDeliveryCharges").val());
    $('#orderModal').find('#inputTotalCharges').val(inputTotalCharges);
  });

  $('#inputImportClearance').on('change', function () {
    var inputTotalCharges = calTotal($("#inputPickupCharge").val(), $("#inputExportClearances").val(), $("#inputAirTicket").val(), $("#inputFlyersFee").val(), $("#inputImportClearance").val(), $("#inputDeliveryCharges").val());
    $('#orderModal').find('#inputTotalCharges').val(inputTotalCharges);
  });

  $('#inputDeliveryCharges').on('change', function () {
    var inputTotalCharges = calTotal($("#inputPickupCharge").val(), $("#inputExportClearances").val(), $("#inputAirTicket").val(), $("#inputFlyersFee").val(), $("#inputImportClearance").val(), $("#inputDeliveryCharges").val());
    $('#orderModal').find('#inputTotalCharges').val(inputTotalCharges);
  });
});

function format (row) {
  var returnString = "";
  var weightData = JSON.parse(row.weight_data);
  var routeData = JSON.parse(row.route);

  returnString += '<div class="row"><div class="col-3"><p>Shipper Address: '+row.pickup_address+
  '</p></div><div class="col-3"><p>Shipper PIC: '+row.pickup_pic+
  '</p></div><div class="col-3"><p>Shipper PIC Contact: '+row.pickup_contact+
  '</p></div><div class="col-3"><p>Shipper PIC Email: '+row.pickup_email+
  '</p></div><div class="col-3"><p>Receiver Address: '+row.delivery_address+
  '</p></div><div class="col-3"><p>Receiver PIC: '+row.delivery_pic+
  '</p></div><div class="col-3"><p>Receiver PIC Contact: '+row.delivery_contact+
  '</p></div><div class="col-3"><p>Receiver PIC Email: '+row.delivery_email+
  '</p></div><div class="col-3"><p>Number of Cartons: '+row.number_of_carton+
  '</p></div><div class="col-3"><p>Cargo Ready Time: '+row.cargo_ready_time+
  '</p></div><div class="col-3"><p>Weight ('+((parseFloat(row.volumetric_weight) > parseFloat(row.total_cargo_weight)) ? 'Volumetric': 'Total Carton')+
  '): '+((parseFloat(row.volumetric_weight) > parseFloat(row.total_cargo_weight)) ? row.volumetric_weight: row.total_cargo_weight)+'</p></div><div class="col-md-3"><div class="row"><div class="col-3"><button type="button" class="btn btn-warning btn-sm" onclick="edit('+row.sale_id+
  ')"><i class="fas fa-pen"></i></button></div><div class="col-3"><button type="button" class="btn btn-success btn-sm" onclick="completed('+row.sale_id+
  ')"><i class="fas fa-check-circle"></i></button></div><div class="col-3"><button type="button" class="btn btn-info btn-sm" onclick="printQuote('+row.sale_id+
  ')"><i class="fas fa-print"></i></button></div><div class="col-3"><button type="button" class="btn btn-danger btn-sm" onclick="cancel('+row.sale_id+
  ')"><i class="fas fa-trash"></i></button></div></div></div></div>';
  
  returnString += '<hr><h5>Cargo Details:</h5><table style="width: 100%;"><thead><tr><th>Piece Densed Weight</th><th>Dimension Length (cm)</th><th>Dimension Width (cm)</th><th>Dimension Height (cm)</th><th>Volumetric Weight</th></tr></thead><tbody>';
  
  for(var i=0; i<weightData.length; i++){
    returnString += '<tr><td>'+weightData[i].Weight+' KG</td><td>'+weightData[i].L+'</td><td>'+weightData[i].W+'</td><td>'+weightData[i].H+'</td><td>'+calVolumetric(weightData[i].W, weightData[i].L, weightData[i].H, weightData[i].Unit).toString()+' KG</td></tr>';
  }
  
  returnString += '</tbody></table><br><div class="row"><div class="col-6"><p>Volumetric Weight: '+row.volumetric_weight+
  ' KG</p></div><div class="col-6"><p>Total Cargo Weight: '+row.total_cargo_weight+' KG</p></div></div>';

  returnString += '<hr><div class="row"><div class="col-3"><h5>Status:</h5></div><div class="col-7"><select id="updateStatusDd" class="form-control"><option value="" selected disabled hidden>Please Select</option><option value="Cargo collected">Cargo collected</option><option value="Cargo received by courier">Cargo received by courier</option><option value="Courier check-in cargo">Courier check-in cargo</option><option value="Customs clearance completed">Customs clearance completed</option><option value="Baggage confirmed on board">Baggage confirmed on board</option><option value="Courier boarded">Courier boarded</option><option value="Courier arrived">Courier arrived</option><option value="Cargo retrieved">Cargo retrieved</option><option value="Customs cleared">Customs cleared</option><option value="Courier has handed shipment">Courier has handed shipment</option></select></div><div class="col-2"><button type="button" class="btn btn-danger btn-sm" onclick="updateLog('+row.id+')">Update</button></div></div><hr>';
  returnString += '<p>Job created at ' + row.created_datetime +'</p>';

  if(row.log != null && row.log != ""){
    var logData = JSON.parse(row.log);

    for(var i=0; i<logData.length; i++){
      returnString += '<p>' + logData[i].status + ' at ' + logData[i].timestamp +'</p>';
    }
  }

  return returnString;
}

function updateLog(id){
  $('#spinnerLoading').show();
  var status = $('#updateStatusDd').val();

  $.post('php/updateLog.php', {jobID: id, jobStatus: status}, function(data){
    var obj = JSON.parse(data); 
    
    if(obj.status === 'success'){
      toastr["success"](obj.message, "Success:");
      $('#tableforOrder').DataTable().ajax.reload();
    }
    else if(obj.status === 'failed'){
      toastr["error"](obj.message, "Failed:");
    }
    else{
      toastr["error"]("Something wrong when edit", "Failed:");
    }

    $('#spinnerLoading').hide();
  });
}

function edit(id){
  $('#spinnerLoading').show();
  $.post('php/getSales.php', {userID: id}, function(data){
    var obj = JSON.parse(data);
    
    if(obj.status === 'success'){
      // Initialised
      $('#orderModal').find('.TableId').remove();
      $('#itemList').find('.shipmentlist').remove();
      size3 = 0;
      size2 = 0;
      size = 0;

      // General and shipments
      $('#orderModal').find('#id').val(obj.message.id);
      $('#orderModal').find('#saleId').val(obj.message.sale_id);
      $('#orderModal').find('#inputHandler').val(obj.message.handled_by);
      $('#orderModal').find('#inputCustomerName').val(obj.message.customer_name);
      $('#orderModal').find('#inputContactNum').val(obj.message.contact_no);
      $('#orderModal').find('#inputEmail').val(obj.message.email);
      $('#orderModal').find('#inputShipmentType').val(obj.message.shipment_type);
      $('#orderModal').find('#inputNotesInternal').val(obj.message.internal_notes);
      $('#orderModal').find('#inputNotestoCustomer').val(obj.message.customer_notes);
      $('#orderModal').find('#inputPickupAddress').val(obj.message.pickup_address);
      $('#orderModal').find('#inputPickupName').val(obj.message.pickup_pic);
      $('#orderModal').find('#inputPickupContactNum').val(obj.message.pickup_contact);
      $('#orderModal').find('#inputPickupEmail').val(obj.message.pickup_email);
      $('#orderModal').find('#inputDeliveryAddress').val(obj.message.delivery_address);
      $('#orderModal').find('#inputDeliveryName').val(obj.message.delivery_pic);
      $('#orderModal').find('#inputDeliveryContactNum').val(obj.message.delivery_contact);
      $('#orderModal').find('#inputDeliveryEmail').val(obj.message.delivery_email);

      // Shipment Info
      $('#orderModal').find('#cargoReadyTime').val(obj.message.cargo_ready_time);
      $('#orderModal').find('#inputNumberofCarton').val(obj.message.number_of_carton);
      $('#orderModal').find('#inputVolumetricWeight').val(obj.message.volumetric_weight);
      $('#orderModal').find('#inputTotalCartonWeight').val(obj.message.total_cargo_weight);
      var weightData = JSON.parse(obj.message.weight_data);

      for(var i=0; i<weightData.length; i++){
        var $addContents = $("#addShipments").clone();
        $("#shipmentlist").append($addContents.html());

        $("#shipmentlist").find('.details:last').attr("id", "detail" + size2);
        $("#shipmentlist").find('.details:last').attr("data-index", size2);
        $("#shipmentlist").find('#remove:last').attr("id", "remove" + size2);

        $("#shipmentlist").find('#inputCartonPiecesWeight:last').attr('name', 'inputCartonPiecesWeight['+size2+']').attr("id", "inputCartonPiecesWeight" + size2).val(weightData[i].Weight);
        $("#shipmentlist").find('#inputDimensionW:last').attr('name', 'inputDimensionW['+size2+']').attr("id", "inputDimensionW" + size2).val(weightData[i].W);
        $("#shipmentlist").find('#inputDimensionL:last').attr('name', 'inputDimensionL['+size2+']').attr("id", "inputDimensionL" + size2).val(weightData[i].L);
        $("#shipmentlist").find('#inputDimensionH:last').attr('name', 'inputDimensionH['+size2+']').attr("id", "inputDimensionH" + size2).val(weightData[i].H);

        size2++;
      }

      // Route Info
      var routeData = JSON.parse(obj.message.route);

      for(var i=0; i<routeData.length; i++){
        var $addContents = $("#addContents").clone();
        $("#TableId").append($addContents.html());

        $("#TableId").find('.details:last').attr("id", "detail" + size);
        $("#TableId").find('.details:last').attr("data-index", size);
        $("#TableId").find('#remove:last').attr("id", "remove" + size);

        $("#TableId").find('#route:last').attr('name', 'route['+size+']').attr("id", "route" + size).val(routeData[i].route);
        $("#TableId").find('#departure:last').attr('name', 'departure['+size+']').attr("id", "departure" + size).val(routeData[i].departure);
        $("#TableId").find('#depatureTime:last').attr('name', 'depatureTime['+size+']').attr("id", "depatureTime" + size).attr("data-target", "#depatureTimePicker" + size).val(routeData[i].depatureTime);
        $("#TableId").find('#arrival:last').attr('name', 'arrival['+size+']').attr("id", "arrival" + size).val(routeData[i].arrival);
        $("#TableId").find('#arrivalTime:last').attr('name', 'arrivalTime['+size+']').attr("id", "arrivalTime" + size).attr("data-target", "#arrivalTimePicker" + size).val(routeData[i].arrivalTime);
        $("#TableId").find('#depatureTimePicker:last').attr("id", "depatureTimePicker" + size);
        $("#TableId").find("#depatureTimePicker" + size).find('.input-group-append').attr("data-target", "#depatureTimePicker" + size);
        $("#TableId").find('#arrivalTimePicker:last').attr("id", "arrivalTimePicker" + size);
        $("#TableId").find("#arrivalTimePicker" + size).find('.input-group-append').attr("data-target", "#arrivalTimePicker" + size);

        $("#depatureTimePicker" + size).datetimepicker({
          icons: { time: 'far fa-clock' },
          format: 'YYYY-MM-DD HH:mm:ss'
        });

        $("#arrivalTimePicker" + size).datetimepicker({
          icons: { time: 'far fa-clock' },
          format: 'YYYY-MM-DD HH:mm:ss'
        });

        size++;
      }

      // Pricing
      $('#orderModal').find('#inputPickupCharge').val(obj.message.pickup_charge);
      $('#orderModal').find('#inputExportClearances').val(obj.message.export_clearances);
      $('#orderModal').find('#inputAirTicket').val(obj.message.air_ticket);
      $('#orderModal').find('#inputFlyersFee').val(obj.message.flyers_fee);
      $('#orderModal').find('#inputImportClearance').val(obj.message.import_clearance);
      $('#orderModal').find('#inputDeliveryCharges').val(obj.message.delivery_charges);
      $('#orderModal').find('#inputTotalCharges').val(obj.message.total_amount);

      if(obj.message.extra_charges != null && obj.message.extra_charges != ''){
        var chargesData = JSON.parse(obj.message.extra_charges);

        for(var i=0; i<chargesData.length; i++){
          var $addContents = $("#addContents3").clone();
          $("#chargesList").append($addContents.html());

          $("#chargesList").find('.details:last').attr("id", "detail" + size3);
          $("#chargesList").find('.details:last').attr("data-index", size3);
          $("#chargesList").find('#remove:last').attr("id", "remove" + size3);

          $("#chargesList").find('#extraChargesName:last').attr('name', 'extraChargesName['+size3+']').attr("id", "extraChargesName" + size3).val(chargesData[i].extraChargesName);
          $("#chargesList").find('#extraChargesAmount:last').attr('name', 'extraChargesAmount['+size3+']').attr("id", "extraChargesAmount" + size3).val(chargesData[i].extraChargesAmount);
          size3++;
        }
      }

      $('#orderModal').modal('show');

      // Trigger Actions
      //$('#orderModal').find('#inputShipmentType').trigger('change');
      
      $('#orderForm').validate({
          errorElement: 'span',
          errorPlacement: function (error, element) {
              error.addClass('invalid-feedback');
              element.closest('.form-group').append(error);
          },
          highlight: function (element, errorClass, validClass) {
              $(element).addClass('is-invalid');
          },
          unhighlight: function (element, errorClass, validClass) {
              $(element).removeClass('is-invalid');
          }
      });
    }
    else if(obj.status === 'failed'){
        toastr["error"](obj.message, "Failed:");
    }
    else{
        toastr["error"]("Something wrong when activate", "Failed:");
    }
    $('#spinnerLoading').hide();
  });
}

function printQuote(row) {
  $('#spinnerLoading').show();
  $.post('php/generateQuo.php', {salesID: row}, function(data){
    var obj = JSON.parse(data); 
    
    if(obj.status === 'success'){
      var printWindow = window.open('', '', 'height=400,width=800');
      printWindow.document.write(obj.message);
      printWindow.document.close();
      setTimeout(function(){
          printWindow.print();
          printWindow.close();
      }, 1000);
    }
    else if(obj.status === 'failed'){
      toastr["error"](obj.message, "Failed:");
    }
    else{
      toastr["error"]("Something wrong when edit", "Failed:");
    }

    $('#spinnerLoading').hide();
  });
}

function cancel(id) {
  $('#spinnerLoading').show();
  $.post('php/cancelSales.php', {salesID: id}, function(data){
    var obj = JSON.parse(data); 
    
    if(obj.status === 'success'){
      toastr["success"](obj.message, "Success:");
      $('#tableforOrder').DataTable().ajax.reload();
    }
    else if(obj.status === 'failed'){
      toastr["error"](obj.message, "Failed:");
    }
    else{
      toastr["error"]("Something wrong when edit", "Failed:");
    }

    $('#spinnerLoading').hide();
  });
}

function paid(id) {
  $('#spinnerLoading').show();
  $.post('php/paidSales.php', {salesID: id}, function(data){
    var obj = JSON.parse(data); 
    
    if(obj.status === 'success'){
      toastr["success"](obj.message, "Success:");
      $('#tableforOrder').DataTable().ajax.reload();
    }
    else if(obj.status === 'failed'){
      toastr["error"](obj.message, "Failed:");
    }
    else{
      toastr["error"]("Something wrong when edit", "Failed:");
    }

    $('#spinnerLoading').hide();
  });
}

function shipped(id) {
  $('#spinnerLoading').show();
  $.post('php/shipSales.php', {salesID: id}, function(data){
    var obj = JSON.parse(data); 
    
    if(obj.status === 'success'){
      toastr["success"](obj.message, "Success:");
      $('#tableforOrder').DataTable().ajax.reload();
    }
    else if(obj.status === 'failed'){
      toastr["error"](obj.message, "Failed:");
    }
    else{
      toastr["error"]("Something wrong when edit", "Failed:");
    }

    $('#spinnerLoading').hide();
  });
}

function completed(id) {
  $('#spinnerLoading').show();
  $.post('php/completeSales.php', {salesID: id}, function(data){
    var obj = JSON.parse(data); 
    
    if(obj.status === 'success'){
      toastr["success"](obj.message, "Success:");
      $('#tableforOrder').DataTable().ajax.reload();
    }
    else if(obj.status === 'failed'){
      toastr["error"](obj.message, "Failed:");
    }
    else{
      toastr["error"]("Something wrong when edit", "Failed:");
    }

    $('#spinnerLoading').hide();
  });
}

</script>