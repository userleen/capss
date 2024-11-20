<?php include 'includes/session.php'; ?>
<?php include 'includes/header.php'; ?>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  <?php include 'includes/navbar.php'; ?>
  <?php include 'includes/menubar.php'; ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Teacher List
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li>Employees</li>
        <li class="active">Teacher List</li>
      </ol>
    </section>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <!-- Main content -->
    <section class="content">
      <?php
        if(isset($_SESSION['error'])){
          echo "
            <div class='alert alert-danger alert-dismissible'>
              <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
              <h4><i class='icon fa fa-warning'></i> Error!</h4>
              ".$_SESSION['error']."
            </div>
          ";
          unset($_SESSION['error']);
        }
        if(isset($_SESSION['success'])){
          echo "
            <div class='alert alert-success alert-dismissible'>
              <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
              <h4><i class='icon fa fa-check'></i> Success!</h4>
              ".$_SESSION['success']."
            </div>
          ";
          unset($_SESSION['success']);
        }
      ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
   
      <!-- Small boxes -->
      <div class="row">
        <div class="col-lg-4 col-xs-4">
          <!-- large box -->
          <div class="small-box" style="background-color: #600000; color: #fff;">
            <div class="inner">
              <?php
               $sql = "SELECT * FROM employees WHERE user_type = 'Teacher'";

                $stmt = $conn->query($sql);
                $rowCount = $stmt->rowCount();
                echo "<h3>".$rowCount."</h3>";
              ?>
              <h2>Teachers</h2>
              <p>Don Honorio Ventura State University <br>LUBAO CAMPUS</p>
            </div>
            <div class="icon">
              <i class="ion ion-person-stalker"></i>
            </div>
  
            <!-- Sidebar inside the box -->
            <div class="box box-solid custom-sidebar">
            <div class="box-header with-border">
  <h3 class="box-title d-none d-md-block">Departments</h3>
  <button class="btn btn-default d-md-none" type="button" data-toggle="collapse" data-target="#departmentsCollapse" aria-expanded="false" aria-controls="departmentsCollapse">
    <i class="fas fa-bars"></i> Departments
  </button>
</div>
<div class="box-body collapse d-md-block" id="departmentsCollapse">
  <!-- Sidebar content -->
  <ul class="list-group">
       <li class="list-group-item">
      <a href="?department=">
        <i class="fas fa-people"></i> All Employees
      </a>
    </li>
    <li class="list-group-item">
      <a href="?department=CCS">
        <i class="fas fa-laptop"></i> College of Computing Studies (CCS)
      </a>
    </li>
    <li class="list-group-item">
      <a href="?department=CEA">
        <i class="fas fa-cogs"></i> College of Engineering and Architecture (CEA)
      </a>
    </li>
    <li class="list-group-item">
      <a href="?department=COE">
        <i class="fas fa-book-open"></i> College of Education (COE)
      </a>
    </li>
    <li class="list-group-item">
      <a href="?department=CHM">
        <i class="fas fa-stethoscope"></i> College of Health and Allied Medical Sciences (CHM)
      </a>
    </li>
    <li class="list-group-item">
      <a href="?department=CBS">
        <i class="fas fa-briefcase"></i> College of Business Studies (CBS)
      </a>
    </li>
  </ul>
</div>
</div>
          </div>
        </div>
      
        <div class="col-lg-8 col-xs-12">
          <div class="box">
            <div class="box-header with-border">
               <a href="#addnew" data-toggle="modal" class="btn btn-primary btn-sm btn-flat"><i class="fa fa-plus"></i> New</a>
            </div>
            
            <div class="box-body">
              <div class="table-responsive">
                <table id="example1" class="table table-bordered">
                  <thead>
                    <th>Photo</th>
                      <th>Name</th>
                    <th>Employee Id</th>
                    <th>Email</th>
                  </thead>
                  <tbody>
<?php
// Get the department from the URL parameter if it exists
$department = isset($_GET['department']) ? $_GET['department'] : null;

// Prepare SQL query based on the department parameter
if ($department) {
    $sql = "SELECT *, employees.id AS empid FROM employees WHERE department = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$department]);
} else {
    $sql = "SELECT *, employees.id AS empid FROM employees";
    $stmt = $conn->query($sql);
}

