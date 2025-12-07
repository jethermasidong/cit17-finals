<?php
session_start();
include "config.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name  = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role  = $_POST['role'];
    $check = mysqli_prepare($conn, "SELECT user_id FROM users WHERE email=? LIMIT 1");
    mysqli_stmt_bind_param($check, "s", $email);
    mysqli_stmt_execute($check);
    mysqli_stmt_store_result($check);

    if (mysqli_stmt_num_rows($check) > 0) {
        $error = "Email already exists. Please use a different email.";
        mysqli_stmt_close($check);
    } else {
        mysqli_stmt_close($check);
        $stmt = mysqli_prepare($conn, "INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "ssss", $name, $email, $password, $role);

        if (mysqli_stmt_execute($stmt)) {
            header("Location: users.php");
            exit();
        } else {
            $error = "Error adding user.";
        }

        mysqli_stmt_close($stmt);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Add User</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50 min-h-screen flex items-center justify-center px-4">

<div class="w-full max-w-md bg-white rounded-2xl shadow-lg p-8">

    <h1 class="text-3xl font-bold text-gray-900 mb-6 text-center">Add User</h1>

    <?php if($error): ?>
    <div class="bg-red-100 text-red-700 p-3 rounded mb-4 text-sm text-center">
        <?php echo $error; ?>
    </div>
    <?php endif; ?>

    <form method="POST" class="space-y-5">

        <input type="text" name="name" placeholder="Name" required
            class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-green-500 focus:outline-none"/>

        <input type="email" name="email" placeholder="Email" required
            class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-green-500 focus:outline-none"/>

        <input type="password" name="password" placeholder="Password" required
            class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-green-500 focus:outline-none"/>

        <select name="role" required
            class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-green-500 focus:outline-none">
            <option value="admin">Admin</option>
            <option value="tutor">Tutor</option>
            <option value="student">Student</option>
        </select>

        <button class="w-full bg-green-600 text-white font-medium py-3 rounded-lg hover:bg-green-700 transition">Add User</button>

        <a href="users.php" class="block text-center mt-3 text-gray-600 hover:underline">Back</a>
    </form>

</div>

</body>
</html>
