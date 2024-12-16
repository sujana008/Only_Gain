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

// Handle form submission for adding inspection reports
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['addReport'])) {
    $inspectionDate = $_POST['inspection_date'];
    $temperature = $_POST['temperature'];
    $humidity = $_POST['humidity'];
    $defects = $_POST['defects'];
    $hygieneRating = $_POST['hygiene_rating'];

    $sql = "INSERT INTO inspection (InspectionDate, Temperature, Humidity, `Defect/Damage`, HygieneRating) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("siisi", $inspectionDate, $temperature, $humidity, $defects, $hygieneRating);

    if ($stmt->execute()) {
        echo "<script>alert('Inspection report added successfully!');</script>";
    } else {
        echo "<script>alert('Error: " . $stmt->error . "');</script>";
    }

    $stmt->close();
}

// Fetch inspection reports
$sql = "SELECT * FROM inspection ORDER BY InspectionDate DESC";
$result = $conn->query($sql);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inspection - QA Officer</title>
    <link rel="stylesheet" href="../theme.css">
    <link rel="stylesheet" href="./qa_officer.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="qa-officer-container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <h2>QA Officer Panel</h2>
            <nav>
                <ul>
                    <li><a href="inspections.html" class="active">Inspections</a></li>
                    <li><a href="loss_tracking_qa_officer.html">Loss Tracking</a></li>
                    <li><a href="real_time_monitoring_qa_officer.html">Real Time Monitoring</a></li>
                    <li><a href="../index.html">Log Out</a></li>
                </ul>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="content">
            <header class="head">
                <h1>Inspection</h1>
            </header>

            <!-- Graph Section -->
            <section class="graphs-section">
                <div class="graph">
                    <canvas id="inspectionsPerYearGraph"></canvas>
                </div>
                <div class="graph">
                    <canvas id="inspectionsPerMonthGraph"></canvas>
                </div>
            </section>

            <!-- Inspection Reports Section -->
            <section class="inspection-reports">
                <h2>Inspection Reports</h2>
                <button class="btn-primary" id="add-report-btn">Add Inspection Report</button>
                <table>
                    <thead>
                        <tr>
                            <th>Inspection Date</th>
                            <th>Temperature</th>
                            <th>Humidity</th>
                            <th>Defects/Damage</th>
                            <th>Hygiene Rating</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result->num_rows > 0): ?>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?= htmlspecialchars($row['InspectionDate']) ?></td>
                                    <td><?= htmlspecialchars($row['Temperature']) ?></td>
                                    <td><?= htmlspecialchars($row['Humidity']) ?></td>
                                    <td><?= htmlspecialchars($row['Defect/Damage']) ?></td>
                                    <td><?= htmlspecialchars($row['HygieneRating']) ?></td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5">No inspection reports found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </section>

            <!-- Add New Report Modal -->
            <div id="report-modal" class="modal">
                <div class="modal-content">
                    <span class="close">&times;</span>
                    <h3>Add Inspection Report</h3>
                    <form method="POST" id="new-report-form">
                        <label for="inspection-date">Inspection Date:</label>
                        <input type="date" name="inspection_date" id="inspection-date" required>
                        <label for="temperature">Temperature:</label>
                        <input type="number" name="temperature" id="temperature" placeholder="Enter temperature" required>
                        <label for="humidity">Humidity:</label>
                        <input type="number" name="humidity" id="humidity" placeholder="Enter humidity" required>
                        <label for="defects">Defects/Damage:</label>
                        <textarea name="defects" id="defects" placeholder="Enter defects or damage details" required></textarea>
                        <label for="hygiene-rating">Hygiene Rating:</label>
                        <input type="number" name="hygiene_rating" id="hygiene-rating" min="1" max="5" placeholder="Rate hygiene (1-5)" required>
                        <button type="submit" name="addReport" class="btn-primary">Add Report</button>
                    </form>
                </div>
            </div>
        </main>
    </div>

    <script>
        // Modal Logic
        const modal = document.getElementById('report-modal');
        const openModalBtn = document.getElementById('add-report-btn');
        const closeModal = document.querySelector('.modal .close');

        // Open Modal
        openModalBtn.addEventListener('click', () => {
            modal.style.display = 'flex';
        });

        // Close Modal
        closeModal.addEventListener('click', () => {
            modal.style.display = 'none';
        });

        // Close Modal on Outside Click
        window.addEventListener('click', (event) => {
            if (event.target === modal) {
                modal.style.display = 'none';
            }
        });
    </script>
</body>
</html>
