<?php
include 'includes/session.php';

if (isset($_POST['add'])) {
    $employee_id = $_POST['employee_id'];
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $address = $_POST['address'];
    $birthdate = $_POST['birthdate'];
    $contact = $_POST['contact'];
    $email = $_POST['email'];
    $gender = $_POST['gender'];
    $filename = $_FILES['photo']['name'];

    // Check if user_type is set and not empty
    if (isset($_POST['user_type']) && !empty($_POST['user_type'])) {
        $user_type = $_POST['user_type'];

        // If user_type is Teacher, get department value, else set it to null
        $department = ($user_type == 'Teacher' && isset($_POST['department'])) ? $_POST['department'] : null;
    } else {
        // Default value if user_type is not provided
        $user_type = null;
        $department = null;
    }

    // Hash the password before storing it in the database
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    if (!empty($filename)) {
        move_uploaded_file($_FILES['photo']['tmp_name'], '../images/'.$filename);
    }

    try {
        // Prepare the SQL statement
        $sql = "INSERT INTO employees (employee_id, firstname, lastname, address, birthdate, contact_info, email, gender, user_type, department, password, photo, created_on) VALUES (:employee_id, :firstname, :lastname, :address, :birthdate, :contact, :email, :gender, :user_type, :department, :password, :filename, NOW())";
        $stmt = $conn->prepare($sql);

        // Bind parameters
        $stmt->bindParam(':employee_id', $employee_id);
        $stmt->bindParam(':firstname', $firstname);
        $stmt->bindParam(':lastname', $lastname);
        $stmt->bindParam(':address', $address);
        $stmt->bindParam(':birthdate', $birthdate);
        $stmt->bindParam(':contact', $contact);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':gender', $gender);
        $stmt->bindParam(':user_type', $user_type);
        $stmt->bindParam(':department', $department);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':filename', $filename);

        // Execute the statement
        if ($stmt->execute()) {
            $_SESSION['success'] = 'Employee added successfully';
        } else {
            $_SESSION['error'] = 'Failed to add employee';
        }
    } catch (PDOException $e) {
        $_SESSION['error'] = 'Error: ' . $e->getMessage();
    }
} else {
    $_SESSION['error'] = 'Fill up add form first';
}

header('location: employee.php');
?>
