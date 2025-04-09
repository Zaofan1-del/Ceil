<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>STUDENT</title>
    <link rel="stylesheet" href="css/student.css" />
    <!-- Font Awesome -->
    <link rel="stylesheet" href="css/all.min.css" />
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;700&display=swap" rel="stylesheet" />
</head>
<body>
<?php include("header.php") ?>

<div class="container-img">
    <div class="image-container">
        <img src="imgs/Z.jpg" alt="Background Image" class="background-image">
    </div>
</div>

<!-- Start Personal Card -->
<div class="personal-card">
    <?php
    session_start();
    if (isset($_SESSION['user_email'])) { // استخدام البريد الإلكتروني بدلاً من user_id
        // جلب معلومات الطالب من قاعدة البيانات
        $user_email = $_SESSION['user_email'];
        // الاتصال بقاعدة البيانات
        $conn = new mysqli("localhost", "root", "", "ceil");
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // جلب بيانات الطالب باستخدام البريد الإلكتروني
        $query = "SELECT nom_etud, prenom_etud, num_niv, num_group, num_lang FROM etudiant WHERE email = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $user_email); // ربط البريد الإلكتروني
        $stmt->execute();
        $result = $stmt->get_result();
        $student = $result->fetch_assoc();

        if ($student) {
            // جلب اسم اللغة
            $query_lang = "SELECT nom_lang FROM langue WHERE num_lang = ?";
            $stmt_lang = $conn->prepare($query_lang);
            $stmt_lang->bind_param("s", $student['num_lang']);
            $stmt_lang->execute();
            $result_lang = $stmt_lang->get_result();
            $language = $result_lang->fetch_assoc();

            // جلب اسم المستوى
            $query_niv = "SELECT nom_niv FROM niveau WHERE num_niv = ?";
            $stmt_niv = $conn->prepare($query_niv);
            $stmt_niv->bind_param("s", $student['num_niv']);
            $stmt_niv->execute();
            $result_niv = $stmt_niv->get_result();
            $level = $result_niv->fetch_assoc();

            // جلب اسم المجموعة
            $query_group = "SELECT nom_group FROM groupe WHERE num_group = ?";
            $stmt_group = $conn->prepare($query_group);
            $stmt_group->bind_param("s", $student['num_group']);
            $stmt_group->execute();
            $result_group = $stmt_group->get_result();
            $group = $result_group->fetch_assoc();

            // عرض المعلومات
            echo "<h1 class='name'><strong></strong> " . htmlspecialchars($student['nom_etud']) . " " . htmlspecialchars($student['prenom_etud']) . "</h1>";
            echo "<p><strong>Language:</strong> " . (isset($language['nom_lang']) ? htmlspecialchars($language['nom_lang']) : 'N/A') . "</p>";
            echo "<p><strong>Level:</strong> " . (isset($level['nom_niv']) ? htmlspecialchars($level['nom_niv']) : 'N/A') . "</p>";
            echo "<p><strong>Group:</strong> " . (isset($group['nom_group']) ? htmlspecialchars($group['nom_group']) : 'N/A') . "</p>";
        } else {
            echo "<p>No student data found.</p>";
        }

        // Close statements if they were created
        if (isset($stmt)) $stmt->close();
        if (isset($stmt_lang)) $stmt_lang->close();
        if (isset($stmt_niv)) $stmt_niv->close();
        if (isset($stmt_group)) $stmt_group->close();
        
        $conn->close();
    } else {
        echo "<p>User not logged in.</p>";
    }
    ?>

   <div>
        <button class="button">
        <a href="logout.php">
            <span class="span1" aria-hidden="true">
                <span class="icone"></span>
            </span>
            <span class="text">Logout</span>
        </a>
        </button>
    </div>
</div>
<!-- End Personal Card -->

<!-- Start Articles -->
<div class="articles" id="articles">
    <div class="container">
        <a href="notes.php">
            <div class="box">
                <img src="imgs/notes.jpg" alt="" />
                <div class="content">
                    <h3>Your Notes</h3>
                    <p>See your notes.</p>
                </div>
            </div>
        </a> 

        <a href="group_stud.php">
            <div class="box">
                <img src="imgs/T.jpg" alt="" />
                <div class="content">
                    <h3>Your group</h3>
                    <p>See your group.</p>
                </div>
            </div>
        </a>

        <a href="edit_student_self.php">
            <div class="box">
                <img src="imgs/inf.jpg" alt="" />
                <div class="content">
                    <h3>Edit Informations</h3>
                    <p>Edit your informations ... .</p>
                </div>
            </div>
        </a>

        <a href="password.php">
            <div class="box">
                <img src="imgs/password.jpg" alt="" />
                <div class="content">
                    <h3>Edit Password</h3>
                    <p>Change your password.</p>
                </div>
            </div>
        </a>
    </div>
</div>

<?php include("footer.php") ?>

</body>
</html>