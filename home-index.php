<?php
session_start();
include "config.php";

$testimonials_res = mysqli_query($conn, "
    SELECT r.rating, r.comment, u.name AS customer_name
    FROM reviews r
    JOIN users u ON r.student_id = u.user_id
    ORDER BY r.created_at DESC
    LIMIT 10
");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Primal Tutoring Services</title>
<script src="https://cdn.tailwindcss.com"></script>
<style>
.scrollbar-hide::-webkit-scrollbar { display: none; }
.scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
body { background-color: #f9f9f9; color: #000; }
</style>
</head>
<body class="bg-gray-50">


<nav class="bg-white shadow fixed w-full z-50">
    <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
        <a href="home-index.php" class="text-xl md:text-2xl font-bold text-black">Primal Tutoring Services</a>
        <!-- Desktop Menu -->
        <ul class="hidden md:flex gap-6 items-center text-black">
            <li><a href="home-index.php" class="hover:text-gray-700">Home</a></li>
            <li><a href="#services" class="hover:text-gray-700">Services</a></li>
            <?php if(isset($_SESSION['user_id'])): ?>
                <li><a href="login.php" class="hover:text-gray-700">Dashboard</a></li>
                <li><a href="logout.php" class="hover:text-gray-700">Logout</a></li>
            <?php else: ?>
                <li><a href="login.php" class="hover:text-gray-700">Login</a></li>
                <li><a href="register.php" class="hover:text-gray-700">Register</a></li>
            <?php endif; ?>
        </ul>
        <!-- Mobile Menu Button -->
        <button id="mobile-menu-button" class="md:hidden text-black focus:outline-none">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
        </button>
    </div>
    <!-- Mobile Menu -->
    <div id="mobile-menu" class="hidden md:hidden bg-white shadow-lg">
        <ul class="flex flex-col gap-4 px-6 py-4 text-black">
            <li><a href="home-index.php" class="hover:text-gray-700 block">Home</a></li>
            <li><a href="#services" class="hover:text-gray-700 block">Services</a></li>
            <?php if(isset($_SESSION['user_id'])): ?>
                <li><a href="login.php" class="hover:text-gray-700 block">Dashboard</a></li>
                <li><a href="logout.php" class="hover:text-gray-700 block">Logout</a></li>
            <?php else: ?>
                <li><a href="login.php" class="hover:text-gray-700 block">Login</a></li>
                <li><a href="register.php" class="hover:text-gray-700 block">Register</a></li>
            <?php endif; ?>
        </ul>
    </div>
</nav>

<section class="relative h-screen flex items-center justify-center">
    <img src="bg1.jpg" class="absolute inset-0 w-full h-full object-cover opacity-35">
    <div class="relative bg-black bg-opacity-50 p-6 md:p-10 rounded-xl text-center text-white max-w-4xl mx-4">
        <h1 class="text-3xl sm:text-4xl md:text-6xl font-bold mb-4">Your Wellness Journey Starts Here</h1>
        <p class="text-base sm:text-lg md:text-xl mb-6">Learn, grow, and achieve with our expert tutors.</p>
        <div class="flex flex-col sm:flex-row justify-center gap-4">
            <a href="#services" class="px-6 py-3 bg-white text-black rounded hover:bg-gray-200 transition text-center">View Services</a>
            <a href="login.php" class="px-6 py-3 border border-white rounded hover:bg-white hover:text-black transition text-center">Book Now</a>
        </div>
    </div>
</section>

<section id="services" class="max-w-7xl mx-auto py-20 px-6">
    <h2 class="text-3xl font-bold mb-12" style="text-align:left;">Primal Tutoring Services</h2>
    <div class="grid md:grid-cols-3 gap-8">
        <?php
        $services = [
            ['name'=>'Math Tutoring', 'desc'=>'Expert guidance in Algebra, Calculus, and more.', 'price'=>'₱500', 'duration'=>'1hr'],
            ['name'=>'Science Tutoring', 'desc'=>'Physics, Chemistry, Biology explained simply.', 'price'=>'₱500', 'duration'=>'1hr'],
            ['name'=>'English Tutoring', 'desc'=>'Improve grammar, reading, and writing skills.', 'price'=>'₱400', 'duration'=>'1hr'],
            ['name'=>'Computer Science Tutoring', 'desc'=>'Programming, algorithms, and database lessons.', 'price'=>'₱600', 'duration'=>'1hr'],
            ['name'=>'Filipino Tutoring', 'desc'=>'Master Filipino language and literature.', 'price'=>'₱400', 'duration'=>'1hr']
        ];
        foreach($services as $s):
        ?>
        <div class="bg-white rounded-xl shadow p-6 flex flex-col">
            <h3 class="text-xl font-semibold mb-2"><?php echo $s['name']; ?></h3>
            <p class="text-gray-600 mb-2"><?php echo $s['desc']; ?></p>
            <p class="text-gray-500 mb-4">Price: <?php echo $s['price']; ?> | Duration: <?php echo $s['duration']; ?></p>
            <a href="login.php" class="mt-auto px-4 py-2 bg-black text-white rounded hover:bg-gray-800 transition text-center">Book Now</a>
        </div>
        <?php endforeach; ?>
    </div>
</section>

<section class="bg-gray-100 py-20 px-6">
    <h2 class="text-3xl font-bold text-center mb-12">What Our Customers Say</h2>
    <div class="flex overflow-x-auto gap-6 scrollbar-hide snap-x snap-mandatory">
        <?php while($t = mysqli_fetch_assoc($testimonials_res)): ?>
            <div class="bg-white rounded-xl shadow p-6 min-w-[300px] flex-shrink-0 snap-start">
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-12 h-12 rounded-full bg-gray-300 flex items-center justify-center font-bold text-black">
                        <?php echo strtoupper(substr($t['customer_name'],0,1)); ?>
                    </div>
                    <div>
                        <p class="font-semibold"><?php echo htmlspecialchars($t['customer_name']); ?></p>
                        <div class="flex text-yellow-400">
                            <?php for($i=0;$i<5;$i++): ?>
                                <span><?php echo $i<$t['rating']?'★':'☆'; ?></span>
                            <?php endfor; ?>
                        </div>
                    </div>
                </div>
                <p class="text-gray-600"><?php echo substr(htmlspecialchars($t['comment']),0,100).'...'; ?></p>
            </div>
        <?php endwhile; ?>
    </div>
</section>


<section class="py-20 bg-black text-white text-center px-6">
    <h2 class="text-4xl font-bold mb-6">Ready to Start Learning? Book Your Session Today!</h2>
    <div class="flex justify-center gap-4 flex-wrap">
        <a href="register.php" class="px-6 py-3 bg-white text-black rounded hover:bg-gray-100 transition">Create Account</a>
        <a href="login.php" class="px-6 py-3 border border-white rounded hover:bg-white hover:text-black transition">Book Now</a>
    </div>
</section>

</body>
</html>
