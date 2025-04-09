<?php
session_start();

// إعدادات قاعدة البيانات
$servername = "localhost";
$username = "root";
$password = "";
$database = "ceil";

// إنشاء اتصال بقاعدة البيانات
$conn = new mysqli($servername, $username, $password, $database);

// التحقق من الاتصال
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// التحقق مما إذا كان المستخدم مسجلاً كطالب
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header("Location: login.php");
    exit();
}

// استرجاع البريد الإلكتروني للمستخدم المسجل
$user_email = $_SESSION['user_email']; 

// استرجاع رقم الطالب بناءً على البريد الإلكتروني
$query = "SELECT num_etud FROM etudiant WHERE email = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $user_email);
$stmt->execute();
$result = $stmt->get_result();
$student = $result->fetch_assoc();

if (!$student) {
    echo "Error: Student data not found.";
    exit();
}

$num_etud = $student['num_etud'];

// استرجاع درجات الطالب مع اسم اللغة
$query = "SELECT n.date, n.note, l.nom_lang 
          FROM note n 
          JOIN langue l ON n.num_lang = l.num_lang 
          WHERE n.num_etud = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $num_etud);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Notes</title>
    <link rel="stylesheet" href="css/notes.css"> <!-- رابط لملف CSS -->
</head>
<body>
<?php include("header.php") ?>

<div class="container">
    <h1 class="h">Your Notes</h1>
    <table>
        <tr>
            <th>Date</th>
            <th>Note</th>
            <th>Language</th> <!-- تم تغييرها من رقم اللغة إلى اسم اللغة -->
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['date']); ?></td>
                <td><?php echo htmlspecialchars($row['note']); ?></td>
                <td><?php echo htmlspecialchars($row['nom_lang']); ?></td> <!-- عرض اسم اللغة -->
            </tr>
        <?php endwhile; ?>
    </table>
    <br>
</div>
    <?php include("footer_.php") ?>

</body>
</html>

<?php
$stmt->close();
$conn->close();
?>