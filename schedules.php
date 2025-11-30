<?php
session_start();
include "config.php";
if (!isset($_SESSION['user_id']) || $_SESSION['role']!=='admin') header("Location: login.php");

$res = mysqli_query($conn,"SELECT s.schedule_id,u.name as tutor_name,sub.subject_name,s.day_of_week,s.start_time,s.end_time,s.status 
FROM tutor_schedules s 
JOIN users u ON s.tutor_id=u.user_id 
JOIN subjects sub ON s.subject_id=sub.subject_id 
ORDER BY s.schedule_id DESC");
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Schedules</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen p-6">
<div class="max-w-6xl mx-auto">
<div class="flex justify-between items-center mb-4">
<h1 class="text-2xl font-bold">Schedules</h1>
<div>
<a href="schedule_add.php" class="px-4 py-2 bg-green-600 text-white rounded">Add Schedule</a>
<a href="admin.php" class="ml-2 px-4 py-2 bg-gray-200 rounded">Back</a>
</div>
</div>
<div class="bg-white rounded shadow overflow-auto">
<table class="w-full text-sm">
<thead class="bg-gray-50"><tr><th class="p-2">Tutor</th><th>Subject</th><th>Day</th><th>Start</th><th>End</th><th>Status</th><th>Action</th></tr></thead>
<tbody>
<?php while($row=mysqli_fetch_assoc($res)): ?>
<tr class="border-t">
<td class="p-2"><?php echo htmlspecialchars($row['tutor_name']); ?></td>
<td class="p-2"><?php echo htmlspecialchars($row['subject_name']); ?></td>
<td class="p-2"><?php echo $row['day_of_week']; ?></td>
<td class="p-2"><?php echo $row['start_time']; ?></td>
<td class="p-2"><?php echo $row['end_time']; ?></td>
<td class="p-2"><?php echo $row['status']; ?></td>
<td class="p-2">
<a href="schedule_edit.php?id=<?php echo $row['schedule_id']; ?>" class="text-blue-600 mr-2">Edit</a>
<a href="schedule_delete.php?id=<?php echo $row['schedule_id']; ?>" class="text-red-600" onclick="return confirm('Delete schedule?')">Delete</a>
</td>
</tr>
<?php endwhile; ?>
</tbody>
</table>
</div>
</div>
</body>
</html>
