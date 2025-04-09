<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!--========== BOX ICONS ==========-->
    <link href='https://cdn.jsdelivr.net/npm/boxicons@2.0.5/css/boxicons.min.css' rel='stylesheet'>

    <!--========== CSS ==========-->
    <link rel="stylesheet" href="assets/css/footer.css">

    <title>Responsive website food</title>
</head>
<body class="footer-body">

    <!--========== FOOTER ==========-->
    <footer class="footer">
        <div class="footer__container">
            <div class="footer__content">
                <a href="#" class="footer__logo"><img id="footer-logo" src="assets/img/black.png" alt=""></a>
                <span class="footer__description">Language Teaching Center</span>
                <div>
                    <a href="https://www.facebook.com/profile.php?id=100063516701902" target="_blank" class="footer__social"><i class='bx bxl-facebook'></i></a>
                    <a href="#" class="footer__social"><i class='bx bxl-instagram'></i></a>
                    <a href="#" class="footer__social"><i class='bx bxl-whatsapp'></i></a>
                </div>
            </div>

            <div class="footer__content">
                <h3 class="footer__title">Pages</h3>
                <ul>
                    <li><a href="index.php" class="footer__link">Home</a></li>
                    <li><a href="about.php" class="footer__link">About CEIL</a></li>
                    <li><a href="login.php" class="footer__link">Log in</a></li>
                    <li><a href="index.php?lang=<?= $current_language ?>#contact-us" class="footer__link">Contact us</a></li>
                </ul>
            </div>

            <div class="footer__content">
                <h3 class="footer__title">Address</h3>
                <ul>
                    <li>Intensive Language Teaching Center - C.E.I.L - Jijel</li>
                    <li>University of Jijel -tassoust-</li>
                    <li>+213 34 55 00 00</li>
                    <li>ceil-info@email.com</li>
                </ul>
            </div>
        </div>

        <p class="footer__copy">&#169; Copyright © 2025 designed and programmed by M - S</p>
    </footer>

    <!--========== MAIN JS ==========-->
    <script src="assets/js/main.js"></script>
</body>
</html>