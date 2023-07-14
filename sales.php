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
}
?>

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
			<div class="col-sm-6">
				<h1 class="m-0 text-dark">Sales</h1>
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
                      <label>Pickup Company Name & Address</label>
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
                      <label>Delivery Company Name & Address</label>
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
                <div style="margin-top:3%" class="row details">
                  <div class="col-4">
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
                  <div class="col-4">
                    <div class="form-group">
                      <label>Departure Airport</label>
                      <select id="inputDepAirport" name="inputDepAirport" class="form-control">
                        <option value="" selected disabled hidden>Please Select</option>
                        <option value="KUL">KUL</option>
                        <option value="NRT">NRT</option>
                        <option value="TOL">TOL</option>
                        <option value="DOH">DOH</option>
                        <option value="LON">LON</option>
                        <option value="LIS">LIS</option>
                      </select>
                    </div>
                  </div>
                  <div class="col-4">
                    <div class="form-group">
                      <label>Destination Airport</label>
                      <select id="inputDesAirport" name="inputDesAirport" class="form-control">
                        <option value="" selected disabled hidden>Please Select</option>
                        <option value="KUL">KUL</option>
                        <option value="NRT">NRT</option>
                        <option value="TOL">TOL</option>
                        <option value="DOH">DOH</option>
                        <option value="LON">LON</option>
                        <option value="LIS">LIS</option>
                      </select>
                    </div>
                  </div>
                  <!--div class="col-4">
                    <button class="btn btn-danger btn-sm" id="remove"><i class="fa fa-times"></i></button>
                  </div-->
                  <div class="col-2">
                    <div class="form-group">
                      <label>Dimension Width</label>
                      <input id="inputDimensionW" name="inputDimensionW" type="text" class="form-control" placeholder="Enter ...">
                    </div>
                  </div>
                  <div class="col-2">
                    <div class="form-group">
                      <label>Dimension Length</label>
                      <input id="inputDimensionL" name="inputDimensionL" type="text" class="form-control" placeholder="Enter ...">
                    </div>
                  </div>
                  <div class="col-2">
                    <div class="form-group">
                      <label>Dimension Height</label>
                      <input id="inputDimensionH" name="inputDimensionH" type="text" class="form-control" placeholder="Enter ...">
                    </div>
                  </div>
                  <div class="col-2">
                    <div class="form-group">
                      <label>Units</label>
                      <select id="inputUnit" name="inputUnit" class="form-control">
                        <option value="CM" selected>CM</option>
                        <option value="M">M</option>
                      </select>
                    </div>
                  </div>
                  <div class="col-4">
                    <div class="form-group">
                      <label>Volumetric Weight</label>
                      <div class="input-group mb-3">
                        <input id="inputVolumetricWeight" name="inputVolumetricWeight" type="number" class="form-control" value="0.00" readonly>
                        <div class="input-group-append">
                          <span class="input-group-text">kg</span>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-4">
                    <div class="form-group">
                      <label>Number of Carton</label>
                      <input id="inputNumberofCarton" name="inputNumberofCarton" type="number" class="form-control" value="0">
                    </div>
                  </div>
                  <div class="col-4">
                    <div class="form-group">
                      <label>
                        Carton Pieces Weight &nbsp&nbsp
                        <input type="checkbox" id="checkboxSamePieceWeight">Same Piece Weight</input>
                      </label>
                      <div class="input-group mb-3">
                        <input id="inputCartonPiecesWeight" name="inputCartonPiecesWeight" type="number" class="form-control">
                        <div class="input-group-append">
                          <span class="input-group-text">kg</span>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-4">
                    <div class="form-group">
                      <label>Total Carton Weight</label>
                      <div class="input-group mb-3">
                        <input id="inputTotalCartonWeight" name="inputTotalCartonWeight" type="number" class="form-control">
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
                <table>
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
              <div class="card-body" id="itemList">
                <div class="row">
                  <div class="col-4">
                    <div class="form-group" id="pickupCharges">
                      <label>
                        Pickup Charge &nbsp&nbsp
                        <input type="checkbox" id="checkboxPickup">Quote</input>
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
                        <input type="checkbox" id="checkboxExport">Quote</input>
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
                        <input type="checkbox" id="checkboxAir">Quote</input>
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
                        <input type="checkbox" id="checkboxFlyers">Quote</input>
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
                        <input type="checkbox" id="checkboxImport">Quote</input>
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
                        <input type="checkbox" id="checkboxDelivery">Quote</input>
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
        <option value="KUL">KUL</option>
        <option value="NRT">NRT</option>
        <option value="TOL">TOL</option>
        <option value="DOH">DOH</option>
        <option value="LON">LON</option>
        <option value="LIS">LIS</option>
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
        <option value="KUL">KUL</option>
        <option value="NRT">NRT</option>
        <option value="TOL">TOL</option>
        <option value="DOH">DOH</option>
        <option value="LON">LON</option>
        <option value="LIS">LIS</option>
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

