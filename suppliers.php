<?php
require_once 'php/db_connect.php';

session_start();

if(!isset($_SESSION['userID'])){
  echo '<script type="text/javascript">';
  echo 'window.location.href = "login.html";</script>';
}
else{
  $user = $_SESSION['userID'];
}
?>

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
			<div class="col-sm-6">
				<h1 class="m-0 text-dark">Suppliers</h1>
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
                  <div class="col-9"></div>
                  <div class="col-3">
                      <button type="button" class="btn btn-block bg-gradient-warning btn-sm" id="addSuppliers">Add Suppliers</button>
                  </div>
              </div>
          </div>
					<div class="card-body">
						<table id="supplierTable" class="table table-bordered table-striped">
							<thead>
								<tr>
                                    <th>Code</th>
									<th>Name</th>
									<th>Address</th>
                                    <th>Address 2</th>
                                    <th>Address 3</th>
                                    <th>Address 4</th>
                                    <th>PostCode</th>
									<th>Phone</th>
									<th>Email</th>
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

<div class="modal fade" id="addModal">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <form role="form" id="supplierForm">
            <div class="modal-header">
              <h4 class="modal-title">Add Suppliers</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="card-body">
                <div class="form-group">
                  <input type="hidden" class="form-control" id="id" name="id">
                </div>
                <div class="form-group">
                  <label for="name">Supplier Code *</label>
                  <input type="text" class="form-control" name="code" id="code" placeholder="Enter Supplier Code" required>
                </div>
                <div class="form-group">
                  <label for="name">Supplier Name *</label>
                  <input type="text" class="form-control" name="name" id="name" placeholder="Enter Supplier Name" required>
                </div>
                <div class="form-group"> 
                  <label for="address">Address *</label>
                  <textarea class="form-control" id="address" name="address" placeholder="Enter your address" required></textarea>
                </div>
                <div class="form-group"> 
                  <label for="address2">Address 2*</label>
                  <textarea class="form-control" id="address2" name="address2" placeholder="Enter your address 2" required></textarea>
                </div>
                <div class="form-group"> 
                  <label for="address3">Address 3*</label>
                  <textarea class="form-control" id="address3" name="address3" placeholder="Enter your address 3" required></textarea>
                </div>
                <div class="form-group"> 
                  <label for="address4">Address 4*</label>
                  <textarea class="form-control" id="address4" name="address4" placeholder="Enter your address 4" required></textarea>
                </div>
                <div class="form-group"> 
                  <label for="postCode">Postcode *</label>
                  <input class="form-control" id="postCode" name="postCode" placeholder="Enter your post code" required>
                </div>
                <div class="form-group">
                  <label for="phone">Phone *</label>
                  <input type="text" class="form-control" name="phone" id="phone" placeholder="Enter Full Name" required>
                </div>
                <div class="form-group"> 
                  <label for="email">Email *</label>
                  <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required>
                </div>
              </div>
            </div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary" name="submit" id="submitMember">Submit</button>
            </div>
        </form>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<script>
$(function () {
    $("#supplierTable").DataTable({
        "responsive": true,
        "autoWidth": false,
        'processing': true,
        'serverSide': true,
        'serverMethod': 'post',
        'ajax': {
            'url':'php/loadSupplier.php'
        },
        'columns': [
            { data: 'supplier_code' },
            { data: 'supplier_name' },
            { data: 'supplier_address' },
            { data: 'supplier_address2' },
            { data: 'supplier_address3' },
            { data: 'supplier_address4' },
            { data: 'postcode' },
            { data: 'supplier_phone' },
            { data: 'supplier_email' },
            { 
                data: 'id',
                render: function ( data, type, row ) {
                    return '<div class="row"><div class="col-3"><button type="button" id="edit'+data+'" onclick="edit('+data+')" class="btn btn-success btn-sm"><i class="fas fa-pen"></i></button></div><div class="col-3"><button type="button" id="deactivate'+data+'" onclick="deactivate('+data+')" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button></div></div>';
                }
            }
        ],
        "rowCallback": function( row, data, index ) {

            $('td', row).css('background-color', '#E6E6FA');
        },
    });
    
    $.validator.setDefaults({
        submitHandler: function () {
            $('#spinnerLoading').show();
            $.post('php/suppliers.php', $('#supplierForm').serialize(), function(data){
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
            });
        }
    });

    $('#addSuppliers').on('click', function(){
        $('#addModal').find('#id').val("");
        $('#addModal').find('#code').val("");
        $('#addModal').find('#name').val("");
        $('#addModal').find('#address').val("");
        $('#addModal').find('#address2').val("");
        $('#addModal').find('#address3').val("");
        $('#addModal').find('#address4').val("");
        $('#addModal').find('#postCode').val("");
        $('#addModal').find('#phone').val("");
        $('#addModal').find('#email').val("");
        $('#addModal').modal('show');
        
        $('#supplierForm').validate({
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
});

function edit(id){
    $('#spinnerLoading').show();
    $.post('php/getSupplier.php', {userID: id}, function(data){
        var obj = JSON.parse(data);
        
        if(obj.status === 'success'){
            $('#addModal').find('#id').val(obj.message.id);
            $('#addModal').find('#code').val(obj.message.supplier_code);
            $('#addModal').find('#name').val(obj.message.supplier_name);
            $('#addModal').find('#address').val(obj.message.supplier_address);
            $('#addModal').find('#address2').val(obj.message.supplier_address2);
            $('#addModal').find('#address3').val(obj.message.supplier_address3);
            $('#addModal').find('#address4').val(obj.message.supplier_address4);
            $('#addModal').find('#postCode').val(obj.message.postcode);
            $('#addModal').find('#phone').val(obj.message.supplier_phone);
            $('#addModal').find('#email').val(obj.message.supplier_email);
            $('#addModal').modal('show');
            
            $('#supplierForm').validate({
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

function deactivate(id){
    $('#spinnerLoading').show();
    $.post('php/deleteSupplier.php', {userID: id}, function(data){
        var obj = JSON.parse(data);
        
        if(obj.status === 'success'){
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
            toastr["error"]("Something wrong when activate", "Failed:");
            $('#spinnerLoading').hide();
        }
    });
}
</script>