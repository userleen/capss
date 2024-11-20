<?php
include 'includes/session.php';

// Set timezone to Philippines
date_default_timezone_set('Asia/Manila'); 

if (isset($_POST['room'])) {
    $room = $_POST['room'];

    try {
        // Prepare the SQL query to fetch the schedule for the scanned room
        $sql = "SELECT CONCAT(e.firstname, ' ', e.lastname) AS teacher_name, 
                       s.subjects, s.class, s.day, s.starttime, s.endtime 
                FROM scheduless s 
                JOIN employees e ON s.teacherid = e.id 
                LEFT JOIN attendance_teacher a ON CONCAT(e.firstname, ' ', e.lastname) = a.teacher_name 
                    AND DATE(a.recorded_at) = CURDATE() -- Check if attendance is recorded for today
                WHERE s.room = ? 
                AND a.teacher_name IS NULL -- Only show schedules without attendance today
                ORDER BY FIELD(s.day, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday')";
        
        $stmt = $conn->prepare($sql);
        $stmt->execute([$room]);

        $schedules = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (count($schedules) > 0) {
            // Build an HTML table to display the schedule
            echo "<div class='table-responsive'>
                    <table class='table table-bordered'>
                        <thead>
                            <tr>
                                <th>Teacher & Time</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>";
            foreach ($schedules as $schedule) {
                $time = htmlspecialchars($schedule['starttime']) . " - " . htmlspecialchars($schedule['endtime']);
                $teacherWithTime = htmlspecialchars($schedule['teacher_name']) . "<br>" . $time;

                echo "<tr>
                        <td>" . $teacherWithTime . "</td>
                        <td>
                            <div class='btn-group-vertical'>
                                <button class='btn btn-success' onclick='markAttendance(\"present\", \"" . htmlspecialchars($schedule['teacher_name']) . "\", \"" . htmlspecialchars($schedule['day']) . "\", \"" . htmlspecialchars($schedule['subjects']) . "\", \"" . htmlspecialchars($schedule['class']) . "\", \"$room\")'>Present</button>
                                <button class='btn btn-warning' onclick='markAttendance(\"late\", \"" . htmlspecialchars($schedule['teacher_name']) . "\", \"" . htmlspecialchars($schedule['day']) . "\", \"" . htmlspecialchars($schedule['subjects']) . "\", \"" . htmlspecialchars($schedule['class']) . "\", \"$room\")'>Late</button>
                                <button class='btn btn-danger' onclick='markAttendance(\"absent\", \"" . htmlspecialchars($schedule['teacher_name']) . "\", \"" . htmlspecialchars($schedule['day']) . "\", \"" . htmlspecialchars($schedule['subjects']) . "\", \"" . htmlspecialchars($schedule['class']) . "\", \"$room\")'>Absent</button>
                            </div>
                        </td>
                    </tr>";
            }
            echo "</tbody></table></div>";
        } else {
            echo "<p>No schedule available for room: " . htmlspecialchars($room) . " or attendance already recorded.</p>";
        }
    } catch (PDOException $e) {
        echo "Error fetching schedule: " . $e->getMessage();
    }
} else {
    echo "<p>No room provided.</p>";
}

// Assuming this is the attendance_teacher endpoint where data is received
$inputData = json_decode(file_get_contents('php://input'), true);
if ($inputData) {
    error_log('Received date: ' . $inputData['date']); 
}
?>

<script>
function markAttendance(status, teacherName, day, subjects, className, room) {
    const currentDateTime = new Date();
    const recordedAt = currentDateTime.toISOString(); // Get current date and time in ISO format
    const time = currentDateTime.toLocaleTimeString(); // Get current time in locale format

    const attendanceData = { 
        teacher_name: teacherName, 
        day: day, 
        subjects: subjects, 
        class: className, 
        room: room, 
        recorded_at: recordedAt, // Send the recorded_at value
        time: time, 
        status: status 
    };

    console.log("Attendance Data: ", attendanceData); // Debugging log

    fetch('attendance_teacher', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(attendanceData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message ); // Show saved data
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}
</script>
