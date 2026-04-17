<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GPA Calculator - Lab 2</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <link rel="stylesheet" href="style.css">
</head>
<body class="bg-light">

    <div class="container py-5">
        <div class="card shadow-sm p-4 mt-4">
            <h5 class="mb-3">GPA Progress:</h5>
            <div class="progress" style="height: 30px;">
                <div id="gpa-bar" class="progress-bar progress-bar-striped progress-bar-animated bg-secondary" 
                     role="progressbar" style="width: 0%;">
                    0.00
                </div>
            </div>
            
            <div class="mt-4">
                <a href="export_csv.php" class="btn btn-primary">
                    Download History (CSV)
                </a>
            </div>
        </div>
    </div>

    <script>
    function updateUI(response) {
        let percentage = (response.gpa / 4) * 100;
        let bar = document.getElementById('gpa-bar');

        bar.style.width = percentage + "%";
        bar.innerText = response.gpa;
        
        // تغيير اللون بناءً على النتيجة القادمة من PHP
        bar.className = "progress-bar progress-bar-striped progress-bar-animated " + response.progress_color;
    }
    </script>
</body>
</html>
