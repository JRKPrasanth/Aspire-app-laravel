html

<canvas id="statusChart"></canvas>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
new Chart(document.getElementById("statusChart"), {
    type: 'pie',
    data: {
        labels: ["Success", "Failed", "Needs Improvement"],
        datasets: [{
            data: [{{ $success }}, {{ $failed }}, {{ $improve }}]
        }]
    }
});
</script>