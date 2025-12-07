<?php
session_start();
include "config.php";

if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$uid  = $_SESSION['user_id'];
$role = $_SESSION['role'];

if($role === 'admin'){
    $stmt = mysqli_prepare($conn, "
        SELECT r.review_id, u.name AS student_name, t.name AS tutor_name,
               r.rating, r.comment, r.created_at, r.student_id
        FROM reviews r
        JOIN users u ON r.student_id = u.user_id
        JOIN users t ON r.tutor_id = t.user_id
        ORDER BY r.created_at DESC
    ");
} elseif($role === 'tutor'){
    $stmt = mysqli_prepare($conn, "
        SELECT r.review_id, u.name AS student_name, t.name AS tutor_name,
               r.rating, r.comment, r.created_at, r.student_id
        FROM reviews r
        JOIN users u ON r.student_id = u.user_id
        JOIN users t ON r.tutor_id = t.user_id
        WHERE t.user_id = ?
        ORDER BY r.created_at DESC
    ");
    mysqli_stmt_bind_param($stmt, "i", $uid);
} else {
    $stmt = mysqli_prepare($conn, "
        SELECT r.review_id, u.name AS student_name, t.name AS tutor_name,
               r.rating, r.comment, r.created_at, r.student_id
        FROM reviews r
        JOIN users u ON r.student_id = u.user_id
        JOIN users t ON r.tutor_id = t.user_id
        WHERE u.user_id = ?
        ORDER BY r.created_at DESC
    ");
    mysqli_stmt_bind_param($stmt, "i", $uid);
}

mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Reviews</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen p-6">

<div class="max-w-7xl mx-auto">

<h1 class="text-3xl font-bold mb-6 text-gray-800">Reviews</h1>

<div class="bg-white rounded-xl shadow-lg overflow-auto border border-gray-200">
<table class="w-full text-base">
<thead class="bg-gray-100 border-b border-gray-200">
<tr>
<th class="p-4 text-left font-semibold text-gray-700">Student</th>
<th class="p-4 text-left font-semibold text-gray-700">Tutor</th>
<th class="p-4 text-left font-semibold text-gray-700">Rating</th>
<th class="p-4 text-left font-semibold text-gray-700">Comment</th>
<th class="p-4 text-left font-semibold text-gray-700">Date</th>
<th class="p-4 text-left font-semibold text-gray-700">Action</th>
</tr>
</thead>

<tbody>
<?php while($row = mysqli_fetch_assoc($res)): ?>
<tr class="border-b hover:bg-gray-50 transition">
<td class="p-4"><?= htmlspecialchars($row['student_name']); ?></td>
<td class="p-4"><?= htmlspecialchars($row['tutor_name']); ?></td>
<td class="p-4">
<span class="px-3 py-1 rounded-full text-white 
<?php 
if ($row['rating'] >= 4) echo 'bg-green-600';
elseif ($row['rating'] == 3) echo 'bg-yellow-500';
else echo 'bg-red-600';
?>">
⭐ <?= $row['rating']; ?>/5
</span>
</td>
<td class="p-4"><?= htmlspecialchars($row['comment']); ?></td>
<td class="p-4"><?= $row['created_at']; ?></td>

<td class="p-4">
<?php if($role === 'student' && $row['student_id'] == $uid): ?>
<a href="review_edit.php?id=<?= $row['review_id']; ?>" class="text-blue-600 font-medium hover:underline mr-3">Edit</a>
<a href="review_delete.php?id=<?= $row['review_id']; ?>" class="text-red-600 font-medium hover:underline" onclick="return confirm('Delete review?')">Delete</a>
<?php elseif($role === 'admin'): ?>
<a href="review_delete.php?id=<?= $row['review_id']; ?>" class="text-red-600 font-medium hover:underline" onclick="return confirm('Delete review?')">Delete</a>
<?php endif; ?>
</td>

</tr>
<?php endwhile; ?>
</tbody>
</table>
</div>

<a href="<?= ($role === 'admin') ? 'admin.php' : (($role === 'tutor') ? 'teacher.php' : 'index-student.php'); ?>" 
class="block mt-4 text-gray-600 hover:underline">
← Back
</a>

</div>

</body>
</html>
