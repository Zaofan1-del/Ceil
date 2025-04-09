<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ceil";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch languages from database
$language_query = "SELECT * FROM langue";
$languages = $conn->query($language_query);

$errors = [];
$success_message = ''; // متغير لتخزين رسالة النجاح

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect data from the form
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $birth_date = $_POST['birth_date'];
    $birth_place = $_POST['birth_place'];
    $student_status = $_POST['student_status'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $language_id = $_POST['language_id'];

    // Validation rules
    if (!preg_match('/^[A-Za-z ]+$/', $first_name)) {
        $errors['first_name'] = "First name must contain only Latin letters.";
    }
    if (!preg_match('/^[A-Za-z ]+$/', $last_name)) {
        $errors['last_name'] = "Last name must contain only Latin letters.";
    }
    if (!preg_match('/^[A-Za-z0-9, ]+$/', $birth_place)) {
        $errors['birth_place'] = "Birth place must contain only Latin letters, numbers, and commas.";
    }
    if ($password !== $confirm_password) {
        $errors['password'] = "Passwords do not match.";
    }

    // Check if email already exists
    $check_email_query = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($check_email_query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $email_result = $stmt->get_result();

    if ($email_result->num_rows > 0) {
        $errors['email'] = "Email already exists.";
    }

    if (empty($errors)) {
        // Hash password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        // Generate new student number
        $get_max_id_query = "SELECT MAX(num_etud) AS max_num FROM etudiant";
        $result = $conn->query($get_max_id_query);
        $row = $result->fetch_assoc();
        $new_student_number = str_pad($row['max_num'] + 1, 5, '0', STR_PAD_LEFT);

        // Insert into users table
        $insert_user_query = "INSERT INTO users (email, password, role) VALUES (?, ?, 'student')";
        $stmt = $conn->prepare($insert_user_query);
        $stmt->bind_param("ss", $email, $hashed_password);
        $stmt->execute();

        // Insert into students table
        $insert_student_query = "INSERT INTO etudiant (num_etud, date_naisc, lieu_naisc, nom_etud, prenom_etud, statut_etud, tel, email, num_lang) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($insert_student_query);
        $stmt->bind_param("sssssssss", $new_student_number, $birth_date, $birth_place, $first_name, $last_name, $student_status, $phone, $email, $language_id);

        if ($stmt->execute()) {
            $success_message = "<div class='success'>
            <strong>Congratulations!</strong> You have successfully registered. Your student number is <strong>$new_student_number</strong>. 
            Please keep this number safe for future reference.
          </div>";
        } else {
            echo "<p class='error'>An error occurred during registration.</p>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Registration</title>
    <link rel="stylesheet" href="css/register.css"> 
</head>
<body>
<?php include("header.php") ?>
    <div class="container">
    <?php if ($success_message): ?>
            <?= $success_message; ?>
        <?php endif; ?>

        <h1 class="h">Student Registration</h1>
        <form action="" method="POST" class="add-student-form">
            <label for="first_name">First Name:</label>
            <input type="text" id="first_name" name="first_name" required>
            <div class="error"> <?= $errors['first_name'] ?? '' ?> </div>

            <label for="last_name">Last Name:</label>
            <input type="text" id="last_name" name="last_name" required>
            <div class="error"> <?= $errors['last_name'] ?? '' ?> </div>

            <label for="birth_date">Date of Birth:</label>
            <input type="date" id="birth_date" name="birth_date" required>

            <label for="birth_place">Place of Birth:</label>
            <input type="text" id="birth_place" name="birth_place" required>
            <div class="error"> <?= $errors['birth_place'] ?? '' ?> </div>

            <label for="student_status">Student Status:</label>
            <select id="student_status" name="student_status" required>
                <option value="University Student">University Student</option>
                <option value="Not a University Student">Not a University Student</option>
            </select>

            <label for="phone">Phone Number:</label>
            <input type="text" id="phone" name="phone" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            <div class="error"> <?= $errors['email'] ?? '' ?> </div>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <label for="confirm_password">Confirm Password:</label>
            <input type="password" id="confirm_password" name="confirm_password" required>
            <div class="error"> <?= $errors['password'] ?? '' ?> </div>

            <label for="language_id">Language:</label>
            <select name="language_id" id="language_id" required>
                <?php while ($row = $languages->fetch_assoc()) {
                    echo "<option value='{$row['num_lang']}'>{$row['nom_lang']}</option>";
                } ?>
            </select>

            <button type="submit" class="btn">Register</button>
        </form>

      
    </div>
    <?php include("footer.php") ?>

</body>
</html>

<?php
// إغلاق الاتصال بعد الانتهاء
$conn->close();
?>