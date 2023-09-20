<?php
require_once 'php/db_connect.php';

session_start();

if(!isset($_SESSION['userID'])){
  echo '<script type="text/javascript">';
  echo 'window.location.href = "login.html";</script>';
}
else{
  $user = $_SESSION['userID'];
  $customers = $db->query("SELECT * FROM users");
}
?>

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
			<div class="col-sm-6">
				<h1 class="m-0 text-dark">Logs</h1>
			</div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<!-- Main content -->
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
                  <div class="col-4">
                    <div class="form-group">
                      <label for="inputName">Name</label>
                      <select class="form-control" style="width: 100%;" id="inputName" name="inputName">
                        <option value="" selected disabled hidden>Please Select</option>
                        <?php while($customersRow=mysqli_fetch_assoc($customers)){ ?>
                          <option value="<?=$customersRow['id'] ?>"><?=$customersRow['name'] ?></option>
                        <?php } ?>
                      </select>
                    </div>
                  </div>
            </div>
            <div class="row">
                <div class="col-9"></div>
                <div class="col-3">
                <button type="button" class="btn btn-block bg-gradient-warning btn-sm" id="searchLog">Search Log</button>
                </div>
            </div>
          </div>
					<div class="card-body">
						<table id="logTable" class="table table-bordered table-striped">
							<thead>
								<tr>
                  <th>No</th>
									<th>User Name</th>
									<th>Date Time</th>
									<th>Actions</th>
								</tr>
							</thead>
						</table>
					</div><!-- /.card-body -->
				</div><!-- /.card -->
			</div><!-- /.col -->
		</div><!-- /.row -->
	</div><!-- /.container-fluid -->
</section><!-- /.content -->


<script>
$(function () {
    $('#inputStartTime').datetimepicker({
    icons: { time: 'far fa-clock' },
    format: 'YYYY-MM-DD HH:mm:ss'
    });

    $('#inputEndTime').datetimepicker({
    icons: { time: 'far fa-clock' },
    format: 'YYYY-MM-DD HH:mm:ss'
    });

  $('#searchLog').on('click', function(){
    var inputName = $("#inputName").val();
    var inputStartTime = $("#startTime").val();
    var inputEndTime = $("#endTime").val();

    $("#logTable").DataTable().clear().destroy();

    $("#logTable").DataTable({
        "responsive": true,
        "autoWidth": false,
        'searching': false,
        'processing': true,
        'serverSide': true,
        'serverMethod': 'post',
        'ajax': {
            'url':'php/searchLogs.php',
            'data': {
               inputName: inputName, 
               inputStartTime: inputStartTime, inputEndTime: inputEndTime
            }
        },
        'columns': [
            { data: 'id' },
            { data: 'userName' },
            { data: 'created_dateTime' },
            { data: 'action' },
           
        ]
    });
    
  });


    $("#logTable").DataTable({
        "responsive": true,
        "autoWidth": false,
        'searching': false,
        'processing': true,
        'serverSide': true,
        'serverMethod': 'post',
        'ajax': {
            'url':'php/loadLogs.php'
        },
        'columns': [
            { data: 'id' },
            { data: 'userName' },
            { data: 'created_dateTime' },
            { data: 'action' },
           
        ]
    });
    
    $.validator.setDefaults({
        submitHandler: function () {
            $('#spinnerLoading').show();
            /*$.post('php/suppliers.php', $('#supplierForm').serialize(), function(data){
                var obj = JSON.parse(data); 

                if(obj.status === 'success'){
                    $('#addModal').modal('hide');
                    toastr["success"](obj.message, "Success:");

                    $.get('suppliers.php', function(data) {
                        $('#mainContents').html(data);
                        $('#spinnerLoading').hide();
                    });
                }
                else if(obj.status === 'failed'){
                    toastr["error"](obj.message, "Failed:");
                    $('#spinnerLoading').hide();
                }
                else{
                    toastr["error"]("Something wrong when edit", "Failed:");
                    $('#spinnerLoading').hide();
                }
            });*/
            var formData = new FormData($('#supplierForm')[0]);

            $.ajax({
                type: "POST",
                enctype: 'multipart/form-data',
                url: "php/suppliers.php",
                data: formData, // Use the FormData object
                processData: false,
                contentType: false,
                cache: false,
                timeout: 60000,
                success: function (data) {
                    var obj = JSON.parse(data); 
                
                    if(obj.status === 'success'){
                        $('#addModal').modal('hide');
                        toastr["success"](obj.message, "Success:");
                        
                        $.get('suppliers.php', function(data) {
                            $('#mainContents').html(data);
                            $('#spinnerLoading').hide();
                        });
                    }
                    else if(obj.status === 'failed'){
                        toastr["error"](obj.message, "Failed:");
                        $('#spinnerLoading').hide();
                    }
                    else{
                        toastr["error"]("Something wrong when edit", "Failed:");
                        $('#spinnerLoading').hide();
                    }
                },
                error: function (e) {
                    toastr["error"](e.responseText, "Failed:");
                    $('#spinnerLoading').hide();
                }
            });
        }
    });

});


</script>