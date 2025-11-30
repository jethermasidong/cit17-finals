<?php
session_start();
include "config.php";

$error = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = mysqli_prepare($conn, "SELECT user_id,name,password,role FROM users WHERE email=? LIMIT 1");
    mysqli_stmt_bind_param($stmt,"s",$email);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt,$user_id,$name,$hash,$role);
    if (mysqli_stmt_fetch($stmt)) {
        if (password_verify($password,$hash)) {
            $_SESSION['user_id'] = $user_id;
            $_SESSION['name'] = $name;
            $_SESSION['role'] = $role;
            mysqli_stmt_close($stmt);

            // Redirect based on role
            if ($role==='admin') header("Location: admin.php");
            elseif ($role==='tutor') header("Location: teacher.php");
            elseif ($role==='student') header("Location: index.php"); // Student dashboard
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
<title>Login</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
<div class="w-full max-w-md bg-white rounded shadow p-6">
<h1 class="text-2xl font-semibold mb-4">Login</h1>
<?php if($error): ?>
<div class="bg-red-100 text-red-700 p-3 rounded mb-4"><?php echo $error; ?></div>
<?php endif; ?>
<form method="POST" class="space-y-4">
<input type="email" name="email" placeholder="Email" required class="w-full border rounded px-3 py-2"/>
<input type="password" name="password" placeholder="Password" required class="w-full border rounded px-3 py-2"/>
<button class="w-full bg-indigo-600 text-white py-2 rounded">Login</button>
</form>
</div>
</body>
</html>
