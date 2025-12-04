<?php
session_start();
include "config.php";

if(!isset($_SESSION['user_id']) || $_SESSION['role']!=='student') header("Location: login.php");

$student_id = $_SESSION['user_id'];
$error = "";

$booking_id = intval($_GET['booking_id'] ?? $_POST['booking_id'] ?? 0);

if($booking_id > 0){
    $stmt = mysqli_prepare($conn, "
        SELECT b.booking_id, s.tutor_id, t.name AS tutor_name
        FROM bookings b
        JOIN tutor_schedules s ON b.schedule_id = s.schedule_id
        JOIN users t ON s.tutor_id = t.user_id
        WHERE b.booking_id = ? 
          AND b.student_id = ? 
          AND b.status = 'completed'
        LIMIT 1
    ");
    mysqli_stmt_bind_param($stmt, "ii", $booking_id, $student_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $b_id, $tutor_id, $tutor_name);
    if(!mysqli_stmt_fetch($stmt)){
        die("Booking not found, not completed, or already reviewed.");
    }
    mysqli_stmt_close($stmt);
}

if($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['rating'], $_POST['comment'])){
    $rating = intval($_POST['rating']);
    $comment = trim($_POST['comment']);

    $check = mysqli_query($conn, "SELECT review_id FROM reviews WHERE booking_id=$booking_id AND student_id=$student_id");
    if(mysqli_num_rows($check) > 0){
        die("You have already reviewed this booking.");
    }

    $stmt = mysqli_prepare($conn, "
        INSERT INTO reviews (booking_id, student_id, tutor_id, rating, comment)
        VALUES (?,?,?,?,?)
    ");
    mysqli_stmt_bind_param($stmt, "iiiis", $booking_id, $student_id, $tutor_id, $rating, $comment);
    if(mysqli_stmt_execute($stmt)){
        header("Location: reviews.php");
        exit();
    } else {
        $error = "Error adding review.";
    }
    mysqli_stmt_close($stmt);
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Add Review</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center p-6">

<div class="w-full max-w-lg bg-white rounded-2xl shadow-lg p-8">
    <h1 class="text-3xl font-bold mb-6 text-gray-900 text-center">
        Add Review for <?php echo htmlspecialchars($tutor_name); ?>
    </h1>

    <?php if($error): ?>
    <div class="bg-red-100 text-red-700 p-4 rounded mb-4"><?php echo $error;?></div>
    <?php endif; ?>

    <form method="POST" class="space-y-5">
        <input type="number" name="rating" min="1" max="5" placeholder="Rating (1-5)" required
            class="w-full border rounded-lg px-4 py-3 focus:ring-2 focus:ring-green-500 focus:outline-none"/>

        <textarea name="comment" placeholder="Write your feedback" required
            class="w-full border rounded-lg px-4 py-3 focus:ring-2 focus:ring-green-500 focus:outline-none"></textarea>

        <input type="hidden" name="booking_id" value="<?php echo $booking_id; ?>"/>

        <button class="w-full bg-green-600 text-white py-3 rounded-lg font-medium hover:bg-green-700 transition">
            Submit Review
        </button>

        <a href="reviews.php" class="block text-center mt-3 text-gray-600 hover:underline">Back</a>
    </form>
</div>

</body>
</html>
