<?php
// الاتصال بقاعدة البيانات
$servername = "localhost";
$username = "root"; // Database username
$password = ""; // Database password
$dbname = "ceil"; // Database name

// إنشاء الاتصال
$conn = new mysqli($servername, $username, $password, $dbname);

// التحقق من الاتصال
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// استعلام لجلب اللغات
$language_query = "SELECT * FROM langue";
$languages = $conn->query($language_query);

// استعلام لجلب المستويات
$level_query = "SELECT * FROM niveau";
$levels = $conn->query($level_query);

// متغير لتخزين رسائل الأخطاء أو النجاح
$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // الحصول على البيانات من النموذج
    $num_group = $_POST['num_group']; // Ensure using num_group instead of code_group
    $nom_group = $_POST['nom_group'];
    $num_lang = $_POST['num_lang'];
    $num_niv = $_POST['num_niv'];

    // التحقق مما إذا كان كود الفوج موجودًا بالفعل
    $check_query = "SELECT * FROM groupe WHERE num_group = ?";
    $check_stmt = $conn->prepare($check_query);
    $check_stmt->bind_param("s", $num_group);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows > 0) {
        $message = "<p style='color: red;'>Group code already exists!</p>";
    } else {
        // استعلام لإدخال البيانات في جدول `groupe`
        $sql = "INSERT INTO groupe (num_group, nom_group, num_lang, num_niv) VALUES ('$num_group', '$nom_group', '$num_lang', '$num_niv')";

        if ($conn->query($sql) === TRUE) {
            $message = "<p style='color: green;'>Group added successfully!</p>";
        } else {
            $message = "<p style='color: red;'>Error: " . $conn->error . "</p>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Group</title>
    <link rel="stylesheet" href="css/add_group.css"> <!-- Link to CSS file -->
</head>
<body>
<?php include("header.php") ?>

    <h2 class="new-grp">Add New Group</h2>
    
    <?php if ($message): ?> <!-- Display message here -->
        <div class="message"><?php echo $message; ?></div>
    <?php endif; ?>

    <form method="POST" class="add-group-form">
        <label for="num_group">Group Code:</label>
        <input type="text" id="num_group" name="num_group" required>

        <label for="nom_group">Group Name:</label>
        <input type="text" id="nom_group" name="nom_group" required>

        <label for="num_lang">Language:</label>
        <select name="num_lang" id="num_lang" required>
            <option value="">Select Language</option>
            <?php
            if ($languages->num_rows > 0) {
                while($row = $languages->fetch_assoc()) {
                    echo "<option value='" . $row['num_lang'] . "'>" . $row['nom_lang'] . "</option>";
                }
            } else {
                echo "<option value=''>No languages available</option>";
            }
            ?>
        </select>

        <label for="num_niv">Level:</label>
        <select name="num_niv" id="num_niv" required>
            <option value="">Select Level</option>
            <?php
            if ($levels->num_rows > 0) {
                while($row = $levels->fetch_assoc()) {
                    echo "<option value='" . $row['num_niv'] . "'>" . $row['nom_niv'] . "</option>";
                }
            } else {
                echo "<option value=''>No levels available</option>";
            }
            ?>
        </select>

        <button type="submit">Add</button>
    </form>
    <?php include("footer.php") ?>

</body>
</html>

<?php
// إغلاق الاتصال بقاعدة البيانات
$conn->close();
?>