<?php
include('connect_db.php');
// Start session to handle login state
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $role = $_POST['userRole'];
    $userId = $_POST['userId'];
    $password = $_POST['password'];

    // Validate inputs
    if (empty($role) || empty($userId) || empty($password)) {
        echo "<script>alert('All fields are required.'); window.location.href='index.html';</script>";
        exit;
    }

    // Query to check user credentials
    $sql = "SELECT * FROM users WHERE id = ? AND password = ? AND role = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iss", $userId, $password, $role);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Store user data in session
        $_SESSION['userId'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];

        // Redirect to appropriate page
        if ($role === 'admin') {
            header("Location: ./admin/admin.php");
            exit;
        } elseif ($role === 'qa_officer') {
            header("Location: ./qa_officer/inspection.php");
            exit;
        }
    } else {
        // Invalid credentials
        echo "<script>alert('Invalid credentials. Please try again.'); window.location.href='index.html';</script>";
        exit;
    }

    $stmt->close();
}

$conn->close();
?>
