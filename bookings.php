<?php
session_start();
include "config.php";

if(!isset($_SESSION['user_id'])) header("Location: login.php");

$role = $_SESSION['role'];
$uid = $_SESSION['user_id'];

if($role === 'admin'){
    $res = mysqli_query($conn, "
        SELECT b.booking_id, b.status AS booking_status, u.name AS student_name, t.name AS tutor_name,
               sub.subject_name, ts.day_of_week, ts.start_time, ts.end_time
        FROM bookings b
        JOIN tutor_schedules ts ON b.schedule_id = ts.schedule_id
        JOIN users u ON b.student_id = u.user_id
        JOIN users t ON ts.tutor_id = t.user_id
        JOIN subjects sub ON ts.subject_id = sub.subject_id
        ORDER BY b.created_at DESC
    ");
} elseif($role === 'student'){
    $res = mysqli_query($conn, "
        SELECT b.booking_id, b.status AS booking_status, u.name AS student_name, t.name AS tutor_name,
               sub.subject_name, ts.day_of_week, ts.start_time, ts.end_time
        FROM bookings b
        JOIN tutor_schedules ts ON b.schedule_id = ts.schedule_id
        JOIN users u ON b.student_id = u.user_id
        JOIN users t ON ts.tutor_id = t.user_id
        JOIN subjects sub ON ts.subject_id = sub.subject_id
        WHERE u.user_id = $uid
        ORDER BY b.created_at DESC
    ");
} else {
    // For tutors, show only bookings where they are the tutor
    $res = mysqli_query($conn, "
        SELECT b.booking_id, b.status AS booking_status, u.name AS student_name, t.name AS tutor_name,
               sub.subject_name, ts.day_of_week, ts.start_time, ts.end_time
        FROM bookings b
        JOIN tutor_schedules ts ON b.schedule_id = ts.schedule_id
        JOIN users u ON b.student_id = u.user_id
        JOIN users t ON ts.tutor_id = t.user_id
        JOIN subjects sub ON ts.subject_id = sub.subject_id
        WHERE t.user_id = $uid
        ORDER BY b.created_at DESC
    ");
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Bookings</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen p-6">
<div class="max-w-6xl mx-auto">
<h1 class="text-2xl font-bold mb-4">Bookings</h1>
<div class="bg-white rounded shadow overflow-auto">
<table class="w-full text-sm">
<thead class="bg-gray-50">
<tr>
<th class="p-2">Booking ID</th>
<th class="p-2">Student</th>
<th class="p-2">Tutor</th>
<th class="p-2">Subject</th>
<th class="p-2">Schedule</th>
<th class="p-2">Booking Status</th>
<th class="p-2">Action</th>
</tr>
</thead>
<tbody>
<?php while($row=mysqli_fetch_assoc($res)): ?>
<tr class="border-t">
<td class="p-2"><?php echo $row['booking_id'];?></td>
<td class="p-2"><?php echo htmlspecialchars($row['student_name']);?></td>
<td class="p-2"><?php echo htmlspecialchars($row['tutor_name']);?></td>
<td class="p-2"><?php echo htmlspecialchars($row['subject_name']);?></td>
<td class="p-2"><?php echo $row['day_of_week']." ".$row['start_time']."-".$row['end_time'];?></td>
<td class="p-2"><?php echo ucfirst($row['booking_status']);?></td>
<td class="p-2">
<?php if($role==='admin'): ?>
<a href="bookings_edit.php?id=<?php echo $row['booking_id'];?>" class="text-blue-600 mr-2">Edit</a>
<a href="bookings_delete.php?id=<?php echo $row['booking_id'];?>" class="text-red-600" onclick="return confirm('Delete booking?')">Delete</a>
<?php endif; ?>
</td>
</tr>
<?php endwhile;?>
</tbody>
</table>
</div>
<?php if($role==='admin'): ?>
<a href="bookings_create.php" class="inline-block mt-4 px-4 py-2 bg-green-600 text-white rounded">Add Booking</a>
<?php endif;?>
<a href="<?php echo $role==='admin'?'admin.php':($role==='tutor'?'teacher.php':'index.php');?>" class="block mt-4 text-gray-600">Back</a>
</div>
</body>
</html>
