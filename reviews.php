<?php
session_start();
include "config.php";

if(!isset($_SESSION['user_id'])) header("Location: login.php");

$uid = $_SESSION['user_id'];
$role = $_SESSION['role'];

if($role === 'admin'){
    $res = mysqli_query($conn, "
        SELECT r.review_id, u.name AS student_name, t.name AS tutor_name,
               r.rating, r.comment, r.created_at
        FROM reviews r
        JOIN users u ON r.student_id = u.user_id
        JOIN users t ON r.tutor_id = t.user_id
        ORDER BY r.created_at DESC
    ");
} elseif($role === 'tutor'){
    $res = mysqli_query($conn, "
        SELECT r.review_id, u.name AS student_name, t.name AS tutor_name,
               r.rating, r.comment, r.created_at
        FROM reviews r
        JOIN users u ON r.student_id = u.user_id
        JOIN users t ON r.tutor_id = t.user_id
        WHERE t.user_id = $uid
        ORDER BY r.created_at DESC
    ");
} else { 
    $res = mysqli_query($conn, "
        SELECT r.review_id, u.name AS student_name, t.name AS tutor_name,
               r.rating, r.comment, r.created_at
        FROM reviews r
        JOIN users u ON r.student_id = u.user_id
        JOIN users t ON r.tutor_id = t.user_id
        WHERE u.user_id = $uid
        ORDER BY r.created_at DESC
    ");
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Reviews</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen p-6">

<div class="max-w-7xl mx-auto">

    <h1 class="text-3xl font-bold mb-6 text-gray-800">Reviews</h1>

    <div class="bg-white rounded-xl shadow-lg overflow-auto border border-gray-200">

        <table class="w-full text-base">
            <thead class="bg-gray-100 border-b border-gray-200">
                <tr>
                    <th class="p-4 text-left font-semibold text-gray-700">Student</th>
                    <th class="p-4 text-left font-semibold text-gray-700">Tutor</th>
                    <th class="p-4 text-left font-semibold text-gray-700">Rating</th>
                    <th class="p-4 text-left font-semibold text-gray-700">Comment</th>
                    <th class="p-4 text-left font-semibold text-gray-700">Date</th>
                    <th class="p-4 text-left font-semibold text-gray-700">Action</th>
                </tr>
            </thead>

            <tbody>
            <?php while($row = mysqli_fetch_assoc($res)): ?>
                <tr class="border-b hover:bg-gray-50 transition">
                    <td class="p-4"><?php echo htmlspecialchars($row['student_name']); ?></td>
                    <td class="p-4"><?php echo htmlspecialchars($row['tutor_name']); ?></td>
                    <td class="p-4">
                        <span class="px-3 py-1 rounded-full text-white 
                            <?php 
                                if ($row['rating'] >= 4) echo 'bg-green-600';
                                else if ($row['rating'] == 3) echo 'bg-yellow-500';
                                else echo 'bg-red-600';
                            ?>">
                            ⭐ <?php echo $row['rating']; ?>/5
                        </span>
                    </td>
                    <td class="p-4"><?php echo htmlspecialchars($row['comment']); ?></td>
                    <td class="p-4"><?php echo $row['created_at']; ?></td>

                    <td class="p-4">
                        <?php if($role==='student'): ?>
                            <a href="review_edit.php?id=<?php echo $row['review_id']; ?>" 
                               class="text-blue-600 font-medium hover:underline mr-3">
                                Edit
                            </a>
                            <a href="review_delete.php?id=<?php echo $row['review_id']; ?>" 
                               class="text-red-600 font-medium hover:underline"
                               onclick="return confirm('Delete review?')">
                                Delete
                            </a>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>

    </div>

    <a href="<?php echo ($role==='admin')?'admin.php':(($role==='tutor')?'teacher.php':'index-student.php');?>" 
       class="block mt-4 text-gray-600 hover:underline">
       ← Back
    </a>

</div>

</body>
</html>
