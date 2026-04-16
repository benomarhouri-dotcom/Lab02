<?php
include 'db_config.php';

header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="gpa_history.csv"');

$output = fopen('php://output', 'w');
fputcsv($output, array('ID', 'Student Name', 'Semester', 'GPA', 'Date'));

$query = "SELECT * FROM gpa_records ORDER BY calculation_date DESC";
$result = mysqli_query($conn, $query);

while ($row = mysqli_fetch_assoc($result)) {
    fputcsv($output, $row);
}
fclose($output);
?>
