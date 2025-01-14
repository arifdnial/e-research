<?php
include 'config.php';
session_start();

// Ensure admin is logged in
if ($_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Fetch metrics for dashboard
$totalResearchersQuery = "SELECT COUNT(*) AS total FROM researcher WHERE registration_status = 'registered'";
$resultResearchers = $conn->query($totalResearchersQuery);
$totalResearchers = ($resultResearchers->num_rows > 0) ? $resultResearchers->fetch_assoc()['total'] : 0;

$pendingApprovalsQuery = "SELECT COUNT(*) AS total FROM researcher WHERE registration_status = 'pending'";
$resultPending = $conn->query($pendingApprovalsQuery);
$pendingApprovals = ($resultPending->num_rows > 0) ? $resultPending->fetch_assoc()['total'] : 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Majlis Agama Islam Negeri Sembilan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
        body {
            display: flex;
        }
        .sidebar {
            min-width: 250px;
            max-width: 250px;
            background: #343a40;
            color: white;
            height: 100vh;
            position: fixed;
        }
        .sidebar a {
            color: white;
            text-decoration: none;
            display: block;
            padding: 10px 20px;
        }
        .sidebar a:hover {
            background: #495057;
        }
        .content {
            margin-left: 250px;
            padding: 20px;
            width: 100%;
        }
        .navbar {
            background-color: #343a40;
        }
        .navbar .navbar-brand {
            color: white;
        }
        .navbar .navbar-brand:hover {
            color: #ffc107;
        }
        .card {
            margin-bottom: 20px;
        }
        .stats {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }
        .stats .card {
            flex: 1;
            margin-right: 20px;
        }
        .stats .card:last-child {
            margin-right: 0;
        }
    </style>
</head>
<body>
<!-- Sidebar -->
<div class="sidebar">
    <h4 class="text-center py-3">Dashboard</h4>
    <a href="admin_dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
    <a href="manageresearchers.php"><i class="fas fa-users"></i> Manage Researchers</a>
    <a href="reports.php"><i class="fas fa-file-alt"></i> Reports</a>
    <a href="settings.php"><i class="fas fa-cogs"></i> Settings</a>
    <a href="login.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
</div>


    <!-- Content -->
    <div class="content">
        <!-- Navbar -->
        <nav class="navbar navbar-expand-lg navbar-dark">
            <a class="navbar-brand" href="#">Majlis Agama Islam Negeri Sembilan</a>
        </nav>

        <!-- Dashboard Content -->
        <div class="container mt-4">
            <h3>Admin Dashboard</h3>

            <!-- Stats Section -->
            <div class="stats">
                <div class="card text-white bg-primary">
                    <div class="card-body">
                        <h5 class="card-title">Total Registered Researchers</h5>
                        <h2><?php echo $totalResearchers; ?></h2>
                    </div>
                </div>
              
                <div class="card text-white bg-warning">
                    <div class="card-body">
                        <h5 class="card-title">Pending Approvals</h5>
                        <h2><?php echo $pendingApprovals; ?></h2>
                    </div>
                </div>
            </div>

            <!-- Recent Activity Section -->
            <h4>Recent Activity</h4>
            <div class="card">
                <div class="card-body">
                    <p>No recent activity to display.</p>
                </div>
            </div>

            <!-- Placeholder for Charts -->
            <h4>Analytics</h4>
            <div class="card">
                <div class="card-body">
                    <p>Chart placeholder (e.g., researcher growth or submission trends).</p>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>