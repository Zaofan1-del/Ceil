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
    $num_niv = $_POST['num_niv'];
    $num_group = $_POST['num_group'];
    $num_lang = $_POST['num_lang'];

    // تحديث البيانات
    $sql_update = "UPDATE etudiant SET 
        nom_etud=?, 
        prenom_etud=?, 
        date_naisc=?, 
        lieu_naisc=?, 
        tel=?, 
        num_niv=?, 
        num_group=?, 
        num_lang=?
        WHERE email=?";

    $stmt = $conn->prepare($sql_update);
    $stmt->bind_param("sssssiiss", $nom_etud, $prenom_etud, $date_naisc, $lieu_naisc, $tel, $num_niv, $num_group, $num_lang, $email);

    if ($stmt->execute()) {
        echo "<script>alert('Data updated successfully!'); window.location.href='edit_student.php';</script>";
    } else {
        echo "Error updating data: " . $conn->error;
    }
    $stmt->close();
}

// جلب اللغات والمستويات
$languesResult = $conn->query("SELECT * FROM langue");
$niveauxResult = $conn->query("SELECT * FROM niveau");
$groupsResult = $conn->query("SELECT * FROM groupe"); // استعلام لجلب المجموعات

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
        <h1 class="h">Edit Student Information</h2>
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

            <label for="num_niv">Level:</label>
            <select name="num_niv" required>
                <?php while ($niveau = $niveauxResult->fetch_assoc()) { ?>
                    <option value="<?= $niveau['num_niv']; ?>" <?= ($student['num_niv'] == $niveau['num_niv']) ? 'selected' : ''; ?>>
                        <?= $niveau['nom_niv']; ?>
                    </option>
                <?php } ?>
            </select>

            <label for="num_group">Group:</label>
            <select name="num_group" required>
                <option value="">Select Group</option> <!-- Default option -->
                <?php
                if ($groupsResult->num_rows > 0) {
                    while ($row = $groupsResult->fetch_assoc()) {
                        echo '<option value="' . htmlspecialchars($row['num_group']) . '" ' . (($student['num_group'] == $row['num_group']) ? 'selected' : '') . '>' . htmlspecialchars($row['nom_group']) . '</option>';
                    }
                } else {
                    echo '<option value="">No groups available</option>';
                }
                ?>
            </select>

            <label for="num_lang">Language:</label>
            <select name="num_lang" required>
                <?php while ($langue = $languesResult->fetch_assoc()) { ?>
                    <option value="<?= $langue['num_lang']; ?>" <?= ($student['num_lang'] == $langue['num_lang']) ? 'selected' : ''; ?>>
                        <?= $langue['nom_lang']; ?>
                    </option>
                <?php } ?>
            </select>

            <input type="submit" value="Update Information" class="submit-button">
        </form>
    </div>
    <?php include("footer.php") ?>

</body>
</html>