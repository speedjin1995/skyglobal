<?php
require_once 'php/db_connect.php';

session_start();

if(!isset($_SESSION['userID'])){
  echo '<script type="text/javascript">';
  echo 'window.location.href = "login.html";</script>';
}
else{
  $user = $_SESSION['userID'];
  $role = $_SESSION['role'];
  $users = $db->query("SELECT * FROM users WHERE deleted = '0'");
  $customers = $db->query("SELECT * FROM customers WHERE customer_status = '0'");
  $customers2 = $db->query("SELECT * FROM customers WHERE customer_status = '0'");
  $suppliers = $db->query("SELECT * FROM suppliers WHERE supplier_status = '0'");
  $suppliers2 = $db->query("SELECT * FROM suppliers WHERE supplier_status = '0'");
  $airport = $db->query("SELECT * FROM airport");
  $airport2 = $db->query("SELECT * FROM airport");
  $airport3 = $db->query("SELECT * FROM airport");
  $airport4 = $db->query("SELECT * FROM airport");
}
?>

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
			<div class="col-sm-6">
				<h1 class="m-0 text-dark">Report</h1>
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
              <div class="col-4">
                <div class="form-group">
                  <label>From Time</label>
                  <div class="input-group date" id="inputStartTime" data-target-input="nearest">
                    <input type="text" class="form-control datetimepicker-input" id="startTime" name="startTime" data-target="#inputStartTime" />
                    <div class="input-group-append" data-target="#inputStartTime" data-toggle="datetimepicker">
                      <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-4">
                <div class="form-group">
                  <label>To Time</label>
                  <div class="input-group date" id="inputEndTime" data-target-input="nearest">
                    <input type="text" class="form-control datetimepicker-input" id="endTime" name="endTime" data-target="#inputEndTime" />
                    <div class="input-group-append" data-target="#inputEndTime" data-toggle="datetimepicker">
                      <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                    </div>
                  </div>
                </div>
              </div>
              <!--div class="col-4">
                <div class="form-group">
                  <label for="inputName">Customer Name</label>
                  <select class="form-control" style="width: 100%;" id="inputName" name="inputName">
                    <option value="" selected disabled hidden>Please Select</option>
                  </select>
                </div>
              </div-->
            </div>
            <div class="row">
              <div class="col-9"></div>
              <div class="col-3">
                <button type="button" class="btn btn-block bg-gradient-warning btn-sm" id="searchOrder">Search</button>
              </div>
            </div>
          </div>
          <div class="card-body">
            <table id="tableforOrder" class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th>Job No.</th>
                  <th>Quotation</th>
                  <th>Purchases</th>
                  <th>Profit</th>
                </tr>
              </thead>
            </table>
          </div><!-- /.card-body -->
        </div>
      </div>
    </div>
  </div>
</section><!-- /.content -->

<script>
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
      'url':'php/loadReports.php'
    },
    'columns': [
      { data: 'job_no' },
      { 
        data: 'id',
        render: function ( data, type, row ) {
          return sales(row);
        }
      },
      { 
        data: 'id',
        render: function ( data, type, row ) {
          return purchases(row);
        }
      },
      { data: 'profit' },
    ]       
  });

  $('#inputStartTime').datetimepicker({
    icons: { time: 'far fa-clock' },
    format: 'YYYY-MM-DD HH:mm:ss'
  });

  $('#inputEndTime').datetimepicker({
    icons: { time: 'far fa-clock' },
    format: 'YYYY-MM-DD HH:mm:ss'
  });

  $('#searchOrder').on('click', function(){
    //var inputName = $("#inputName").val();
    var inputStartTime = $("#startTime").val();
    var inputEndTime = $("#endTime").val();

    $("#tableforOrder").DataTable().clear().destroy();

    var table = $("#tableforOrder").DataTable({
      "responsive": true,
      "autoWidth": false,
      'processing': true,
      'serverSide': true,
      'searching': false,
      'serverMethod': 'post',
      'ordering': false,
      'ajax': {
        'type': 'POST',
        'url':'php/searchReports.php',
        'data': {
          //inputName: inputName, 
          inputStartTime: inputStartTime, 
          inputEndTime: inputEndTime
        }
      },
      'columns': [
        { data: 'job_no' },
        { 
          data: 'id',
          render: function ( data, type, row ) {
            return sales(row);
          }
        },
        { 
          data: 'id',
          render: function ( data, type, row ) {
            return purchases(row);
          }
        },
        { data: 'profit' },
      ]        
    });
  });
});

function sales(row) {
  var returnString = "";

  returnString += '<div class="row"><div class="col-8">Items</div><div class="col-4">Amount</div></div><hr>';

  for(var i=0; i<row.sales.length; i++){
    returnString += '<div class="row"><div class="col-8">'+ row.sales[i].extraChargesName +'</div><div class="col-4">' + parseFloat(row.sales[i].extraChargesAmount).toFixed(2)  + '</div></div>';
  }

  returnString += '<hr><div class="row"><div class="col-8">Total Amount</div><div class="col-4">' + parseFloat(row.totalSales).toFixed(2) + '</div></div><hr>';

  return returnString;
}

function purchases(row) {
  var returnString = "";

  returnString += '<div class="row"><div class="col-8">Items</div><div class="col-4">Amount</div></div><hr>';

  for(var i=0; i<row.purchases.length; i++){
    returnString += '<div class="row"><div class="col-8">'+ row.purchases[i].itemName +'</div><div class="col-4">' + parseFloat(row.purchases[i].itemPrice).toFixed(2)  + '</div></div>';
  }

  returnString += '<hr><div class="row"><div class="col-8">Total Amount</div><div class="col-4">' + parseFloat(row.totalPurchases).toFixed(2)  + '</div></div><hr>';

  return returnString;
}

function copyclipboard(id) {
  // Get the text field
  /*var copyText = document.getElementById("myInput");

  // Select the text field
  copyText.select();
  copyText.setSelectionRange(0, 99999); // For mobile devices

   // Copy the text inside the text field
  navigator.clipboard.writeText(copyText.value);*/

  $('#spinnerLoading').show();
  $.post('php/copyPurchases.php', {salesID: id}, function(data){
    var obj = JSON.parse(data); 
    
    if(obj.status === 'success'){
      
      var text= "Mode: "+obj.shipmentType+"\n\n"+"Pickup: "+obj.pickUpAddress+"\n\n"+"Delivery: "+obj.deliveryAddress
      +"\n\n"+"Pickup Time: "+obj.cargoReadyTime+"\n\n"+"Price: "+obj.totalAmount+"USD" +"\n\n"+"Flight: \n"+obj.routeInfo
      +"\n"+"Notes: "+obj.customerNotes;
      
      navigator.clipboard.writeText(text);
    }
    else if(obj.status === 'failed'){
      toastr["error"](obj.message, "Failed:");
    }
    else{
      toastr["error"]("Something wrong when copy", "Failed:");
    }

    $('#spinnerLoading').hide();
  });

}
</script>