<?php
session_start();
include "config.php";

if(!isset($_SESSION['user_id']) || $_SESSION['role']!=='student') header("Location: login.php");

$student_id = $_SESSION['user_id'];
$review_id = intval($_GET['id'] ?? 0);

$error = "";

// Fetch the review to edit
$stmt = mysqli_prepare($conn, "SELECT booking_id, tutor_id, rating, comment FROM reviews WHERE review_id=? AND student_id=? LIMIT 1");
mysqli_stmt_bind_param($stmt, "ii", $review_id, $student_id);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $booking_id, $tutor_id, $rating, $comment);
if(!mysqli_stmt_fetch($stmt)){
    mysqli_stmt_close($stmt);
    die("Review not found or access denied.");
}
mysqli_stmt_close($stmt);

// Handle form submission
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
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
<div class="w-full max-w-md bg-white rounded shadow p-6">
<h1 class="text-2xl font-semibold mb-4">Edit Review</h1>
<?php if($error): ?><div class="bg-red-100 text-red-700 p-3 rounded mb-4"><?php echo $error;?></div><?php endif;?>
<form method="POST" class="space-y-4">
<input type="number" name="rating" value="<?php echo $rating;?>" placeholder="Rating (1-5)" min="1" max="5" required class="w-full border rounded px-3 py-2"/>
<textarea name="comment" placeholder="Comment" class="w-full border rounded px-3 py-2" required><?php echo htmlspecialchars($comment);?></textarea>
<button class="w-full bg-blue-600 text-white py-2 rounded">Update Review</button>
<a href="reviews.php" class="block text-center mt-2 text-gray-600">Back</a>
</form>
</div>
</body>
</html>
