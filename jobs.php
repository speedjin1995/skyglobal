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
  ')"><i class="fas fa-pen"></i></button></div><div class="col-3"><button type="button" class="btn btn-danger btn-sm" onclick="completed('+row.sale_id+
  ')"><i class="fas fa-check-circle"></i></button></div><div class="col-3"><button type="button" class="btn btn-info btn-sm" onclick="printQuote('+row.sale_id+
  ')"><i class="fas fa-print"></i></button></div><div class="col-3"><button type="button" class="btn btn-danger btn-sm" onclick="cancel('+row.sale_id+
  ')"><i class="fas fa-trash"></i></button></div></div></div></div>';
  
  returnString += '<hr><h5>Cargo Details:</h5><table style="width: 100%;"><thead><tr><th>Piece Densed Weight</th><th>Dimension Length (cm)</th><th>Dimension Width (cm)</th><th>Dimension Height (cm)</th><th>Volumetric Weight</th></tr></thead><tbody>';
  
  for(var i=0; i<weightData.length; i++){
    returnString += '<tr><td>'+weightData[i].Weight+' KG</td><td>'+weightData[i].L+'</td><td>'+weightData[i].W+'</td><td>'+weightData[i].H+'</td><td>'+calVolumetric(weightData[i].W, weightData[i].L, weightData[i].H, weightData[i].Unit).toString()+' KG</td></tr>';
  }
  
  returnString += '</tbody></table><br><div class="row"><div class="col-6"><p>Volumetric Weight: '+row.volumetric_weight+
  ' KG</p></div><div class="col-6"><p>Total Cargo Weight: '+row.total_cargo_weight+' KG</p></div></div>';

  returnString += '<hr><h5>Status:</h5><p>Created at: <br>' + row.created_datetime +'</p>';

  return returnString;
}

function edit(id){
  $('#spinnerLoading').show();
  $.post('php/getAirports.php', {userID: id}, function(data){
    var obj = JSON.parse(data);
    
    if(obj.status === 'success'){
      /*$('#addModal').find('#id').val(obj.message.id);
      $('#addModal').find('#name').val(obj.message.name);
      $('#addModal').find('#iata').val(obj.message.iata);
      $('#addModal').find('#icao').val(obj.message.icao);
      $('#addModal').modal('show');
      
      $('#customerForm').validate({
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
      });*/
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