<?php
session_start();
include "config.php";
if (!isset($_SESSION['user_id']) || $_SESSION['role']!=='admin') header("Location: login.php");

$id = $_GET['id'] ?? 0;
if ($id) mysqli_query($conn, "DELETE FROM users WHERE user_id=".$id);
header("Location: users.php");
exit();
?>
