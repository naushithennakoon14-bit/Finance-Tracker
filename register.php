<?php
include 'db.php';

if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}

if (isset($_POST['register'])) {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $image = '';
    if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] === UPLOAD_ERR_OK) {
        $image = time() . '_' . basename($_FILES['profile_pic']['name']);
        $target = "uploads/" . $image;
        if (move_uploaded_file($_FILES['profile_pic']['tmp_name'], $target)) {
            $sql = "INSERT INTO users (full_name, email, password, profile_pic) VALUES ('" . mysqli_real_escape_string($conn, $name) . "', '" . mysqli_real_escape_string($conn, $email) . "', '" . mysqli_real_escape_string($conn, $password) . "', '" . mysqli_real_escape_string($conn, $image) . "')";
        } else {
            $sql = "INSERT INTO users (full_name, email, password) VALUES ('" . mysqli_real_escape_string($conn, $name) . "', '" . mysqli_real_escape_string($conn, $email) . "', '" . mysqli_real_escape_string($conn, $password) . "')";
        }
    } else {
        $sql = "INSERT INTO users (full_name, email, password) VALUES ('" . mysqli_real_escape_string($conn, $name) . "', '" . mysqli_real_escape_string($conn, $email) . "', '" . mysqli_real_escape_string($conn, $password) . "')";
    }

    if (mysqli_query($conn, $sql)) {
        header("Location: login.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style> body { font-family: 'Poppins', sans-serif; } </style>
</head>
<body class="bg-purple-50 flex items-center justify-center min-h-screen">
    <div class="bg-white p-8 rounded-3xl shadow-2xl w-full max-w-md border border-purple-100">
        <h2 class="text-3xl font-bold text-purple-800 mb-2 text-center">Join Us!</h2>
        <p class="text-gray-500 text-center mb-6">Start tracking your finance today.</p>
        
        <form action="" method="POST" enctype="multipart/form-data" class="space-y-4">
            <div class="flex flex-col items-center mb-4">
                <div class="relative">
                    <img id="preview" src="https://via.placeholder.com/100" class="w-24 h-24 rounded-full object-cover border-4 border-purple-200">
                    <label for="file-upload" class="absolute bottom-0 right-0 bg-purple-600 p-2 rounded-full text-white cursor-pointer hover:bg-purple-700 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                    </label>
                    <input id="file-upload" name="profile_pic" type="file" class="hidden" onchange="previewImg(this)">
                </div>
            </div>

            <input type="text" name="name" placeholder="Full Name" class="w-full p-4 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-400 outline-none transition" required>
            <input type="email" name="email" placeholder="Email Address" class="w-full p-4 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-400 outline-none transition" required>
            <input type="password" name="password" placeholder="Password" class="w-full p-4 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-400 outline-none transition" required>
            
            <button name="register" class="w-full bg-purple-600 hover:bg-purple-700 text-white font-semibold p-4 rounded-xl shadow-lg shadow-purple-200 transition duration-300 transform hover:-translate-y-1">Create Account</button>
        </form>
        <p class="mt-6 text-center text-gray-600">Already have an account? <a href="login.php" class="text-purple-600 font-bold">Login</a></p>
    </div>

    <script>
        function previewImg(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) { document.getElementById('preview').src = e.target.result; }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</body>
</html>