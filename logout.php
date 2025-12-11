<?php
require_once 'auth.php';

startSession();

// Çıkış yap
logoutUser();
header('Location: index.php');
exit();
?>
