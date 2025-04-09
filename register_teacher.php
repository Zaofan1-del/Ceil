<?php
// إعداد الاتصال بقاعدة البيانات
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ceil";

// إنشاء الاتصال
$conn = new mysqli($servername, $username, $password, $dbname);

// التحقق من الاتصال
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// متغير لتخزين رسائل الأخطاء أو النجاح
$error_message = '';
$success_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // جمع البيانات من النموذج
    $email = $_POST['email'];
    $password = $_POST['password'];
    $name = $_POST['name'];
    $surname = $_POST['surname'];
    $num_ens = $_POST['num_ens'];

    // التحقق من أن البريد الإلكتروني غير موجود بالفعل في جدول users أو جدول enseignement
    $check_email_query = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($check_email_query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $user_result = $stmt->get_result();

    $check_teacher_query = "SELECT * FROM enseignement WHERE email = ?";
    $stmt = $conn->prepare($check_teacher_query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $teacher_result = $stmt->get_result();

    if ($user_result->num_rows > 0 || $teacher_result->num_rows > 0) {
        $error_message = "Email already exists.";
    } else {
        // تشفير كلمة المرور
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // إدخال بيانات المستخدم في جدول users
        $insert_user_query = "INSERT INTO users (email, password, role) VALUES (?, ?, 'teacher')";
        $stmt = $conn->prepare($insert_user_query);
        $stmt->bind_param("ss", $email, $hashed_password);
        $stmt->execute();

        // الحصول على ID الذي تم توليده في جدول users
        $user_id = $stmt->insert_id;

        // إدخال بيانات الأستاذ في جدول enseignement
        $insert_teacher_query = "INSERT INTO enseignement (id, num_ens, nom_ens, pre_ens, email) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($insert_teacher_query);
        $stmt->bind_param("sssss", $user_id, $num_ens, $name, $surname, $email);
        $stmt->execute();

        $success_message = "Teacher registered successfully!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Teacher</title>
    <link rel="stylesheet" href="css/register_tchr.css"> <!-- Link to CSS file -->
</head>
<body>
<?php include("header.php") ?>

    <div class="container">
        <h1 class="h">Register</h1>

        <?php
        // عرض الرسائل في الأعلى
        if (isset($error_message)) {
            echo "<p class='error'>$error_message</p>";
        }
        if (isset($success_message)) {
            echo "<p class='success'>$success_message</p>";
        }
        ?>

        <form action="register_teacher.php" method="POST" class="add-teacher-form">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <label for="name">First Name:</label>
            <input type="text" id="name" name="name" required>

            <label for="surname">Last Name:</label>
            <input type="text" id="surname" name="surname" required>

            <label for="num_ens">Teacher Number:</label>
            <input type="text" id="num_ens" name="num_ens" required>

            <input type="submit" value="Register Teacher" class="button">
        </form>
    </div>
    <?php include("footer.php") ?>

</body>
</html>

<?php
// إغلاق الاتصال بعد الانتهاء
$conn->close();
?>