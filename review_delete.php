<?php
session_start();
include "config.php";

if(!isset($_SESSION['user_id']) || $_SESSION['role']!=='student') header("Location: login.php");

$student_id = $_SESSION['user_id'];
$review_id = intval($_GET['id'] ?? 0);


$stmt = mysqli_prepare($conn, "DELETE FROM reviews WHERE review_id=? AND student_id=?");
mysqli_stmt_bind_param($stmt, "ii", $review_id, $student_id);
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);

header("Location: reviews.php");
exit();
?>
