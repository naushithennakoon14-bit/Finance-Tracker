<?php
include 'db.php';

// If user is not authenticated, redirect to the login screen
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Get user data
$user_query = mysqli_query($conn, "SELECT * FROM users WHERE id='$user_id'");
$user = mysqli_fetch_assoc($user_query);

// Calculate income, expenses, and balance
$income_query = mysqli_query($conn, "SELECT SUM(amount) AS total FROM transactions WHERE user_id='$user_id' AND type='income'");
$income = mysqli_fetch_assoc($income_query)['total'] ?? 0;

$expense_query = mysqli_query($conn, "SELECT SUM(amount) AS total FROM transactions WHERE user_id='$user_id' AND type='expense'");
$expense = mysqli_fetch_assoc($expense_query)['total'] ?? 0;

$balance = $income - $expense;

// Get last 5 transactions
$transactions = mysqli_query($conn, "SELECT * FROM transactions WHERE user_id='$user_id' ORDER BY date DESC LIMIT 5");

if (isset($_POST['add_transaction'])) {
    $amount = $_POST['amount'];
    $type = $_POST['type'];
    $category = $_POST['category'];
    $description = $_POST['description'];
    $date = $_POST['date'];
    $u_id = $_SESSION['user_id'];

    $insert_sql = "INSERT INTO transactions (user_id, type, category, amount, description, date) 
                   VALUES ('$u_id', '$type', '$category', '$amount', '$description', '$date')";
    
    if (mysqli_query($conn, $insert_sql)) {
        echo "<script>alert('Transaction Added Successfully!'); window.location='dashboard.php';</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finance Tracker - Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style> body { font-family: 'Poppins', sans-serif; } </style>
</head>
<body class="bg-gray-50 flex">

    <aside class="w-64 bg-purple-900 min-h-screen text-white p-6 hidden lg:block sticky top-0 h-screen">
        <h1 class="text-2xl font-bold mb-10 flex items-center gap-2">
            <span class="p-2 bg-purple-600 rounded-lg">P</span> PlanEase
        </h1>
        <nav class="space-y-4">
            <a href="dashboard.php" class="flex items-center gap-3 p-3 bg-purple-700 rounded-xl transition">
                <span>🏠 Dashboard</span>
            </a>
            <a href="transactions.php" class="flex items-center gap-3 p-3 hover:bg-purple-800 rounded-xl transition">
                <span>💸 Transactions</span>
            </a>
            <a href="reports.php" class="flex items-center gap-3 p-3 hover:bg-purple-800 rounded-xl transition">
                <span>📊 Reports</span>
            </a>
            <a href="settings.php" class="flex items-center gap-3 p-3 hover:bg-purple-800 rounded-xl transition">
                <span>⚙️ Settings</span>
            </a>
            <a href="logout.php" class="flex items-center gap-3 p-3 hover:bg-red-500 rounded-xl transition mt-20 text-red-200">
                <span>🚪 Logout</span>
            </a>
        </nav>
    </aside>

    <main class="flex-1 p-6 md:p-10">
        <header class="flex justify-between items-center mb-10">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Hello, <?php echo explode(' ', $user['full_name'])[0]; ?>! 👋</h2>
                <p class="text-gray-500 text-sm">Track your daily expenses easily.</p>
            </div>
            <div class="flex items-center gap-4">
                <button onclick="toggleModal()" class="bg-purple-600 text-white px-4 py-2 rounded-xl hover:bg-purple-700 transition shadow-lg shadow-purple-200">+ Add New</button>
                <img src="<?php echo !empty($user['profile_pic']) ? 'uploads/' . htmlspecialchars($user['profile_pic']) : 'https://placehold.co/100x100/ede9fe/6d28d9?text=User'; ?>" class="w-12 h-12 rounded-full border-2 border-purple-500 shadow-sm object-cover">
            </div>
        </header>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
            <div class="bg-purple-700 p-6 rounded-3xl text-white shadow-xl shadow-purple-200">
                <p class="opacity-80 text-sm font-medium">Total Balance</p>
                <h3 class="text-3xl font-bold mt-1">LKR <?php echo number_format($balance, 2); ?></h3>
            </div>
            <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
                <p class="text-gray-500 text-sm font-medium">Total Income</p>
                <h3 class="text-3xl font-bold mt-1 text-green-500">+ LKR <?php echo number_format($income, 2); ?></h3>
            </div>
            <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
                <p class="text-gray-500 text-sm font-medium">Total Expenses</p>
                <h3 class="text-3xl font-bold mt-1 text-red-500">- LKR <?php echo number_format($expense, 2); ?></h3>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100">
                <h4 class="text-lg font-bold text-gray-800 mb-6">Spending Analytics</h4>
                <canvas id="expenseChart" height="200"></canvas>
            </div>

            <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100">
                <h4 class="text-lg font-bold text-gray-800 mb-6">Recent Transactions</h4>
                <div class="space-y-4">
                    <?php while($row = mysqli_fetch_assoc($transactions)): ?>
                    <div class="flex justify-between items-center p-4 bg-gray-50 rounded-2xl hover:bg-gray-100 transition">
                        <div class="flex items-center gap-4">
                            <div class="p-3 rounded-xl <?php echo $row['type'] == 'income' ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600'; ?>">
                                <?php echo $row['type'] == 'income' ? '↓' : '↑'; ?>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-800"><?php echo $row['category']; ?></p>
                                <p class="text-xs text-gray-400"><?php echo $row['date']; ?></p>
                            </div>
                        </div>
                        <p class="font-bold <?php echo $row['type'] == 'income' ? 'text-green-500' : 'text-red-500'; ?>">
                            <?php echo ($row['type'] == 'income' ? '+' : '-') . " LKR " . number_format($row['amount'], 2); ?>
                        </p>
                    </div>
                    <?php endwhile; ?>
                </div>
            </div>
        </div>
    </main>


    <div id="modal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
    <div class="bg-white p-8 rounded-3xl shadow-2xl w-full max-w-md transform transition-all">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-xl font-bold text-purple-800">Add Transaction</h3>
            <button onclick="toggleModal()" class="text-gray-400 hover:text-gray-600 text-2xl">&times;</button>
        </div>

        <form action="" method="POST" class="space-y-4">
            <div class="grid grid-cols-2 gap-4">
                <button type="button" onclick="setType('income')" id="btn-income" class="p-3 rounded-xl border-2 border-green-500 text-green-600 font-bold hover:bg-green-50 transition">Income</button>
                <button type="button" onclick="setType('expense')" id="btn-expense" class="p-3 rounded-xl border-2 border-red-500 text-red-600 font-bold hover:bg-red-50 transition">Expense</button>
            </div>
            <input type="hidden" name="type" id="trans_type" value="income" required>

            <input type="number" step="0.01" name="amount" placeholder="Amount (LKR)" class="w-full p-4 bg-gray-50 border border-gray-200 rounded-xl outline-none focus:ring-2 focus:ring-purple-400" required>
            
            <select name="category" class="w-full p-4 bg-gray-50 border border-gray-200 rounded-xl outline-none focus:ring-2 focus:ring-purple-400">
                <option value="Salary">Salary</option>
                <option value="Food">Food</option>
                <option value="Transport">Transport</option>
                <option value="Shopping">Shopping</option>
                <option value="Bills">Bills</option>
                <option value="Other">Other</option>
            </select>

            <input type="date" name="date" class="w-full p-4 bg-gray-50 border border-gray-200 rounded-xl outline-none focus:ring-2 focus:ring-purple-400" value="<?php echo date('Y-m-d'); ?>" required>
            
            <textarea name="description" placeholder="Short Note (Optional)" class="w-full p-4 bg-gray-50 border border-gray-200 rounded-xl outline-none focus:ring-2 focus:ring-purple-400"></textarea>

            <button name="add_transaction" class="w-full bg-purple-600 text-white font-bold p-4 rounded-xl shadow-lg hover:bg-purple-700 transition">Save Transaction</button>
        </form>
    </div>
</div>

    

    <script>
        const ctx = document.getElementById('expenseChart').getContext('2d');
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Income', 'Expense'],
                datasets: [{
                    data: [<?php echo $income; ?>, <?php echo $expense; ?>],
                    backgroundColor: ['#10B981', '#EF4444'],
                    borderWidth: 0,
                    hoverOffset: 10
                }]
            },
            options: {
                cutout: '70%',
                plugins: {
                    legend: { position: 'bottom' }
                }
            }
        });
    </script>

    <script>
    function toggleModal() {
        const modal = document.getElementById('modal');
        modal.classList.toggle('hidden');
    }

    function setType(type) {
        document.getElementById('trans_type').value = type;
        const incBtn = document.getElementById('btn-income');
        const expBtn = document.getElementById('btn-expense');

        if(type === 'income') {
            incBtn.classList.add('bg-green-500', 'text-white');
            expBtn.classList.remove('bg-red-500', 'text-white');
        } else {
            expBtn.classList.add('bg-red-500', 'text-white');
            incBtn.classList.remove('bg-green-500', 'text-white');
        }
    }
    
    // Default set to income on load
    setType('income');
</script>
</body>
</html>