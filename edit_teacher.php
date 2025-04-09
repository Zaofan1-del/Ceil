<?php
session_start(); // بدء الجلسة

// معلومات الاتصال بقاعدة البيانات
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

// التحقق من وجود user_id في الجلسة
if (!isset($_SESSION['user_id'])) {
    die("User  ID not found in session.");
}

$user_id_session = $conn->real_escape_string($_SESSION['user_id']);
$message = "";

// التحقق من وجود user_id في جدول users
$sql_user = "SELECT * FROM users WHERE id = '$user_id_session'";
$result_user = $conn->query($sql_user);

if ($result_user->num_rows == 0) {
    die("User  ID not found in users table.");
}

// جلب بيانات الأستاذ
$sql_teacher = "SELECT * FROM enseignement WHERE id = '$user_id_session'";
$result_teacher = $conn->query($sql_teacher);

if ($result_teacher->num_rows == 0) {
    die("Teacher not found or you do not have permission to edit their information.");
}

$teacher = $result_teacher->fetch_assoc();

// معالجة طلب الحفظ عند إرسال النموذج
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom_ens = $conn->real_escape_string($_POST['nom_ens']);
    $pre_ens = $conn->real_escape_string($_POST['pre_ens']);
    $new_email = $conn->real_escape_string($_POST['email']);

    // تحديث بيانات الأستاذ
    $update_teacher_sql = "UPDATE enseignement SET 
        nom_ens = '$nom_ens', 
        pre_ens = '$pre_ens', 
        email = '$new_email'
        WHERE id = '$user_id_session'";

    // تحديث البريد الإلكتروني في جدول users
    $update_user_sql = "UPDATE users SET email = '$new_email' WHERE id = '$user_id_session'";

    if ($conn->query($update_teacher_sql) === TRUE && $conn->query($update_user_sql) === TRUE) {
        $message = "Teacher information and email updated successfully.";
        // تحديث البيانات المعروضة بعد الحفظ
        $teacher['nom_ens'] = $nom_ens;
        $teacher['pre_ens'] = $pre_ens;
        $teacher['email'] = $new_email;
    } else {
        $message = "Error updating information: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Teacher Information</title>
    <link rel="stylesheet" href="css/edit_teacher.css"> <!-- ربط ملف CSS -->
</head>
<body>
<?php include("header.php") ?>

    <div class="container edit-teacher-container"> <!-- إضافة كلاس خاص -->
        <h1 class="h">Edit Teacher Information</h1>

        <?php if ($message): ?>
        <div class="notification <?= strpos($message, 'Error') !== false ? 'error' : 'success' ?>">
            <?= htmlspecialchars($message) ?>
        </div>
        <?php endif; ?>

        <form method="POST" class="edit-teacher-form">
            <label for="nom_ens">Name:</label>
            <input type="text" id="nom_ens" name="nom_ens" value="<?= htmlspecialchars($teacher['nom_ens']) ?>" required>

            <label for="pre_ens">Surname:</label>
            <input type="text" id="pre_ens" name="pre_ens" value="<?= htmlspecialchars($teacher['pre_ens']) ?>" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?= htmlspecialchars($teacher['email']) ?>" required>

            <button type="submit" class="submit-button">Save Changes</button>
        </form>
    </div>
    <?php include("footer.php") ?>

</body>
</html>

<?php
$conn->close();
?>