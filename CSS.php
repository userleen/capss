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
      <h1>Students List</h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li>Employees</li>
        <li class="active">Student List</li>
      </ol>
    </section>
    
    <!-- Main content -->
    <section class="content">
      <?php
        if (isset($_SESSION['error'])) {
          echo "
            <div class='alert alert-danger alert-dismissible'>
              <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
              <h4><i class='icon fa fa-warning'></i> Error!</h4>
              " . $_SESSION['error'] . "
            </div>
          ";
          unset($_SESSION['error']);
        }
        if (isset($_SESSION['success'])) {
          echo "
            <div class='alert alert-success alert-dismissible'>
              <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
              <h4><i class='icon fa fa-check'></i> Success!</h4>
              " . $_SESSION['success'] . "
            </div>
          ";
          unset($_SESSION['success']);
        }
        if (isset($_SESSION['warning'])) {
          echo "
            <div class='alert alert-warning alert-dismissible'>
              <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
              <h4><i class='icon fa fa-exclamation-triangle'></i> Warning!</h4>
              " . $_SESSION['warning'] . "
            </div>
          ";
          unset($_SESSION['warning']);
        }
      ?>
      
      <!-- Small boxes -->
      <div class="row">
        <div class="col-lg-4 col-xs-4">
          <!-- large box -->
          <div class="small-box bg-black">
            <div class="inner">
              <h3>BSIT</h3>
              <p>Bachelor of Science <br>in Information Technology</p>
            </div>
            <div class="icon">
              <i class="ion ion-laptop" style="color: white;"></i>
            </div>
  

          <!-- Sidebar inside the box -->
          <div class="box box-solid custom-sidebar">
            <div class="box-header with-border">
              <h3 class="box-title">Year Levels</h3>
            </div>
            <div class="box-body">
              <!-- Sidebar content -->
              <ul class="list-group">
                <li class="list-group-item"><a href="?year=1">1st Year</a></li>
                <li class="list-group-item"><a href="?year=2">2nd Year</a></li>
                <li class="list-group-item"><a href="?year=3">3rd Year</a></li>
                <li class="list-group-item"><a href="?year=4">4th Year</a></li>
              </ul>
            </div>
          </div>
        </div>
        </div>
        <!-- Right Column: Student Table -->
        <div class="col-lg-8 col-xs-12">
          <div class="box">
            <div class="box-header with-border">
              <button type="button" class="btn btn-info btn-sm btn-flat" data-toggle="modal" data-target="#uploadCsvModal">
                <i class="fa fa-upload"></i> Upload Student List
              </button>
            </div>

            <!-- Upload CSV Modal -->
            <div class="modal fade" id="uploadCsvModal" tabindex="-1" role="dialog" aria-labelledby="uploadCsvModalLabel">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="uploadCsvModalLabel">Upload CSV File list of students</h4>
                  </div>
                  <form action="upload_csv.php" method="post" enctype="multipart/form-data">
                    <div class="modal-body">
                      <div class="form-group">
                        <label for="csvFile">Choose CSV File:</label>
                        <input type="file" name="file" id="csvFile" class="form-control" accept=".csv" required>
                      </div>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                      <button type="submit" name="upload" class="btn btn-primary">Upload</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
            <!-- End of Upload CSV Modal -->

            <!-- Box Body: Student Table -->
            <div class="box-body">
              <div class="table-responsive">
                <table id="example1" class="table table-bordered">
                  <thead>
                    <tr>
                      <th>Student ID</th>
                      <th>Last Name</th>
                      <th>First Name</th>
                      <th>Class</th>
                      <th>Gender</th>
                      <th>Email</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                      // Check if year is set in query parameters
                      $year = isset($_GET['year']) ? (int)$_GET['year'] : null;

                      // Prepare SQL query based on the year parameter
                      if ($year) {
                        $sql = "SELECT * FROM students WHERE year = ?";
                        $stmt = $conn->prepare($sql);
                        $stmt->execute([$year]);
                      } else {
                        $sql = "SELECT * FROM students";
                        $stmt = $conn->query($sql);
                      }

                      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo "<tr>";
                        echo "<td><a href='Admin_student_prof.php?id=" . $row['student_id'] . "'>" . $row['student_id'] . "</a></td>";
                        echo "<td>" . $row['lastname'] . "</td>";
                        echo "<td>" . $row['firstname'] . "</td>";
                        echo "<td>" . $row['class'] . "</td>";
                        echo "<td>" . $row['gender'] . "</td>";
                        echo "<td>" . $row['email'] . "</td>";
                        echo "</tr>";
                      }
                    ?>
                  </tbody>
                </table>
              </div>
            </div>
            <!-- End of Box Body -->
          </div>
        </div>
      </div>
    </section>   
  </div>
  
  <?php include 'includes/footer.php'; ?>
  <?php include 'includes/student_modal.php'; ?>
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
</script>
<!-- Custom CSS -->
<style>
  .custom-sidebar {
    background: linear-gradient(135deg, #f0f0f0, #d9d9d9); /* Gradient background */
    border: 1px solid #ccc;
    border-radius: 4px;
    min-height: 450px; /* Adjusted minimum height */
    margin-top: 10px; /* Added margin for spacing */
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
</style>

</body>
</html>
