<?php
session_start();
include "config.php";

if(!isset($_SESSION['user_id']) || $_SESSION['role']!=='student') header("Location: login.php");

$student_id = $_SESSION['user_id'];

if(isset($_POST['book_submit'])){
    $schedule_id = intval($_POST['schedule_id']);
    $check = mysqli_prepare($conn, "SELECT * FROM bookings WHERE student_id=? AND schedule_id=?");
    mysqli_stmt_bind_param($check, "ii", $student_id, $schedule_id);
    mysqli_stmt_execute($check);
    $check_res = mysqli_stmt_get_result($check);
    if(mysqli_num_rows($check_res) === 0){
        $stmt = mysqli_prepare($conn, "INSERT INTO bookings (student_id, schedule_id, status, created_at) VALUES (?, ?, 'pending', NOW())");
        mysqli_stmt_bind_param($stmt, "ii", $student_id, $schedule_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        echo "<script>alert('Booking request submitted successfully!'); window.location.href='".$_SERVER['PHP_SELF']."';</script>";
    } else {
        echo "<script>alert('You already booked this schedule.');</script>";
    }
    mysqli_stmt_close($check);
}

$bookings_res = mysqli_query($conn, "
    SELECT b.booking_id, t.name AS tutor_name, t.user_id AS tutor_id, s.subject_id, sub.subject_name, 
           s.day_of_week, s.start_time, s.end_time, b.status
    FROM bookings b
    JOIN tutor_schedules s ON b.schedule_id = s.schedule_id
    JOIN users t ON s.tutor_id = t.user_id
    JOIN subjects sub ON s.subject_id = sub.subject_id
    WHERE b.student_id = $student_id
    ORDER BY b.created_at DESC
");

$reviewed_bookings = [];
$review_res = mysqli_query($conn, "SELECT booking_id FROM reviews WHERE student_id=$student_id");
while($r = mysqli_fetch_assoc($review_res)){
    $reviewed_bookings[] = $r['booking_id'];
}

$schedules_res = mysqli_query($conn, "
    SELECT s.schedule_id, t.name AS tutor_name, sub.subject_name, s.day_of_week, s.start_time, s.end_time
    FROM tutor_schedules s
    JOIN users t ON s.tutor_id = t.user_id
    JOIN subjects sub ON s.subject_id = sub.subject_id
    WHERE s.schedule_id NOT IN (SELECT schedule_id FROM bookings WHERE student_id=$student_id)
    ORDER BY s.day_of_week, s.start_time
");
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Student Dashboard</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen relative">

<!-- Logout -->
<a href="logout.php" 
    class="fixed top-6 right-6 px-6 py-3 bg-red-500 text-white rounded-xl font-semibold hover:bg-red-600 transition shadow-lg z-50">
    Logout
</a>

<div class="max-w-6xl mx-auto px-6">

    <!-- Welcome Section -->
    <div class="bg-gradient-to-r from-indigo-600 to-indigo-400 text-white rounded-3xl p-10 sm:p-12 mb-12 shadow-lg relative overflow-hidden">
        <div class="absolute -top-20 -right-20 w-48 h-48 sm:w-64 sm:h-64 bg-white/10 rounded-full"></div>
        <div class="absolute -bottom-20 -left-20 w-48 h-48 sm:w-64 sm:h-64 bg-white/10 rounded-full"></div>
        <h1 class="text-4xl sm:text-5xl md:text-6xl font-extrabold mb-3">Welcome, <?php echo htmlspecialchars($_SESSION['name']); ?>!</h1>
        <p class="text-lg sm:text-xl md:text-2xl">Book sessions, manage your bookings, and leave reviews for your tutors.</p>
    </div>

    <!-- Booking Form -->
    <?php if(mysqli_num_rows($schedules_res) > 0): ?>
    <h2 class="text-2xl sm:text-3xl font-bold mb-6 text-gray-800">Book a Session</h2>
    <form method="POST" class="bg-white p-6 sm:p-8 rounded-2xl shadow-md mb-12 grid gap-4 sm:grid-cols-2 items-center">
        <select name="schedule_id" required class="border rounded-lg px-4 py-3 w-full focus:ring-2 focus:ring-indigo-500 focus:outline-none">
            <option value="">Select a schedule</option>
            <?php while($s = mysqli_fetch_assoc($schedules_res)): ?>
                <option value="<?php echo $s['schedule_id']; ?>">
                    <?php echo htmlspecialchars($s['tutor_name'] . " - " . $s['subject_name'] . " (" . $s['day_of_week'] . " " . substr($s['start_time'],0,5) . "-" . substr($s['end_time'],0,5) . ")"); ?>
                </option>
            <?php endwhile; ?>
        </select>
        <button type="submit" name="book_submit" class="px-6 py-3 bg-green-600 text-white rounded-xl font-semibold hover:bg-green-700 transition shadow w-full sm:w-auto">Book Now</button>
    </form>
    <?php else: ?>
    <p class="text-gray-500 mb-6 text-center">No available schedules to book at the moment.</p>
    <?php endif; ?>

    <!-- My Bookings -->
    <h2 class="text-2xl sm:text-3xl font-bold mb-6 text-gray-800">My Bookings</h2>
    <div class="grid gap-4 sm:gap-6">
        <?php while($row = mysqli_fetch_assoc($bookings_res)): ?>
        <div class="bg-white rounded-2xl shadow-md p-4 sm:p-6 flex flex-col sm:flex-row justify-between items-start sm:items-center transform transition-transform hover:scale-102">
            <div class="mb-3 sm:mb-0">
                <h3 class="text-lg sm:text-xl font-semibold"><?php echo htmlspecialchars($row['tutor_name']); ?></h3>
                <p class="text-gray-600"><?php echo htmlspecialchars($row['subject_name']); ?></p>
                <p class="text-gray-500"><?php echo $row['day_of_week'] . ' ' . substr($row['start_time'],0,5) . '-' . substr($row['end_time'],0,5); ?></p>
            </div>
            <div class="flex flex-col sm:flex-row sm:items-center gap-3 w-full sm:w-auto mt-2 sm:mt-0">
                <span class="px-4 py-1 rounded-full font-medium <?php
                    echo $row['status']=='completed' ? 'bg-green-100 text-green-700' :
                         ($row['status']=='pending' ? 'bg-yellow-100 text-yellow-700' :
                         ($row['status']=='approved' ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-600'));
                ?>">
                    <?php echo ucfirst($row['status']); ?>
                </span>
                <?php if($row['status']==='completed' && !in_array($row['booking_id'],$reviewed_bookings)): ?>
                    <form method="POST" action="review_add.php" class="inline w-full sm:w-auto">
                        <input type="hidden" name="booking_id" value="<?php echo $row['booking_id']; ?>"/>
                        <input type="hidden" name="tutor_id" value="<?php echo $row['tutor_id']; ?>"/>
                        <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-xl font-medium hover:bg-green-700 transition shadow w-full sm:w-auto">Add Review</button>
                    </form>
                <?php elseif(in_array($row['booking_id'],$reviewed_bookings)): ?>
                    <span class="text-gray-500 font-medium">Reviewed</span>
                <?php else: ?>
                    <span class="text-gray-400 font-medium">N/A</span>
                <?php endif; ?>
            </div>
        </div>
        <?php endwhile; ?>
    </div>

    <!-- Additional Actions -->
    <div class="mt-8 flex flex-col sm:flex-row gap-4">
        <a href="reviews.php" class="px-6 py-3 bg-blue-600 text-white rounded-xl font-semibold hover:bg-blue-700 transition shadow w-full sm:w-auto text-center">My Reviews</a>
    </div>

</div>
</body>
</html>
