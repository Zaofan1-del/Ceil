<?php
session_start(); // بدء الجلسة

// التحقق مما إذا كان المستخدم مسجلاً
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // إعادة التوجيه إلى صفحة تسجيل الدخول إذا لم يكن مسجلاً
    exit();
}

// معلومات الاتصال بقاعدة البيانات
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ceil";

$conn = new mysqli($servername, $username, $password, $dbname);

// التحقق من الاتصال
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = ""; // متغير لتخزين الرسائل

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $current_password = $_POST['current_password'];
    $new_email = trim($_POST['new_email']);
    $user_id = $_SESSION['user_id'];

    // جلب معلومات المستخدم للتحقق من كلمة المرور
    $query = "SELECT password FROM users WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($current_password, $user['password'])) {
        // تحديث البريد الإلكتروني في قاعدة البيانات
        $update_query = "UPDATE users SET email = ? WHERE id = ?";
        $update_stmt = $conn->prepare($update_query);
        $update_stmt->bind_param("si", $new_email, $user_id);

        if ($update_stmt->execute()) {
            $_SESSION['user_email'] = $new_email; // تحديث البريد الإلكتروني في الجلسة
            $message = "Email updated successfully!";
        } else {
            $message = "Error updating email: " . $conn->error;
        }

        $update_stmt->close();
    } else {
        $message = "Incorrect password!";
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Email</title>
    <link rel="stylesheet" href="css/email_admin.css"> <!-- ربط ملف CSS -->
</head>
<body>
<?php include("header.php") ?>

<div class="container change-email-container">

    <h1 class="h">Change Email</h1>

    <?php if ($message): ?>
        <div class="notification <?= strpos($message, 'Error') !== false ? 'error' : 'success' ?>">
            <?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>

    <form method="POST" class="change-email-form">
        <label for="current_password">Current Password:</label>
        <input type="password" id="current_password" name="current_password" required>

        <label for="new_email">New Email:</label>
        <input type="email" id="new_email" name="new_email" required>

        <button type="submit">Update Email</button>
    </form>
</div>

<?php include("footer.php") ?>
</body>
</html>