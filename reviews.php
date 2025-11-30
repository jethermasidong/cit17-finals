<?php
session_start();
include "config.php";

if(!isset($_SESSION['user_id'])) header("Location: login.php");

$uid = $_SESSION['user_id'];
$role = $_SESSION['role'];

if($role === 'admin'){
    $res = mysqli_query($conn, "
        SELECT r.review_id, u.name AS student_name, t.name AS tutor_name,
               r.rating, r.comment, r.created_at
        FROM reviews r
        JOIN users u ON r.student_id = u.user_id
        JOIN users t ON r.tutor_id = t.user_id
        ORDER BY r.created_at DESC
    ");
} elseif($role === 'tutor'){
    $res = mysqli_query($conn, "
        SELECT r.review_id, u.name AS student_name, t.name AS tutor_name,
               r.rating, r.comment, r.created_at
        FROM reviews r
        JOIN users u ON r.student_id = u.user_id
        JOIN users t ON r.tutor_id = t.user_id
        WHERE t.user_id = $uid
        ORDER BY r.created_at DESC
    ");
} else { // student
    $res = mysqli_query($conn, "
        SELECT r.review_id, u.name AS student_name, t.name AS tutor_name,
               r.rating, r.comment, r.created_at
        FROM reviews r
        JOIN users u ON r.student_id = u.user_id
        JOIN users t ON r.tutor_id = t.user_id
        WHERE u.user_id = $uid
        ORDER BY r.created_at DESC
    ");
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Reviews</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen p-6">
<div class="max-w-6xl mx-auto">
<h1 class="text-2xl font-bold mb-4">Reviews</h1>

<div class="bg-white rounded shadow overflow-auto">
<table class="w-full text-sm">
<thead class="bg-gray-50">
<tr>
<th class="p-2">Student</th>
<th class="p-2">Tutor</th>
<th class="p-2">Rating</th>
<th class="p-2">Comment</th>
<th class="p-2">Date</th>
<th class="p-2">Action</th>
</tr>
</thead>
<tbody>
<?php while($row = mysqli_fetch_assoc($res)): ?>
<tr class="border-t">
<td class="p-2"><?php echo htmlspecialchars($row['student_name']); ?></td>
<td class="p-2"><?php echo htmlspecialchars($row['tutor_name']); ?></td>
<td class="p-2"><?php echo $row['rating']; ?></td>
<td class="p-2"><?php echo htmlspecialchars($row['comment']); ?></td>
<td class="p-2"><?php echo $row['created_at']; ?></td>
<td class="p-2">
<?php if($role==='student'): ?>
<a href="review_edit.php?id=<?php echo $row['review_id']; ?>" class="text-blue-600 mr-2">Edit</a>
<a href="review_delete.php?id=<?php echo $row['review_id']; ?>" class="text-red-600" onclick="return confirm('Delete review?')">Delete</a>
<?php endif; ?>
</td>
</tr>
<?php endwhile; ?>
</tbody>
</table>
</div>

<?php if($role==='student'): ?>
<a href="review_add.php" class="inline-block mt-4 px-4 py-2 bg-green-600 text-white rounded">Add Review</a>
<?php endif; ?>

<a href="<?php echo ($role==='admin')?'admin.php':(($role==='tutor')?'teacher.php':'index.php');?>" class="block mt-4 text-gray-600">Back</a>
</div>
</body>
</html>
