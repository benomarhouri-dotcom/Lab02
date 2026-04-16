 <?php
include 'db_config.php';

// استلام البيانات من النموذج (AJAX)
$student_name = $_POST['student_name'];
$semester = $_POST['semester'];
$courses = $_POST['courses']; // مصفوفة تحتوي على الدرجات والوحدات

$total_points = 0;
$total_credits = 0;

foreach ($courses as $course) {
    $total_points += ($course['grade'] * $course['credit']);
    $total_credits += $course['credit'];
}

$gpa = ($total_credits > 0) ? ($total_points / $total_credits) : 0;

// تحديد الحالة واللون لشريط التقدم (Bootstrap)
if ($gpa >= 3.5) { $status = "Distinction"; $color = "bg-success"; }
elseif ($gpa >= 3.0) { $status = "Merit"; $color = "bg-primary"; }
elseif ($gpa >= 2.0) { $status = "Pass"; $color = "bg-warning"; }
else { $status = "Fail"; $color = "bg-danger"; }

// حفظ البيانات في قاعدة البيانات
$stmt = $conn->prepare("INSERT INTO gpa_records (student_name, semester_label, gpa) VALUES (?, ?, ?)");
$stmt->bind_param("ssd", $student_name, $semester, $gpa);
$stmt->execute();

// إرسال الرد
echo json_encode([
    'success' => true,
    'gpa' => number_format($gpa, 2),
    'status' => $status,
    'progress_color' => $color,
    'message' => "Your GPA is " . number_format($gpa, 2) . " ($status)."
]);
?>
