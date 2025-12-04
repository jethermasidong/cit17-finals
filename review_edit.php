<?php
session_start();
include "config.php";

if(!isset($_SESSION['user_id']) || $_SESSION['role']!=='student') header("Location: login.php");

$student_id = $_SESSION['user_id'];
$review_id = intval($_GET['id'] ?? 0);

$error = "";

$stmt = mysqli_prepare($conn, "SELECT booking_id, tutor_id, rating, comment FROM reviews WHERE review_id=? AND student_id=? LIMIT 1");
mysqli_stmt_bind_param($stmt, "ii", $review_id, $student_id);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $booking_id, $tutor_id, $rating, $comment);
if(!mysqli_stmt_fetch($stmt)){
    mysqli_stmt_close($stmt);
    die("Review not found or access denied.");
}
mysqli_stmt_close($stmt);

if($_SERVER['REQUEST_METHOD']==='POST'){
    $rating = $_POST['rating'];
    $comment = $_POST['comment'];

    $stmt = mysqli_prepare($conn, "UPDATE reviews SET rating=?, comment=? WHERE review_id=? AND student_id=?");
    mysqli_stmt_bind_param($stmt, "isii", $rating, $comment, $review_id, $student_id);
    if(mysqli_stmt_execute($stmt)){
        header("Location: reviews.php");
        exit();
    } else $error = "Error updating review.";
    mysqli_stmt_close($stmt);
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Edit Review</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center p-6">

<div class="w-full max-w-lg bg-white rounded-2xl shadow-lg p-8">
    <h1 class="text-3xl font-bold mb-6 text-gray-900 text-center">Edit Review</h1>

    <?php if($error): ?>
    <div class="bg-red-100 text-red-700 p-4 rounded mb-4"><?php echo $error;?></div>
    <?php endif; ?>

    <form method="POST" class="space-y-5">
        <input type="number" name="rating" value="<?php echo $rating;?>" placeholder="Rating (1-5)" min="1" max="5" required
            class="w-full border rounded-lg px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:outline-none"/>

        <textarea name="comment" placeholder="Comment" required
            class="w-full border rounded-lg px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:outline-none"><?php echo htmlspecialchars($comment);?></textarea>

        <button class="w-full bg-blue-600 text-white py-3 rounded-lg font-medium hover:bg-blue-700 transition">Update Review</button>
        <a href="reviews.php" class="block text-center mt-3 text-gray-600 hover:underline">Back</a>
    </form>
</div>

</body>
</html>
