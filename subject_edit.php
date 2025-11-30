<?php
session_start();
include "config.php";
if (!isset($_SESSION['user_id']) || $_SESSION['role']!=='admin') header("Location: login.php");

$id = $_GET['id'] ?? 0;
$res = mysqli_query($conn, "SELECT * FROM subjects WHERE subject_id=".$id);
$subject = mysqli_fetch_assoc($res);
if(!$subject){ header("Location: subjects.php"); exit(); }

$error="";
if($_SERVER['REQUEST_METHOD']==='POST'){
    $name = trim($_POST['subject_name']);
    $stmt = mysqli_prepare($conn,"UPDATE subjects SET subject_name=? WHERE subject_id=?");
    mysqli_stmt_bind_param($stmt,"si",$name,$id);
    if(mysqli_stmt_execute($stmt)) header("Location: subjects.php");
    else $error="Error updating subject.";
    mysqli_stmt_close($stmt);
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Edit Subject</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
<div class="w-full max-w-md bg-white rounded shadow p-6">
<h1 class="text-2xl font-semibold mb-4">Edit Subject</h1>
<?php if($error): ?><div class="bg-red-100 text-red-700 p-3 rounded mb-4"><?php echo $error;?></div><?php endif;?>
<form method="POST" class="space-y-4">
<input type="text" name="subject_name" value="<?php echo htmlspecialchars($subject['subject_name']); ?>" required class="w-full border rounded px-3 py-2"/>
<button class="w-full bg-blue-600 text-white py-2 rounded">Update Subject</button>
<a href="subjects.php" class="block text-center mt-2 text-gray-600">Back</a>
</form>
</div>
</body>
</html>
