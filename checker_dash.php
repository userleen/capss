<?php  
include 'includes/session.php';
include 'timezone.php'; 

$today = date('Y-m-d');
$year = date('Y');
if(isset($_GET['year'])){
    $year = $_GET['year'];
}

include 'includes/header.php'; 
?>

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
    <?php include 'includes/navbar.php'; ?>
    <?php include 'includes/menubar.php'; ?>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <section class="content-header">
            <h1>Checker Dashboard</h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Dashboard</li>
            </ol>
        </section>

        <section class="content">
            <div class="row">
                <style>
                   
                    #qr-reader {
                        width: 100%;
                        max-width: 500px;
                        margin: 0 auto;
                    }
                    #qr-result {
                        margin-top: 20px;
                        font-size: 18px;
                    }
                </style>

<div class="col-lg-12">
                    <div class="small-box bg-aqua">
                        <div class="inner with-border">
                            <h2 style="text-align: left;">
                                Hello, <?php echo isset($user['firstname']) && isset($user['lastname']) ? htmlspecialchars($user['firstname'] . ' ' . $user['lastname']) : 'Guest'; ?>!
                            </h2>
                            <div style="float: left;">
                                <p id="date" style="display: inline-block; margin-left: 10px;"></p>
                                <p id="time" class="bold" style="display: inline-block;"></p>
                            </div>
                            <div class="clearfix"></div>
                            <hr>
                            <div>
                                <?php
                                $currentTime = date("H:i:s");
                                $currentDay = date("l");

                                $sql = "SELECT COUNT(DISTINCT teacherid) AS teacher_count 
                                        FROM scheduless 
                                        WHERE starttime <= :currentTime AND endtime >= :currentTime AND day = :currentDay";

                                $stmt = $conn->prepare($sql);
                                $stmt->bindParam(':currentTime', $currentTime);
                                $stmt->bindParam(':currentDay', $currentDay);
                                $stmt->execute();

                                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                                echo "<div><h3>" . $result['teacher_count'] . "</h3></div>";
                                ?>
                                <p>Total Teacher currently teaching</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-person-stalker"></i>
                            </div>
                            <a href="current_teaching.php" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                            <hr>
                           <button type="button" class="btn btn-primary" onclick="window.location.href='scan.php'">
    Open QR Code Scanner
</button>

                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
    <div class="box">
        <div class="box-header">
            <h3 class="box-title">Teacher Attendance Today</h3>
        </div>
        <div class="box-body">
        <table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>Name</th>
            <th>Room</th>
            <th>Time In</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
    <?php
    try {
        // Fetch attendance for today without joining employees
        $sqlAttendance = "SELECT * FROM attendance_teacher WHERE DATE(recorded_at) = CURDATE()";
        $stmtAttendance = $conn->prepare($sqlAttendance);
        $stmtAttendance->execute();
        $attendanceRows = $stmtAttendance->fetchAll(PDO::FETCH_ASSOC);

        if ($attendanceRows) {
            foreach ($attendanceRows as $attendanceRow) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($attendanceRow['teacher_name']) . "</td>"; // Assuming you have 'teacher_name' in the table
                echo "<td>" . htmlspecialchars($attendanceRow['room']) . "</td>";
                echo "<td>" . htmlspecialchars($attendanceRow['recorded_at']) . "</td>"; // Adjust if needed
                echo "<td>" . htmlspecialchars($attendanceRow['status']) . "</td>"; // Display status
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='4'>No attendance records found for today.</td></tr>";
        }
    } catch (PDOException $e) {
        echo "<tr><td colspan='4'>Error fetching attendance data: " . $e->getMessage() . "</td></tr>";
    }
    ?>
    </tbody>
</table>

        </div>
        <div class="text-center" style="background-color: rgba(0, 0, 0, 0.5); padding: 10px;">
            <a href="attendance.php" style="color: #fff;">More info <i class="fa fa-arrow-circle-right"></i></a>
        </div>
    </div>
</div>
</div>
        </section>
    </div>

    <?php include 'includes/footer.php'; ?>

  <?php include 'includes/scripts.php'; ?>

   

</body>
</html>
