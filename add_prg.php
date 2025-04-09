<?php
// إعدادات الاتصال بقاعدة البيانات
$servername = "127.0.0.1";
$username = "root"; // استبدل باسم المستخدم الخاص بك
$password = ""; // استبدل بكلمة المرور الخاصة بك
$dbname = "ceil"; // اسم قاعدة البيانات

// إنشاء اتصال
$conn = new mysqli($servername, $username, $password, $dbname);

// التحقق من الاتصال
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// متغيرات الرسائل
$message = '';
$error_message = '';

// معالجة تحميل الصورة
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $num_group = $_POST['num_group'];
    $target_dir = "uploads/";

    // تحقق مما إذا كان المجلد موجودًا، وإذا لم يكن موجودًا، قم بإنشائه
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0755, true);
    }

    $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // التحقق مما إذا كانت الصورة فعلاً صورة
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if ($check !== false) {
        $message = "The file is an image - " . $check["mime"] . ".";
        $uploadOk = 1;
    } else {
        $error_message = "The file is not an image.";
        $uploadOk = 0;
    }

    // السماح فقط بأنواع معينة من الملفات
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
        $error_message = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }

    // تحقق مما إذا كان $uploadOk تم تعيينه إلى 0 بسبب خطأ
    if ($uploadOk == 0) {
        $error_message = "Sorry, your file was not uploaded.";
    } else {
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
            $message = "The image has been uploaded successfully.";
        } else {
            $error_message = "Sorry, there was an error uploading your file.";
        }
    }

    // تحديث قاعدة البيانات (يمكنك الاحتفاظ بهذا الجزء إذا كنت تريد تحديث قاعدة البيانات)
    if ($uploadOk == 1) {
        $sql = "UPDATE groupe SET prg_group='$target_file' WHERE num_group='$num_group'";
        if ($conn->query($sql) !== TRUE) {
            $error_message = "Error updating record: " . $conn->error;
        }
    }
}
// استعلام لجلب الأفواج من قاعدة البيانات
$groups_sql = "SELECT num_group, nom_group FROM groupe";
$groups_result = $conn->query($groups_sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Upload Image</title>
    <link rel="stylesheet" href="css/add_prg.css" />
</head>
<body>
<?php include("header.php") ?>
     <!-- عرض رسائل الخطأ والنجاح -->
     <?php if ($message): ?>
        <div class="message success"><?php echo $message; ?></div>
    <?php endif; ?>
    <?php if ($error_message): ?>
        <div class="message error"><?php echo $error_message; ?></div>
    <?php endif; ?>
    <h2 class="h">Add program for Group</h2>
    <form action="" method="post" enctype="multipart/form-data">
        <label for="num_group">Select Group:</label>
        <select name="num_group" id="num_group">
            <?php
            // تحقق مما إذا كانت هناك مجموعات
            if ($groups_result->num_rows > 0) {
                // عرض كل مجموعة في القائمة المنسدلة
                while ($row = $groups_result->fetch_assoc()) {
                    echo "<option value='" . $row['num_group'] . "'>" . $row['nom_group'] . "</option>";
                }
            } else {
                echo "<option value=''>No groups available</option>";
            }
            ?>
        </select> <br><br>
        <label for="fileToUpload">Choose an image to upload:</label>
        <input type="file" name="fileToUpload" id="fileToUpload" required><br><br>
        <input type="submit" value="Upload Image" name="submit">
    </form>
    <?php include("footer.php") ?>
</body>
</html>
<?php
$conn->close();
?>