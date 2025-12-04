<?php
session_start();
include "config.php";

// Only allow tutors to access
if(!isset($_SESSION['user_id']) || $_SESSION['role']!=='tutor') {
    header("Location: login.php");
    exit();
}

$error="";
$tutors=mysqli_query($conn,"SELECT * FROM users WHERE role='tutor'");
$subjects=mysqli_query($conn,"SELECT * FROM subjects");

if($_SERVER['REQUEST_METHOD']==='POST'){
    $tutor_id=$_SESSION['user_id']; // Automatically use logged-in tutor
    $subject_id=$_POST['subject_id'];
    $day=$_POST['day_of_week'];
    $start=$_POST['start_time'];
    $end=$_POST['end_time'];

    $stmt=mysqli_prepare($conn,"INSERT INTO tutor_schedules (tutor_id,subject_id,day_of_week,start_time,end_time) VALUES (?,?,?,?,?)");
    mysqli_stmt_bind_param($stmt,"issss",$tutor_id,$subject_id,$day,$start,$end);
    if(mysqli_stmt_execute($stmt)) header("Location: teacher.php"); // Redirect back to tutor dashboard
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
<body class="bg-gray-50 min-h-screen flex items-center justify-center p-6">

<div class="w-full max-w-lg bg-white rounded-2xl shadow-lg p-8">
    <h1 class="text-3xl font-bold mb-6 text-gray-900 text-center">Add Schedule</h1>

    <?php if($error): ?>
    <div class="bg-red-100 text-red-700 p-4 rounded mb-4"><?php echo $error;?></div>
    <?php endif; ?>

    <form method="POST" class="space-y-5">

        <select name="subject_id" required class="w-full border rounded-lg px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:outline-none">
            <option value="">Select Subject</option>
            <?php while($row=mysqli_fetch_assoc($subjects)): ?>
            <option value="<?php echo $row['subject_id'];?>"><?php echo htmlspecialchars($row['subject_name']);?></option>
            <?php endwhile;?>
        </select>

        <select name="day_of_week" required class="w-full border rounded-lg px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:outline-none">
            <option value="">Select Day</option>
            <option>Monday</option><option>Tuesday</option><option>Wednesday</option>
            <option>Thursday</option><option>Friday</option><option>Saturday</option><option>Sunday</option>
        </select>

        <input type="time" name="start_time" required class="w-full border rounded-lg px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:outline-none"/>
        <input type="time" name="end_time" required class="w-full border rounded-lg px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:outline-none"/>

        <button class="w-full bg-green-600 text-white py-3 rounded-lg font-medium hover:bg-green-700 transition">Add Schedule</button>
        <a href="teacher.php" class="block text-center mt-3 text-gray-600 hover:underline">Back</a>
    </form>
</div>

</body>
</html>
