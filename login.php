<?php
session_start(); // Start session

// Check if the user is already logged in
if (isset($_SESSION['user_id'])) {
    // Redirect to the appropriate profile page based on the role
    if ($_SESSION['role'] == 'student') {
        header("Location: student_page.php");
        exit();
    } elseif ($_SESSION['role'] == 'teacher') {
        header("Location: teacher_page.php");
        exit();
    } elseif ($_SESSION['role'] == 'admin') {
        header("Location: admin_page.php");
        exit();
    }
}

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ceil";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (!empty($email) && !empty($password)) {
        // Check if the email exists in the users table
        $check_user_query = "SELECT * FROM users WHERE email = ?";
        $stmt = $conn->prepare($check_user_query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $user_result = $stmt->get_result();

        if ($user_result->num_rows > 0) {
            $user = $user_result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['role'] = $user['role']; // تخزين الدور في الجلسة
                $_SESSION['user_email'] = $user['email']; // تخزين البريد الإلكتروني في الجلسة

                // توجيه المستخدم إلى الصفحة المناسبة بناءً على الدور
                if ($user['role'] == 'student') {
                    header("Location: student_page.php");
                } elseif ($user['role'] == 'teacher') {
                    header("Location: teacher_page.php");
                } elseif ($user['role'] == 'admin') {
                    header("Location: admin_page.php");
                } else {
                    $error_message = "Unknown role!";
                }
                exit();
            } else {
                $error_message = "Incorrect password!";
            }
        } else {
            $error_message = "Email not registered!";
        }
    } else {
        $error_message = "Please fill in all fields!";
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="css/login.css"> 
</head>
<body>
<?php include("header.php") ?>

<div class="login" id="login">
    <form action="login.php" method="POST" class="login__form">
        <h2 class="login__title">Log In</h2>
        
        <div class="login__group">
            <div>
                <label for="email" class="login__label">Email</label>
                <input type="email" name="email" placeholder="Write your email" id="email" class="login__input" required>
            </div>
            
            <div>
                <label for="password" class="login__label">Password</label>
                <input type="password" name="password" placeholder="Enter your password" id="password" class="login__input" required>
            </div>
        </div>

        <div>
            <p class="login__signup">
                You do not have an account? <a href="register.php">Sign up</a>
            </p>
            <button type="submit" class="login__button">Log In</button>
        </div>
    </form>
    <?php if (isset($error_message)) { echo "<p>$error_message</p>"; } ?>
</div>

<?php include("footer.php") ?>
</body>
</html>