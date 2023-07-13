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
                  <label for="inputCustomerName">Customer Name</label>
                  <select class="form-control" style="width: 100%;" id="inputCustomerName" name="inputCustomerName">
                    <option value="" selected disabled hidden>Please Select</option>
                    <?php while($customersRow=mysqli_fetch_assoc($customers)){ ?>
                      <option value="<?=$customersRow['customer_name'] ?>"><?=$customersRow['customer_name'] ?></option>
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
          <h4 class="modal-title">Create New Order</h4>
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
                        <?php while($customersRow=mysqli_fetch_assoc($customers)){ ?>
                          <option value="<?=$customersRow['customer_name'] ?>"><?=$customersRow['customer_name'] ?></option>
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
                  <!--div class="col-4">
                    <div class="form-group">
                      <label for="inputEmail">Total Price</label>
                      <input type="totalPrice" class="form-control" id="totalPrice" name="totalPrice" value="0.00" readonly>
                    </div>
                  </div-->
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
                      <textarea id="inputAddress" name="inputAddress" class="form-control" rows="3" placeholder="Enter Address"></textarea>
                    </div>
                  </div>
                  <div class="col-3">
                    <div class="form-group">
                      <label for="inputEmail">Pickup Company PIC</label>
                      <input type="text" class="form-control" id="inputEmail" name="inputEmail" placeholder="Example: dummy@mail.com">
                    </div>
                  </div>
                  <div class="col-3">
                    <div class="form-group">
                      <label for="inputContactNum">Pickup Company PIC Phone</label>
                      <input type="text" class="form-control" id="inputContactNum" name="inputContactNum" placeholder="Example: 01X-1234567"
                        data-inputmask='"mask": "999-9999999"' data-mask>
                    </div>
                  </div>
                  <div class="col-3">
                    <div class="form-group">
                      <label for="inputEmail">Pickup Company PIC Email</label>
                      <input type="email" class="form-control" id="inputEmail" name="inputEmail" placeholder="Example: dummy@mail.com">
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-3">
                    <div class="form-group">
                      <label>Delivery Company Name & Address</label>
                      <textarea id="inputAddress" name="inputAddress" class="form-control" rows="3" placeholder="Enter Address"></textarea>
                    </div>
                  </div>
                  <div class="col-3">
                    <div class="form-group">
                      <label for="inputEmail">Delivery Company PIC</label>
                      <input type="text" class="form-control" id="inputEmail" name="inputEmail" placeholder="Example: dummy@mail.com">
                    </div>
                  </div>
                  <div class="col-3">
                    <div class="form-group">
                      <label for="inputContactNum">Delivery Company PIC Phone</label>
                      <input type="text" class="form-control" id="inputContactNum" name="inputContactNum" placeholder="Example: 01X-1234567"
                        data-inputmask='"mask": "999-9999999"' data-mask>
                    </div>
                  </div>
                  <div class="col-3">
                    <div class="form-group">
                      <label for="inputEmail">Delivery Company PIC Email</label>
                      <input type="email" class="form-control" id="inputEmail" name="inputEmail" placeholder="Example: dummy@mail.com">
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
                        <input type="text" class="form-control datetimepicker-input" id="cargoReadyTime" data-target="#inputCargoReadyTime" />
                        <div class="input-group-append" data-target="#inputCargoReadyTime" data-toggle="datetimepicker">
                          <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-4">
                    <div class="form-group">
                      <label>Departure Airport</label>
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
                      <label>Destination Airport</label>
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
                  <!--div class="col-4">
                    <button class="btn btn-danger btn-sm" id="remove"><i class="fa fa-times"></i></button>
                  </div-->
                  <div class="col-4">
                    <!-- text input -->
                    <div class="form-group">
                      <label>Dimension</label>
                      <input id="inputDimension" type="text" class="form-control" placeholder="Enter ...">
                    </div>
                  </div>
                  <div class="col-4">
                    <div class="form-group">
                      <label>Number of Carton</label>
                      <input id="inputNumberofCarton" type="number" class="form-control" placeholder="Enter ...">
                    </div>
                  </div>
                  <div class="col-4">
                    <!-- text input -->
                    <div class="form-group">
                      <label>Weight of Carton</label>
                      <div class="input-group mb-3">
                        <input id="inputWeightofCarton" type="number" class="form-control">
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
                          <span class="input-group-text">USD</span>
                        </div>
                        <input type="number" class="form-control" id="inputPickupCharge" placeholder="Enter Pickup Charges"/>
                      </div>
                    </div>
                  </div>
                  <div class="col-4">
                    <div class="form-group" id="exportClearance">
                      <label>Export Clearances</label>
                      <div class="input-group">
                        <div class="input-group-prepend">
                          <span class="input-group-text">USD</span>
                        </div>
                        <input type="number" class="form-control" id="inputExportClearances" placeholder="Enter Export Clearances">
                      </div>
                    </div>
                  </div>
                  <div class="col-4" id="airTicket">
                    <div class="form-group">
                      <label>Air Ticket</label>
                      <div class="input-group">
                        <div class="input-group-prepend">
                          <span class="input-group-text">USD</span>
                        </div>
                        <input type="number" class="form-control" id="inputAirTicket" placeholder="Enter Air Ticket">
                      </div>
                    </div>
                  </div>
                  <div class="col-4" id="flyersFee">
                    <div class="form-group">
                      <label>Flyers Fee</label>
                      <div class="input-group">
                        <div class="input-group-prepend">
                          <span class="input-group-text">USD</span>
                        </div>
                        <input type="number" class="form-control" id="inputFlyersFee" placeholder="Enter Flyers Fee">
                      </div>
                    </div>
                  </div>
                  <div class="col-4" id="importClearance">
                    <div class="form-group">
                      <label>Import Clearance</label>
                      <div class="input-group">
                        <div class="input-group-prepend">
                          <span class="input-group-text">USD</span>
                        </div>
                        <input type="number" class="form-control" id="inputImportClearance" placeholder="Enter Import Clearance">
                      </div>
                    </div>
                  </div>
                  <div class="col-4" id="deliveryCharges">
                    <div class="form-group">
                      <label>Delivery Charges</label>
                      <div class="input-group">
                        <div class="input-group-prepend">
                          <span class="input-group-text">USD</span>
                        </div>
                        <input type="number" class="form-control" id="inputDeliveryCharges" placeholder="Enter Delivery Charges">
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
                        <input type="text" class="form-control" id="inputTotalCharges" placeholder="Enter Delivery Charges" readonly>
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
  <div style="margin-top:3%" class="row details">
    <div class="col-4">
      <div class="form-group">
        <label>Cargo Ready Time</label>
        <div class="input-group date" id="inputCargoReadyTime" data-target-input="nearest">
          <input type="text" class="form-control datetimepicker-input" id="cargoReadyTime" data-target="#inputCargoReadyTime" />
          <div class="input-group-append" data-target="#inputCargoReadyTime" data-toggle="datetimepicker">
            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-4">
      <!-- text input -->
      <div class="form-group">
        <label>Pickup Address</label>
        <textarea id="inputPickupAddress" class="form-control" rows="3"
          placeholder="Enter Pickup Address"></textarea>
      </div>
    </div>
    <div class="col-4">
      <button class="btn btn-danger btn-sm" id="remove"><i class="fa fa-times"></i></button>
    </div>
    <div class="col-4">
      <!-- text input -->
      <div class="form-group">
        <label>Dimension</label>
        <input id="inputDimension" type="text" class="form-control" placeholder="Enter ...">
      </div>
    </div>
    <div class="col-4">
      <div class="form-group">
        <label>Number of Carton</label>
        <input id="inputNumberofCarton" type="number" class="form-control" placeholder="Enter ...">
      </div>
    </div>
    <div class="col-4">
      <!-- text input -->
      <div class="form-group">
        <label>Weight of Carton</label>
        <div class="input-group mb-3">
          <input id="inputWeightofCarton" type="number" class="form-control">
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
            <span class="input-group-text">USD</span>
          </div>
          <input type="number" class="form-control" id="inputPickupCharge" placeholder="Enter Pickup Charges"/>
        </div>
      </div>
    </div>
    <div class="col-4">
      <div class="form-group" id="exportClearance">
        <label>Export Clearances</label>
        <div class="input-group">
          <div class="input-group-prepend">
            <span class="input-group-text">USD</span>
          </div>
          <input type="number" class="form-control" id="inputExportClearances" placeholder="Enter Export Clearances">
        </div>
      </div>
    </div>
    <div class="col-4" id="airTicket">
      <div class="form-group">
        <label>Air Ticket</label>
        <div class="input-group">
          <div class="input-group-prepend">
            <span class="input-group-text">USD</span>
          </div>
          <input type="number" class="form-control" id="inputAirTicket" placeholder="Enter Air Ticket">
        </div>
      </div>
    </div>
    <div class="col-4" id="flyersFee">
      <div class="form-group">
        <label>Flyers Fee</label>
        <div class="input-group">
          <div class="input-group-prepend">
            <span class="input-group-text">USD</span>
          </div>
          <input type="number" class="form-control" id="inputFlyersFee" placeholder="Enter Flyers Fee">
        </div>
      </div>
    </div>
    <div class="col-4" id="importClearance">
      <div class="form-group">
        <label>Import Clearance</label>
        <div class="input-group">
          <div class="input-group-prepend">
            <span class="input-group-text">USD</span>
          </div>
          <input type="number" class="form-control" id="inputImportClearance" placeholder="Enter Import Clearance">
        </div>
      </div>
    </div>
    <div class="col-4" id="deliveryCharges">
      <div class="form-group">
        <label>Delivery Charges</label>
        <div class="input-group">
          <div class="input-group-prepend">
            <span class="input-group-text">USD</span>
          </div>
          <input type="number" class="form-control" id="inputDeliveryCharges" placeholder="Enter Delivery Charges">
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
          <input type="text" class="form-control" id="inputTotalCharges" placeholder="Enter Delivery Charges" readonly>
        </div>
      </div>
    </div>
  </div>
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
    //$('#itemList').find('.details').remove();
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
    $("#itemList").append($addContents.html());

    $("#itemList").find('.details:last').attr("id", "detail" + size);
    $("#itemList").find('.details:last').attr("data-index", size);
    $("#itemList").find('#remove:last').attr("id", "remove" + size);

    $("#itemList").find('#cargoReadyTime:last').attr('name', 'cargoReadyTime['+size+']').attr("id", "cargoReadyTime" + size);
    $("#itemList").find('#inputPickupAddress:last').attr('name', 'inputPickupAddress['+size+']').attr("id", "inputPickupAddress" + size);
    $("#itemList").find('#inputDimension:last').attr('name', 'inputDimension['+size+']').attr("id", "inputDimension" + size);
    $("#itemList").find('#inputNumberofCarton:last').attr('name', 'inputNumberofCarton['+size+']').attr("id", "inputNumberofCarton" + size);
    $("#itemList").find('#inputWeightofCarton:last').attr('name', 'inputWeightofCarton['+size+']').attr("id", "inputWeightofCarton" + size);
    $("#itemList").find('#inputPickupCharge:last').attr('name', 'inputPickupCharge['+size+']').attr("id", "inputPickupCharge" + size).val("0.00");
    $("#itemList").find('#inputExportClearances:last').attr('name', 'inputExportClearances['+size+']').attr("id", "inputExportClearances" + size).val("0.00");
    $("#itemList").find('#inputAirTicket:last').attr('name', 'inputAirTicket['+size+']').attr("id", "inputAirTicket" + size).val("0.00");
    $("#itemList").find('#inputFlyersFee:last').attr('name', 'inputFlyersFee['+size+']').attr("id", "inputFlyersFee" + size).val("0.00");
    $("#itemList").find('#inputImportClearance:last').attr('name', 'inputImportClearance['+size+']').attr("id", "inputImportClearance" + size).val("0.00");
    $("#itemList").find('#inputDeliveryCharges:last').attr('name', 'inputDeliveryCharges['+size+']').attr("id", "inputDeliveryCharges" + size).val("0.00");
    $("#itemList").find('#inputTotalCharges:last').attr('name', 'inputTotalCharges['+size+']').attr("id", "inputTotalCharges" + size).val("0.00");

    if($('#inputShipmentType').val() == 'Airport to airport'){
      $("#itemList").find('#inputAirTicket' + size).attr('readonly', false);
      $("#itemList").find('#inputFlyersFee' + size).attr('readonly', false);
      $("#itemList").find("#inputPickupCharge" + size).attr('readonly', true);
      $("#itemList").find('#inputExportClearances' + size).attr('readonly', true);
      $("#itemList").find('#inputImportClearance' + size).attr('readonly', true);
      $("#itemList").find('#inputDeliveryCharges' + size).attr('readonly', true);
    }
    else if($('#inputShipmentType').val() == 'Door to origin airport'){
      $("#itemList").find("#inputPickupCharge" + size).attr('readonly', false);
      $("#itemList").find('#inputExportClearances' + size).attr('readonly', false);
      $("#itemList").find('#inputAirTicket' + size).attr('readonly', true);
      $("#itemList").find('#inputFlyersFee' + size).attr('readonly', true);
      $("#itemList").find('#inputImportClearance' + size).attr('readonly', true);
      $("#itemList").find('#inputDeliveryCharges' + size).attr('readonly', true);
    }
    else if($('#inputShipmentType').val() == 'Door to destination airport'){
      $("#itemList").find("#inputPickupCharge" + size).attr('readonly', false);
      $("#itemList").find('#inputExportClearances' + size).attr('readonly', false);
      $("#itemList").find('#inputAirTicket' + size).attr('readonly', false);
      $("#itemList").find('#inputFlyersFee' + size).attr('readonly', false);
      $("#itemList").find('#inputImportClearance' + size).attr('readonly', true);
      $("#itemList").find('#inputDeliveryCharges' + size).attr('readonly', true);
    }
    else if($('#inputShipmentType').val() == 'Airport to door'){
      $("#itemList").find('#inputPickupCharge' + size).attr('readonly', true);
      $("#itemList").find('#inputExportClearances' + size).attr('readonly', true);
      $("#itemList").find('#inputAirTicket' + size).attr('readonly', true);
      $("#itemList").find('#inputFlyersFee' + size).attr('readonly', true);
      $("#itemList").find('#inputImportClearance' + size).attr('readonly', false);
      $("#itemList").find('#inputDeliveryCharges' + size).attr('readonly', false);
    }
    else if($('#inputShipmentType').val() == 'Origin Airport to door'){
      $("#itemList").find('#inputPickupCharge' + size).attr('readonly', true);
      $("#itemList").find('#inputExportClearances' + size).attr('readonly', true);
      $("#itemList").find('#inputAirTicket' + size).attr('readonly', false);
      $("#itemList").find('#inputFlyersFee' + size).attr('readonly', false);
      $("#itemList").find('#inputImportClearance' + size).attr('readonly', false);
      $("#itemList").find('#inputDeliveryCharges' + size).attr('readonly', false);
    }

    //Date and time picker
    $("#itemList").find('#inputCargoReadyTime:last').datetimepicker({
      icons: { time: 'far fa-clock' },
      format: 'YYYY-MM-DD HH:mm:ss'
    });
    
    $('[data-mask]').inputmask();

    size++;
  });

  $("#itemList").on('click', 'button[id^="remove"]', function () {
    var index = $(this).parents('.details').attr('data-index');
    $("#itemList").append('<input type="hidden" name="deleted[]" value="'+index+'"/>');
    $(this).parents('.details').remove();
  });

  $("#itemList").on('change', 'input[id^="inputPickupCharge"]', function () {
    var inputPickupCharge = $(this).parents('.details').find('input[id^="inputPickupCharge"]').val();
    var inputExportClearances = $(this).parents('.details').find('input[id^="inputExportClearances"]').val();
    var inputAirTicket = $(this).parents('.details').find('input[id^="inputAirTicket"]').val();
    var inputFlyersFee = $(this).parents('.details').find('input[id^="inputFlyersFee"]').val();
    var inputImportClearance = $(this).parents('.details').find('input[id^="inputImportClearance"]').val();
    var inputDeliveryCharges = $(this).parents('.details').find('input[id^="inputDeliveryCharges"]').val();

    var inputTotalCharges = calTotal(inputPickupCharge, inputExportClearances, inputAirTicket, inputFlyersFee, inputImportClearance, inputDeliveryCharges);
    $(this).parents('.details').find('input[id^="inputTotalCharges"]').val(inputTotalCharges);
  });

  $("#itemList").on('change', 'input[id^="inputExportClearances"]', function () {
    var inputPickupCharge = $(this).parents('.details').find('input[id^="inputPickupCharge"]').val();
    var inputExportClearances = $(this).parents('.details').find('input[id^="inputExportClearances"]').val();
    var inputAirTicket = $(this).parents('.details').find('input[id^="inputAirTicket"]').val();
    var inputFlyersFee = $(this).parents('.details').find('input[id^="inputFlyersFee"]').val();
    var inputImportClearance = $(this).parents('.details').find('input[id^="inputImportClearance"]').val();
    var inputDeliveryCharges = $(this).parents('.details').find('input[id^="inputDeliveryCharges"]').val();

    var inputTotalCharges = calTotal(inputPickupCharge, inputExportClearances, inputAirTicket, inputFlyersFee, inputImportClearance, inputDeliveryCharges);
    $(this).parents('.details').find('input[id^="inputTotalCharges"]').val(inputTotalCharges);
  });

  $("#itemList").on('change', 'input[id^="inputAirTicket"]', function () {
    var inputPickupCharge = $(this).parents('.details').find('input[id^="inputPickupCharge"]').val();
    var inputExportClearances = $(this).parents('.details').find('input[id^="inputExportClearances"]').val();
    var inputAirTicket = $(this).parents('.details').find('input[id^="inputAirTicket"]').val();
    var inputFlyersFee = $(this).parents('.details').find('input[id^="inputFlyersFee"]').val();
    var inputImportClearance = $(this).parents('.details').find('input[id^="inputImportClearance"]').val();
    var inputDeliveryCharges = $(this).parents('.details').find('input[id^="inputDeliveryCharges"]').val();

    var inputTotalCharges = calTotal(inputPickupCharge, inputExportClearances, inputAirTicket, inputFlyersFee, inputImportClearance, inputDeliveryCharges);
    $(this).parents('.details').find('input[id^="inputTotalCharges"]').val(inputTotalCharges);
  });

  $("#itemList").on('change', 'input[id^="inputFlyersFee"]', function () {
    var inputPickupCharge = $(this).parents('.details').find('input[id^="inputPickupCharge"]').val();
    var inputExportClearances = $(this).parents('.details').find('input[id^="inputExportClearances"]').val();
    var inputAirTicket = $(this).parents('.details').find('input[id^="inputAirTicket"]').val();
    var inputFlyersFee = $(this).parents('.details').find('input[id^="inputFlyersFee"]').val();
    var inputImportClearance = $(this).parents('.details').find('input[id^="inputImportClearance"]').val();
    var inputDeliveryCharges = $(this).parents('.details').find('input[id^="inputDeliveryCharges"]').val();

    var inputTotalCharges = calTotal(inputPickupCharge, inputExportClearances, inputAirTicket, inputFlyersFee, inputImportClearance, inputDeliveryCharges);
    $(this).parents('.details').find('input[id^="inputTotalCharges"]').val(inputTotalCharges);
  });

  $('#itemList').on('change', 'input[id^="inputImportClearance"]', function () {
    var inputPickupCharge = $(this).parents('.details').find('input[id^="inputPickupCharge"]').val();
    var inputExportClearances = $(this).parents('.details').find('input[id^="inputExportClearances"]').val();
    var inputAirTicket = $(this).parents('.details').find('input[id^="inputAirTicket"]').val();
    var inputFlyersFee = $(this).parents('.details').find('input[id^="inputFlyersFee"]').val();
    var inputImportClearance = $(this).parents('.details').find('input[id^="inputImportClearance"]').val();
    var inputDeliveryCharges = $(this).parents('.details').find('input[id^="inputDeliveryCharges"]').val();

    var inputTotalCharges = calTotal(inputPickupCharge, inputExportClearances, inputAirTicket, inputFlyersFee, inputImportClearance, inputDeliveryCharges);
    $(this).parents('.details').find('input[id^="inputTotalCharges"]').val(inputTotalCharges);
  });

  $('#itemList').on('change', 'input[id^="inputDeliveryCharges"]', function () {
    var inputPickupCharge = $(this).parents('.details').find('input[id^="inputPickupCharge"]').val();
    var inputExportClearances = $(this).parents('.details').find('input[id^="inputExportClearances"]').val();
    var inputAirTicket = $(this).parents('.details').find('input[id^="inputAirTicket"]').val();
    var inputFlyersFee = $(this).parents('.details').find('input[id^="inputFlyersFee"]').val();
    var inputImportClearance = $(this).parents('.details').find('input[id^="inputImportClearance"]').val();
    var inputDeliveryCharges = $(this).parents('.details').find('input[id^="inputDeliveryCharges"]').val();

    var inputTotalCharges = calTotal(inputPickupCharge, inputExportClearances, inputAirTicket, inputFlyersFee, inputImportClearance, inputDeliveryCharges);
    $(this).parents('.details').find('input[id^="inputTotalCharges"]').val(inputTotalCharges);
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