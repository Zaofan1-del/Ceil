<?php
// الحصول على اسم الصفحة الحالية
$current_page = basename($_SERVER['PHP_SELF']);

// تحديد اللغة الحالية
$current_language = isset($_GET['lang']) ? $_GET['lang'] : 'en'; // الافتراضي هو الإنجليزية

// بناء رابط الصفحة الجديدة
$new_url = "arabic/" . pathinfo($current_page, PATHINFO_FILENAME) . "_ar.php";
?>

<!DOCTYPE html>
<html lang="<?= $current_language === 'ar' ? 'ar' : 'en' ?>">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Responsive Website</title>
    <link rel="stylesheet" href="assets/css/header.css">
    <link href='https://cdn.jsdelivr.net/npm/boxicons@2.0.5/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="css/all.min.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;700&display=swap" rel="stylesheet" />
</head>
<body class="header-body">

    <!--========== HEADER ==========-->
    <header class="l-header" id="header">
        <nav class="nav bd-container">
            <img id="logo" src="assets/img/black.png" alt="Logo" class="logo">

            <div class="nav__menu" id="nav-menu">
                <ul class="nav__list">
                    <li class="nav__item"><a href="index.php?lang=<?= $current_language ?>" class="nav__link">Home</a></li>
                    <li class="nav__item"><a href="about.php?lang=<?= $current_language ?>" class="nav__link">About</a></li>
                    <li class="nav__item"><a href="login.php?lang=<?= $current_language ?>" class="nav__link">Account</a></li>
                    <li class="nav__item"><a href="index.php?lang=<?= $current_language ?>#contact-us" class="nav__link">Contact us</a></li>
                    <li><i class='bx bx-moon change-theme' id="theme-button"></i></li>
                    <li><a href="<?= $new_url ?>" class="nav__link">Arabic</a></li> <!-- زر تغيير اللغة -->
                </ul>
            </div>

            <div class="nav__toggle" id="nav-toggle">
                <i class='bx bx-menu'></i>
            </div>
        </nav>
    </header>

    <!--========== MAIN JS ==========-->
    <script src="assets/js/main.js"></script>
</body>
</html>