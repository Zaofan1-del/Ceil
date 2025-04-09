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

// متغير لتخزين رسائل الأخطاء أو النجاح
$message = '';

// معالجة بيانات النموذج عند الإرسال
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $num_lang = $conn->real_escape_string($_POST["num_lang"]);
    $nom_lang = $conn->real_escape_string($_POST["nom_lang"]);

    // التحقق مما إذا كان كود اللغة موجودًا بالفعل
    $check_query = "SELECT * FROM langue WHERE num_lang = ?";
    $check_stmt = $conn->prepare($check_query);
    $check_stmt->bind_param("s", $num_lang);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    // التحقق من أن الحقول ليست فارغة
    if (!empty($num_lang) && !empty($nom_lang)) {
        if ($check_result->num_rows > 0) {
            $message = "<p style='color: red;'>Language code already exists!</p>";
        } else {
            $sql = "INSERT INTO langue (num_lang, nom_lang) VALUES ('$num_lang', '$nom_lang')";

            if ($conn->query($sql) === TRUE) {
                $message = "<p style='color: green;'>Language added successfully!</p>";
            } else {
                $message = "<p style='color: red;'>Error adding language: " . $conn->error . "</p>";
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
    <title>Add New Language</title>
    <link rel="stylesheet" href="css/add_langue.css"> <!-- Link to CSS file -->
</head>
<body>
<?php include("header.php") ?>

    <h1 class="new-lng">Add New Language</h1>

    <?php if ($message): ?> <!-- Display message here -->
        <div class="message"><?php echo $message; ?></div>
    <?php endif; ?>

    <form method="post" action="" class="add-language-form">
        <label for="num_lang">Language Code:</label>
        <input type="text" id="num_lang" name="num_lang" required>

        <label for="nom_lang">Language Name:</label>
        <input type="text" id="nom_lang" name="nom_lang" required>

        <button type="submit">Add</button>
    </form>

    <?php include("footer.php") ?>

</body>
</html>

<?php
// إغلاق الاتصال بقاعدة البيانات
$conn->close();
?>