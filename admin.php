<?php
session_start();
include "config.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role']!=='admin') {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Admin Dashboard</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen p-6">
<div class="max-w-6xl mx-auto">
<h1 class="text-3xl font-bold mb-6">Admin Dashboard</h1>
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
<a href="users.php" class="block p-6 bg-blue-600 text-white rounded shadow">Manage Users</a>
<a href="subjects.php" class="block p-6 bg-green-600 text-white rounded shadow">Manage Subjects</a>
<a href="schedules.php" class="block p-6 bg-purple-600 text-white rounded shadow">Manage Schedules</a>
<a href="bookings.php" class="block p-6 bg-indigo-600 text-white rounded shadow">View Bookings</a>
<a href="reviews.php" class="block p-6 bg-pink-600 text-white rounded shadow">View Reviews</a>
<a href="change_admin_password.php" class="block p-6 bg-gray-800 text-white rounded shadow">Change Password</a>
<a href="logout.php" class="block p-6 bg-red-600 text-white rounded shadow">Logout</a>
</div>
</div>
</body>
</html>
