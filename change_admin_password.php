<?php
session_start();
include "config.php";
if(!isset($_SESSION['user_id']) || $_SESSION['role']!=='admin') header("Location: login.php");

$error="";
$success="";

if($_SERVER['REQUEST_METHOD']==='POST'){
    $current = $_POST['current_password'];
    $new = $_POST['new_password'];
    $confirm = $_POST['confirm_password'];

    $stmt = mysqli_prepare($conn,"SELECT password FROM users WHERE user_id=?");
    mysqli_stmt_bind_param($stmt,"i",$_SESSION['user_id']);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt,$hash);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);

    if(!password_verify($current,$hash)){
        $error="Current password incorrect.";
    } elseif($new !== $confirm){
        $error="New password and confirm password do not match.";
    } else {
        $new_hash = password_hash($new,PASSWORD_DEFAULT);
        mysqli_query($conn,"UPDATE users SET password='$new_hash' WHERE user_id=".$_SESSION['user_id']);
        $success="Password changed successfully.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Change Password</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
<div class="w-full max-w-md bg-white rounded shadow p-6">
<h1 class="text-2xl font-semibold mb-4">Change Admin Password</h1>
<?php if($error): ?><div class="bg-red-100 text-red-700 p-3 rounded mb-4"><?php echo $error;?></div><?php endif;?>
<?php if($success): ?><div class="bg-green-100 text-green-700 p-3 rounded mb-4"><?php echo $success;?></div><?php endif;?>
<form method="POST" class="space-y-4">
<input type="password" name="current_password" placeholder="Current Password" required class="w-full border rounded px-3 py-2"/>
<input type="password" name="new_password" placeholder="New Password" required class="w-full border rounded px-3 py-2"/>
<input type="password" name="confirm_password" placeholder="Confirm Password" required class="w-full border rounded px-3 py-2"/>
<button class="w-full bg-indigo-600 text-white py-2 rounded">Change Password</button>
<a href="admin.php" class="block text-center mt-2 text-gray-600">Back</a>
</form>
</div>
</body>
</html>
