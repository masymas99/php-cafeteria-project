<?php
session_start();

// إنهاء الجلسة
session_unset(); // إزالة جميع المتغيرات من الجلسة
session_destroy(); // إنهاء الجلسة تمامًا

// إعادة التوجيه إلى صفحة تسجيل الدخول
header("Location: ../login/login.php");
exit();
?>
