<?php include 'includes/session.php'; ?>
<?php include 'includes/header.php'; ?>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  <?php include 'includes/navbar.php'; ?>
  <?php include 'includes/menubar.php'; ?>

  <div class="content-wrapper">
    <section class="content-header">
      <h1>
        Student List
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li>Students</li>
        <li class="active">Student List</li>
      </ol>
    </section>
    
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

      <div class="row">
        <div class="col-lg-12">
          <div class="small-box" style="background-color: #600000; color: #fff;">
            <div class="inner">
              <h2>CLASSES</h2>
              <p>Don Honorio Ventura State University <br>LUBAO CAMPUS</p>
            </div>
            <div class="icon">
              <i class="ion ion-home"></i>
            </div>
          </div>

          <div class="row">
            <div class="col-lg-12 margin-top-negative">
              <div class="box">
                <div class="box-header with-border">
                  <h3 class="box-title dropdown">
                    <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      SUBJECTS
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <?php
                    if (isset($_SESSION['admin'])) {
                        $user_id = $_SESSION['admin'];

                        try {
                            $sql = "SELECT DISTINCT subjects FROM scheduless WHERE teacherid = ? ORDER BY subjects";
                            $stmt = $conn->prepare($sql);
                            $stmt->execute([$user_id]);
                            $subjects = $stmt->fetchAll(PDO::FETCH_COLUMN);
                        } catch (PDOException $e) {
                            echo 'Query failed: ' . htmlspecialchars($e->getMessage());
                            $subjects = [];
                        }
                    } else {
                        echo 'User ID is not set in session.';
                        $subjects = [];
                    }
                    ?>
                    <?php if (!empty($subjects)): ?>
                      <?php foreach ($subjects as $subject): ?>
                          <a class="dropdown-item" href="?subject=<?php echo urlencode($subject); ?>"><?php echo htmlspecialchars($subject); ?></a><br>
                      <?php endforeach; ?>
                    <?php else: ?>
                      <a class="dropdown-item" href="#">No subjects available</a>
                    <?php endif; ?>
                    </div>
                    
                    <h3 class="box-title dropdown">
                      <button class="btn btn-default dropdown-toggle" type="button" id="dropdownClassesButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                          CLASSES
                      </button>
                      <div class="dropdown-menu" aria-labelledby="dropdownClassesButton">
                          <?php
                          try {
                              $sql = "SELECT DISTINCT class FROM students ORDER BY class"; // Adjust query as needed
                              $stmt = $conn->query($sql);
                              $classes = $stmt->fetchAll(PDO::FETCH_COLUMN);
                          } catch (PDOException $e) {
                              echo 'Query failed: ' . htmlspecialchars($e->getMessage());
                              $classes = [];
                          }
                          ?>
                          <?php if (!empty($classes)): ?>
                              <?php foreach ($classes as $class): ?>
                                  <a class="dropdown-item" href="?class=<?php echo urlencode($class); ?>"><?php echo htmlspecialchars($class); ?></a><br>
                              <?php endforeach; ?>
                          <?php else: ?>
                              <a class="dropdown-item" href="#">No classes available</a>
                          <?php endif; ?>
                      </div>
                    </h3>
                  
                  </h3>
                </div>

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
                        // Get the selected class from URL parameters
                        $selectedClass = isset($_GET['class']) ? $_GET['class'] : '';

                        // Prepare the SQL query based on selected class
                        if ($selectedClass) {
                          $sql = "SELECT * FROM students WHERE class = :class";
                          $stmt = $conn->prepare($sql);
                          $stmt->bindParam(':class', $selectedClass);
                        } else {
                          $sql = "SELECT * FROM students"; // Default query to get all students
                          $stmt = $conn->query($sql);
                        }
                        
                        $stmt->execute();

                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            echo "<tr>";
                            echo "<td><a href='student_attendance_report.php?id=" . $row['student_id'] . "&user_id=" . urlencode($_SESSION['admin']) . "'>" . $row['student_id'] . "</a></td>";
                            echo "<td>" . htmlspecialchars($row['lastname']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['firstname']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['class']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['gender']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                            echo "</tr>";
                        }
                        ?>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>

        </div>
      </div>
    </section>
  </div>

  <?php include 'includes/footer.php'; ?>
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

<style>
.custom-sidebar {
  border: 1px solid #ccc;
  border-radius: 4px;
  min-height: 0px; /* Adjusted minimum height */
  margin-top: -20px;
  margin-bottom: -10px; /* Space below the sidebar */
}

.custom-sidebar .box-header {
  border-bottom: 1px solid #ccc;
  font-weight: bold;
}

.custom-sidebar .box-body {
  padding: 0;
}
.margin-top-negative {
  margin-top: -20px;
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

.dropdown-menu {
    background-color: #f8f9fa; /* Light background color */
    border: 1px solid #ccc; /* Border color */
    border-radius: 4px; /* Rounded corners */
    min-width: 160px; /* Minimum width for dropdown */
    z-index: 1000; /* Ensure it appears above other elements */
}
</style>
</body>
</html>
