<?php
include 'route.php';
include 'includes/session.php';
include 'timezone.php';

// Get the current time and day of the week
$currentTime = date("H:i:s");
$currentDay = date("l"); // Outputs the full name of the day (e.g., Monday)

// SQL query to retrieve teachers currently teaching along with class information
$sql = "SELECT e.firstname, e.lastname, s.starttime, s.endtime, s.room, s.class 
        FROM employees e
        INNER JOIN scheduless s ON e.id = s.teacherid
        WHERE s.starttime <= :currentTime 
        AND s.endtime >= :currentTime 
        AND s.day = :currentDay";

// Prepare the SQL statement
$stmt = $conn->prepare($sql);

// Bind the parameters
$stmt->bindParam(':currentTime', $currentTime);
$stmt->bindParam(':currentDay', $currentDay);

// Execute the query
$stmt->execute();

// Fetch the result
$teachersWithClasses = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include 'includes/header.php'; ?>

<!-- Include Bootstrap CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">

<body class="hold-transition skin-blue sidebar-mini">
    <div class="wrapper">
        <?php include 'includes/navbar.php'; ?>
        <?php include 'includes/menubar.php'; ?>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <h1>
                    Teachers Currently Teaching
                </h1>
                <ol class="breadcrumb">
                    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li class="active">Teachers Currently Teaching</li>
                </ol>
            </section>
            <!-- Main content -->
            <section class="content">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="box">
                            <div class="box-header">
                                <h3 class="box-title">Teachers Currently Teaching</h3>
                            </div>
                            <div class="box-body">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Teacher Name</th>
                                            <th>Room</th>
                                            <th>Class</th>
                                            <th>Class Start Time</th>
                                            <th>Class End Time</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($teachersWithClasses)) : ?>
                                            <?php foreach ($teachersWithClasses as $teacher) : ?>
                                                <tr>
                                                    <td><?= htmlspecialchars($teacher['firstname'] . ' ' . $teacher['lastname']) ?></td>
                                                    <td><?= $teacher['room'] ?></td>
                                                    <td><?= $teacher['class'] ?></td>
                                                    <td><?= $teacher['starttime'] ?></td>
                                                    <td><?= $teacher['endtime'] ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else : ?>
                                            <tr>
                                                <td colspan='5'>No teachers currently have classes.</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>
</body>

</html>