<script>
var contentIndex = 0;
var size = $("#TableId").find(".details").length

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

  $('#inputDimensionW').on('change', function () {
    var inputDimensionW = $("#inputDimensionW").val() ? $("#inputDimensionW").val() : 0.00;
    var inputDimensionL = $("#inputDimensionL").val() ? $("#inputDimensionL").val() : 0.00;
    var inputDimensionH = $("#inputDimensionH").val() ? $("#inputDimensionH").val() : 0.00;
    var inputUnit = $("#inputUnit").val();

    var inputTotalCharges = calVolumetric(inputDimensionW, inputDimensionL, inputDimensionH, inputUnit);
    $("#inputVolumetricWeight").val(inputTotalCharges);
  });

  $('#inputDimensionL').on('change', function () {
    var inputDimensionW = $("#inputDimensionW").val() ? $("#inputDimensionW").val() : 0.00;
    var inputDimensionL = $("#inputDimensionL").val() ? $("#inputDimensionL").val() : 0.00;
    var inputDimensionH = $("#inputDimensionH").val() ? $("#inputDimensionH").val() : 0.00;
    var inputUnit = $("#inputUnit").val();

    var inputTotalCharges = calVolumetric(inputDimensionW, inputDimensionL, inputDimensionH, inputUnit);
    $("#inputVolumetricWeight").val(inputTotalCharges);
  });

  $('#inputDimensionH').on('change', function () {
    var inputDimensionW = $("#inputDimensionW").val() ? $("#inputDimensionW").val() : 0.00;
    var inputDimensionL = $("#inputDimensionL").val() ? $("#inputDimensionL").val() : 0.00;
    var inputDimensionH = $("#inputDimensionH").val() ? $("#inputDimensionH").val() : 0.00;
    var inputUnit = $("#inputUnit").val();

    var inputTotalCharges = calVolumetric(inputDimensionW, inputDimensionL, inputDimensionH, inputUnit);
    $("#inputVolumetricWeight").val(inputTotalCharges);
  });

  $('#inputUnit').on('change', function () {
    var inputDimensionW = $("#inputDimensionW").val() ? $("#inputDimensionW").val() : 0.00;
    var inputDimensionL = $("#inputDimensionL").val() ? $("#inputDimensionL").val() : 0.00;
    var inputDimensionH = $("#inputDimensionH").val() ? $("#inputDimensionH").val() : 0.00;
    var inputUnit = $("#inputUnit").val();

    var inputTotalCharges = calVolumetric(inputDimensionW, inputDimensionL, inputDimensionH, inputUnit);
    $("#inputVolumetricWeight").val(inputTotalCharges);
  });

  $('#inputNumberofCarton').on('change', function () {
    var inputNumberofCarton = $("#inputNumberofCarton").val() ? $("#inputNumberofCarton").val() : 0.00;
    var inputCartonPiecesWeight = $("#inputCartonPiecesWeight").val() ? $("#inputCartonPiecesWeight").val() : 0.00;

    if($('#checkboxSamePieceWeight').prop('checked')){
      var inputTotalCharges = calWeight(inputNumberofCarton, inputCartonPiecesWeight);
      $("#inputTotalCartonWeight").val(inputTotalCharges);
    }
  });

  $('#inputCartonPiecesWeight').on('change', function () {
    var inputNumberofCarton = $("#inputNumberofCarton").val() ? $("#inputNumberofCarton").val() : 0.00;
    var inputCartonPiecesWeight = $("#inputCartonPiecesWeight").val() ? $("#inputCartonPiecesWeight").val() : 0.00;

    if($('#checkboxSamePieceWeight').prop('checked')){
      var inputTotalCharges = calWeight(inputNumberofCarton, inputCartonPiecesWeight);
      $("#inputTotalCartonWeight").val(inputTotalCharges);
    }
  });
});

