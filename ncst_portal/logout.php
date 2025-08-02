<?php
session_start();
session_unset();
session_destroy();
header('Location: ncst_login.php'); // or adjust the path as needed
exit;
?>