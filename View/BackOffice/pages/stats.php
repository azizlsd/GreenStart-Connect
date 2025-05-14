<?php
require_once 'C:\xampp\htdocs\GreenStartConnect\View\BackOffice\headerBack.php';
?>
<?php
require_once __DIR__ . '/../../../Controller/eventC.php';

// Optional: Token-based access control
/*
$accessToken = 'your_secure_token';
if (!isset($_GET['token']) || $_GET['token'] !== $accessToken) {
    http_response_code(403);
    echo "Accès non autorisé";
    exit;
}
*/

$startDate = $_GET['start_date'] ?? date('Y-m-d', strtotime('-12 months'));
$endDate = $_GET['end_date'] ?? date('Y-m-d');

try {
    $stats = EventController::getEventStatistics($startDate, $endDate);
    error_log("Event stats retrieved: total_events=" . $stats['total_events']);
} catch (Exception $e) {
    error_log("Error retrieving event stats: " . $e->getMessage());
    $stats = [
        'total_events' => 0,
        'events_by_month' => []
    ];
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistiques des Événements</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f4f4f4;
        }
        h1 {
            color: #228B22;
        }
        .filter-form {
            margin-bottom: 20px;
        }
        .stats-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }
        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            flex: 1;
            min-width: 300px;
        }
        canvas {
            max-width: 100%;
        }
    </style>
</head>
<body>
    <h1>Statistiques des Événements</h1>

    <!-- Filter Form for Date Range -->
    <form class="filter-form" method="GET">
        <label for="start_date">Date de début : </label>
        <input type="date" id="start_date" name="start_date" value="<?php echo htmlspecialchars($startDate); ?>">
        <label for="end_date">Date de fin : </label>
        <input type="date" id="end_date" name="end_date" value="<?php echo htmlspecialchars($endDate); ?>">
        <button type="submit">Filtrer</button>
    </form>

    <!-- [ Main Content ] start -->
    <div class="row">
        <!-- [ Bar Chart ] -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>Bar chart</h5>
                </div>
                <div class="card-body">
                    <canvas id="barChart"></canvas>
                </div>
            </div>
        </div>

        <!-- [ Pie Chart ] -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>Pie Chart</h5>
                </div>
                <div class="card-body">
                    <canvas id="pieChart"></canvas>
                </div>
            </div>
        </div>

        <!-- [ Area Chart ] -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>Area Chart</h5>
                </div>
                <div class="card-body">
                    <canvas id="areaChart"></canvas>
                </div>
            </div>
        </div>

        <!-- [ Line Chart ] -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>Basic Line Chart</h5>
                </div>
                <div class="card-body">
                    <canvas id="lineChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    <!-- [ Main Content ] end -->

    <script>
        // Bar Chart (events by month)
        const barChartCtx = document.getElementById('barChart').getContext('2d');
        new Chart(barChartCtx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode(array_column($stats['events_by_month'], 'month')); ?>,
                datasets: [{
                    label: 'Événements',
                    data: <?php echo json_encode(array_column($stats['events_by_month'], 'events')); ?>,
                    backgroundColor: 'rgba(34, 139, 34, 0.2)',
                    borderColor: 'rgba(34, 139, 34, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Pie Chart (event distribution example)
        const pieChartCtx = document.getElementById('pieChart').getContext('2d');
        new Chart(pieChartCtx, {
            type: 'pie',
            data: {
                labels: ['Event A', 'Event B', 'Event C'], // Replace with actual event data
                datasets: [{
                    label: 'Event Distribution',
                    data: [40, 25, 35], // Replace with actual data
                    backgroundColor: ['#FF5733', '#33FF57', '#3357FF'],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true
            }
        });

        // Area Chart (example area data)
        const areaChartCtx = document.getElementById('areaChart').getContext('2d');
        new Chart(areaChartCtx, {
            type: 'line',
            data: {
                labels: <?php echo json_encode(array_column($stats['events_by_month'], 'month')); ?>,
                datasets: [{
                    label: 'Événements',
                    data: <?php echo json_encode(array_column($stats['events_by_month'], 'events')); ?>,
                    fill: true,
                    backgroundColor: 'rgba(34, 139, 34, 0.3)',
                    borderColor: 'rgba(34, 139, 34, 1)',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Line Chart (same event data as bar chart)
        const lineChartCtx = document.getElementById('lineChart').getContext('2d');
        new Chart(lineChartCtx, {
            type: 'line',
            data: {
                labels: <?php echo json_encode(array_column($stats['events_by_month'], 'month')); ?>,
                datasets: [{
                    label: 'Événements',
                    data: <?php echo json_encode(array_column($stats['events_by_month'], 'events')); ?>,
                    fill: false,
                    borderColor: 'rgba(34, 139, 34, 1)',
                    tension: 0.1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</body>
</html>
<?php
require_once 'C:\xampp\htdocs\GreenStartConnect\View\BackOffice\footerBack.php';
?>