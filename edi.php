<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Student Profile</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <style>
        .container-form {
            margin-top: 50px;
            max-width: 600px;
        }
        .card {
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .card h2 {
            margin-bottom: 20px;
        }
    </style>
    <script>
        function previewPhoto(input) {
            const file = input.files[0];
            const preview = document.getElementById('photoPreview');
            const reader = new FileReader();
            reader.onload = function (e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            };
            if (file) {
                reader.readAsDataURL(file);
            } else {
                preview.src = '';
                preview.style.display = 'none';
            }
        }
    </script>
</head>
<body>
    <div class="container container-form">
        <div class="card">
            <h2>Edit Student Profile</h2>
            <?php
            if (isset($_SESSION['error'])) {
                echo "<div class='alert alert-danger'>".$_SESSION['error']."</div>";
                unset($_SESSION['error']);
            }
            ?>
            <form action="" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label>First Name:</label>
                    <input type="text" name="firstname" class="form-control" value="<?php echo htmlspecialchars($row['firstname']); ?>" required>
                </div>
                <div class="form-group">
                    <label>Last Name:</label>
                    <input type="text" name="lastname" class="form-control" value="<?php echo htmlspecialchars($row['lastname']); ?>" required>
                </div>
                <div class="form-group">
                    <label>Email:</label>
                    <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($row['email']); ?>" required>
                </div>
                <div class="form-group">
                    <label>Class:</label>
                    <input type="text" name="class" class="form-control" value="<?php echo htmlspecialchars($row['class']); ?>" required>
                </div>
                <div class="form-group">
                    <label>Address:</label>
                    <input type="text" name="address" class="form-control" value="<?php echo htmlspecialchars($row['address']); ?>" required>
                </div>
                <div class="form-group">
                    <label>Birthdate:</label>
                    <input type="date" name="birthdate" class="form-control" value="<?php echo htmlspecialchars($row['birthdate']); ?>" required>
                </div>
                <div class="form-group">
                    <label>Contact:</label>
                    <input type="text" name="contact_info" class="form-control" value="<?php echo htmlspecialchars($row['contact_info']); ?>" required>
                </div>
                <div class="form-group">
                    <label>Gender:</label>
                    <select name="gender" class="form-control" required>
                        <option value="Male" <?php if ($row['gender'] == 'Male') echo 'selected'; ?>>Male</option>
                        <option value="Female" <?php if ($row['gender'] == 'Female') echo 'selected'; ?>>Female</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Course:</label>
                    <input type="text" name="course" class="form-control" value="<?php echo htmlspecialchars($row['course']); ?>" required>
                </div>
                <div class="form-group">
                    <label>Year:</label>
                    <input type="number" name="year" class="form-control" value="<?php echo htmlspecialchars($row['year']); ?>" required>
                </div>
                <div class="form-group">
                    <label>Photo:</label>
                    <input type="file" name="photo" class="form-control" onchange="previewPhoto(this)">
                    <img id="photoPreview" src="../images/<?php echo htmlspecialchars($row['photo']); ?>" alt="Current Photo" style="width: 100px; height: 100px; margin-top: 10px; display: block;">
                </div>
                <button type="submit" class="btn btn-primary">Update Profile</button>
                <a href="student_profile.php?id=<?php echo $student_id; ?>" class="btn btn-default">Cancel</a>
            </form>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>
</body>
</html>
