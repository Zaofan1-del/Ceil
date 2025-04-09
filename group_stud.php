<?php
session_start();

// إعدادات الاتصال بقاعدة البيانات
$servername = "localhost";
$username = "root"; // استبدل باسم المستخدم الخاص بك
$password = ""; // استبدل بكلمة المرور الخاصة بك
$dbname = "ceil";

// إنشاء الاتصال
$conn = new mysqli($servername, $username, $password, $dbname);

// التحقق من الاتصال
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// التحقق مما إذا كان المستخدم مسجلاً الدخول
if (!isset($_SESSION['user_id'])) {
    echo "User  ID not found in session.";
    exit();
}

$user_id = $_SESSION['user_id'];

// استرجاع البريد الإلكتروني للتلميذ بناءً على user_id
$query = "SELECT email FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Group Information</title>
    <link rel="stylesheet" href="css/group_etud.css"> 
</head>
<body>
<?php include("header.php") ?>

    <div class="container-group">
        <h1>Students Group Information</h1>

        <?php
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            $student_email = $user['email'];

            // استرجاع معلومات التلميذ بناءً على البريد الإلكتروني
            $query = "SELECT num_group FROM etudiant WHERE email = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("s", $student_email);
            $stmt->execute();
            $student_result = $stmt->get_result();

            if ($student_result->num_rows > 0) {
                $student = $student_result->fetch_assoc();
                $student_group = $student['num_group'];

                // استرجاع معلومات الفوج بناءً على num_group
                $query = "SELECT * FROM groupe WHERE num_group = ?";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("s", $student_group);
                $stmt->execute();
                $group_result = $stmt->get_result();

                if ($group_result->num_rows > 0) {
                    $group = $group_result->fetch_assoc();
                    $group_name = $group['nom_group'];
                    $program_image = $group['prg_group'];

                    echo "<h2>Group : " . htmlspecialchars($group_name) . "</h2>";

                    // استرجاع التلاميذ في الفوج
                    $query = "SELECT * FROM etudiant WHERE num_group = ?";
                    $stmt = $conn->prepare($query);
                    $stmt->bind_param("s", $student_group);
                    $stmt->execute();
                    $students = $stmt->get_result();
                    if ($students->num_rows > 0) {
                        echo "<h3>Students :</h3>";
                        echo "<ul>";
                        while ($student = $students->fetch_assoc()) {
                            echo "<li>" . htmlspecialchars($student['nom_etud']) . " " . htmlspecialchars($student['prenom_etud']) . "</li>";
                        }
                        echo "</ul>";
                    } else {
                        echo "<p>No students found in this group.</p>";
                    }

                    // عرض صورة البرنامج
                    if (!empty($program_image)) {
                        echo "<h3>The program :</h3>";
                        echo "<img src='" . htmlspecialchars($program_image) . "' alt='Program Image'>";
                    } else {
                        echo "<p>No program image available for this group.</p>";
                    }
                } else {
                    echo "<p>No group found for this student.</p>";
                }
            } else {
                echo "<p>No student found with this email.</p>";
            }
        } else {
            echo "<p>No user found with this ID.</p>";
        }

        $stmt->close();
        $conn->close();
        ?>
    </div>
    <?php include("footer.php") ?>

</body>
</html>