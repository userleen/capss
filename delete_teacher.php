<?php
include 'includes/conn.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

if (isset($_GET['id'])) {
    $employee_id = $_GET['id'];  // Get the employee_id from the URL parameter

    // Prepare a DELETE statement using employee_id
    $sql = "DELETE FROM employees WHERE employee_id = ?";
    $stmt = $conn->prepare($sql);

    // Execute the statement with the employee_id and catch any errors
    if ($stmt->execute([$employee_id])) {
        header("Location: employee.php?message=deleted");
    } else {
        // Display any SQL errors if the query fails
        $errorInfo = $stmt->errorInfo();
        echo "Error deleting record: " . $errorInfo[2]; // Display SQL error
    }
} else {
    header("Location: employee.php");
}
