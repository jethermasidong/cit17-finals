<?php
session_start();
include "config.php";
if (!isset($_SESSION['user_id']) || $_SESSION['role']!=='admin') header("Location: login.php");

$res = mysqli_query($conn, "SELECT * FROM users ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Manage Users</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen p-6">
<div class="max-w-6xl mx-auto">
<div class="flex justify-between items-center mb-4">
<h1 class="text-2xl font-bold">Users</h1>
<div>
<a href="users_add.php" class="px-4 py-2 bg-green-600 text-white rounded">Add User</a>
<a href="admin.php" class="ml-2 px-4 py-2 bg-gray-200 rounded">Back</a>
</div>
</div>
<div class="bg-white rounded shadow overflow-auto">
<table class="w-full text-sm">
<thead class="bg-gray-50"><tr><th class="p-2">Name</th><th>Email</th><th>Role</th><th>Action</th></tr></thead>
<tbody>
<?php while($row = mysqli_fetch_assoc($res)): ?>
<tr class="border-t">
<td class="p-2"><?php echo htmlspecialchars($row['name']); ?></td>
<td class="p-2"><?php echo htmlspecialchars($row['email']); ?></td>
<td class="p-2"><?php echo htmlspecialchars($row['role']); ?></td>
<td class="p-2">
<a href="users_edit.php?id=<?php echo $row['user_id']; ?>" class="text-blue-600 mr-2">Edit</a>
<a href="users_delete.php?id=<?php echo $row['user_id']; ?>" class="text-red-600" onclick="return confirm('Delete user?')">Delete</a>
</td>
</tr>
<?php endwhile; ?>
</tbody>
</table>
</div>
</div>
</body>
</html>
