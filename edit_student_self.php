<?php
session_start();

// التحقق مما إذا كان المستخدم مسجل الدخول
if (!isset($_SESSION['user_email'])) {
    header("Location: login.php");
    exit();
}

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

$email = $_SESSION['user_email'];

// جلب بيانات الطالب باستخدام البريد الإلكتروني
$sql = "SELECT * FROM etudiant WHERE email=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $student = $result->fetch_assoc();
} else {
    echo "Error: Student not found.";
    exit();
}
$stmt->close();

// معالجة تحديث البيانات
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom_etud = $_POST['nom_etud'];
    $prenom_etud = $_POST['prenom_etud'];
    $date_naisc = $_POST['date_naisc'];
    $lieu_naisc = $_POST['lieu_naisc'];
    $tel = $_POST['tel'];
    $new_email = $_POST['email']; // إضافة حقل البريد الإلكتروني

    // تحديث البيانات
    $sql_update = "UPDATE etudiant SET 
        nom_etud=?, 
        prenom_etud=?, 
        date_naisc=?, 
        lieu_naisc=?, 
        tel=?, 
        email=? 
        WHERE email=?";

    $stmt = $conn->prepare($sql_update);
    $stmt->bind_param("sssssss", $nom_etud, $prenom_etud, $date_naisc, $lieu_naisc, $tel, $new_email, $email);

    // تحديث البريد الإلكتروني في جدول users
    $sql_update_user = "UPDATE users SET email=? WHERE email=?";
    $stmt_user = $conn->prepare($sql_update_user);
    $stmt_user->bind_param("ss", $new_email, $email);

    if ($stmt->execute() && $stmt_user->execute()) {
        // تحديث البريد الإلكتروني في الجلسة
        $_SESSION['user_email'] = $new_email;
        echo "<script>alert('تم تحديث البيانات بنجاح!'); window.location.href='edit_student_self.php';</script>";
    } else {
        echo "Error updating data: " . $conn->error;
    }
    $stmt->close();
    $stmt_user->close();
}

// جلب اللغات
$languesResult = $conn->query("SELECT * FROM langue");

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Student Information</title>
    <link rel="stylesheet" href="css/edit_student.css"> <!-- Link to CSS -->
</head>
<body>
<?php include("header.php") ?>

    <div class="container edit-student-container">
        <h1 class="h">Edit Student Information</h1>
        <form method="POST" action="" class="edit-student-form">
            <label for="nom_etud">First Name:</label>
            <input type="text" name="nom_etud" value="<?= htmlspecialchars($student['nom_etud']) ?>" required>

            <label for="prenom_etud">Last Name:</label>
            <input type="text" name="prenom_etud" value="<?= htmlspecialchars($student['prenom_etud']) ?>" required>

            <label for="date_naisc">Date of Birth:</label>
            <input type="date" name="date_naisc" value="<?= htmlspecialchars($student['date_naisc']) ?>" required>

            <label for="lieu_naisc">Place of Birth:</label>
            <input type="text" name="lieu_naisc" value="<?= htmlspecialchars($student['lieu_naisc']) ?>" required>

            <label for="tel">Phone:</label>
            <input type="text" name="tel" value="<?= htmlspecialchars($student['tel']) ?>" required>

            <label for="email">Email:</label>
            <input type="email" name="email" value="<?= htmlspecialchars($student['email']) ?>" required> <!-- حقل البريد الإلكتروني -->

            <input type="submit" value="Update Information" class="submit-button">
        </form>
    </div>
    <?php include("footer.php") ?>

</body>
</html>