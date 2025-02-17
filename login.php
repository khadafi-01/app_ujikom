<?php
// login.php
session_start();
include 'config/db.php';

// Proses login ketika form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Escape input untuk keamanan
    $username = $conn->real_escape_string($_POST['username']);
    $password = $_POST['password']; // Password dalam bentuk plain text

    // Query untuk mencari user berdasarkan username
    $sql = "SELECT * FROM tbl_user WHERE username = '$username'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        // Verifikasi password menggunakan password_verify
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            header("Location: index.php");
            exit;
        } else {
            $error = "Password salah!";
        }
    } else {
        $error = "Username tidak ditemukan!";
    }
}
