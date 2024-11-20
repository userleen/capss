<?php
include 'includes/session.php';

if (isset($_POST['empid'])) {
    $empid = $_POST['empid'];

    // Prepare the SQL statement to delete the employee
    $sql = "DELETE FROM employees WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $empid);

    if ($stmt->execute()) {
        $_SESSION['success'] = 'Employee deleted successfully';
    } else {
        $_SESSION['error'] = 'Failed to delete employee';
    }
    header('location: employee.php'); // Redirect back to the teacher list
}
?>
