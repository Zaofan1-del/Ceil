<?php
session_start(); // بدء الجلسة
session_unset(); // إزالة جميع المتغيرات المسجلة في الجلسة
session_destroy(); // إنهاء الجلسة بالكامل
header("Location: login.php"); // إعادة التوجيه إلى صفحة تسجيل الدخول
exit();
?>