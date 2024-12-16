<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <!-- Include the centralized theme CSS -->
    <link rel="stylesheet" href="../theme.css">
    <link rel="stylesheet" href="admin.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="admin-container">
        <!-- Sidebar -->
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

        <!-- Main Content -->
        <main class="content">
            <header class="head">
                <h1>Dashboard</h1>
            </header>
            <section class="dashboard">
                <!-- Graph Section -->
                <div class="graph-section">
                    <h2>Loss Tracking Over Time</h2>
                    <canvas id="lossGraph"></canvas>
                </div>

                <!-- Stats Section -->
                <div class="stats">
                    <div class="stat-box">
                        <h3>Total Loss</h3>
                        <p>5,632 kg</p>
                    </div>
                    <div class="stat-box">
                        <h3>Loss Reduction</h3>
                        <p>+12% compared to last season</p>
                    </div>
                </div>
            </section>
        </main>
    </div>

    <script>
        // Render the graph dynamically
        const ctx = document.getElementById('lossGraph').getContext('2d');
    
        // Example loss data
        const lossData = [12, 19, 3, 5, 2, 3];
    
        // Determine color based on loss value
        const graphColors = lossData.map(loss => {
            return loss > 10 ? 'rgba(255, 99, 132, 1)' : 'rgba(88, 129, 87, 1)'; // Red if high loss, Green if low
        });
    
        const lossGraph = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [{
                    label: 'Loss Over Time',
                    data: lossData,
                    backgroundColor: 'rgba(88, 129, 87, 0.2)', // Green shade for area under curve
                    borderColor: function (context) {
                        return graphColors[context.dataIndex]; // Dynamic border color
                    },
                    borderWidth: 2,
                    pointBackgroundColor: graphColors,
                    pointBorderColor: 'rgba(255, 255, 255, 1)',
                    pointBorderWidth: 2,
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'top' }
                },
                scales: {
                    x: { title: { display: true, text: 'Month' } },
                    y: { beginAtZero: true, title: { display: true, text: 'Loss (kg)' } }
                }
            }
        });
    </script>
    
</body>
</html>
