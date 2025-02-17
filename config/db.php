<?php

//Konfigurasi koneksi ke databaase MySQL
$host = "localhost";
$user = "root";
$password = "";
$db = "db_ujikom_khadafiahmadalfarezi";

// Membuat koneksi
$conn = new mysqli($host, $user, $password, $db);

// Cek koneksi DB
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
