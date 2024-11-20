<?php
session_start();
include 'includes/conn.php';

// Check if the user is logged in
if (!isset($_SESSION['employee_id'])) {
    header('Location: index.php'); // Redirect to login page if not logged in
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $employee_id = $_SESSION['employee_id'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate new password
    if ($new_password !== $confirm_password) {
        $_SESSION['error'] = 'Passwords do not match.';
        header('Location: change_password.php');
        exit();
    }

    // Update the password in the database
    try {
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT); // Hash the new password
        $sql = "UPDATE employees SET password = :password WHERE employee_id = :employee_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':password', $hashed_password);
        $stmt->bindParam(':employee_id', $employee_id);
        $stmt->execute();

        $_SESSION['success'] = 'Password changed successfully!';
        header('Location: student_dashh.php'); // Redirect to student dashboard
        exit();
    } catch (PDOException $e) {
        $_SESSION['error'] = 'Error updating password: ' . $e->getMessage();
        header('Location: change_password.php');
        exit();
    }
}

// Display errors and success messages if any
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <title>Change Password</title>
</head>
<body>
    <div class="container">
        <h2>Change Password</h2>
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger">
                <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success">
                <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>
        <form action="/student_change_password" method="POST">
            <div class="form-group">
                <label for="newPassword">New Password</label>
                <input type="password" class="form-control" id="newPassword" name="new_password" required>
            </div>
            <div class="form-group">
                <label for="confirmPassword">Confirm Password</label>
                <input type="password" class="form-control" id="confirmPassword" name="confirm_password" required>
            </div>
            <button type="submit" class="btn btn-primary">Change Password</button>
        </form>
    </div>
</body>
</html>
