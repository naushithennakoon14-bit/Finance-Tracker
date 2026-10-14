<?php
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Obtain total monthly income and expenses (for the chart)
$monthly_data = mysqli_query($conn, "
    SELECT 
        DATE_FORMAT(date, '%M') as month, 
        SUM(CASE WHEN type = 'income' THEN amount ELSE 0 END) as income,
        SUM(CASE WHEN type = 'expense' THEN amount ELSE 0 END) as expense
    FROM transactions 
    WHERE user_id = '$user_id' 
    GROUP BY MONTH(date)
    ORDER BY date ASC
");

$months = [];
$incomes = [];
$expenses = [];

while($row = mysqli_fetch_assoc($monthly_data)) {
    $months[] = $row['month'];
    $incomes[] = $row['income'];
    $expenses[] = $row['expense'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style> body { font-family: 'Poppins', sans-serif; } </style>
</head>
<body class="bg-gray-50 flex">

    <aside class="w-64 bg-purple-900 min-h-screen text-white p-6 hidden lg:block sticky top-0 h-screen">
        <h1 class="text-2xl font-bold mb-10 flex items-center gap-2"><span class="p-2 bg-purple-600 rounded-lg">P</span> PlanEase</h1>
        <nav class="space-y-4">
            <a href="dashboard.php" class="flex items-center gap-3 p-3 hover:bg-purple-800 rounded-xl transition"><span>🏠 Dashboard</span></a>
            <a href="transactions.php" class="flex items-center gap-3 p-3 hover:bg-purple-800 rounded-xl transition"><span>💸 Transactions</span></a>
            <a href="reports.php" class="flex items-center gap-3 p-3 bg-purple-700 rounded-xl transition"><span>📊 Reports</span></a>
            <a href="settings.php" class="flex items-center gap-3 p-3 hover:bg-purple-800 rounded-xl transition"><span>⚙️ Settings</span></a>
            <a href="logout.php" class="flex items-center gap-3 p-3 hover:bg-red-500 rounded-xl transition mt-20"><span>🚪 Logout</span></a>
        </nav>
    </aside>

    <main class="flex-1 p-6 md:p-10 max-w-6xl mx-auto">
        <div class="flex flex-col md:flex-row justify-between items-center mb-10 gap-4">
            <div>
                <h2 class="text-3xl font-bold text-gray-800">Financial Reports</h2>
                <p class="text-gray-500">Analyze your income and savings over time.</p>
            </div>
            
            <div class="flex gap-3">
                <a href="export_excel.php" class="flex items-center gap-2 bg-green-600 text-white px-5 py-3 rounded-xl hover:bg-green-700 transition font-medium shadow-md">
                    <span>📥 Excel</span>
                </a>
                <a href="print_report.php" target="_blank" class="flex items-center gap-2 bg-purple-600 text-white px-5 py-3 rounded-xl hover:bg-purple-700 transition font-medium shadow-md">
                    <span>📄 PDF</span>
                </a>
            </div>
        </div>

        <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100 mb-8">
            <h3 class="text-xl font-bold text-gray-800 mb-6">Income vs Expense Trend</h3>
            <div class="h-96">
                <canvas id="mainReportChart"></canvas>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="bg-gradient-to-br from-purple-600 to-indigo-700 p-8 rounded-3xl text-white shadow-xl">
                <h4 class="text-lg font-medium opacity-80">Report Insight</h4>
                <p class="text-2xl font-bold mt-2">Your spending is 10% lower than last month.</p>
                <div class="mt-6 p-4 bg-white bg-opacity-10 rounded-2xl border border-white border-opacity-20 text-sm italic">
                    "Save today for a secure tomorrow."
                </div>
            </div>

            <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100 flex items-center justify-center">
                <div class="text-center">
                    <p class="text-gray-400 font-medium mb-2">Current Month Savings</p>
                    <h2 class="text-5xl font-black text-purple-700">
                        <?php 
                            $savings = end($incomes) - end($expenses);
                            echo ($savings > 0 ? '+' : '') . number_format($savings, 0); 
                        ?>
                    </h2>
                    <p class="text-sm text-gray-400 mt-2">Keep up the good work!</p>
                </div>
            </div>
        </div>
    </main>

    

    <script>
        const ctx = document.getElementById('mainReportChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($months); ?>,
                datasets: [
                    {
                        label: 'Monthly Income',
                        data: <?php echo json_encode($incomes); ?>,
                        backgroundColor: '#10B981',
                        borderRadius: 8
                    },
                    {
                        label: 'Monthly Expense',
                        data: <?php echo json_encode($expenses); ?>,
                        backgroundColor: '#EF4444',
                        borderRadius: 8
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'top' }
                },
                scales: {
                    y: { beginAtZero: true, grid: { display: false } },
                    x: { grid: { display: false } }
                }
            }
        });
    </script>
</body>
</html>