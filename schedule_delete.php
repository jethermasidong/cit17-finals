<?php
session_start();
include "config.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: schedules.php");
    exit();
}

$id = intval($_GET['id']);
$stmt = mysqli_prepare($conn, "DELETE FROM tutor_schedules WHERE schedule_id=?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);

header("Location: schedules.php");
exit();
?>
