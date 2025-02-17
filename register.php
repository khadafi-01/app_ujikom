<?php
session_start();
include 'config/db.php';

// Proses register ketika form disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Escape untuk keamanan
    $username = $conn->real_escape_string($_POST['username']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validasi input kosong
    if (empty($username) || empty($password) || empty($confirm_password)) {
        $error = "Semua data harus diisi!";
    } else if (!preg_match('/[a-zA-Z]/', $password) || !preg_match('/[0-9]/', $password)) {
        // Validasi minimal harus ada huruf dan angka
        $error = "Password harus mengandung huruf dan angka!";
    } elseif ($password !==  $confirm_password) {
        // Validasi kecocokan password
        $error = "Password tidak cocok!";
    } else {
        // Hash password menggunakan algoritma terbaru (default BCRYPT)
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        // Insert data user ke tabel tbl_user
        $sql = "INSERT INTO tbl_user (username, password) VALUES ('$username', '$passwordHash')";
        if ($conn->query($sql) === TRUE) {
            $_SESSION['user_id'] = $conn->insert_id;
            $success = "Error: " . $conn->error;
        }
    }
}
