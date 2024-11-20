<?php
session_start();
include 'conn.php'; // Include your database connection file

$user_id = $_SESSION['student_id'];

$sqlAttendance = "SELECT subject, classinfo AS class, room, time_in, status 
                  FROM student_attendance 
                  WHERE DATE(time_in) = CURDATE()";
$stmtAttendance = $conn->prepare($sqlAttendance);
$stmtAttendance->execute();
$attendanceRows = $stmtAttendance->fetchAll(PDO::FETCH_ASSOC);

// Build HTML output for the table
$output = '';
if ($attendanceRows) {
    foreach ($attendanceRows as $attendanceRow) {
        $output .= "<tr>";
        $output .= "<td>" . htmlspecialchars($attendanceRow['subject']) . "</td>";
        $output .= "<td>" . htmlspecialchars($attendanceRow['class']) . "</td>";
        $output .= "<td>" . htmlspecialchars($attendanceRow['room']) . "</td>";
        $output .= "<td>" . htmlspecialchars($attendanceRow['time_in']) . "</td>";
        $output .= "<td>" . htmlspecialchars($attendanceRow['status']) . "</td>";
        $output .= "</tr>";
    }
} else {
    $output .= "<tr><td colspan='5'>No attendance records found for today.</td></tr>";
}

// Return the HTML output
echo $output;
?>
