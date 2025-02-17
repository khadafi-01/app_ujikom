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
            $success = "Data berhasil tersimpan!";

            // Redirect ke halaman login setelah berhasil
            header('Location: login.php'); // Ganti dengan URL yang sesuai
            exit();
        } else {
            $error = "Error: " . $conn->error;
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
</head>

<style>
    /* CSS to add line under the eye icon */
    .fa-eye-slash::after,
    .fa-eye::after {
        content: '';
        position: absolute;
        width: 120%;
        /* Membuat garis sedikit lebih panjang */
        height: 2px;
        background-color: black;
        bottom: 5.6px;
        left: -12%;
        /* Memperpanjang ke kiri agar lebih simetris */
        visibility: hidden;
        transform: rotate(-60deg);
        /* Default: hidden when password is visible */
    }

    .fa-eye-slash::after {
        visibility: visible;
        /* Show line when the password is hidden */
    }
</style>
</head>

<body class="bg-gray-100 flex justify-center items-center h-screen">
    <div class="w-full max-w-sm bg-white p-6 rounded shadow-lg">
        <h2 class="text-2xl font-bold mb-4 text-center">Register</h2>
        <?php if (!empty($error)) : ?>
            <script>
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: '<?php echo $error; ?>',
                });
            </script>
        <?php elseif (!empty($success)) : ?>
            <script>
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: '<?php echo $success; ?>',
                }).then(() => {
                    // Using Toastify to show the success message
                    Toastify({
                        text: "Data berhasil tersimpan",
                        backgroundColor: "green",
                        duration: 3000, // 3 seconds
                        close: true,
                        gravity: "bottom", // "top" or "bottom"
                        position: "center", // "left", "center", or "right"
                        stopOnFocus: true // Will not close the toast if hovered
                    }).showToast();

                    // Redirect after a short delay
                    setTimeout(() => {
                        window.location.href = "login.php";
                    }, 3000); // Redirect after 3 seconds (when toast message is gone)
                });
            </script>
        <?php endif; ?>

        <form id="registerForm" action="" method="POST">
            <div class="mb-4">
                <label for="username" class="block text-gray-700">Username</label>
                <input type="text" id="username" name="username" class="w-full p-2 border rounded">
            </div>
            <div class="mb-4">
                <label for="password" class="block text-gray-700">Password</label>
                <div class="relative">
                    <input type="password" id="password" name="password" class="w-full p-2 border rounded pr-10">
                    <i class="fas fa-eye absolute top-3 right-3 cursor-pointer text-gray-500" id="togglePassword"></i>
                </div>
                <div id="password_strength" class="mt-2 text-sm"></div>
                <div id="password_bar" class="h-1 bg-gray-300 mt-1 rounded-full"></div>
            </div>
            <div class="mb-4">
                <label for="confirm_password" class="block text-gray-700">Confirm Password</label>
                <div class="relative">
                    <input type="password" id="confirm_password" name="confirm_password" class="w-full p-2 border rounded pr-10">
                    <i class="fas fa-eye absolute top-3 right-3 cursor-pointer text-gray-500" id="toggleConfirmPassword"></i>
                </div>
            </div>
            <button class="bg-blue-500 px-4 py-2 rounded hover:bg-blue-700 w-full" type="submit">Register</button>
        </form>
    </div>
</body>

</html>