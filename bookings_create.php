<?php
session_start();
include "config.php";
if(!isset($_SESSION['user_id']) || $_SESSION['role']!=='admin') header("Location: login.php");

$error="";
$students = mysqli_query($conn,"SELECT * FROM users WHERE role='student'");
$schedules = mysqli_query($conn,"SELECT ts.schedule_id,u.name AS tutor_name,sub.subject_name,ts.day_of_week,ts.start_time,ts.end_time 
FROM tutor_schedules ts 
JOIN users u ON ts.tutor_id=u.user_id
JOIN subjects sub ON ts.subject_id=sub.subject_id
WHERE ts.status='available'");

if($_SERVER['REQUEST_METHOD']==='POST'){
    $schedule_id = $_POST['schedule_id'];
    $student_id = $_POST['student_id'];

    $stmt = mysqli_prepare($conn,"INSERT INTO bookings (schedule_id,student_id) VALUES (?,?)");
    mysqli_stmt_bind_param($stmt,"ii",$schedule_id,$student_id);
    if(mysqli_stmt_execute($stmt)){
        mysqli_query($conn,"UPDATE tutor_schedules SET status='booked' WHERE schedule_id=".$schedule_id);
        header("Location: bookings.php"); exit();
    } else $error="Error creating booking.";
    mysqli_stmt_close($stmt);
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Create Booking</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
<div class="w-full max-w-md bg-white rounded shadow p-6">
<h1 class="text-2xl font-semibold mb-4">Create Booking</h1>
<?php if($error): ?><div class="bg-red-100 text-red-700 p-3 rounded mb-4"><?php echo $error;?></div><?php endif;?>
<form method="POST" class="space-y-4">
<select name="student_id" required class="w-full border rounded px-3 py-2">
<option value="">Select Student</option>
<?php while($row=mysqli_fetch_assoc($students)): ?>
<option value="<?php echo $row['user_id'];?>"><?php echo htmlspecialchars($row['name']);?></option>
<?php endwhile;?>
</select>
<select name="schedule_id" required class="w-full border rounded px-3 py-2">
<option value="">Select Schedule</option>
<?php while($row=mysqli_fetch_assoc($schedules)): ?>
<option value="<?php echo $row['schedule_id'];?>">
<?php echo htmlspecialchars($row['tutor_name']." - ".$row['subject_name']." ".$row['day_of_week']." ".$row['start_time']."-".$row['end_time']);?>
</option>
<?php endwhile;?>
</select>
<button class="w-full bg-green-600 text-white py-2 rounded">Create Booking</button>
<a href="bookings.php" class="block text-center mt-2 text-gray-600">Back</a>
</form>
</div>
</body>
</html>
