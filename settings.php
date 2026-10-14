<?php
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$success_msg = "";
$error_msg = "";

// Fetching current user data
$query = mysqli_query($conn, "SELECT * FROM users WHERE id='$user_id'");
$user = mysqli_fetch_assoc($query);

// Profile Update Logic
if (isset($_POST['update_profile'])) {
    $name = $_POST['full_name'];
    $email = $_POST['email'];
    
    // Update image only if uploaded
    if (!empty($_FILES['profile_pic']['name'])) {
        $image = time() . '_' . $_FILES['profile_pic']['name'];
        $target = "uploads/" . $image;
        if (move_uploaded_file($_FILES['profile_pic']['tmp_name'], $target)) {
            mysqli_query($conn, "UPDATE users SET full_name='$name', email='$email', profile_pic='$image' WHERE id='$user_id'");
        }
    } else {
        mysqli_query($conn, "UPDATE users SET full_name='$name', email='$email' WHERE id='$user_id'");
    }
    header("Location: settings.php?success=Profile updated successfully");
}

// Password Change Logic
if (isset($_POST['change_password'])) {
    $current_pass = $_POST['current_password'];
    $new_pass = $_POST['new_password'];

    if (password_verify($current_pass, $user['password'])) {
        $hashed_new_pass = password_hash($new_pass, PASSWORD_DEFAULT);
        mysqli_query($conn, "UPDATE users SET password='$hashed_new_pass' WHERE id='$user_id'");
        $success_msg = "Password changed successfully!";
    } else {
        $error_msg = "Current password is incorrect!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style> body { font-family: 'Poppins', sans-serif; } </style>
</head>
<body class="bg-gray-50 flex">

    <aside class="w-64 bg-purple-900 min-h-screen text-white p-6 hidden lg:block sticky top-0 h-screen">
        <h1 class="text-2xl font-bold mb-10 flex items-center gap-2"><span class="p-2 bg-purple-600 rounded-lg">P</span> PlanEase</h1>
        <nav class="space-y-4">
            <a href="dashboard.php" class="flex items-center gap-3 p-3 hover:bg-purple-800 rounded-xl transition"><span>🏠 Dashboard</span></a>
            <a href="transactions.php" class="flex items-center gap-3 p-3 hover:bg-purple-800 rounded-xl transition"><span>💸 Transactions</span></a>
            <a href="settings.php" class="flex items-center gap-3 p-3 bg-purple-700 rounded-xl transition"><span>⚙️ Settings</span></a>
            <a href="logout.php" class="flex items-center gap-3 p-3 hover:bg-red-500 rounded-xl transition mt-20"><span>🚪 Logout</span></a>
        </nav>
    </aside>

    <main class="flex-1 p-6 md:p-10 max-w-4xl mx-auto">
        <h2 class="text-3xl font-bold text-gray-800 mb-8">Account Settings</h2>

        <?php if(isset($_GET['success']) || $success_msg): ?>
            <div class="bg-green-100 text-green-700 p-4 rounded-xl mb-6"><?php echo $_GET['success'] ?? $success_msg; ?></div>
        <?php endif; ?>
        <?php if($error_msg): ?>
            <div class="bg-red-100 text-red-700 p-4 rounded-xl mb-6"><?php echo $error_msg; ?></div>
        <?php endif; ?>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            
            <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100">
                <h3 class="text-xl font-semibold mb-6 text-purple-800">Edit Profile</h3>
                <form action="" method="POST" enctype="multipart/form-data" class="space-y-4">
                    <div class="flex flex-col items-center mb-4">
                        <img src="<?php echo !empty($user['profile_pic']) ? 'uploads/' . htmlspecialchars($user['profile_pic']) : 'https://placehold.co/120x120/ede9fe/6d28d9?text=User'; ?>" class="w-24 h-24 rounded-full border-4 border-purple-100 mb-2 object-cover">
                        <input type="file" name="profile_pic" class="text-xs text-gray-500">
                    </div>
                    <label class="block text-sm font-medium text-gray-600">Full Name</label>
                    <input type="text" name="full_name" value="<?php echo $user['full_name']; ?>" class="w-full p-3 border rounded-xl outline-none focus:ring-2 focus:ring-purple-400">
                    
                    <label class="block text-sm font-medium text-gray-600">Email Address</label>
                    <input type="email" name="email" value="<?php echo $user['email']; ?>" class="w-full p-3 border rounded-xl outline-none focus:ring-2 focus:ring-purple-400">
                    
                    <button name="update_profile" class="w-full bg-purple-600 text-white font-bold py-3 rounded-xl hover:bg-purple-700 transition">Update Profile</button>
                </form>
            </div>

            <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100">
                <h3 class="text-xl font-semibold mb-6 text-purple-800">Change Password</h3>
                <form action="" method="POST" class="space-y-4">
                    <label class="block text-sm font-medium text-gray-600">Current Password</label>
                    <input type="password" name="current_password" required class="w-full p-3 border rounded-xl outline-none focus:ring-2 focus:ring-purple-400">
                    
                    <label class="block text-sm font-medium text-gray-600">New Password</label>
                    <input type="password" name="new_password" required class="w-full p-3 border rounded-xl outline-none focus:ring-2 focus:ring-purple-400">
                    
                    <button name="change_password" class="w-full bg-gray-800 text-white font-bold py-3 rounded-xl hover:bg-black transition">Change Password</button>
                </form>
            </div>

        </div>
    </main>
</body>
</html>