<?php
session_start();
include "config.php";
if (!isset($_SESSION['user_id']) || $_SESSION['role']!=='admin') header("Location: login.php");

$res = mysqli_query($conn, "SELECT * FROM subjects ORDER BY subject_name ASC");
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Subjects</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen p-6">
<div class="max-w-6xl mx-auto">
<div class="flex justify-between items-center mb-4">
<h1 class="text-2xl font-bold">Subjects</h1>
<div>
<a href="subjects_add.php" class="px-4 py-2 bg-green-600 text-white rounded">Add Subject</a>
<a href="admin.php" class="ml-2 px-4 py-2 bg-gray-200 rounded">Back</a>
</div>
</div>
<div class="bg-white rounded shadow overflow-auto">
<table class="w-full text-sm">
<thead class="bg-gray-50"><tr><th class="p-2">Subject</th><th>Action</th></tr></thead>
<tbody>
<?php while($row = mysqli_fetch_assoc($res)): ?>
<tr class="border-t">
<td class="p-2"><?php echo htmlspecialchars($row['subject_name']); ?></td>
<td class="p-2">
<a href="subject_edit.php?id=<?php echo $row['subject_id']; ?>" class="text-blue-600 mr-2">Edit</a>
<a href="subject_delete.php?id=<?php echo $row['subject_id']; ?>" class="text-red-600" onclick="return confirm('Delete subject?')">Delete</a>
</td>
</tr>
<?php endwhile; ?>
</tbody>
</table>
</div>
</div>
</body>
</html>
