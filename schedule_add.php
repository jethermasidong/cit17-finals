<?php
session_start();
include "config.php";
if(!isset($_SESSION['user_id']) || $_SESSION['role']!=='admin') header("Location: login.php");

$error="";
$tutors=mysqli_query($conn,"SELECT * FROM users WHERE role='tutor'");
$subjects=mysqli_query($conn,"SELECT * FROM subjects");

if($_SERVER['REQUEST_METHOD']==='POST'){
    $tutor_id=$_POST['tutor_id'];
    $subject_id=$_POST['subject_id'];
    $day=$_POST['day_of_week'];
    $start=$_POST['start_time'];
    $end=$_POST['end_time'];

    $stmt=mysqli_prepare($conn,"INSERT INTO tutor_schedules (tutor_id,subject_id,day_of_week,start_time,end_time) VALUES (?,?,?,?,?)");
    mysqli_stmt_bind_param($stmt,"iisss",$tutor_id,$subject_id,$day,$start,$end);
    if(mysqli_stmt_execute($stmt)) header("Location: schedules.php");
    else $error="Error adding schedule.";
    mysqli_stmt_close($stmt);
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Add Schedule</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
<div class="w-full max-w-md bg-white rounded shadow p-6">
<h1 class="text-2xl font-semibold mb-4">Add Schedule</h1>
<?php if($error): ?><div class="bg-red-100 text-red-700 p-3 rounded mb-4"><?php echo $error;?></div><?php endif;?>
<form method="POST" class="space-y-4">
<select name="tutor_id" required class="w-full border rounded px-3 py-2">
<option value="">Select Tutor</option>
<?php while($row=mysqli_fetch_assoc($tutors)): ?>
<option value="<?php echo $row['user_id'];?>"><?php echo htmlspecialchars($row['name']);?></option>
<?php endwhile;?>
</select>
<select name="subject_id" required class="w-full border rounded px-3 py-2">
<option value="">Select Subject</option>
<?php while($row=mysqli_fetch_assoc($subjects)): ?>
<option value="<?php echo $row['subject_id'];?>"><?php echo htmlspecialchars($row['subject_name']);?></option>
<?php endwhile;?>
</select>
<select name="day_of_week" required class="w-full border rounded px-3 py-2">
<option value="">Select Day</option>
<option>Monday</option><option>Tuesday</option><option>Wednesday</option>
<option>Thursday</option><option>Friday</option><option>Saturday</option><option>Sunday</option>
</select>
<input type="time" name="start_time" required class="w-full border rounded px-3 py-2"/>
<input type="time" name="end_time" required class="w-full border rounded px-3 py-2"/>
<button class="w-full bg-green-600 text-white py-2 rounded">Add Schedule</button>
<a href="schedules.php" class="block text-center mt-2 text-gray-600">Back</a>
</form>
</div>
</body>
</html>
