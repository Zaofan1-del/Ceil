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

// استرجاع رقم الأستاذ بناءً على user_id
$query = "SELECT num_ens FROM enseignement WHERE id = ?";
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
    <title>Groups and Students</title>
    <link rel="stylesheet" href="css/group.css">
</head>
<body>
<?php include("header.php") ?>

<div class="container-group">
    <h1>Group Management System</h1>

    <?php
    if ($result->num_rows > 0) {
        $teacher = $result->fetch_assoc();
        $teacher_id = $teacher['num_ens'];

        // استرجاع الأفواج التي يدرسها الأستاذ
        $query = "SELECT * FROM groupe WHERE num_ens = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $teacher_id);
        $stmt->execute();
        $groups = $stmt->get_result();

        // التحقق مما إذا كان هناك فوج محدد
        if (isset($_GET['group'])) {
            $group_num = $_GET['group'];

            // استرجاع التلاميذ في الفوج المحدد
            $query = "SELECT * FROM etudiant WHERE num_group = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("s", $group_num);
            $stmt->execute();
            $students = $stmt->get_result();

            echo "<h2>Students in Group: " . htmlspecialchars($group_num ?? '') . "</h2>"; // استخدام null coalescing operator
            if ($students->num_rows > 0) {
                echo "<table>";
                echo "<tr><th>Name</th><th>Surname</th><th>Email</th></tr>";
                while ($student = $students->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($student['nom_etud'] ?? '') . "</td>"; // استخدام null coalescing operator
                    echo "<td>" . htmlspecialchars($student['prenom_etud'] ?? '') . "</td>"; // استخدام null coalescing operator
                    echo "<td>" . htmlspecialchars($student['email'] ?? '') . "</td>"; // استخدام null coalescing operator
                    echo "</tr>";
                }
                echo "</table>";

                // استرجاع البرنامج الخاص بالفوج
                $query = "SELECT prg_group FROM groupe WHERE num_group = ?";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("s", $group_num);
                $stmt->execute();
                $program_result = $stmt->get_result();

                if ($program_result->num_rows > 0) {
                    $program = $program_result->fetch_assoc();
                    $program_image = htmlspecialchars($program['prg_group'] ?? ''); // استخدام null coalescing operator
                    echo "<h3>The program: " . htmlspecialchars($group_num ?? '') . "</h3>"; // استخدام null coalescing operator
                    echo "<img src='" . $program_image . "' alt='Program Image' style='max-width: 100%; height: auto;'>";
                } else {
                    echo "<p>No program found for this group.</p>";
                }
            } else {
                echo "<p>No students in this group.</p>";
            }
        } else {
            if ($groups->num_rows > 0) {
                echo "<h2>Groups You Teach</h2>";
                echo "<ul>";
                while ($group = $groups->fetch_assoc()) {
                    echo "<li>";
                    echo "Group Name: " . htmlspecialchars($group['nom_group'] ?? '') . " - <a href='? group=" . htmlspecialchars($group['num_group'] ?? '') . "'>View Students</a>";
                    echo "</li>";
                }
                echo "</ul>";
            } else {
                echo "<p>No groups associated with this teacher.</p>";
            }
        }
    } else {
        echo "<p>No teacher found with this ID.</p>";
    }

    $stmt->close();
    $conn->close();
    ?>
</div>

<?php include("footer.php") ?>

</body>
</html>