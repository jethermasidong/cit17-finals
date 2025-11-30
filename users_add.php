<?php
session_start();
include "config.php";
if (!isset($_SESSION['user_id']) || $_SESSION['role']!=='admin') header("Location: login.php");

$error = "";
if ($_SERVER['REQUEST_METHOD']==='POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];

    $stmt = mysqli_prepare($conn,"INSERT INTO users (name,email,password,role) VALUES (?,?,?,?)");
    mysqli_stmt_bind_param($stmt,"ssss",$name,$email,$password,$role);
    if (mysqli_stmt_execute($stmt)) {
        header("Location: users.php");
        exit();
    } else $error="Error adding user.";
    mysqli_stmt_close($stmt);
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Add User</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
<div class="w-full max-w-md bg-white rounded shadow p-6">
<h1 class="text-2xl font-semibold mb-4">Add User</h1>
<?php if($error): ?>
<div class="bg-red-100 text-red-700 p-3 rounded mb-4"><?php echo $error; ?></div>
<?php endif; ?>
<form method="POST" class="space-y-4">
<input type="text" name="name" placeholder="Name" required class="w-full border rounded px-3 py-2"/>
<input type="email" name="email" placeholder="Email" required class="w-full border rounded px-3 py-2"/>
<input type="password" name="password" placeholder="Password" required class="w-full border rounded px-3 py-2"/>
<select name="role" required class="w-full border rounded px-3 py-2">
<option value="admin">Admin</option>
<option value="tutor">Tutor</option>
<option value="student">Student</option> <!-- Added student role -->
</select>
<button class="w-full bg-green-600 text-white py-2 rounded">Add User</button>
<a href="users.php" class="block text-center mt-2 text-gray-600">Back</a>
</form>
</div>
</body>
</html>
