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
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>

<body>
    <?php include 'includes/header.php'; ?>
    <div class="flex items-center justify-center min-h-screen bg-gray-100">
        <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md">
            <h2 class="text-3xl font-bold text-center text-gray-800 mb-6">Login</h2>

            <?php if (isset($error)): ?>
                <div class="bg-red-200 text-red-800 p-3 mb-4 rounded-lg">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="mb-5">
                    <label for="username" class="block text-gray-700 text-sm font-semibold mb-2">Username</label>
                    <input type="text" id="username" name="username" required class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="mb-5">
                    <label for="password" class="block text-gray-700 text-sm font-semibold mb-2">Password</label>
                    <div class="relative">
                        <input type="password" id="password" name="password" required class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <i id="togglePassword" class="fas fa-eye absolute right-3 top-1/2 transform -translate-y-1/2 cursor-pointer text-gray-500"></i>
                    </div>
                </div>

                <button type="submit" class="w-full bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">Login</button>
            </form>

            <p class="mt-4 text-center text-sm">
                Belum punya akun? <a href="register.php" class="text-blue-600 hover:underline">Register</a>
            </p>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>

</body>

</html>