function order(row) {
  if(row.sales_no != null && row.sales_no != ''){
    return '<div class="row"><div class="col-12">' + row.sales_no + '</div></div>';
  }

  return '<div class="row"><div class="col-12">' + row.quotation_no + '</div></div>';
}

function details(row) {
  return '<div class="row"><div class="col-12">Handler Name: ' + row.handled_by 
  + '</div></div><div class="row"><div class="col-12">Customer Name: ' + row.customer_name 
  + '</div></div><div class="row"><div class="col-12">Contact Number: ' + row.contact_no 
  + '</div></div><div class="row"><div class="col-12">Email: ' + row.email 
  + '</div></div><div class="row"><div class="col-12">Shipment Type: '+ row.shipment_type 
  + '</div></div><div class="row"><div class="col-12">Address: '+ row.customer_address 
  + '</div></div><div class="row"><div class="col-12">Notes (Internal): ' + row.internal_notes 
  + '</div></div><div class="row"><div class="col-12">Notes to Customer: ' + row.customer_notes 
  + '</div></div>';
}

function status(row) {
  var returnString = '<h5>Status:</h5><p>Created at: <br>' + row.created_datetime +'</p><p>Quoted at: <br>' + row.quoted_datetime +'</p>';

  if(row.paid_datetime != null && row.paid_datetime != ''){
    returnString += '<p>Paid at: <br>' + row.paid_datetime +'</p>';
  }

  if(row.shipped_datetime != null && row.shipped_datetime != ''){
    returnString += '<p>Shipped at: <br>' + row.shipped_datetime +'</p>';
  }

  if(row.completed_datetime != null && row.completed_datetime != ''){
    returnString += '<p>Completed at: <br>' + row.completed_datetime +'</p>';
  }

  if(row.cancelled_datetime != null && row.cancelled_datetime != ''){
    returnString += '<p>Cancelled at: <br>' + row.cancelled_datetime +'</p>';
  }
    
  returnString += '<p><small>Status:</small></p>';
  returnString += '<div class="row"><div class="col-3"><button type="button" onclick="cancel('+
  row.id+')" class="btn btn-warning btn-sm"><i class="fas fa fa-times"></i></button></div><div class="col-3"><button type="button" onclick="paid('+
  row.id+')" class="btn btn-primary btn-sm"><i class="fa fa-credit-card"></i></button></div><div class="col-3"><button type="button" onclick="shipped('+
  row.id+')" class="btn btn-info btn-sm"><i class="fa fa-truck"></i></button></div><div class="col-3"><button type="button" onclick="completed('+
  row.id+')" class="btn btn-success btn-sm"><i class="fa fa-check-circle"></i></button></div></div>';
  
  returnString += '<p><small>Action:</small></p>';
  returnString += '<div class="row"><div class="col-3"><button type="button" onclick="printQuote('+
  row.id+')" class="btn btn-primary btn-sm"><i class="fas fa-file-contract"></i></button></div><div class="col-3"><button type="button" onclick="printSO('+
  row.id+')" class="btn btn-success btn-sm"><i class="fas fa-file"></i></button></div></div>';

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