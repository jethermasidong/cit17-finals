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

<body class="bg-gray-50 min-h-screen p-10">

<div class="max-w-6xl mx-auto">

    <div class="flex justify-between items-center mb-8">
        <h1 class="text-4xl font-bold text-gray-900">Users</h1>
        <div>
            <a href="users_add.php" class="px-5 py-3 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 transition">Add User</a>
            <a href="admin.php" class="ml-3 px-5 py-3 bg-gray-200 font-medium rounded-lg hover:bg-gray-300 transition">Back</a>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow overflow-auto">
        <table class="w-full text-base">
            <thead class="bg-gray-100 text-gray-700 uppercase text-left">
                <tr>
                    <th class="p-4">Name</th>
                    <th class="p-4">Email</th>
                    <th class="p-4">Role</th>
                    <th class="p-4">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = mysqli_fetch_assoc($res)): ?>
                <tr class="border-t hover:bg-gray-50 transition">
                    <td class="p-4"><?php echo htmlspecialchars($row['name']); ?></td>
                    <td class="p-4"><?php echo htmlspecialchars($row['email']); ?></td>
                    <td class="p-4 capitalize"><?php echo htmlspecialchars($row['role']); ?></td>
                    <td class="p-4">
                        <a href="users_edit.php?id=<?php echo $row['user_id']; ?>" class="text-blue-600 font-medium hover:underline mr-3">Edit</a>
                        <a href="users_delete.php?id=<?php echo $row['user_id']; ?>" class="text-red-600 font-medium hover:underline" onclick="return confirm('Delete user?')">Delete</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

</div>

</body>
</html>
