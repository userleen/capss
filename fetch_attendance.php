<?php
include 'includes/session.php'; // Include session for database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $teacher = isset($_POST['teacher']) ? $_POST['teacher'] : '';

    try {
        // Fetch attendance data based on selected teacher
        if ($teacher) {
            $sql = "SELECT recorded_at, status FROM attendance_teacher 
                    WHERE teacher_name = :teacher 
                    AND MONTH(recorded_at) = MONTH(CURDATE()) 
                    AND YEAR(recorded_at) = YEAR(CURDATE())";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':teacher', $teacher);
        } else {
            $sql = "SELECT recorded_at, status FROM attendance_teacher 
                    WHERE MONTH(recorded_at) = MONTH(CURDATE()) 
                    AND YEAR(recorded_at) = YEAR(CURDATE())";
            $stmt = $conn->prepare($sql);
        }

        $stmt->execute();
        $attendances = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Prepare events array for FullCalendar
        $events = [];
        foreach ($attendances as $attendance) {
            $date = date('Y-m-d', strtotime($attendance['recorded_at']));
            $events[] = [
                'title' => htmlspecialchars($teacher . ' - ' . htmlspecialchars($attendance['status'])),
                'start' => $date,
                'color' => ($attendance['status'] == 'Present') ? 'green' : (($attendance['status'] == 'Late') ? 'orange' : 'red'),
            ];
        }

        echo json_encode($events); // Return events as JSON
    } catch (PDOException $e) {
        echo json_encode([]); // Return empty array on error
    }
}
?>