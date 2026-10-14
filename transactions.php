<?php
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Delete Logic
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM transactions WHERE id='$id' AND user_id='$user_id'");
    header("Location: transactions.php");
}

// Search and Filter Logic
$search = $_GET['search'] ?? '';
$filter_type = $_GET['filter_type'] ?? '';

$query = "SELECT * FROM transactions WHERE user_id='$user_id'";

if ($search) {
    $query .= " AND (category LIKE '%$search%' OR description LIKE '%$search%')";
}
if ($filter_type) {
    $query .= " AND type='$filter_type'";
}

$query .= " ORDER BY date DESC";
$results = mysqli_query($conn, $query);
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
            <a href="transactions.php" class="flex items-center gap-3 p-3 bg-purple-700 rounded-xl transition"><span>💸 Transactions</span></a>
            <a href="logout.php" class="flex items-center gap-3 p-3 hover:bg-red-500 rounded-xl transition mt-20"><span>🚪 Logout</span></a>
        </nav>
    </aside>

    <main class="flex-1 p-6 md:p-10">
        <header class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
            <h2 class="text-2xl font-bold text-gray-800">All Transactions</h2>
            
            <form action="" method="GET" class="flex flex-wrap gap-3 w-full md:w-auto">
                <input type="text" name="search" value="<?php echo $search; ?>" placeholder="Search description..." class="p-3 border rounded-xl outline-none focus:ring-2 focus:ring-purple-400 w-full md:w-64 shadow-sm">
                <select name="filter_type" class="p-3 border rounded-xl outline-none focus:ring-2 focus:ring-purple-400 shadow-sm">
                    <option value="">All Types</option>
                    <option value="income" <?php if($filter_type == 'income') echo 'selected'; ?>>Income</option>
                    <option value="expense" <?php if($filter_type == 'expense') echo 'selected'; ?>>Expense</option>
                </select>
                <button type="submit" class="bg-purple-600 text-white px-6 py-3 rounded-xl hover:bg-purple-700 transition font-medium">Filter</button>
            </form>
        </header>

        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead class="bg-purple-50">
                    <tr>
                        <th class="p-4 text-purple-800 font-semibold">Date</th>
                        <th class="p-4 text-purple-800 font-semibold">Category</th>
                        <th class="p-4 text-purple-800 font-semibold">Description</th>
                        <th class="p-4 text-purple-800 font-semibold text-right">Amount</th>
                        <th class="p-4 text-purple-800 font-semibold text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php if(mysqli_num_rows($results) > 0): ?>
                        <?php while($row = mysqli_fetch_assoc($results)): ?>
                        <tr class="hover:bg-gray-50 transition">
                            <td class="p-4 text-sm text-gray-600"><?php echo date('M d, Y', strtotime($row['date'])); ?></td>
                            <td class="p-4 font-medium text-gray-800">
                                <span class="px-3 py-1 rounded-full text-xs <?php echo $row['type'] == 'income' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'; ?>">
                                    <?php echo $row['category']; ?>
                                </span>
                            </td>
                            <td class="p-4 text-sm text-gray-500 italic"><?php echo $row['description'] ?: '-'; ?></td>
                            <td class="p-4 text-right font-bold <?php echo $row['type'] == 'income' ? 'text-green-500' : 'text-red-500'; ?>">
                                <?php echo ($row['type'] == 'income' ? '+' : '-') . " LKR " . number_format($row['amount'], 2); ?>
                            </td>
                            <td class="p-4 text-center">
                                <a href="?delete=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure?')" class="text-red-400 hover:text-red-600 transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="p-10 text-center text-gray-400">No transactions found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>

</body>
</html>