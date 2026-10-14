<?php
include 'db.php';
if (!isset($_SESSION['user_id'])) exit;

$user_id = $_SESSION['user_id'];
$filename = "Finance_Report_" . date('Y-m-d') . ".csv";

// Creating the header component
header("Content-Type: text/csv");
header("Content-Disposition: attachment; filename=$filename");

$output = fopen("php://output", "w");
// Entering column names
fputcsv($output, array('Date', 'Type', 'Category', 'Amount', 'Description'));

// Exporting database data to a CSV file
$query = mysqli_query($conn, "SELECT date, type, category, amount, description FROM transactions WHERE user_id='$user_id' ORDER BY date DESC");

while ($row = mysqli_fetch_assoc($query)) {
    fputcsv($output, $row);
}
fclose($output);
exit;
?>