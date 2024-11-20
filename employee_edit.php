<?php
include 'includes/session.php';

if (isset($_POST['edit'])) {
    $employee_id = $_POST['employee_id'];
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $address = $_POST['address'];
    $birthdate = $_POST['birthdate'];
    $contact = $_POST['contact'];
    $email = $_POST['email'];
    $gender = $_POST['gender'];
    $department = $_POST['department'];
    $empid = $_POST['empid']; // Add this line to retrieve the employee ID
    
    // Prepare SQL statement
    $sql = "UPDATE employees SET firstname = :firstname, lastname = :lastname, address = :address, birthdate = :birthdate, contact_info = :contact, gender = :gender, department = :department WHERE id = :empid";
    
    // Prepare the statement
    $stmt = $conn->prepare($sql);
    
    // Bind parameters
    $stmt->bindParam(':firstname', $firstname);
    $stmt->bindParam(':lastname', $lastname);
    $stmt->bindParam(':address', $address);
    $stmt->bindParam(':birthdate', $birthdate);
    $stmt->bindParam(':contact', $contact);
    $stmt->bindParam(':gender', $gender);
    $stmt->bindParam(':department', $department);
    $stmt->bindParam(':empid', $empid);
    
    // Execute the statement
    if ($stmt->execute()) {
        $_SESSION['success'] = 'Employee updated successfully';
    } else {
        $_SESSION['error'] = 'Error updating employee: ' . $stmt->errorInfo()[2];
    }
} else {
    $_SESSION['error'] = 'Select employee to edit first';
}

// Redirect back to the employee page
header('location: employee.php');
?>
