<?php
include 'db.php';

if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}

if (isset($_POST['login'])) {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $result = mysqli_query($conn, "SELECT * FROM users WHERE email='" . mysqli_real_escape_string($conn, $email) . "'");
    $user = mysqli_fetch_assoc($result);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['full_name'];
        header("Location: dashboard.php");
        exit();
    } else {
        $error = "Invalid Email or Password!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
</head>
<body class="bg-purple-50 flex items-center justify-center min-h-screen">
    <div class="bg-white p-10 rounded-3xl shadow-2xl w-full max-w-md border border-purple-100">
        <h2 class="text-3xl font-bold text-purple-800 mb-2 text-center">Welcome Back!</h2>
        <p class="text-gray-500 text-center mb-8">Login to manage your expenses.</p>

        <?php if(isset($error)) { echo "<p class='text-red-500 text-center mb-4 bg-red-50 p-2 rounded'>$error</p>"; } ?>

        <form action="" method="POST" class="space-y-5">
            <div class="relative">
                <input type="email" name="email" placeholder="Email Address" class="w-full p-4 pl-12 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-400 outline-none transition" required>
                <span class="absolute left-4 top-4 text-gray-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.206" />
                    </svg>
                </span>
            </div>

            <div class="relative">
                <input type="password" name="password" placeholder="Password" class="w-full p-4 pl-12 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-400 outline-none transition" required>
                <span class="absolute left-4 top-4 text-gray-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </span>
            </div>

            <button name="login" class="w-full bg-purple-600 hover:bg-purple-700 text-white font-semibold p-4 rounded-xl shadow-lg shadow-purple-200 transition duration-300 transform hover:-translate-y-1">Login Now</button>
        </form>

        <p class="mt-8 text-center text-gray-600">Don't have an account? <a href="register.php" class="text-purple-600 font-bold">Register</a></p>
    </div>
</body>
</html>