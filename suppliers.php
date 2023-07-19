<?php
require_once 'php/db_connect.php';

session_start();

if(!isset($_SESSION['userID'])){
  echo '<script type="text/javascript">';
  echo 'window.location.href = "login.html";</script>';
}
else{
  $user = $_SESSION['userID'];
  $countries1 = $db->query("SELECT * FROM countries");
  $countries2 = $db->query("SELECT * FROM countries");
}
?>

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
			<div class="col-sm-6">
				<h1 class="m-0 text-dark">Flyers</h1>
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
                                <button type="button" class="btn btn-block bg-gradient-warning btn-sm" id="addSuppliers">Add Flyers</button>
                            </div>
                        </div>
                    </div>
					<div class="card-body">
						<table id="supplierTable" class="table table-bordered table-striped">
							<thead>
								<tr>
                                    <th>Name</th>
									<th>Address</th>
									<th>Phone</th>
									<th>Email</th>
                                    <th>Passport Expiry Date</th>
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
              <h4 class="modal-title">Add Flyer</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="card-body">
                <input type="hidden" class="form-control" id="id" name="id">    
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="profilePic">Profile Picture *</label>
                            <div id="image-preview">
                                <label for="image-upload" id="image-label">Choose Image</label>
                                <input type="file" name="image-upload" id="image-upload" required/>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="username">Username *</label>
                            <input type="text" class="form-control" name="username" id="username" placeholder="Enter User Name" required>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label for="stationCountry">Station Country *</label>
                            <select class="form-control" style="width: 100%;" id="stationCountry" name="stationCountry">
                                <option value="" selected disabled hidden>Please Select</option>
                                <?php while($countries1Row=mysqli_fetch_assoc($countries1)){ ?>
                                    <option value="<?=$countries1Row['country'] ?>"><?=$countries1Row['country'] ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="firstName">First Name *</label>
                            <input type="text" class="form-control" name="firstName" id="firstName" placeholder="Enter first Name" required>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label for="lastName">Last Name *</label>
                            <input type="text" class="form-control" name="lastName" id="lastName" placeholder="Enter last Name" required>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="form-group"> 
                            <label for="address">Address</label>
                            <textarea class="form-control" id="address" name="address" placeholder="Enter your address"></textarea>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="phone">Phone *</label>
                            <input type="text" class="form-control" name="phone" id="phone" placeholder="Enter Full Name" required>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group"> 
                            <label for="email">Email *</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="nationality">Nationality *</label>
                            <select class="form-control" style="width: 100%;" id="nationality" name="nationality">
                                <option value="" selected disabled hidden>Please Select</option>
                                <?php while($countries2Row=mysqli_fetch_assoc($countries2)){ ?>
                                    <option value="<?=$countries2Row['country'] ?>"><?=$countries2Row['country'] ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label for="dob">Date of Birth *</label>
                            <div class="input-group date" id="dob" data-target-input="nearest">
                                <input type="text" class="form-control datetimepicker-input" id="dob" name="dob" data-target="#dob" required/>
                                <div class="input-group-append" data-target="#dob" data-toggle="datetimepicker">
                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="passport">Passport *</label>
                            <input type="text" class="form-control" name="passport" id="passport" placeholder="Enter Passport" required>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group"> 
                            <label for="passportExpiry">Passport Expiry Date *</label>
                            <div class="input-group date" id="passportExpiry" data-target-input="nearest">
                                <input type="text" class="form-control datetimepicker-input" id="passportExpiry" name="passportExpiry" data-target="#passportExpiry" required/>
                                <div class="input-group-append" data-target="#passportExpiry" data-toggle="datetimepicker">
                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="form-group"> 
                            <label for="remark">Remark </label>
                            <textarea class="form-control" id="remark" name="remark" placeholder="Enter your remark"></textarea>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="profilePic">Passport Picture *</label>
                            <div id="image-preview3">
                                <label for="image-upload3" id="image-label3">Choose Image</label>
                                <input type="file" name="image-upload3" id="image-upload3" required/>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label for="visaPic">Visa Picture *</label>
                            <div id="image-preview2">
                                <label for="image-upload2" id="image-label2">Choose Image</label>
                                <input type="file" name="image-upload2" id="image-upload2" required/>
                            </div>
                        </div>
                    </div>
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
    $('#passportExpiry').datetimepicker({
        format: 'YYYY-MM-DD'
    });

    $('#dob').datetimepicker({
        format: 'YYYY-MM-DD'
    });

    $.uploadPreview({
        input_field: "#image-upload",   // Default: .image-upload
        preview_box: "#image-preview",  // Default: .image-preview
        label_field: "#image-label",    // Default: .image-label
        label_default: "Choose Image",   // Default: Choose File
        label_selected: "Change Image",  // Default: Change File
        no_label: false                 // Default: false
    });

    $.uploadPreview({
        input_field: "#image-upload2",   // Default: .image-upload
        preview_box: "#image-preview2",  // Default: .image-preview
        label_field: "#image-label2",    // Default: .image-label
        label_default: "Choose Image",   // Default: Choose File
        label_selected: "Change Image",  // Default: Change File
        no_label: false                 // Default: false
    });

    $.uploadPreview({
        input_field: "#image-upload3",   // Default: .image-upload
        preview_box: "#image-preview3",  // Default: .image-preview
        label_field: "#image-label3",    // Default: .image-label
        label_default: "Choose Image",   // Default: Choose File
        label_selected: "Change Image",  // Default: Change File
        no_label: false                 // Default: false
    });

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
            { data: 'supplier_name' },
            { data: 'supplier_address' },
            { data: 'supplier_phone' },
            { data: 'supplier_email' },
            { data: 'passport_expiry_date' },
            { 
                data: 'id',
                render: function ( data, type, row ) {
                    return '<div class="row"><div class="col-3"><button type="button" id="edit'+data+'" onclick="edit('+data+')" class="btn btn-success btn-sm"><i class="fas fa-pen"></i></button></div><div class="col-3"><button type="button" id="deactivate'+data+'" onclick="deactivate('+data+')" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button></div></div>';
                }
            }
        ]
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
            })
            /*$.ajax({
                type: "POST",
                enctype: 'multipart/form-data',
                url: "php/suppliers.php",
                data: $('#supplierForm').serialize(),
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
            });*/
        }
    });

    $('#addSuppliers').on('click', function(){
        $('#addModal').find('#id').val("");
        $('#addModal').find('#name').val("");
        $('#addModal').find('#address').val("");
        $('#addModal').find('#phone').val("");
        $('#addModal').find('#email').val("");
        $('#addModal').find('#passport').val("");
        $('#addModal').find('#passportExpiry').val("");
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
            $('#addModal').find('#name').val(obj.message.supplier_name);
            $('#addModal').find('#address').val(obj.message.supplier_address);
            $('#addModal').find('#phone').val(obj.message.supplier_phone);
            $('#addModal').find('#email').val(obj.message.supplier_email);
            $('#addModal').find('#passport').val(obj.message.passport);
            $('#addModal').find('#passportExpiry').val(obj.message.passport_expiry_date);
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