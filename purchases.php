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
				<h1 class="m-0 text-dark">Purchases</h1>
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
                <button type="button" class="btn btn-block bg-gradient-warning btn-sm" id="addPurchase">Create New Purchase</button>
              </div>
            </div>
          </div>
          <div class="card-body">
            <table id="tableforPurchase" class="table table-bordered table-striped">
            <thead>
                <tr>
                  <th style="width: 10%;">Purchase Id</th>
                  <th style="width: 35%;">Job No.</th>
                  <th style="width: 10%;">Date</th>
                  <th style="width: 35%;">Items</th>
                  <th style="width: 10%;">Created Datetime</th>
                </tr>
              </thead>
            </table>
          </div><!-- /.card-body -->
        </div>
      </div>
    </div>
  </div>
</section><!-- /.content -->

<div class="modal fade" id="purchaseModal">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <form role="form" id="purchaseForm">
        <div class="modal-header">
          <h4 class="modal-title">Create Purchase</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="container-fluid">
            <div class="card card-primary">
              <div class="card-body">
                <!--<input type="hidden" class="form-control" id="id" name="id">
                <input type="hidden" class="form-control" id="purchaseId" name="purchaseId">--->
                <div class="row">
                  <h4>General Informations</h4>
                </div>
                <div class="row">
                  <div class="col-4">
                    <div class="form-group">
                      <label for="inputJobNo">Job Number</label>
                      <input type="text" class="form-control" id="inputJobNo" name="inputJobNo" placeholder=""
                        data-inputmask='' data-mask>
                    </div>
                  </div>
                  <div class="col-2">
                    <div class="form-group">
                       <button type="button" id="search" name="search" style="margin-top: 22%;" class="btn btn-block bg-gradient-warning btn-sm search" >Search</button>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-3">
                    <div class="form-group">
                      <label>Date</label>
                      <div class="input-group date" id="inputDate" data-target-input="nearest">
                        <input type="text" class="form-control datetimepicker-input" id="inputDate" name="inputDate" data-target="#inputDate" />
                        <div class="input-group-append" data-target="#inputDate" data-toggle="datetimepicker">
                          <div class="input-group-text"><i class="fa fa-calendar"></i></div>
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
                  <h4>Purchases Details</h4>
                  <button style="margin-left:auto;margin-right: 25px;" type="button" class="btn btn-primary add-row">Add Item</button>
                </div>
                <table style="width: 100%;">
                  <thead>
                    <tr>
                      <th>No.</th>
                      <th>Item</th>
                      <th>Price</th>
                    </tr>
                  </thead>
                  <tbody class="TableId" name="TableId" id="TableId"></tbody>
                </table>
              </div>
            </div>
          </div><!-- /.container-fluid -->
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary" name="submit" id="submitPurchase">Save Change</button>
        </div>
      </form>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>

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
                        <th>Delete</th>
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
                      <th>Delete</th>
                    </tr>
                  </thead>
                  <!--<tbody id="TableId"></tbody>--->
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



<script type="text/html" id="addContentsPurchase">
  <tr class="details">
    <td><input id="purchaseId" type="text" class="form-control purchaseItemRow" readonly></td>
    <td><input id="itemName" type="text" class="form-control purchaseItemRow"></td>
    <td><input type="number" class="form-control purchaseItemRow" id="itemPrice" name="itemPrice" placeholder="Enter Price"/></td>
    
    <td><button class="btn btn-danger btn-sm  purchaseItemRow" id="remove"><i class="fa fa-times"></i></button></td>
  </tr>
</script>

<script>
var contentIndex = 0;
var size = $("#TableId").find(".details").length;
var size2 = $("#shipmentlist").find(".details").length;
var size3 = $("#chargesList").find(".details").length;
var jobId = "";
var jobStatus = "";

