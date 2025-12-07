<?php
session_start();
include "config.php";

$error = "";

$static_admin_email = "admin@gmail.com";
$static_admin_password = "admin123"; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if ($email === $static_admin_email && $password === $static_admin_password) {
        $_SESSION['user_id'] = 0; 
        $_SESSION['name'] = "System Administrator";
        $_SESSION['role'] = "admin";

        header("Location: admin.php");
        exit();
    }

    
    $stmt = mysqli_prepare($conn, "SELECT user_id, name, password, role FROM users WHERE email=? LIMIT 1");
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $user_id, $name, $hash, $role);

    if (mysqli_stmt_fetch($stmt)) {
        if (password_verify($password, $hash)) {
            $_SESSION['user_id'] = $user_id;
            $_SESSION['name'] = $name;
            $_SESSION['role'] = $role;

            mysqli_stmt_close($stmt);

            if ($role === 'admin') header("Location: admin.php");
            elseif ($role === 'tutor') header("Location: teacher.php");
            elseif ($role === 'student') header("Location: index-student.php");
            exit();
        } else {
            $error = "Incorrect password.";
        }
    } else {
        $error = "Email not found.";
    }

    mysqli_stmt_close($stmt);
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Login - Primal Tutoring Services</title>
<script src="https://cdn.tailwindcss.com"></script>

<style>
    body {
        background-image: url('bg.jpg');
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
    }
</style>
</head>

<body class="min-h-screen flex items-center justify-center px-4 relative">

<div class="absolute inset-0 bg-black bg-opacity-40 backdrop-blur-sm"></div>

<div class="w-full max-w-md bg-white/90 backdrop-blur-lg border border-gray-200 rounded-xl shadow-lg p-8 relative">

    <div class="flex justify-center mb-4">
        <img src="logo.png" alt="Logo" class="w-32 h-32 object-cover rounded-full shadow-md border border-gray-300">
    </div>

    <h1 class="text-center text-2xl font-bold tracking-wide text-gray-800 mb-1">
        PRIMAL TUTORING SERVICES
    </h1>
    <p class="text-center text-gray-500 mb-6 text-sm">Login to continue</p>

    <?php if($error): ?>
        <div class="bg-red-100 text-red-700 p-3 rounded mb-4 text-sm">
            <?php echo $error; ?>
        </div>
    <?php endif; ?>

    <form method="POST" class="space-y-4">

        <div>
            <input type="email" name="email" placeholder="Email"
                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none" required>
        </div>

        <div>
            <input type="password" name="password" placeholder="Password"
                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none" required>
        </div>

        <button class="w-full bg-indigo-600 text-white font-medium py-2 rounded-lg hover:bg-indigo-700 transition">
            Login
        </button>

    </form>

    <p class="mt-4 text-center text-gray-600 text-sm">
        Don't have an account? 
        <a href="register.php" class="text-green-600 font-medium hover:underline">Register here</a>
    </p>

</div>

</body>
</html>
