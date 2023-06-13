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
            <div class="row">
                <div class="col-9"></div>
                <div class="col-3">
                  <button type="button" class="btn btn-block bg-gradient-warning btn-sm" id="addOrder">Create New Order</button>
                </div>
            </div>
          </div>
          <div class="card-body">
            <table id="tableforOrder" class="table table-bordered">
              <thead>
                <tr>
                  <th>Order</th>
                  <th colspan="2">Details</th>
                  <th>Status</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td rowspan="8">Dummy 001</td>
                  <td>Handler Name</td>
                  <td>dummy data</td>
                  <td rowspan="8">
                    <div>
                      Status:
                      <p class="state"><small>Status:</small></p>
                      <p>
                        <i class="update-status fa fa-times text-danger tip" data-status="CANCELLED" title="Cancel Order"
                          style="font-size:22px;top:-1px;"></i>
                        <i class="update-status fa fa-envelope tip" data-status="SENT" title="Send to Client via SMS & E-mail"
                          style="color:#3F51B5;"></i>
                        <i class="payment-refresh fa fa-refresh tip text-info" title="Check Payment Status"></i>
                        <i class="update-status fa fa-credit-card tip" data-status="PAID" title="Mark as Paid"
                          style="color:#FF9800;"></i>
                        <i class="update-status fa fa-eye tip" data-status="KIV" title="Mark as KIV"
                          style="font-size:20px;"></i>
                        <i class="update-status fa fa-cog tip" data-status="PROCESSING" title="Mark as Processing"
                          style="color:#757575;"></i>
                        <i class="update-status fa fa-truck tip" data-status="SHIPPED"
                          title="Item delivered / Customer Received" style="color:#3F51B5;"></i>
                        <i class="print-shipment fa fa-print tip" title="Print Shipment" style="color:#3F51B5;"></i>
                        <i class="update-status fa fa-check-circle text-success tip" data-status="COMPLETED"
                          title="Mark as Completed" style="font-size:19px;"></i>
                      </p>
                    </div>
                    <div>
                      Action:
                      <p>
                        <i class="show-sale fa fa-file-text tip" title="View" style="color:#009688;"></i>
                        <i class="upload fa fa-upload tip" title="Upload File" style="color:#000;"></i>
                        <i class="order-no fa"><i style="padding: 1%;" class="generate-report fa fa-file-text tip"
                            title="Print Cash Sale"></i></i>
                        <i class="order-no fa"><i style="padding: 1%;" class="cash-sale fa fa-envelope tip"
                            title="Send Cash Sale"></i></i>
                        <i class="whatsapp-status fa fa-whatsapp tip" data-status="WHATSAPP" title="Send to Client via Whatsapp"
                          style="color:#25D366;"></i>
                      </p>
                    </div>
                    <div>
                      Created Dates: 12/4/2023 4:35:00 PM
                    </div>
                  </td>
                </tr>
                <tr>
                  <td>Customer Name</td>
                  <td>Kankaku Piero</td>
                </tr>
                <tr>
                  <td>Contact Number</td>
                  <td>016-7788990</td>
                </tr>
                <tr>
                  <td>Email</td>
                  <td>dummy@gmail.com</td>
                </tr>
                <tr>
                  <td>Shipment Type</td>
                  <td>Airport to airport</td>
                </tr>
                <tr>
                  <td>Address</td>
                  <td>44, Sri Aman, Gat Lebuh Mallum, 10300, Pulau Pinang</td>
                </tr>
                <tr>
                  <td>Notes (Internal)</td>
                  <td>Dummy Notes</td>
                </tr>
                <tr>
                  <td>Notes to Customer</td>
                  <td>-</td>
                </tr>
              </tbody>
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
                      <label for="inputEmail">Total Price</label>
                      <input type="totalPrice" class="form-control" id="totalPrice" name="totalPrice" value="0.00" readonly>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-4">
                    <div class="form-group">
                      <label>Address</label>
                      <textarea id="inputAddress" name="inputAddress" class="form-control" rows="3" placeholder="Enter Address"></textarea>
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
              </div>
            </div>
            <div class="card card-primary">
              <div class="card-body" id="itemList">
                <div class="row">
                  <h4>Add Items</h4>
                  <button style="margin-left:auto;margin-right: 25px;" type="button" class="btn btn-primary add-row">Add New</button>
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
            <span class="input-group-text">RM</span>
          </div>
          <input type="text" class="form-control" id="inputPickupCharge" placeholder="Enter Pickup Charges" data-inputmask='"mask": "99.99"' data-mask>
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
          <input type="text" class="form-control" id="inputExportClearances" placeholder="Enter Export Clearances" data-inputmask='"mask": "99.99"' data-mask>
        </div>
      </div>
    </div>
    <div class="col-4" id="airTicket">
      <div class="form-group">
        <label>Air Ticket</label>
        <div class="input-group">
          <div class="input-group-prepend">
            <span class="input-group-text">RM</span>
          </div>
          <input type="text" class="form-control" id="inputAirTicket" placeholder="Enter Air Ticket" data-inputmask='"mask": "99.99"' data-mask>
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
          <input type="text" class="form-control" id="inputFlyersFee" placeholder="Enter Flyers Fee" data-inputmask='"mask": "99.99"' data-mask>
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
          <input type="text" class="form-control" id="inputImportClearance" placeholder="Enter Import Clearance" data-inputmask='"mask": "99.99"' data-mask>
        </div>
      </div>
    </div>
    <div class="col-4" id="deliveryCharges">
      <div class="form-group">
        <label>Delivery Charges</label>
        <div class="input-group">
          <div class="input-group-prepend">
            <span class="input-group-text">RM</span>
          </div>
          <input type="text" class="form-control" id="inputDeliveryCharges" placeholder="Enter Delivery Charges" data-inputmask='"mask": "99.99"' data-mask>
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

  $.validator.setDefaults({
    submitHandler: function () {
      if($('#orderModal').hasClass('show')){
        $('#spinnerLoading').show();
        $.post('php/insertSales.php', $('#orderForm').serialize(), function(data){
          var obj = JSON.parse(data); 
          if(obj.status === 'success'){
            $('#orderModal').modal('hide');
            toastr["success"](obj.message, "Success:");
            //$('#weightTable').DataTable().ajax.reload();
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
    $('#itemList').find('.details').remove();
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
      $("#itemList").find("#inputPickupCharge" + size).attr('readonly', true);
      $("#itemList").find('#inputExportClearances' + size).attr('readonly', true);;
      $("#itemList").find('#inputImportClearance' + size).attr('readonly', true);;
      $("#itemList").find('#inputDeliveryCharges' + size).attr('readonly', true);;
    }
    else if($('#inputShipmentType').val() == 'Door to origin airport'){
      $("#itemList").find('#inputAirTicket' + size).attr('readonly', true);;
      $("#itemList").find('#inputFlyersFee' + size).attr('readonly', true);;
      $("#itemList").find('#inputImportClearance' + size).attr('readonly', true);;
      $("#itemList").find('#inputDeliveryCharges' + size).attr('readonly', true);;
    }
    else if($('#inputShipmentType').val() == 'Door to destination airport'){
      $("#itemList").find('#inputImportClearance' + size).attr('readonly', true);;
      $("#itemList").find('#inputDeliveryCharges' + size).attr('readonly', true);;
    }
    else if($('#inputShipmentType').val() == 'Airport to door'){
      $("#itemList").find('#inputPickupCharge' + size).attr('readonly', true);;
      $("#itemList").find('#inputExportClearances' + size).attr('readonly', true);;
      $("#itemList").find('#inputAirTicket' + size).attr('readonly', true);;
      $("#itemList").find('#inputFlyersFee' + size).attr('readonly', true);;
    }
    else if($('#inputShipmentType').val() == 'Origin Airport to door'){
      $("#itemList").find('#inputPickupCharge:last').attr('readonly', true);;
      $("#itemList").find('#inputExportClearances:last').attr('readonly', true);;
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
  });addOrder
});
</script>