$(function () {
  var table = $("#tableforPurchase").DataTable({
    "responsive": true,
    "autoWidth": false,
    'processing': true,
    'serverSide': true,
    'searching': false,
    'serverMethod': 'post',
    'ordering': false,
    'ajax': {
      'url':'php/loadPurchases.php'
    },
    'columns': [
      { 
        data: 'id',
        render: function ( data, type, row ) {
          return simplyShowId(row);
        }
      },
      { 
        data: 'id',
        render: function ( data, type, row ) {
          return simplyShowJobNo(row);
        }
      },
      { 
        data: 'id',
        render: function ( data, type, row ) {
          return simplyShowDate(row);
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
          return simplyShowCreatedDatetime(row);
        }
      }
    ]       
  });

  $('[data-mask]').inputmask();

  $.validator.setDefaults({
    submitHandler: function () {
      if($('#purchaseModal').hasClass('show')){
        $('#spinnerLoading').show();
        $.post('php/insertPurchase.php', $('#purchaseForm').serialize(), function(data){
          var obj = JSON.parse(data); 
          if(obj.status === 'success'){
            $('#purchaseModal').modal('hide');
            toastr["success"](obj.message, "Success:");
            $('#tableforPurchase').DataTable().ajax.reload();
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

  $(".search").click(function(){
    $('#spinnerLoading').show();

    var jobId = $('#purchaseModal').find('#inputJobNo').val();

    $('#purchaseModal').find('#search').attr("disabled", "disabled");
  

    $.post('php/getPurchases.php', {jobId: jobId}, function(data){
    var obj = JSON.parse(data);
    
    if(obj.status === 'success'){

      obj.message = obj.message.replace("\\\"", "\"");
      var items = JSON.parse(obj.message);


      for(var i=0; i<items.length; i++) {
        var $addContents = $("#addContentsPurchase").clone();
        $("#TableId").append($addContents.html());

        $("#TableId").find('.details:last').attr("id", "detail" + size);
        $("#TableId").find('.details:last').attr("data-index", size);
        $("#TableId").find('#remove:last').attr("id", "remove" + size);

        $("#TableId").find('#purchaseId:last').attr('name', 'purchaseId['+size+']').attr("id", "purchaseId" + size).val((size+1).toString());
        $("#TableId").find('#itemName:last').val(items[i].extraChargesName);
        $("#TableId").find('#itemName:last').attr('name', 'itemName['+size+']').attr("id", "itemName" + size);
        $("#TableId").find('#itemPrice:last').attr('name', 'itemPrice['+size+']').attr("id", "itemPrice" + size);

        size++;
      }
    }
    else if(obj.status === 'failed'){
        toastr["error"](obj.message, "Failed:");
    }
    else{
        toastr["error"]("Something wrong when activate", "Failed:");
    }
    
    });
    
    $('#spinnerLoading').hide();
  });

  $(".add-row").click(function(){
    var $addContents = $("#addContentsPurchase").clone();
    $("#TableId").append($addContents.html());

    $("#TableId").find('.details:last').attr("id", "detail" + size);
    $("#TableId").find('.details:last').attr("data-index", size);
    $("#TableId").find('#remove:last').attr("id", "remove" + size);

    $("#TableId").find('#purchaseId:last').attr('name', 'purchaseId['+size+']').attr("id", "purchaseId" + size).val((size+1).toString());
    $("#TableId").find('#itemName:last').attr('name', 'itemName['+size+']').attr("id", "itemName" + size);
    $("#TableId").find('#itemPrice:last').attr('name', 'itemPrice['+size+']').attr("id", "itemPrice" + size);

    size++;
  });

  $('#addPurchase').on('click', function(){
    size=0;
    $('#purchaseModal').find('#search').removeAttr("disabled");
    $('#purchaseModal').find('#id').val("");
    $('#purchaseModal').find('#inputJobNo').val("");
    $('[data-mask]').inputmask();
    $('#purchaseModal').modal('show');
    $("#purchaseModal").find('.purchaseItemRow').remove();
    
    
    $('#purchaseForm').validate({
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

  $('#inputDate').datetimepicker({
    icons: { time: 'far fa-clock' },
    format: 'YYYY-MM-DD HH:mm:ss'
  });

  $("#inputJobNo").on('keyup', function () {
    $('#purchaseModal').find('#search').removeAttr("disabled");
  });

  $("#TableId").on('click', 'button[id^="remove"]', function () {
    var index = $(this).parents('.details').attr('data-index');
    size--;
    //$("#TableId").append('<input type="hidden" name="deleted[]" value="'+index+'"/>');
    $(this).parents('.details').remove();
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



function order(row) {
  var returnString = "";
  var weightData = JSON.parse(row.weight_data);

  returnString += '<div class="row"><div class="col-6"><p>Shipper Address: '+row.pickup_address+
  '</p></div><div class="col-6"><p>Shipper PIC: '+row.pickup_pic+
  '</p></div><div class="col-6"><p>Shipper PIC Contact: '+row.pickup_contact+
  '</p></div><div class="col-6"><p>Shipper PIC Email: '+row.pickup_email+
  '</p></div><div class="col-6"><p>Receiver Address: '+row.delivery_address+
  '</p></div><div class="col-6"><p>Receiver PIC: '+row.delivery_pic+
  '</p></div><div class="col-6"><p>Receiver PIC Contact: '+row.delivery_contact+
  '</p></div><div class="col-6"><p>Receiver PIC Email: '+row.delivery_email+
  '</p></div><div class="col-6"><p>Number of Cartons: '+row.number_of_carton+
  '</p></div><div class="col-6"><p>Cargo Ready Time: '+row.cargo_ready_time+
  '</p></div><div class="col-6"><p>Weight ('+((parseFloat(row.volumetric_weight) > parseFloat(row.total_cargo_weight)) ? 'Volumetric': 'Total Carton')+
  '): '+((parseFloat(row.volumetric_weight) > parseFloat(row.total_cargo_weight)) ? row.volumetric_weight: row.total_cargo_weight)+'</p></div><div class="col-6"><div class="row"><div class="col-3"><button type="button" class="btn btn-warning btn-sm" onclick="edit('+row.sale_id+
  ')"><i class="fas fa-pen"></i></button></div><div class="col-3"><button type="button" class="btn btn-success btn-sm" onclick="completed('+row.sale_id+
  ')"><i class="fas fa-check-circle"></i></button></div><div class="col-3"><button type="button" class="btn btn-info btn-sm" onclick="printQuote('+row.sale_id+
  ')"><i class="fas fa-print"></i></button></div><div class="col-3"><button type="button" class="btn btn-danger btn-sm" onclick="cancel('+row.sale_id+
  ')"><i class="fas fa-trash"></i></button></div></div></div></div>';

  returnString += '<br><h5>Cargo Details:</h5><table style="width: 100%;"><thead><tr><th>Piece Densed Weight</th><th>Dimension Length (cm)</th><th>Dimension Width (cm)</th><th>Dimension Height (cm)</th><th>Volumetric Weight</th></tr></thead><tbody>';
  
  for(var i=0; i<weightData.length; i++){
    returnString += '<tr><td>'+weightData[i].Weight+' KG</td><td>'+weightData[i].L+'</td><td>'+weightData[i].W+'</td><td>'+weightData[i].H+'</td><td>'+calVolumetric(weightData[i].W, weightData[i].L, weightData[i].H, weightData[i].Unit).toString()+' KG</td></tr>';
  }
  
  returnString += '</tbody></table><br><div class="row"><div class="col-6"><p>Volumetric Weight: '+row.volumetric_weight+
  ' KG</p></div><div class="col-6"><p>Total Cargo Weight: '+row.total_cargo_weight+' KG</p></div></div>';

  return returnString;
}

function simplyShowId(row) {
  //var weightData = JSON.parse(row.route);
  var returnString = '<div class="row"><div class="col-12">'+row.id+'</div></div><br>';

  return returnString;
}

function simplyShowJobNo(row) {
  //var weightData = JSON.parse(row.route);
  var returnString = '<div class="row"><div class="col-12">'+row.jobNo+'</div></div><br>';

  return returnString;
}

function simplyShowDate(row) {
  //var weightData = JSON.parse(row.route);
  var returnString = '<div class="row"><div class="col-12">'+row.date+'</div></div><br>';

  return returnString;
}

function simplyShowCreatedDatetime(row) {
  //var weightData = JSON.parse(row.route);
  var returnString = '<div class="row"><div class="col-12">'+row.created_datetime+'</div></div><br>';

  returnString += '<p><small>Action:</small></p>';

  returnString += '<div class="row"><div class="col-3"><button type="button" class="btn btn-info btn-sm" onclick="printQuote('+row.id+
  ')"><i class="fas fa-print"></i></button></div><div class="col-3"><button type="button" onclick="cancel('+
  row.id+')" class="btn btn-danger btn-sm"><i class="fas fa fa-times"></i></button></div></div>';

  return returnString;
}

function details(row) {
  var returnString = "";
  returnString += '<div class="row"><div class="col-8">Items</div><div class="col-4">Amount</div></div><hr>';

  var itemsData = JSON.parse(row.items);

  for(var i=0; i<itemsData.length; i++){
    returnString += '<div class="row"><div class="col-8">' + itemsData[i].itemName + '</div><div class="col-4">' + parseFloat(itemsData[i].itemPrice).toFixed(2)  + '</div></div>';
  }

  returnString += '<hr><div class="row"><div class="col-8">Total Amount (USD)</div><div class="col-4">' + parseFloat(row.total).toFixed(2) + '</div></div><hr>';

  return returnString;
}

function cancel(id) {
  $('#spinnerLoading').show();
  $.post('php/cancelPurchases.php', {purchasesID: id}, function(data){
    var obj = JSON.parse(data); 
    
    if(obj.status === 'success'){
      toastr["success"](obj.message, "Success:");
      $('#tableforPurchase').DataTable().ajax.reload();
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

function printQuote(id) {
  $('#spinnerLoading').show();
  $.post('php/generateReportPurchases.php', {purchasesID: id}, function(data){
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