[13‏/4‏/2026 9:10 ص] Ben omar HOURI: <?php
$host = 'localhost';
$db   = 'gpa_system';
$user = 'root';
$pass = ''; // اتركها فارغة إذا كنت تستخدم XAMPP افتراضياً

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
[13‏/4‏/2026 9:12 ص] Ben omar HOURI: <?php
include 'db.php';

if (isset($_POST['courses']) && isset($_POST['student_name'])) {
    $student_name = $_POST['student_name'];
    $semester = $_POST['semester_label'];
    $courses = $_POST['courses'];
    $credits = $_POST['credits'];
    $grades = $_POST['grades'];

    $totalPoints = 0;
    $totalCredits = 0;
    $tableHtml = '';

    for ($i = 0; $i < count($courses); $i++) {
        $course = htmlspecialchars($courses[$i]);
        $cr = floatval($credits[$i]);
        $g = floatval($grades[$i]);

        if ($cr <= 0) continue;

        $pts = $cr * $g;
        $totalPoints += $pts;
        $totalCredits += $cr;

        $tableHtml .= "<tr><td>$course</td><td>$cr</td><td>$g</td><td>$pts</td></tr>";
    }

    if ($totalCredits > 0) {
        $gpa = $totalPoints / $totalCredits;

        // تحديد التقدير واللون للـ Progress Bar
        if ($gpa >= 3.7) { $interp = "Distinction"; $color = "bg-success"; }
        elseif ($gpa >= 3.0) { $interp = "Merit"; $color = "bg-primary"; }
        elseif ($gpa >= 2.0) { $interp = "Pass"; $color = "bg-warning"; }
        else { $interp = "Fail"; $color = "bg-danger"; }

        // حفظ في قاعدة البيانات
        $stmt = $conn->prepare("INSERT INTO calculations (student_name, semester_label, gpa) VALUES (?, ?, ?)");
        $stmt->bind_param("ssd", $student_name, $semester, $gpa);
        $stmt->execute();

        echo json_encode([
            'success' => true,
            'gpa' => number_format($gpa, 2),
            'interpretation' => $interp,
            'colorClass' => $color,
            'progress' => ($gpa / 4) * 100,
            'tableHtml' => $tableHtml
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'No valid courses entered.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Data not received.']);
}
exit;
?>
