[12‏/4‏/2026 9:09 م] Nini Yacine: body{

font-family:Arial;
margin:20px;
background:#e9ecef;

}

h1{

color:#007BFF;

}

.course-row{

display:flex;
gap:10px;
align-items:center;
margin-bottom:10px;

}

.course-row input,
.course-row select{

padding:6px;
border:1px solid #ccc;
border-radius:4px;

}

table{

border-collapse:collapse;
margin-top:20px;

}

table th,table td{

border:1px solid #ccc;
padding:8px 14px;
text-align:center;

}

table th{

background:#007BFF;
color:white;

}
[13‏/4‏/2026 12:14 ص] Nini Yacine: <?php
header('Content-Type: application/json');

if (isset($_POST['course'], $_POST['credits'], $_POST['grade'])) {

    $courses = $_POST['course'];
    $credits = $_POST['credits'];
    $grades = $_POST['grade'];

    $totalPoints = 0;
    $totalCredits = 0;

    $tableHtml = '<table class="table table-bordered mt-3">';
    $tableHtml .= '<thead class="thead-dark">
    <tr>
    <th>Course</th>
    <th>Credits</th>
    <th>Grade</th>
    <th>Points</th>
    </tr>
    </thead><tbody>';

    for ($i = 0; $i < count($courses); $i++) {

        $course = htmlspecialchars($courses[$i]);
        $cr = floatval($credits[$i]);
        $g = floatval($grades[$i]);

        if ($cr <= 0) continue;

        $pts = $cr * $g;

        $totalPoints += $pts;
        $totalCredits += $cr;

        $tableHtml .= "<tr>
        <td>$course</td>
        <td>$cr</td>
        <td>$g</td>
        <td>$pts</td>
        </tr>";
    }

    $tableHtml .= '</tbody></table>';

    if ($totalCredits > 0) {

        $gpa = $totalPoints / $totalCredits;

        if ($gpa >= 3.7) {
            $interp = "Distinction";
        } elseif ($gpa >= 3.0) {
            $interp = "Merit";
        } elseif ($gpa >= 2.0) {
            $interp = "Pass";
        } else {
            $interp = "Fail";
        }

        $message = "Your GPA is " . number_format($gpa, 2) . " ($interp)";

        echo json_encode([
            "success" => true,
            "gpa" => $gpa,
            "message" => $message,
            "tableHtml" => $tableHtml
        ]);

    } else {

        echo json_encode([
            "success" => false,
            "message" => "No valid courses entered."
        ]);
    }

} else {

    echo json_encode([
        "success" => false,
        "message" => "Data not received."
    ]);
}
?>
