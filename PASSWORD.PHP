<?php
session_start();

// تحقق مما إذا كان المستخدم قد سجل الدخول
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// الاتصال بقاعدة البيانات
$servername = "localhost";
$username = "root";
$password = "";
$database = "ceil";

$conn = new mysqli($servername, $username, $password, $database);

// تحقق من الاتصال
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// جلب معرف المستخدم من الجلسة
$user_id = $_SESSION['user_id'];

// متغير لتخزين رسائل الخطأ أو النجاح
$message = '';

// التحقق مما إذا كانت البيانات تم إرسالها عبر POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // التحقق من أن كلمة المرور الجديدة تتطابق مع تأكيد كلمة المرور
    if ($new_password !== $confirm_password) {
        $message = "<p class='error'>New password and confirmation do not match.</p>";
    } else {
        // جلب كلمة المرور الحالية من قاعدة البيانات
        $query = "SELECT password FROM users WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        // التحقق من كلمة المرور الحالية
        if ($user && password_verify($current_password, $user['password'])) {
            // تشفير كلمة المرور الجديدة
            $hashed_new_password = password_hash($new_password, PASSWORD_BCRYPT);

            // تحديث كلمة المرور في قاعدة البيانات
            $update_query = "UPDATE users SET password = ? WHERE id = ?";
            $update_stmt = $conn->prepare($update_query);
            $update_stmt->bind_param("si", $hashed_new_password, $user_id);

            if ($update_stmt->execute()) {
                $message = "<p class='success'>Password changed successfully!</p>";
            } else {
                $message = "<p class='error'>Error occurred while changing the password.</p>";
            }
        } else {
            $message = "<p class='error'>Current password is incorrect.</p>";
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password</title>
    <link rel="stylesheet" href="css/password.css"> <!-- Link to CSS file -->
</head>
<body>
<?php include("header.php") ?>

    <div class="container change-password-container">
        <h1 class="h">Change Password</h1>
        <?php if ($message): ?>
            <div class="message"><?php echo $message; ?></div> <!-- عرض الرسالة هنا -->
        <?php endif; ?>
        <form method="POST" action="" class="change-password-form">
            <label for="current_password">Current Password:</label>
            <input type="password" name="current_password" id="current_password" required>

            <label for="new_password">New Password:</label>
            <input type="password" name="new_password" id="new_password" required>

            <label for="confirm_password">Confirm New Password:</label>
            <input type="password" name="confirm_password" id="confirm_password" required>

            <button type="submit" class="submit-button">Change Password</button>
        </form>
    </div>
    <?php include("footer.php") ?>

</body>
</html>