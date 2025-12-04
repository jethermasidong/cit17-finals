<?php
session_start();
include "config.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'tutor') {
    header("Location: login.php");
    exit();
}

$tutor_name = $_SESSION['name'];
$tutor_id = $_SESSION['user_id'];
$schedules_res = mysqli_query($conn, "
    SELECT ts.schedule_id, sub.subject_name, ts.day_of_week, ts.start_time, ts.end_time, ts.status
    FROM tutor_schedules ts
    JOIN subjects sub ON ts.subject_id = sub.subject_id
    WHERE ts.tutor_id = $tutor_id
    ORDER BY FIELD(ts.day_of_week,'Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'), ts.start_time
");
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Tutor Dashboard</title>
<script src="https://cdn.tailwindcss.com"></script>
<script src="https://unpkg.com/lucide@latest"></script>
</head>

<body class="bg-gray-50 min-h-screen">

<div class="max-w-6xl mx-auto px-6">
    <div class="bg-gradient-to-r from-indigo-600 to-indigo-400 text-white rounded-3xl p-10 mb-12 shadow-lg relative overflow-hidden">
        <div class="absolute -top-16 -right-16 w-64 h-64 bg-white/10 rounded-full"></div>
        <div class="absolute -bottom-16 -left-16 w-64 h-64 bg-white/10 rounded-full"></div>
        <h1 class="text-5xl md:text-6xl font-bold mb-4">Welcome, <?php echo htmlspecialchars($tutor_name); ?>!</h1>
        <p class="text-xl md:text-2xl">Manage your tutoring sessions and track student feedback all in one place.</p>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-12">
        <a href="schedule_add.php"
            class="group p-6 bg-green-50 border border-green-200 rounded-xl shadow-sm hover:shadow-md transition">
            
            <div class="flex items-center gap-4">
                <div class="p-3 bg-green-100 rounded-full">
                    <i data-lucide="plus-circle" class="w-6 h-6 text-green-600"></i>
                </div>
                <div>
                    <h2 class="text-lg font-semibold text-gray-800">Add Schedule</h2>
                    <p class="text-sm text-gray-500">Create a new tutoring schedule for your subjects</p>
                </div>
            </div>
        </a>

        <a href="bookings.php"
            class="group p-6 bg-white border border-gray-200 rounded-xl shadow-sm hover:shadow-md transition">
            
            <div class="flex items-center gap-4">
                <div class="p-3 bg-indigo-100 rounded-full">
                    <i data-lucide="calendar" class="w-6 h-6 text-indigo-600"></i>
                </div>
                <div>
                    <h2 class="text-lg font-semibold text-gray-800">My Bookings</h2>
                    <p class="text-sm text-gray-500">View your scheduled tutoring sessions</p>
                </div>
            </div>
        </a>
        <a href="reviews.php"
            class="group p-6 bg-white border border-gray-200 rounded-xl shadow-sm hover:shadow-md transition">
            
            <div class="flex items-center gap-4">
                <div class="p-3 bg-pink-100 rounded-full">
                    <i data-lucide="star" class="w-6 h-6 text-pink-600"></i>
                </div>
                <div>
                    <h2 class="text-lg font-semibold text-gray-800">My Reviews</h2>
                    <p class="text-sm text-gray-500">See feedback from your students</p>
                </div>
            </div>
        </a>

    </div>
    <div class="bg-white rounded-2xl shadow-lg overflow-auto p-6 mb-12">
        <h2 class="text-3xl font-bold mb-6 text-gray-800">My Schedules</h2>
        <table class="w-full text-left text-base border-collapse">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-3 border-b">Subject</th>
                    <th class="p-3 border-b">Day</th>
                    <th class="p-3 border-b">Start Time</th>
                    <th class="p-3 border-b">End Time</th>
                    <th class="p-3 border-b">Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if(mysqli_num_rows($schedules_res) > 0): ?>
                    <?php while($row = mysqli_fetch_assoc($schedules_res)): ?>
                    <tr class="hover:bg-gray-50 transition">
                        <td class="p-3 border-b"><?php echo htmlspecialchars($row['subject_name']); ?></td>
                        <td class="p-3 border-b"><?php echo $row['day_of_week']; ?></td>
                        <td class="p-3 border-b"><?php echo substr($row['start_time'],0,5); ?></td>
                        <td class="p-3 border-b"><?php echo substr($row['end_time'],0,5); ?></td>
                        <td class="p-3 border-b"><?php echo ucfirst($row['status']); ?></td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="p-3 text-center text-gray-500">No schedules added yet.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="flex justify-end mb-10">
        <a href="logout.php" 
            class="px-6 py-3 bg-red-500 text-white rounded-lg hover:bg-red-600 transition">Logout</a>
    </div>

</div>

<script>
    lucide.createIcons();
</script>

</body>
</html>
