

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Elzero</title>
    <link rel="stylesheet" href="css/admin.css" />
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



<div class="personal-card">
           <h1 class='name'><strong>ADMIN</strong></h1>
    

      <!-- edit email -->
           <button class="button" >
        <a href="email_admin.php">
            <span class="span1" aria-hidden="true">
                <span class="icone"></span>
            </span>
            <span class="text">Edit Email</span>
        </a>
      </button> <br>


      <!-- edit password -->
      <button class="button" >
        <a href="password.php">
            <span class="span1" aria-hidden="true">
                <span class="icone"></span>
            </span>
            <span class="text">password</span>
        </a>
      </button><br>






      <!-- logout -->
      <button class="button out" >
        <a href="logout.php">
            <span class="span1" aria-hidden="true">
                <span class="icone"></span>
            </span>
            <span class="text">Logout</span>
        </a>
      </button>
    
</div>
        
      
   
    <!-- Start Articles -->
    <div class="articles" id="articles">

  
      <div class="container">

      <a href="add_group.php">
        <div class="box">
          <img src="imgs/group.jpg" alt="" />
          <div class="content">
            <h3>Add Group</h3>
            <p>Create a new group for students.</p>
          </div>
        </div>
       </a> 



       <a href="add_prg.php">
        <div class="box">
          <img src="imgs/prg.jpg" alt=""/>
          <div class="content">
            <h3>Add program</h3>
            <p>add programs </p>
          </div>
        </div>
       </a> 




       <a href="add_level.php">
        <div class="box">
          <img src="imgs/level.jpg" alt="" />
          <div class="content">
            <h3>Add Level</h3>
            <p>Create a new levels of education .</p>
          </div>
        </div>
        </a>





        <a href="add_langue.php">
        <div class="box">
          <img src="imgs/langue.jpg" alt="" />
          <div class="content">
            <h3>Add Language</h3>
            <p>Add a language to education .</p>
          </div>
        </div>
        </a>




        <a href="register_teacher.php">
        <div class="box">
          <img src="imgs/teacher.jpg" alt="" />
          <div class="content">
            <h3>Add Teacher</h3>
            <p>Add new teacher and put his (her) informations.</p>
          </div>
        </div>
        </a>



        <a href="register.php">
        <div class="box">
          <img src="imgs/student.jpg" alt="" />
          <div class="content">
            <h3>Add Student</h3>
            <p>Register new students and put their informations .</p>
          </div>
        </div>
        </a>




        <a href="tchr_to_grp.php">
        <div class="box">
          <img src="imgs/5.jpg" alt="" />
          <div class="content">
            <h3>Select Teacher's Group</h3>
            <p>Giving each group its own teacher.</p>
          </div>
        </div>
        </a>



        <a href="add_gr_to_etud.php">
        <div class="box">
          <img src="imgs/6.jpg" alt="" />
          <div class="content">
            <h3>Select Student's Group</h3>
            <p>Giving each group their students.</p>
          </div>
        </div>
        </a>



        <a href="list_students.php">
        <div class="box">
          <img src="imgs/7.jpg" alt="" />
          <div class="content">
            <h3>Students List</h3>
            <p>View the complete list of students enrolled in your institution along with their details.</p>
            <!-- عرض القائمة الكاملة للطلاب المسجلين في مؤسستك مع تفاصيلهم. -->
          </div>
        </div>
        </a>



        <a href="list_teachers.php">
        <div class="box">
          <img src="imgs/8.jpg" alt="" />
          <div class="content">
            <h3>Teachers List</h3>
            <p>Access the list of all teachers </p>
          </div>
        </div>
        </a>
      </div>
    </div>
    <?php include("footer.php") ?>

  </body>
</html>