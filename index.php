<?php
require_once 'php/db_connect.php';

session_start();
$role = 'NORMAL';

if(!isset($_SESSION['userID'])){
  echo '<script type="text/javascript">';
  echo 'window.location.href = "login.html";</script>';
}
else{
  $role = $_SESSION['role'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Sky Global | Jobs Management System</title>

  <link rel="icon" href="images/logo.png" type="image">
  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- IonIcons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <!-- daterange picker -->
  <link rel="stylesheet" href="plugins/daterangepicker/daterangepicker.css">
  <link rel="stylesheet" href="plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
  <!-- Toastr -->
  <link rel="stylesheet" href="plugins/toastr/toastr.min.css">
  <link rel="stylesheet" href="http://cdn.leafletjs.com/leaflet-0.7.3/leaflet.css" />
  <!-- DataTables -->
  <link rel="stylesheet" href="plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <link rel="stylesheet" href="plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
</head>
<!--
`body` tag options:

  Apply one or more of the following classes to to the body tag
  to get the desired effect

  * sidebar-collapse
  * sidebar-mini
-->
<body class="hold-transition sidebar-mini">
<div class="loading" id="spinnerLoading">
<div class='uil-ring-css' style='transform:scale(0.79);'>
    <div></div>
</div>
</div>
<div class="wrapper">
  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="#" class="brand-link logo-switch">
      <img src="images/logo-big.png" alt="Sneakercube Logo" class="brand-image-xl logo-xl">
      <img src="images/logo.png" alt="Sneakercube Logo" class="brand-image-xl logo-xs">
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" id="sideMenu" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-item">
            <a href="#sales" data-file="sales.php" class="nav-link link">
              <i class="nav-icon fas fa-home"></i>
              <p>Sales</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="#jobs" data-file="jobs.php" class="nav-link link">
              <i class="nav-icon fas fa-home"></i>
              <p>Jobs</p>
            </a>
          </li>
          <?php 
            if($role == "ADMIN"){
              echo '<li class="nav-item">
                <a href="#users" data-file="users.php" class="nav-link link">
                  <i class="nav-icon fas fa-user"></i>
                  <p>Staffs</p>
                </a>
              </li>
              <li class="nav-item has-treeview">
                <a href="#" class="nav-link">
                  <i class="nav-icon fas fa-database"></i>
                  <p>Master Data<i class="fas fa-angle-left right"></i></p>
                </a>
                <ul class="nav nav-treeview" style="display: none;">
                  <li class="nav-item">
                    <a href="#suppliers" data-file="suppliers.php" class="nav-link link">
                      <i class="nav-icon fas fa-industry"></i>
                      <p>Flyer</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="#airlines" data-file="airlines.php" class="nav-link link">
                      <i class="nav-icon fas fa-shopping-cart"></i>
                      <p>Airlines</p>
                    </a>
                  </li>
                </ul>
              </li>';
            }
          ?>
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-cogs"></i>
              <p>Settings<i class="fas fa-angle-left right"></i></p>
            </a>
        
            <ul class="nav nav-treeview" style="display: none;">
              <?php 
                if($role == "ADMIN"){
                  echo '<li class="nav-item">
                  <a href="#company" data-file="company.php" class="nav-link link">
                    <i class="nav-icon fas fa-building"></i>
                    <p>Company Profile</p>
                  </a>
                </li>';
                }
              ?>
              <li class="nav-item">
                <a href="#myprofile" data-file="myprofile.php" class="nav-link link">
                  <i class="nav-icon fas fa-id-badge"></i>
                  <p>Profile</p>
                </a>
              </li>
          
              <li class="nav-item">
                <a href="#changepassword" data-file="changePassword.html" class="nav-link link">
                  <i class="nav-icon fas fa-key"></i>
                  <p>Change Password</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item">
            <a href="php/logout.php" class="nav-link">
              <i class="nav-icon fas fa-sign-out-alt"></i>
              <p>Logout</p>
            </a>
          </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper" id="mainContents"></div>
  <!-- /.content-wrapper -->

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->

  <!-- Main Footer -->
  <footer class="main-footer">
    <strong>Copyright &copy; 2023 <a href="#">SKy Global</a>.</strong>All rights reserved.
    <div class="float-right d-none d-sm-inline-block"><b>Version</b> 1.0.0</div>
  </footer>
</div>
<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->

<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE -->
<script src="dist/js/adminlte.js"></script>
<!-- OPTIONAL SCRIPTS -->
<script src="plugins/chart.js/Chart.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>
<!-- date-range-picker -->
<script src="plugins/moment/moment.min.js"></script>
<script src="plugins/daterangepicker/daterangepicker.js"></script>
<script src="plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
<!-- toastr -->
<script src="plugins/toastr/toastr.min.js"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="dist/js/pages/dashboard3.js"></script>
<script src="plugins/heatmap/build/heatmap.js"></script>
<script src="plugins/inputmask/jquery.inputmask.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.8.0/jszip.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.8.0/xlsx.js"></script>
<!-- DataTables  & Plugins -->
<script src="plugins/datatables/jquery.dataTables.min.js"></script>
<script src="plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="plugins/pdfmake/pdfmake.min.js"></script>
<script src="plugins/pdfmake/vfs_fonts.js"></script>
<script src="plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="plugins/datatables-buttons/js/buttons.print.min.js"></script>
<script src="plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
<script src="plugins/jquery-validation/jquery.validate.min.js"></script>
<script>
var ouStartDate = "";
var ouEndDate = "";
var ouStartTime = "";
var ouEndTime = "";

$(function () {
  toastr.options = {
    "closeButton": false,
    "debug": false,
    "newestOnTop": false,
    "progressBar": false,
    "positionClass": "toast-top-right",
    "preventDuplicates": false,
    "onclick": null,
    "showDuration": "300",
    "hideDuration": "1000",
    "timeOut": "5000",
    "extendedTimeOut": "1000",
    "showEasing": "swing",
    "hideEasing": "linear",
    "showMethod": "fadeIn",
    "hideMethod": "fadeOut"
  }

  $('#sideMenu').on('click', '.link', function(){
      $('#spinnerLoading').hide();
      var files = $(this).attr('data-file');
      $('#sideMenu').find('.active').removeClass('active');
      $(this).addClass('active');
      
      $.get(files, function(data) {
        $('#mainContents').html(data);
        $('#spinnerLoading').hide();
      });
  });

  $('#goToProfile').on('click', function(){
      $('#spinnerLoading').show();
      var files = $(this).attr('data-file');
      $('#sideMenu').find('.active').removeClass('active');
      $(this).addClass('active');
      
      $.get(files, function(data) {
          $('#mainContents').html(data);
          $('#spinnerLoading').hide();
      });
  });
  
  $("a[href='#sales']").click();
});

function formatDate(date) {
  var d = new Date(date),
  month = '' + (d.getMonth() + 1),
  day = '' + d.getDate(),
  year = d.getFullYear();

  if (month.length < 2) 
    month = '0' + month;

  if (day.length < 2) 
    day = '0' + day;

  return [year, month, day].join('-');
}

/*function report(type){
  if(type == 'passedingroundmonthly' || type =='passedinlvl1monthly' || type =='groundlvl1monthly' || type =='dastotalvisitorsmonthly' || type =='dastotalzonevisitorsmonthly'){
    window.open("php/export.php?fromDate="+ouStartDate+"&toDate="+ouEndDate+"&type="+type);
  }
  else if(type == 'passedingrounddaily' || type =='passedinlvl1daily' || type =='groundlvl1daily' || type =='dastotalvisitorsdaily' || type =='dastotalzonevisitorsdaily'){
    window.open("php/export.php?fromDate="+ouStartTime+"&toDate="+ouEndTime+"&type="+type);
  }
}*/
</script>
</body>
</html>
