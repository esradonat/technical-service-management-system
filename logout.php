<?php
session_start();

// Tüm oturum değişkenlerini sil
$_SESSION = [];

// Oturumu tamamen yok et
session_destroy();

// Login sayfasına yönlendir
header("Location: login.php");
exit;
