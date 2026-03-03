<?php 
$title = 'Dashboard - Admin Sandbox';
require __DIR__ . '/header.php'; 
require __DIR__ . '/admin_nav.php';
?>

<div class="row">
    <div class="col-md-12">
        <div class="card shadow-sm border-secondary bg-dark-subtle mb-4">
            <div class="card-header bg-primary bg-opacity-25 text-light border-secondary fw-bold">
                Site Activity (Last 7 Days)
            </div>
            <div class="card-body">
                <canvas id="activityChart" height="100"></canvas>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const rawData = <?php echo json_encode($weeklyData ?? []); ?>;
    const labels = rawData.map(d => d.post_date);
    const dataPoints = rawData.map(d => d.post_count);

    const ctx = document.getElementById('activityChart');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Posts per Day',
                data: dataPoints,
                borderColor: '#0d6efd',
                backgroundColor: 'rgba(13, 110, 253, 0.2)',
                borderWidth: 2,
                tension: 0.3,
                fill: true
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { color: '#E0E0E0' },
                    grid: { color: '#333333' }
                },
                x: {
                    ticks: { color: '#E0E0E0' },
                    grid: { color: '#333333' }
                }
            },
            plugins: {
                legend: {
                    labels: { color: '#E0E0E0' }
                }
            }
        }
    });
</script>

<?php require __DIR__ . '/footer.php'; ?>
