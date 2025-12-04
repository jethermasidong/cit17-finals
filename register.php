<?php
session_start();
include "config.php";

$errors = [];

if(isset($_POST['register'])) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $role = isset($_POST['role']) ? $_POST['role'] : 'student';

    // Validation
    if(empty($name) || empty($email) || empty($password) || empty($confirm_password)) {
        $errors[] = "All fields are required.";
    }
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }
    if($password !== $confirm_password) {
        $errors[] = "Passwords do not match.";
    }

    // Check if email exists
    $stmt = mysqli_prepare($conn, "SELECT user_id FROM users WHERE email=?");
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    if(mysqli_stmt_num_rows($stmt) > 0) {
        $errors[] = "Email is already registered.";
    }
    mysqli_stmt_close($stmt);

    // Insert user if no errors
    if(empty($errors)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = mysqli_prepare($conn, "INSERT INTO users (name,email,password,role) VALUES (?,?,?,?)");
        mysqli_stmt_bind_param($stmt, "ssss", $name, $email, $hashed_password, $role);
        if(mysqli_stmt_execute($stmt)) {
            mysqli_stmt_close($stmt);
            echo "<script>alert('Registration successful! Please login.'); window.location.href='login.php';</script>";
            exit;
        } else {
            $errors[] = "Database error: Could not register user.";
        }
        mysqli_stmt_close($stmt);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Register - Primal Tutoring Services</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 flex items-center justify-center min-h-screen">

<div class="bg-white rounded-xl shadow p-8 w-full max-w-md">
    <h2 class="text-2xl font-bold mb-6 text-center">Create Your Account</h2>

    <?php if(!empty($errors)): ?>
        <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
            <ul>
            <?php foreach($errors as $error): ?>
                <li><?php echo htmlspecialchars($error); ?></li>
            <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="POST" class="space-y-4">
        <div>
            <label class="block mb-1 font-semibold">Full Name</label>
            <input type="text" name="name" value="<?php echo isset($name) ? htmlspecialchars($name) : ''; ?>" class="w-full border rounded px-3 py-2" required>
        </div>
        <div>
            <label class="block mb-1 font-semibold">Email</label>
            <input type="email" name="email" value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>" class="w-full border rounded px-3 py-2" required>
        </div>
        <div>
            <label class="block mb-1 font-semibold">Password</label>
            <input type="password" name="password" class="w-full border rounded px-3 py-2" required>
        </div>
        <div>
            <label class="block mb-1 font-semibold">Confirm Password</label>
            <input type="password" name="confirm_password" class="w-full border rounded px-3 py-2" required>
        </div>
        <div>
            <label class="block mb-1 font-semibold">Role</label>
            <select name="role" class="w-full border rounded px-3 py-2">
                <option value="student" selected>Student</option>
                <option value="tutor">Tutor</option>
            </select>
        </div>
        <button type="submit" name="register" class="w-full bg-black text-white px-4 py-2 rounded hover:bg-gray-800 transition">Register</button>
    </form>

    <p class="mt-4 text-center text-gray-600">
        Already have an account? <a href="login.php" class="text-green-600 hover:underline">Login here</a>
    </p>
</div>

</body>
</html>
