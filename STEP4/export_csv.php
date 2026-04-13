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
[13‏/4‏/2026 9:14 ص] Ben omar HOURI: <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>GPA Calculator - Advanced</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="card shadow">
        <div class="card-header bg-dark text-white">
            <h4>GPA Calculator (Lab 2 Extended)</h4>
        </div>
        <div class="card-body">
            <form id="gpaForm">
                <div class="row mb-3">
                    <div class="col">
                        <input type="text" name="student_name" class="form-control" placeholder="Student Name" required>
                    </div>
                    <div class="col">
                        <input type="text" name="semester_label" class="form-control" placeholder="Semester (e.g. Fall 2024)" required>
                    </div>
                </div>

                <div id="courseInputs">
                    <div class="row mb-2">
                        <div class="col-5"><input type="text" name="courses[]" class="form-control" placeholder="Course Name"></div>
                        <div class="col-3"><input type="number" name="credits[]" class="form-control" placeholder="Credits"></div>
                        <div class="col-3"><input type="number" step="0.01" name="grades[]" class="form-control" placeholder="Grade"></div>
                    </div>
                </div>
                
                <button type="button" class="btn btn-secondary btn-sm" onclick="addCourse()">+ Add Course</button>
                <hr>
                <button type="submit" class="btn btn-primary w-100">Calculate & Save</button>
            </form>

            <div id="resultArea" class="mt-4" style="display:none;">
                <h5>Result: <span id="gpaVal"></span> (<span id="interpVal"></span>)</h5>
                <div class="progress mb-3" style="height: 25px;">
                    <div id="gpaBar" class="progress-bar" role="progressbar" style="width: 0%;"></div>
                </div>
                <table class="table table-bordered">
                    <thead><tr><th>Course</th><th>Credits</th><th>Grade</th><th>Points</th></tr></thead>
                    <tbody id="tableBody"></tbody>
                </table>
                <a href="export_csv.php" class="btn btn-success">Download CSV</a>
            </div>
        </div>
    </div>
</div>

<script>
function addCourse() {
    const div = document.createElement('div');
    div.className = 'row mb-2';
    div.innerHTML = 
        <div class="col-5"><input type="text" name="courses[]" class="form-control" placeholder="Course Name"></div>
        <div class="col-3"><input type="number" name="credits[]" class="form-control" placeholder="Credits"></div>
        <div class="col-3"><input type="number" step="0.01" name="grades[]" class="form-control" placeholder="Grade"></div>
    ;
    document.getElementById('courseInputs').appendChild(div);
}

document.getElementById('gpaForm').onsubmit = async (e) => {
    e.preventDefault();
    const formData = new FormData(e.target);
    const response = await fetch('calculate.php', { method: 'POST', body: formData });
    const data = await response.json();

    if(data.success) {
        document.getElementById('resultArea').style.display = 'block';
        document.getElementById('gpaVal').innerText = data.gpa;
        document.getElementById('interpVal').innerText = data.interpretation;
        document.getElementById('tableBody').innerHTML = data.tableHtml;
        
        const bar = document.getElementById('gpaBar');
        bar.style.width = data.progress + '%';
        bar.className = 'progress-bar ' + data.colorClass;
        bar.innerText = data.gpa;
    } else {
        alert(data.message);
    }
};
</script>
</body>
</html>
