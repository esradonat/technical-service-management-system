<?php

$host = "localhost";
$port = "5432";
$dbname = "teknik_servis_db";
$user = "your_username";
$password = "your_password";

try {

    $db = new PDO(
        "pgsql:host=$host;port=$port;dbname=$dbname",
        $user,
        $password
    );

    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {

    die("Veritabanı bağlantı hatası: " . $e->getMessage());

}