// Fetch and display the data
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    ?>
    <tr>
        <td>
            <img src="<?php echo (!empty($row['photo'])) ? '../images/'.$row['photo'] : '../images/profile.jpg'; ?>" width="50px" height="50px">
            &nbsp;&nbsp;&nbsp;
        </td>
        <td>
            <a href="Teacher_profile.php?id=<?php echo $row['empid']; ?>"><?php echo $row['firstname'].' '.$row['lastname']; ?></a>
        </td>
        <td><?php echo $row['employee_id']; ?></td>
        <td><?php echo $row['email']; ?></td>
        <td>
            <!-- Check if the email is one of the specified ones to hide the delete button -->
            <?php if ($row['email'] !== 'jesuslovesclouie23@gmail.com' && $row['email'] !== 'REAFOR@GMAIL.COM' && $row['email'] !== 'kateleenb2@gmail.com' ): ?>
            <a href="delete_teacher.php?id=<?php echo $row['employee_id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this teacher?');">Delete</a>

            <?php else: ?>
                <span class="text-muted"></span> <!-- Optional: You can display something else -->
            <?php endif; ?>
        </td>
    </tr>
    <?php
}
?>


                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>   
  </div>
    
  <?php include 'includes/footer.php'; ?>
  <?php include 'includes/employee_modal.php'; ?>
</div>
<?php include 'includes/scripts.php'; ?>

<script>
$(function(){
  $('.edit').click(function(e){
    e.preventDefault();
    $('#edit').modal('show');
    var id = $(this).data('id');
    getRow(id);
  });

  $('.delete').click(function(e){
    e.preventDefault();
    $('#delete').modal('show');
    var id = $(this).data('id');
    getRow(id);
  });

  $('.photo').click(function(e){
    e.preventDefault();
    var id = $(this).data('id');
    getRow(id);
  });

});

function getRow(id){
  $.ajax({
    type: 'POST',
    url: 'employee_row.php',
    data: {id:id},
    dataType: 'json',
    success: function(response){
      $('.empid').val(response.empid);
      $('.employee_id').html(response.employee_id);
      $('.del_employee_name').html(response.firstname+' '+response.lastname);
      $('#employee_name').html(response.firstname+' '+response.lastname);
      $('#edit_firstname').val(response.firstname);
      $('#edit_lastname').val(response.lastname);
      $('#edit_address').val(response.address);
      $('#datepicker_edit').val(response.birthdate);
      $('#edit_contact').val(response.contact_info);
      $('#gender_val').val(response.gender).html(response.gender);
      $('#position_val').val(response.position_id).html(response.description);
      $('#schedule_val').val(response.schedule_id).html(response.time_in+' - '+response.time_out);
    }
  });
}
</script>

<style>
  <style>
  .custom-sidebar {
    background: linear-gradient(135deg, #f0f0f0, #d9d9d9); /* Gradient background */
    border: 1px solid #ccc;
    border-radius: 4px;
    min-height: 400px; /* Adjusted minimum height */
    margin-top: 10px; /* Added margin for spacing */
    /* Show by default */
    display: block;
  }

  .custom-sidebar .box-header {
    background: #e0e0e0;
    border-bottom: 1px solid #ccc;
    font-weight: bold;
  }

  .custom-sidebar .box-body {
    padding: 0;
  }

  .custom-sidebar .list-group-item {
    border: none;
    background: transparent;
    padding: 10px 15px;
    font-size: 16px;
    color: #333;
    transition: background 0.3s ease, color 0.3s ease;
  }

  .custom-sidebar .list-group-item a {
    color: #333;
    text-decoration: none;
  }

  .custom-sidebar .list-group-item:hover {
    background: #d0d0d0; /* Hover background */
    color: #000;
  }

  .custom-sidebar .list-group-item:hover a {
    color: #000;
  }

  .bg-maroon {
    background-color: #600000; /* Dark red background color */
    color: #fff; /* White text color */
  }

  /* Ensure the box fills the available width */
  .small-box {
    width: 100%;
    box-sizing: border-box; /* Include padding and border in the element's total width and height */
  }

  /* Ensure small-box is responsive */
  @media (max-width: 767px) {
    .small-box {
      width: 345%;
    }
  }

  /* Hide the sidebar on small screens */
 
/* Show the heading on larger screens */
/* Hide elements with .d-none on all screens */
.d-none {
  display: none;
}

/* Show elements with .d-md-block on medium screens and up */
@media (min-width: 767px) {
  .d-md-block {
    display: block;
  }
}

/* Hide elements with .d-md-none on medium screens and up */
@media (min-width: 768px) {
  .d-md-none {
    display: none;
  }
}

/* Show elements with .d-md-none on small screens */
@media (max-width: 767px) {
  .d-md-none {
    display: block;
  }
}



  /* Optional: Add responsiveness for the sidebar inside the box */
  .custom-sidebar {
    margin-top: 20px;
  }
</style>

  
</style>

</body>
</html>
