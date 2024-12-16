<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "only_gains";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_report'])) {
    // Retrieve POST data
    $week = $conn->real_escape_string($_POST['week']);
    $physical_damage = (int)$_POST['physical_damage'];
    $spoilage = (int)$_POST['spoilage'];
    $pest_infestation = (int)$_POST['pest_infestation'];
    $over_ripening = (int)$_POST['over_ripening'];

    // Insert data into the `loss_report` table
    $sql = "INSERT INTO loss_report (`Week`, `Physical Damage`, `Spoilage`, `Pest Infestation`, `Over Ripening`) 
            VALUES ('$week', $physical_damage, $spoilage, $pest_infestation, $over_ripening)";

    if ($conn->query($sql) === TRUE) {
        echo "New loss report added successfully.";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Fetch existing reports
$reports = [];
$sql = "SELECT `Loss Report ID`, `Week`, `Physical Damage`, `Spoilage`, `Pest Infestation`, `Over Ripening` FROM loss_report";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $reports[] = $row;
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Loss Causes - Admin</title>
    <link rel="stylesheet" href="../theme.css">
    <link rel="stylesheet" href="./admin.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="admin-container">
        <aside class="sidebar">
            <h2>Admin Panel</h2>
            <nav>
                <ul>
                    <li><a href="admin.html">Dashboard</a></li>
                    <li class="dropdown">
                        <a class="dropdown-btn">User Roles</a>
                        <ul class="dropdown-content">
                            <li><a href="../farm manager/farm_manager.html">Farm Manager</a></li>
                            <li><a href="../retailer/all_orders.html">Retailer</a></li>
                            <li><a href="../wholesaler/wholesaler.html">Wholesaler</a></li>
                            <li><a href="../qa officer/inspections.html">QA Officer</a></li>
                        </ul>
                    </li>
                    <li><a href="./loss_tracking_admin.html">Loss Tracking</a></li>
                    <li><a href="./admin_loss_cause_report.html">Reports</a></li>
                    <li><a href="../index.html">Log Out</a></li>
                </ul>
            </nav>
        </aside>

        <main class="content">
            <header class="head">
                <h1>Loss Causes Overview</h1>
            </header>

            <section class="graphs-section">
                <div class="graph">
                    <canvas id="lossCausesGraph"></canvas>
                </div>
            </section>

            <section class="stats-section">
                <div class="stat-box">
                    <h3>Physical Damage</h3>
                    <p id="physical-damage-loss">50 kg</p>
                </div>
                <div class="stat-box">
                    <h3>Spoilage</h3>
                    <p id="spoilage-loss">40 kg</p>
                </div>
                <div class="stat-box">
                    <h3>Pest Infestation</h3>
                    <p id="pest-infestation-loss">30 kg</p>
                </div>
                <div class="stat-box">
                    <h3>Over Ripening</h3>
                    <p id="over-ripening-loss">20 kg</p>
                </div>
            </section>

            <section class="report-section">
                <h2>Loss Report</h2>
                <form action="admin_loss_cause.php" method="POST" style="margin-bottom: 20px;">
                    <h3>Add Loss Report</h3>
                    <label for="week">Week:</label>
                    <input type="text" name="week" required>
                    <label for="physical_damage">Physical Damage (kg):</label>
                    <input type="number" name="physical_damage" required>
                    <label for="spoilage">Spoilage (kg):</label>
                    <input type="number" name="spoilage" required>
                    <label for="pest_infestation">Pest Infestation (kg):</label>
                    <input type="number" name="pest_infestation" required>
                    <label for="over_ripening">Over Ripening (kg):</label>
                    <input type="number" name="over_ripening" required>
                    <button type="submit" name="add_report" class="btn-primary">Add Report</button>
                </form>

                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Week</th>
                            <th>Physical Damage (kg)</th>
                            <th>Spoilage (kg)</th>
                            <th>Pest Infestation (kg)</th>
                            <th>Over Ripening (kg)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($reports as $report): ?>
                            <tr>
                                <td><?php echo $report['Loss Report ID']; ?></td>
                                <td><?php echo htmlspecialchars($report['Week']); ?></td>
                                <td><?php echo $report['Physical Damage']; ?></td>
                                <td><?php echo $report['Spoilage']; ?></td>
                                <td><?php echo $report['Pest Infestation']; ?></td>
                                <td><?php echo $report['Over Ripening']; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </section>
        </main>
    </div>

    <script>
        const weeks = <?php echo json_encode(array_column($reports, 'Week')); ?>;
        const physicalDamageData = <?php echo json_encode(array_column($reports, 'Physical Damage')); ?>;
        const spoilageData = <?php echo json_encode(array_column($reports, 'Spoilage')); ?>;
        const pestInfestationData = <?php echo json_encode(array_column($reports, 'Pest Infestation')); ?>;
        const overRipeningData = <?php echo json_encode(array_column($reports, 'Over Ripening')); ?>;

        const lossCausesCtx = document.getElementById('lossCausesGraph').getContext('2d');
        const lossCausesGraph = new Chart(lossCausesCtx, {
            type: 'line',
            data: {
                labels: weeks,
                datasets: [
                    {
                        label: 'Physical Damage (kg)',
                        data: physicalDamageData,
                        borderColor: 'rgba(58, 90, 64, 1)',
                        fill: false,
                        tension: 0.4
                    },
                    {
                        label: 'Spoilage (kg)',
                        data: spoilageData,
                        borderColor: 'rgba(231, 76, 60, 1)',
                        fill: false,
                        tension: 0.4
                    },
                    {
                        label: 'Pest Infestation (kg)',
                        data: pestInfestationData,
                        borderColor: 'rgba(46, 204, 113, 1)',
                        fill: false,
                        tension: 0.4
                    },
                    {
                        label: 'Over Ripening (kg)',
                        data: overRipeningData,
                        borderColor: 'rgba(241, 196, 15, 1)',
                        fill: false,
                        tension: 0.4
                    }
                ]
            },
            options: {
                responsive: true,
                scales: { y: { beginAtZero: true } }
            }
        });
    </script>
</body>
</html>
