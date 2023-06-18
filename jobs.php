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
            <div class="row">
                <div class="col-9"></div>
                <div class="col-3">
                  <button type="button" class="btn btn-block bg-gradient-warning btn-sm" id="addOrder">Create New Jobs</button>
                </div>
            </div>
          </div>
          <div class="card-body">
            <table id="tableforOrder" class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th>Dimension</th>
                  <th>Number of Carton</th>
                  <th>Weight of Cargo</th>
                  <th>Cargo Ready Time</th>
                  <th>Picked Up Address</th>
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
          <h4 class="modal-title">Create New Jobs</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
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
                <input type="text" class="form-control" id="inputCustomerName" name="inputCustomerName" placeholder="Enter Customer Name">
              </div>
            </div>
            <div class="col-4">
              <div class="form-group">
                <label for="inputContactNum">Contact Number</label>
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
            <div class="col-4">
              <div class="form-group">
                <label>Address</label>
                <textarea id="inputAddress" name="inputAddress" class="form-control" rows="3" placeholder="Enter Address"></textarea>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-4">
              <div class="form-group">
                <label>Pickup Address</label>
                <textarea id="inputPickupAddress" name="inputPickupAddress" class="form-control" rows="3" placeholder="Enter Pickup Address"></textarea>
              </div>
            </div>
            <div class="col-4">
              <div class="form-group">
                <label>Notes (Internal)</label>
                <textarea id="inputNotesInternal" name="inputNotesInternal" class="form-control" rows="3"
                  placeholder="Enter Notes (Internal)"></textarea>
              </div>
            </div>
            <div class="col-4">
              <div class="form-group">
                <label>Notes to Customer</label>
                <textarea id="inputNotestoCustomer" name="inputNotestoCustomer" class="form-control" rows="3"
                  placeholder="Enter Notes to Customer"></textarea>
              </div>
            </div>
          </div>
          <div class="row">
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
              <!-- text input -->
              <div class="form-group">
                <label>Dimension</label>
                <input id="inputDimension" name="inputDimension" type="text" class="form-control" placeholder="Enter ...">
              </div>
            </div>
            <div class="col-4">
              <div class="form-group">
                <label>Number of Carton</label>
                <input id="inputNumberofCarton" name="inputNumberofCarton" type="number" class="form-control" placeholder="Enter ...">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-4">
              <div class="form-group">
                <label>Weight of Carton</label>
                <div class="input-group mb-3">
                  <input id="inputWeightofCarton" name="inputWeightofCarton" type="number" class="form-control">
                  <div class="input-group-append">
                    <span class="input-group-text">kg</span>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-4">
              <div class="form-group" id="pickupCharges">
                <label>Pickup Charge</label>
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text">RM</span>
                  </div>
                  <input type="number" class="form-control" id="inputPickupCharge" name="inputPickupCharge" placeholder="Enter Pickup Charges">
                </div>
              </div>
            </div>
            <div class="col-4">
              <div class="form-group" id="exportClearance">
                <label>Export Clearances</label>
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text">RM</span>
                  </div>
                  <input type="number" class="form-control" name="inputExportClearances" id="inputExportClearances" placeholder="Enter Export Clearances">
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-4" id="airTicket">
              <div class="form-group">
                <label>Air Ticket</label>
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text">RM</span>
                  </div>
                  <input type="number" class="form-control" name="inputAirTicket" id="inputAirTicket" placeholder="Enter Air Ticket">
                </div>
              </div>
            </div>
            <div class="col-4" id="flyersFee">
              <div class="form-group">
                <label>Flyers Fee</label>
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text">RM</span>
                  </div>
                  <input type="number" class="form-control" name="inputFlyersFee" id="inputFlyersFee" placeholder="Enter Flyers Fee">
                </div>
              </div>
            </div>
            <div class="col-4" id="importClearance">
              <div class="form-group">
                <label>Import Clearance</label>
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text">RM</span>
                  </div>
                  <input type="number" class="form-control" name="inputImportClearance" id="inputImportClearance" placeholder="Enter Import Clearance">
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-4" id="deliveryCharges">
              <div class="form-group">
                <label>Delivery Charges</label>
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text">RM</span>
                  </div>
                  <input type="number" class="form-control" name="inputDeliveryCharges" id="inputDeliveryCharges" placeholder="Enter Delivery Charges">
                </div>
              </div>
            </div>
            <div class="col-4">
              <div class="form-group">
                <label>Total Charges</label>
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text">RM</span>
                  </div>
                  <input type="text" class="form-control" name="inputTotalCharges" id="inputTotalCharges" placeholder="Enter Delivery Charges" readonly>
                </div>
              </div>
            </div>
          </div>
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
      'url':'php/loadJobs.php'
    },
    'columns': [
      { data: 'dimension' },
      { data: 'number_of_carton' },
      { data: 'weight_of_cargo' },
      { data: 'cargo_ready_time' },
      { data: 'pickup_address' },
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
        $.post('php/insertJobs.php', $('#orderForm').serialize(), function(data){
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

  $("#inputShipmentType").on('change', function () {
    if($('#inputShipmentType').val() == 'Airport to airport'){
      $("#inputPickupCharge").attr('readonly', true);
      $('#inputAirTicket').attr('readonly', false);
      $('#inputFlyersFee').attr('readonly', false);
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
  return '<div class="row"><div class="col-md-3"><p>Pickup Charge: '+row.pickup_charge+
  '</p></div><div class="col-md-3"><p>Export Clearance: '+row.export_clearances+
  '</p></div><div class="col-md-3"><p>Air Ticket: '+row.air_ticket+
  '</p></div><div class="col-md-3"><p>Flyers Fee: '+row.flyers_fee+
  '</p></div></div><div class="row"><div class="col-md-3"><p>Import Clearance: '+row.import_clearance+
  '</p></div><div class="col-md-3"><p>Delivery Charges: '+row.delivery_charges+
  '</p></div><div class="col-md-3"><p>Total Amount: '+row.total_amount+
  '</p></div></div><hr><h5>Status:</h5><p>Created at: <br>' + row.created_datetime +'</p>';
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