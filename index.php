<?php
session_start();
include "config.php";

if(!isset($_SESSION['user_id']) || $_SESSION['role']!=='student') header("Location: login.php");

$student_id = $_SESSION['user_id'];

// Fetch student's bookings
$bookings_res = mysqli_query($conn, "
    SELECT b.booking_id, t.name AS tutor_name, s.subject_id, sub.subject_name, 
           s.day_of_week, s.start_time, s.end_time, b.status
    FROM bookings b
    JOIN tutor_schedules s ON b.schedule_id = s.schedule_id
    JOIN users t ON s.tutor_id = t.user_id
    JOIN subjects sub ON s.subject_id = sub.subject_id
    WHERE b.student_id = $student_id
    ORDER BY b.created_at DESC
");

// Fetch reviews already submitted
$reviewed_bookings = [];
$review_res = mysqli_query($conn, "SELECT booking_id FROM reviews WHERE student_id=$student_id");
while($r = mysqli_fetch_assoc($review_res)){
    $reviewed_bookings[] = $r['booking_id'];
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Student Dashboard</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen p-6">
<div class="max-w-6xl mx-auto">
<h1 class="text-2xl font-bold mb-4">Welcome, <?php echo htmlspecialchars($_SESSION['name']); ?></h1>

<h2 class="text-xl font-semibold mb-2">My Bookings</h2>
<div class="bg-white rounded shadow overflow-auto">
<table class="w-full text-sm">
<thead class="bg-gray-50">
<tr>
<th class="p-2">Tutor</th>
<th class="p-2">Subject</th>
<th class="p-2">Schedule</th>
<th class="p-2">Status</th>
<th class="p-2">Action</th>
</tr>
</thead>
<tbody>
<?php while($row = mysqli_fetch_assoc($bookings_res)): ?>
<tr class="border-t">
<td class="p-2"><?php echo htmlspecialchars($row['tutor_name']); ?></td>
<td class="p-2"><?php echo htmlspecialchars($row['subject_name']); ?></td>
<td class="p-2"><?php echo $row['day_of_week'] . ' ' . substr($row['start_time'],0,5) . '-' . substr($row['end_time'],0,5); ?></td>
<td class="p-2"><?php echo ucfirst($row['status']); ?></td>
<td class="p-2">
<?php if($row['status']==='completed' && !in_array($row['booking_id'],$reviewed_bookings)): ?>
<a href="review_add.php" onclick="event.preventDefault(); document.getElementById('booking-<?php echo $row['booking_id']; ?>').submit();" class="text-green-600">Add Review</a>
<form method="POST" action="review_add.php" id="booking-<?php echo $row['booking_id']; ?>" class="hidden">
<input type="hidden" name="booking_id" value="<?php echo $row['booking_id']; ?>"/>
<input type="hidden" name="tutor_id" value="<?php echo $row['tutor_id']; ?>"/>
</form>
<?php elseif(in_array($row['booking_id'],$reviewed_bookings)): ?>
<span class="text-gray-500">Reviewed</span>
<?php else: ?>
<span class="text-gray-500">N/A</span>
<?php endif; ?>
</td>
</tr>
<?php endwhile; ?>
</tbody>
</table>
</div>

<a href="reviews.php" class="inline-block mt-4 px-4 py-2 bg-blue-600 text-white rounded">My Reviews</a>
<a href="logout.php" class="inline-block mt-4 ml-2 px-4 py-2 bg-gray-600 text-white rounded">Logout</a>
</div>
</body>
</html>
