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
				<h1 class="m-0 text-dark">Quotation</h1>
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
            Filters
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-4">
                <div class="form-group">
                  <label for="filterCustomerName">Customer Name</label>
                  <select class="form-control" style="width: 100%;" id="filterCustomerName" name="filterCustomerName">
                    <option value="" selected disabled hidden>Please Select</option>
                    <?php while($customersRow=mysqli_fetch_assoc($customers)){ ?>
                      <option value="<?=$customersRow['id'] ?>"><?=$customersRow['customer_name'] ?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>
            </div>
          </div><!-- /.card-body -->
        </div>
      </div>
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <div class="row">
                <div class="col-9"></div>
                <div class="col-3">
                  <button type="button" class="btn btn-block bg-gradient-warning btn-sm" id="addOrder">Create New Order</button>
                </div>
            </div>
          </div>
          <div class="card-body">
            <table id="tableforOrder" class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th>Order</th>
                  <th>Details</th>
                  <th>Status</th>
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
          <h4 class="modal-title">Create New Job Quotation</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="container-fluid">
            <div class="card card-primary">
              <div class="card-body">
                <div class="row">
                  <div class="col-4">
                    <div class="form-group">
                      <label for="inputHandler">Handler</label>
                      <select class="form-control" style="width: 100%;" id="inputHandler" name="inputHandler">
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
                      <select class="form-control" style="width: 100%;" id="inputCustomerName" name="inputCustomerName">
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
                        data-inputmask='"mask": "999-9999999"' data-mask>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-4">
                    <div class="form-group">
                      <label for="inputEmail">Email</label>
                      <input type="email" class="form-control" id="inputEmail" name="inputEmail" placeholder="Example: dummy@mail.com">
                    </div>
                  </div>
                  <div class="col-4">
                    <div class="form-group">
                      <label>Shipment Type</label>
                      <select id="inputShipmentType" name="inputShipmentType" class="form-control">
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
                        placeholder="Enter Notes (Internal)"></textarea>
                    </div>
                  </div>
                  <div class="col-6">
                    <div class="form-group">
                      <label>Notes to Customer</label>
                      <textarea id="inputNotestoCustomer" name="inputNotestoCustomer" class="form-control" rows="3"
                        placeholder="Enter Notes to Customer"></textarea>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="card card-primary">
              <div class="card-body">
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
                      <textarea id="inputAddress" name="inputDeliveryAddress" class="form-control" rows="3" placeholder="Enter Address"></textarea>
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
                  <h4>Job Quotation Shipment Informations</h4>
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
                    <div class="form-group">
                      <label>Different Piece Weight&Size</label>
                      <select id="checkboxSamePieceWeight" name="checkboxSamePieceWeight" class="form-control">
                        <option value="Y">Yes</option>
                        <option value="N" selected>No</option>
                      </select>
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
                  <div class="col-2" id="unitsCol">
                    <div class="form-group">
                      <label>Units</label>
                      <select id="inputUnit" name="inputUnit" class="form-control">
                        <option value="CM" selected>CM</option>
                        <option value="M">M</option>
                      </select>
                    </div>
                  </div>
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
                        Pickup Charge &nbsp&nbsp
                        <input type="checkbox" id="checkboxPickup" name="checkboxPickup">Customer RFQ</input>
                      </label>
                      <div class="input-group">
                        <div class="input-group-prepend">
                          <span class="input-group-text">USD</span>
                        </div>
                        <input type="number" class="form-control" id="inputPickupCharge" name="inputPickupCharge" placeholder="Enter Pickup Charges"/>
                      </div>
                    </div>
                  </div>
                  <div class="col-4">
                    <div class="form-group" id="exportClearance">
                      <label>
                        Export Clearances &nbsp&nbsp
                        <input type="checkbox" id="checkboxExport" name="checkboxExport">Customer RFQ</input>
                      </label>
                      <div class="input-group">
                        <div class="input-group-prepend">
                          <span class="input-group-text">USD</span>
                        </div>
                        <input type="number" class="form-control" id="inputExportClearances" name="inputExportClearances" placeholder="Enter Export Clearances">
                      </div>
                    </div>
                  </div>
                  <div class="col-4" id="airTicket">
                    <div class="form-group">
                      <label>
                        Air Ticket &nbsp&nbsp
                        <input type="checkbox" id="checkboxAir" name="checkboxAir">Customer RFQ</input>
                      </label>
                      <div class="input-group">
                        <div class="input-group-prepend">
                          <span class="input-group-text">USD</span>
                        </div>
                        <input type="number" class="form-control" id="inputAirTicket" name="inputAirTicket" placeholder="Enter Air Ticket">
                      </div>
                    </div>
                  </div>
                  <div class="col-4" id="flyersFee">
                    <div class="form-group">
                      <label>
                        Flyers Fee &nbsp&nbsp
                        <input type="checkbox" id="checkboxFlyers" name="checkboxFlyers">Customer RFQ</input>
                      </label>
                      <div class="input-group">
                        <div class="input-group-prepend">
                          <span class="input-group-text">USD</span>
                        </div>
                        <input type="number" class="form-control" id="inputFlyersFee" name="inputFlyersFee" placeholder="Enter Flyers Fee">
                      </div>
                    </div>
                  </div>
                  <div class="col-4" id="importClearance">
                    <div class="form-group">
                      <label>
                        Import Clearance &nbsp&nbsp
                        <input type="checkbox" id="checkboxImport" name="checkboxImport">Customer RFQ</input>
                      </label>
                      <div class="input-group">
                        <div class="input-group-prepend">
                          <span class="input-group-text">USD</span>
                        </div>
                        <input type="number" class="form-control" id="inputImportClearance" name="inputImportClearance" placeholder="Enter Import Clearance">
                      </div>
                    </div>
                  </div>
                  <div class="col-4" id="deliveryCharges">
                    <div class="form-group">
                      <label>
                        Delivery Charges &nbsp&nbsp
                        <input type="checkbox" id="checkboxDelivery" name="checkboxDelivery">Customer RFQ</input>
                      </label>
                      <div class="input-group">
                        <div class="input-group-prepend">
                          <span class="input-group-text">USD</span>
                        </div>
                        <input type="number" class="form-control" id="inputDeliveryCharges" name="inputDeliveryCharges" placeholder="Enter Delivery Charges">
                      </div>
                    </div>
                  </div>
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

<script>
var contentIndex = 0;
var size = $("#TableId").find(".details").length
var size2 = $("#shipmentlist").find(".details").length

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
      'url':'php/loadSales.php'
    },
    'columns': [
      { 
        data: 'id',
        render: function ( data, type, row ) {
          return order(row);
        }
      },
      { 
        data: 'id',
        render: function ( data, type, row ) {
          return details(row);
        }
      },
      { 
        data: 'id',
        render: function ( data, type, row ) {
          return status(row);
        }
      }
    ]       
  });

  $.validator.setDefaults({
    submitHandler: function () {
      if($('#orderModal').hasClass('show')){
        $('#spinnerLoading').show();
        $.post('php/insertSales.php', $('#orderForm').serialize(), function(data){
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

  $('#unitsCol').hide();

  $('#inputCargoReadyTime').datetimepicker({
    icons: { time: 'far fa-clock' },
    format: 'YYYY-MM-DD HH:mm:ss'
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
    $('#itemList').find('.TableId').remove();
    $('#itemList').find('.shipmentlist').remove();
    size2 = 0;
    size = 0;
    $("#newPieces").trigger('click');
    $('#newPieces').hide();
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

  $("#inputCustomerName").change(function(){
    var id = $("#inputCustomerName").val();

    $.post('php/getCustomer.php', {userID: id}, function(data){
      var obj = JSON.parse(data);
      
      if(obj.status === 'success'){
        $('#inputContactNum').val(obj.message.customer_phone);
        $('#inputEmail').val(obj.message.customer_email);
      }
      else if(obj.status === 'failed'){
          toastr["error"](obj.message, "Failed:");
      }
      else{
          toastr["error"]("Something wrong when activate", "Failed:");
      }
    });
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

  $('#checkboxSamePieceWeight').on('change', function () {
    if($(this).val() == 'Y'){
      $('#newPieces').show();
    }
    else{
      $('#newPieces').hide();
    }
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
    $("#shipmentlist").append('<input type="hidden" name="deletedShip[]" value="'+index+'"/>');
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

  $("#TableId").on('click', 'button[id^="remove"]', function () {
    var index = $(this).parents('.details').attr('data-index');
    $("#TableId").append('<input type="hidden" name="deleted[]" value="'+index+'"/>');
    $(this).parents('.details').remove();
  });

  $("#inputPickupCharge").on('change', function () {
    var inputPickupCharge = $("#inputPickupCharge").val() ? $("#inputPickupCharge").val() : 0.00;
    var inputExportClearances = $("#inputExportClearances").val() ? $("#inputExportClearances").val() : 0.00;
    var inputAirTicket = $("#inputAirTicket").val() ? $("#inputAirTicket").val() : 0.00;
    var inputFlyersFee = $("#inputFlyersFee").val() ? $("#inputFlyersFee").val() : 0.00;
    var inputImportClearance = $("#inputImportClearance").val() ? $("#inputImportClearance").val() : 0.00;
    var inputDeliveryCharges = $("#inputDeliveryCharges").val() ? $("#inputDeliveryCharges").val() : 0.00;

    var inputTotalCharges = calTotal(inputPickupCharge, inputExportClearances, inputAirTicket, inputFlyersFee, inputImportClearance, inputDeliveryCharges);
    $("#inputTotalCharges").val(inputTotalCharges);
  });

  $("#inputExportClearances").on('change', function () {
    var inputPickupCharge = $("#inputPickupCharge").val() ? $("#inputPickupCharge").val() : 0.00;
    var inputExportClearances = $("#inputExportClearances").val() ? $("#inputExportClearances").val() : 0.00;
    var inputAirTicket = $("#inputAirTicket").val() ? $("#inputAirTicket").val() : 0.00;
    var inputFlyersFee = $("#inputFlyersFee").val() ? $("#inputFlyersFee").val() : 0.00;
    var inputImportClearance = $("#inputImportClearance").val() ? $("#inputImportClearance").val() : 0.00;
    var inputDeliveryCharges = $("#inputDeliveryCharges").val() ? $("#inputDeliveryCharges").val() : 0.00;

    var inputTotalCharges = calTotal(inputPickupCharge, inputExportClearances, inputAirTicket, inputFlyersFee, inputImportClearance, inputDeliveryCharges);
    $("#inputTotalCharges").val(inputTotalCharges);
  });

  $("#inputAirTicket").on('change', function () {
    var inputPickupCharge = $("#inputPickupCharge").val() ? $("#inputPickupCharge").val() : 0.00;
    var inputExportClearances = $("#inputExportClearances").val() ? $("#inputExportClearances").val() : 0.00;
    var inputAirTicket = $("#inputAirTicket").val() ? $("#inputAirTicket").val() : 0.00;
    var inputFlyersFee = $("#inputFlyersFee").val() ? $("#inputFlyersFee").val() : 0.00;
    var inputImportClearance = $("#inputImportClearance").val() ? $("#inputImportClearance").val() : 0.00;
    var inputDeliveryCharges = $("#inputDeliveryCharges").val() ? $("#inputDeliveryCharges").val() : 0.00;

    var inputTotalCharges = calTotal(inputPickupCharge, inputExportClearances, inputAirTicket, inputFlyersFee, inputImportClearance, inputDeliveryCharges);
    $("#inputTotalCharges").val(inputTotalCharges);
  });

  $("#inputFlyersFee").on('change', function () {
    var inputPickupCharge = $("#inputPickupCharge").val() ? $("#inputPickupCharge").val() : 0.00;
    var inputExportClearances = $("#inputExportClearances").val() ? $("#inputExportClearances").val() : 0.00;
    var inputAirTicket = $("#inputAirTicket").val() ? $("#inputAirTicket").val() : 0.00;
    var inputFlyersFee = $("#inputFlyersFee").val() ? $("#inputFlyersFee").val() : 0.00;
    var inputImportClearance = $("#inputImportClearance").val() ? $("#inputImportClearance").val() : 0.00;
    var inputDeliveryCharges = $("#inputDeliveryCharges").val() ? $("#inputDeliveryCharges").val() : 0.00;

    var inputTotalCharges = calTotal(inputPickupCharge, inputExportClearances, inputAirTicket, inputFlyersFee, inputImportClearance, inputDeliveryCharges);
    $("#inputTotalCharges").val(inputTotalCharges);
  });

  $('#inputImportClearance').on('change', function () {
    var inputPickupCharge = $("#inputPickupCharge").val() ? $("#inputPickupCharge").val() : 0.00;
    var inputExportClearances = $("#inputExportClearances").val() ? $("#inputExportClearances").val() : 0.00;
    var inputAirTicket = $("#inputAirTicket").val() ? $("#inputAirTicket").val() : 0.00;
    var inputFlyersFee = $("#inputFlyersFee").val() ? $("#inputFlyersFee").val() : 0.00;
    var inputImportClearance = $("#inputImportClearance").val() ? $("#inputImportClearance").val() : 0.00;
    var inputDeliveryCharges = $("#inputDeliveryCharges").val() ? $("#inputDeliveryCharges").val() : 0.00;

    var inputTotalCharges = calTotal(inputPickupCharge, inputExportClearances, inputAirTicket, inputFlyersFee, inputImportClearance, inputDeliveryCharges);
    $("#inputTotalCharges").val(inputTotalCharges);
  });

  $('#inputDeliveryCharges').on('change', function () {
    var inputPickupCharge = $("#inputPickupCharge").val() ? $("#inputPickupCharge").val() : 0.00;
    var inputExportClearances = $("#inputExportClearances").val() ? $("#inputExportClearances").val() : 0.00;
    var inputAirTicket = $("#inputAirTicket").val() ? $("#inputAirTicket").val() : 0.00;
    var inputFlyersFee = $("#inputFlyersFee").val() ? $("#inputFlyersFee").val() : 0.00;
    var inputImportClearance = $("#inputImportClearance").val() ? $("#inputImportClearance").val() : 0.00;
    var inputDeliveryCharges = $("#inputDeliveryCharges").val() ? $("#inputDeliveryCharges").val() : 0.00;

    var inputTotalCharges = calTotal(inputPickupCharge, inputExportClearances, inputAirTicket, inputFlyersFee, inputImportClearance, inputDeliveryCharges);
    $("#inputTotalCharges").val(inputTotalCharges);
  });

  

  
});

function order(row) {
  var returnString = "";

  if(row.sales_no != null && row.sales_no != ''){
    returnString += '<div class="row"><div class="col-12">' + row.sales_no + '</div></div><br>';
  }
  else{
    returnString += '<div class="row"><div class="col-12">' + row.quotation_no + '</div></div><br>';
  }

  returnString += '<div class="row"><div class="col-8">Items</div><div class="col-4">Amount</div></div><hr>';

  if(row.pickup_charge != null && row.pickup_charge != ''){
    returnString += '<div class="row"><div class="col-8">Pickup Charges</div><div class="col-4">' + row.pickup_charge + '</div></div>';
  }

  if(row.export_clearances != null && row.export_clearances != ''){
    returnString += '<div class="row"><div class="col-8">Export Clearance</div><div class="col-4">' + row.export_clearances + '</div></div>';
  }

  if(row.air_ticket != null && row.air_ticket != ''){
    returnString += '<div class="row"><div class="col-8">Air Ticket</div><div class="col-4">' + row.air_ticket + '</div></div>';
  }

  if(row.flyers_fee != null && row.flyers_fee != ''){
    returnString += '<div class="row"><div class="col-8">Flyers Fee</div><div class="col-4">' + row.flyers_fee + '</div></div>';
  }

  if(row.import_clearance != null && row.import_clearance != ''){
    returnString += '<div class="row"><div class="col-8">Import Clearance</div><div class="col-4">' + row.import_clearance + '</div></div>';
  }

  if(row.delivery_charges != null && row.delivery_charges != ''){
    returnString += '<div class="row"><div class="col-8">Delivery Charges</div><div class="col-4">' + row.delivery_charges + '</div></div>';
  }

  returnString += '<hr><div class="row"><div class="col-8">Total Amount (USD)</div><div class="col-4">' + row.total_amount + '</div></div><hr>';

  return returnString;
}

function details(row) {
  return '<div class="row"><div class="col-12">Handler Name: ' + row.handled_by 
  + '</div></div><div class="row"><div class="col-12">Customer Name: ' + row.customer_name 
  + '</div></div><div class="row"><div class="col-12">Address: '+ row.customer_address 
  + '</div></div><div class="row"><div class="col-12">Contact Number: ' + row.contact_no 
  + '</div></div><div class="row"><div class="col-12">Email: ' + row.email 
  + '</div></div><div class="row"><div class="col-12">Shipment Type: '+ row.shipment_type 
  + '</div></div><div class="row"><div class="col-12">Route: '+ row.departure_airport + ' -> ' + row.destination_airport 
  + '</div></div><div class="row"><div class="col-12">Notes (Internal): ' + row.internal_notes 
  + '</div></div><div class="row"><div class="col-12">Notes to Customer: ' + row.customer_notes 
  + '</div></div>';
}

function status(row) {
  var returnString = '<h5>Status:</h5><p>Created at: ' + row.created_datetime +'</p><p>Quoted at: ' + row.quoted_datetime +'</p>';

  if(row.paid_datetime != null && row.paid_datetime != ''){
    returnString += '<p>Paid at: ' + row.paid_datetime +'</p>';
  }

  if(row.shipped_datetime != null && row.shipped_datetime != ''){
    returnString += '<p>Shipped at: ' + row.shipped_datetime +'</p>';
  }

  if(row.completed_datetime != null && row.completed_datetime != ''){
    returnString += '<p>Completed at: ' + row.completed_datetime +'</p>';
  }

  if(row.cancelled_datetime != null && row.cancelled_datetime != ''){
    returnString += '<p>Cancelled at: ' + row.cancelled_datetime +'</p>';
  }
    
  returnString += '<p><small>Status:</small></p>';
  returnString += '<div class="row"><div class="col-3"><button type="button" onclick="shipped('+
  row.id+')" class="btn btn-success btn-sm"><i class="fa fa-check-circle"></i></button></div><div class="col-3"><button type="button" onclick="printQuote('+
  row.id+')" class="btn btn-primary btn-sm"><i class="fas fa-file"></i></button></div><div class="col-3"><button type="button" onclick="cancel('+
  row.id+')" class="btn btn-danger btn-sm"><i class="fas fa fa-times"></i></button></div></div>';

  //returnString += '<h5>Files:</h5>';

  return returnString;
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

function printSO(row) {
  $('#spinnerLoading').show();
  $.post('php/generateSalesOrder.php', {salesID: row}, function(data){
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
</script>