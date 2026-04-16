<div class="mt-4">
    <h5>GPA Progress:</h5>
    <div class="progress" style="height: 30px;">
        <div id="gpa-bar" class="progress-bar progress-bar-striped progress-bar-animated" 
             role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="4">
            0.00
        </div>
    </div>
</div>

<a href="export_csv.php" class="btn btn-secondary mt-3">Download History (CSV)</a>

<script>
// كود تحديث الـ Progress Bar عند استلام النتيجة من AJAX
function updateUI(response) {
    let percentage = (response.gpa / 4) * 100;
    let bar = document.getElementById('gpa-bar');
    
    bar.style.width = percentage + "%";
    bar.innerText = response.gpa;
    bar.className = "progress-bar " + response.progress_color;
}
</script>
