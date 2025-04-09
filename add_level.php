<?php
// الاتصال بقاعدة البيانات
$servername = "localhost";
$username = "root"; // Database username
$password = ""; // Database password
$dbname = "ceil"; // Database name

$conn = new mysqli($servername, $username, $password, $dbname);

// التحقق من الاتصال
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// جلب اللغات من قاعدة البيانات
$languages = [];
$sql = "SELECT num_lang, nom_lang FROM langue";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $languages[] = $row;
    }
}

// متغير لتخزين رسائل الأخطاء أو النجاح
$message = '';

// معالجة بيانات النموذج عند الإرسال
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $num_niv = $conn->real_escape_string($_POST["num_niv"]);
    $nom_niv = $conn->real_escape_string($_POST["nom_niv"]);
    $num_lang = $conn->real_escape_string($_POST["num_lang"]);

    // التحقق مما إذا كان كود المستوى موجودًا بالفعل
    $check_query = "SELECT * FROM niveau WHERE num_niv = ?";
    $check_stmt = $conn->prepare($check_query);
    $check_stmt->bind_param("s", $num_niv);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    // التحقق من أن الحقول ليست فارغة
    if (!empty($num_niv) && !empty($nom_niv) && !empty($num_lang)) {
        if ($check_result->num_rows > 0) {
            $message = "<p style='color: red;'>Level number already exists!</p>";
        } else {
            $sql = "INSERT INTO niveau (num_niv, nom_niv, num_lang) VALUES ('$num_niv', '$nom_niv', '$num_lang')";

            if ($conn->query($sql) === TRUE) {
                $message = "<p style='color: green;'>Level added successfully!</p>";
            } else {
                $message = "<p style='color: red;'>Error adding level: " . $conn->error . "</p>";
            }
        }
    } else {
        $message = "<p style='color: red;'>Please fill in all fields.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Level</title>
    <link rel="stylesheet" href="css/level.css"> <!-- Link to CSS file -->
</head>
<body>
<?php include("header.php") ?>

    <h1 class="new-lvl">Add New Level</h1>

    <?php if ($message): ?> <!-- Display message here -->
        <div class="message"><?php echo $message; ?></div>
    <?php endif; ?>

    <form method="post" action="" class="add-level-form">
        <label for="num_niv">Level Number:</label>
        <input type="text" id="num_niv" name="num_niv" required>

        <label for="nom_niv">Level Name:</label>
        <input type="text" id="nom_niv" name="nom_niv" required>

        <label for="num_lang">Select Language:</label>
        <select id="num_lang" name="num_lang" required>
            <option value="" disabled selected>Select Language</option>
            <?php foreach ($languages as $language): ?>
                <option value="<?= htmlspecialchars($language['num_lang']); ?>">
                    <?= htmlspecialchars($language['nom_lang']); ?>
                </option>
            <?php endforeach; ?>
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