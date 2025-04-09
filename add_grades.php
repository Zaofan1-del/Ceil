<?php
session_start();

// تحقق مما إذا كان المستخدم قد سجل الدخول كطالب أو أستاذ
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
    header("Location: login.php");
    exit();
}

// اتصال بقاعدة البيانات
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
$role = $_SESSION['role'];

// إذا كان المستخدم أستاذ
if ($role === 'teacher') {
    // جلب num_ens (رقم الأستاذ) بناءً على user_id
    $query = "SELECT num_ens FROM `enseignement` WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $teacher = $result->fetch_assoc();
    
    $num_ens = $teacher['num_ens'];

    // جلب الأفواج التي يدرسها الأستاذ باستخدام num_ens
    $query = "SELECT g.num_group, g.nom_group, g.num_lang FROM `groupe` g WHERE g.num_ens = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $num_ens);
    $stmt->execute();
    $result = $stmt->get_result();

    $groups = [];
    while ($row = $result->fetch_assoc()) {
        $groups[] = $row;
    }

    $stmt->close();
} else {
    // إذا كان المستخدم طالب، جلب الفوج الذي ينتمي إليه
    $query = "SELECT g.num_group, g.nom_group FROM `groupe` g JOIN `etudiant` e ON g.num_group = e.num_group WHERE e.num_etud = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $groups = [];
    while ($row = $result->fetch_assoc()) {
        $groups[] = $row;
    }

    $stmt->close();
}

// التحقق مما إذا تم اختيار فوج معين
$selected_group = isset($_POST['group_id']) ? $_POST['group_id'] : null;
$students = [];
$message = ''; // متغير لتخزين الرسالة

if ($selected_group) {
    // جلب الطلاب في هذا الفوج
    $query = "SELECT e.num_etud, e.nom_etud, e.prenom_etud, n.note 
              FROM etudiant e 
              LEFT JOIN note n ON e.num_etud = n.num_etud AND n.num_lang = (SELECT num_lang FROM groupe WHERE num_group = ?)
              WHERE e.num_group = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $selected_group, $selected_group);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $students[] = $row;
    }

    $stmt->close();
}

// معالجة إضافة العلامات
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_grades'])) {
    foreach ($_POST['grades'] as $student_id => $grade) {
        if ($grade !== '') {
            // تحقق مما إذا كانت العلامة موجودة بالفعل
            $query = "SELECT * FROM note WHERE num_etud = ? AND num_lang = (SELECT num_lang FROM groupe WHERE num_group = ?)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("is", $student_id, $selected_group);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                // إذا كانت العلامة موجودة، قم بتحديثها
                $query = "UPDATE note SET note = ? WHERE num_etud = ? AND num_lang = (SELECT num_lang FROM groupe WHERE num_group = ?)";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("dis", $grade, $student_id, $selected_group);
            } else {
                // إذا لم تكن العلامة موجودة، قم بإدخالها
                $query = "INSERT INTO note (date, note, num_etud, num_lang) VALUES ( NOW(), ?, ?, (SELECT num_lang FROM groupe WHERE num_group = ?))";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("dis", $grade, $student_id, $selected_group);
            }
            $stmt->execute();
            $stmt->close();
        }
    }
    // تعيين رسالة النجاح
    $message = "Notes saved successfully.";
    // إعادة توجيه بعد حفظ العلامات
    header("Location: " . $_SERVER['PHP_SELF'] . "?message=" . urlencode($message));
    exit();
}

// عرض رسالة النجاح أو الفشل إذا كانت موجودة
if (isset($_GET['message'])) {
    $message = htmlspecialchars($_GET['message']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Grades</title>
    <link rel="stylesheet" href="css/add_notes.css"> <!-- Link to CSS file -->
</head>
<body>
<?php include("header.php") ?>
    <div class="container add-grades-container">
        <?php if ($message): ?>
            <div class="message <?= strpos($message, 'successfully') !== false ? 'success' : 'error'; ?>">
                <?= $message; ?>
            </div>
        <?php endif; ?>
        <h1 class="h">Add Grades</h1>
        <form method="post" class="sel">
            <label for="group_id">Select Group:</label>
            <select name="group_id" id="group_id" onchange="this.form.submit()">
                <option value="">Select Group</option>
                <?php foreach ($groups as $group): ?>
                    <option value="<?= htmlspecialchars($group['num_group']); ?>" <?= ($selected_group == $group['num_group']) ? 'selected' : ''; ?>>
                        <?= htmlspecialchars($group['nom_group']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </form>

        <?php if ($selected_group && count($students) > 0): ?>
            <form method="post" class="form_table">
                <input type="hidden" name="group_id" value="<?= $selected_group; ?>">
                <table>
                    <tr>
                        <th>ID</th>
                        <th>Student Name</th>
                        <th>Last Name</th>
                        <th>Grade</th>
                    </tr>
                    <?php foreach ($students as $student): ?>
                        <tr>
                            <td><?= htmlspecialchars($student['num_etud'] ?? ''); ?></td>
                            <td><?= htmlspecialchars($student['nom_etud'] ?? ''); ?></td>
                            <td><?= htmlspecialchars($student['prenom_etud'] ?? ''); ?></td>
                            <td>
                                <input type="number" name="grades[<?= $student['num_etud']; ?>]" min="0" max="20" step="0.1" value="<?= htmlspecialchars($student['note'] ?? ''); ?>">
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
                <button type="submit" name="submit_grades" class="submit-button">Save Grades</button>
            </form>
        <?php elseif ($selected_group): ?>
            <p style="color: red;">No students in this group.</p>
        <?php endif; ?>
    </div>
    <?php include("footer.php") ?>
</body>
</html>