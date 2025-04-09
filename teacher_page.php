

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>TEACHER</title>
    <link rel="stylesheet" href="css/teacher.css" />
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
            <img src="imgs/W.jpg" alt="Background Image" class="background-image">
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
        $query = "SELECT nom_ens, pre_ens FROM enseignement WHERE email = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $user_email); // ربط البريد الإلكتروني
        $stmt->execute();
        $result = $stmt->get_result();
        $student = $result->fetch_assoc();

        if ($student) {
          
            // عرض المعلومات
           
            echo "<h1 class='name'><strong></strong> " . htmlspecialchars($student['nom_ens']) . " " . htmlspecialchars($student['pre_ens']) . "</h1>";
        
          } else {
            echo "<p>No tacher data found.</p>";
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


   <div >
        <button class="button" >
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

      <a href="add_grades.php">
        <div class="box">
            <img src="imgs/notes.jpg" alt="" />
          <div class="content">
            <h3>Add Notes</h3>
            <p>Add and edit students notes.</p>
          </div>
        </div>
      </a> 

       <a href="group.php">
        <div class="box">
          <img src="imgs/T.jpg" alt="" />
          <div class="content">
            <h3>groups</h3>
            <p>All of your groups.</p>
          </div>
        </div>
       </a> 


       <a href="edit_teacher.php">
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
            <p>Change your password  .</p>
          </div>
        </div>
        </a>






      </div>
    </div>
    <?php include("footer.php") ?>

  </body>
</html>