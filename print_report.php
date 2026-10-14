<?php
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$results = mysqli_query($conn, "SELECT * FROM transactions WHERE user_id='" . mysqli_real_escape_string($conn, $user_id) . "' ORDER BY date DESC");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Finance Report</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-white p-10">
    <div class="text-center mb-10">
        <h1 class="text-3xl font-bold text-purple-800">PlanEase Finance Report</h1>
        <p class="text-gray-500">Generated on: <?php echo date('Y-m-d H:i'); ?></p>
    </div>

    <table class="w-full border-collapse border border-gray-200">
        <thead>
            <tr class="bg-purple-100 text-left">
                <th class="p-3 border">Date</th>
                <th class="p-3 border">Category</th>
                <th class="p-3 border">Type</th>
                <th class="p-3 border text-right">Amount (LKR)</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = mysqli_fetch_assoc($results)): ?>
            <tr>
                <td class="p-3 border"><?php echo $row['date']; ?></td>
                <td class="p-3 border"><?php echo $row['category']; ?></td>
                <td class="p-3 border uppercase"><?php echo $row['type']; ?></td>
                <td class="p-3 border text-right <?php echo $row['type']=='income' ? 'text-green-600' : 'text-red-600'; ?>">
                    <?php echo number_format($row['amount'], 2); ?>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <div class="mt-10 text-center no-print">
        <button onclick="window.print()" class="bg-purple-600 text-white px-8 py-3 rounded-xl font-bold">Download as PDF</button>
        <p class="text-xs text-gray-400 mt-2">* Use 'Save as PDF' in the print dialog.</p>
    </div>

    <style>
        @media print { .no-print { display: none; } }
    </style>
</body>
</html>