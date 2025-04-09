<?php
session_start();

// تحقق مما إذا كان المستخدم قد سجل الدخول كأدمن
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
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

// جلب جميع الأفواج
$query = "SELECT num_group, nom_group FROM groupe";
$result = $conn->query($query);
$groups = [];
while ($row = $result->fetch_assoc()) {
    $groups[] = $row;
}

// جلب الأستاذ المرتبط بالفوج عند اختيار الفوج
$teacher = null;
if (isset($_POST['group_id']) && !empty($_POST['group_id'])) {
    $group_id = $_POST['group_id'];
    $query = "SELECT groupe.num_ens, nom_ens, pre_ens FROM groupe
              JOIN enseignement ON groupe.num_ens = enseignement.num_ens
              WHERE groupe.num_group = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $group_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $teacher = $result->fetch_assoc();
    $stmt->close();
}

// معالجة حذف الأستاذ
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_teacher'])) {
    $group_id = $_POST['group_id'];

    // إزالة الأستاذ من الفوج
    $query = "UPDATE groupe SET num_ens = NULL WHERE num_group = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $group_id);
    if ($stmt->execute()) {
        $success_message = "Teacher successfully removed from the group!";
    } else {
        $error_message = "Failed to remove the teacher from the group.";
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
    <title>Remove Teacher from Group</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            padding: 30px;
            background-color: #f4f4f4;
        }
        h2 {
            color: #333;
        }
        form {
            width: 60%;
            margin: auto;
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        label {
            font-weight: bold;
            display: block;
            margin-bottom: 8px;
            color: #333;
        }
        select, button {
            padding: 12px;
            font-size: 16px;
            margin-bottom: 15px;
            width: 100%;
            border-radius: 5px;
            border: 1px solid #ddd;
        }
        button {
            background-color: #e74c3c;
            color: white;
            cursor: pointer;
            font-weight: bold;
        }
        button:hover {
            background-color: #c0392b;
        }
        .message {
            margin-top: 20px;
            padding: 15px;
            border-radius: 5px;
            color: #fff;
            font-size: 16px;
        }
        .success {
            background-color: #2ecc71;
        }
        .error {
            background-color: #e74c3c;
        }
    </style>
</head>
<body>
<?php include("header.php") ?>
    <h2>Remove Teacher from Group</h2>

    <!-- Form to select group -->
    <form method="POST">
        <label for="group_id">Select Group:</label>
        <select name="group_id" id="group_id" required>
            <option value="">-- Select Group --</option>
            <?php foreach ($groups as $group): ?>
                <option value="<?= $group['num_group']; ?>"><?= htmlspecialchars($group['nom_group']); ?></option>
            <?php endforeach; ?>
        </select><br>

        <button type="submit">Select Group</button>
    </form>

    <?php if ($teacher): ?>
        <!-- If a teacher is assigned to the group, show details and option to remove -->
        <h3>Assigned Teacher:</h3>
        <p><?= htmlspecialchars($teacher['nom_ens']) . ' ' . htmlspecialchars($teacher['pre_ens']); ?></p>

        <!-- Form to remove teacher from the group -->
        <form method="POST">
            <input type="hidden" name="group_id" value="<?= $group_id; ?>">
            <button type="submit" name="delete_teacher">Remove Teacher from Group</button>
        </form>
    <?php endif; ?>

    <!-- Display success or error messages -->
    <?php if (isset($success_message)): ?>
        <div class="message success"><?= $success_message; ?></div>
    <?php elseif (isset($error_message)): ?>
        <div class="message error"><?= $error_message; ?></div>
    <?php endif; ?>
    <?php include("footer.php") ?>

</body>
</html>
