<?php
include 'config.php';
session_start();

// Ensure admin is logged in
if ($_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Fetch all researchers' data
$query = "
    SELECT 
        researcher_id, email, jenis_permohonan, kategori, no_matriks, tarikh_lahir, jantina, 
        kewarganegaraan, pembiayaan
    FROM researcher
";
$result = $conn->query($query);

$data = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}
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
        .btn-action {
            margin: 0 2px;
        }
    </style>
</head>
<body>
<div class="sidebar">
    <h4 class="text-center py-3">Dashboard</h4>
    <a href="admin_dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
    <a href="manageresearchers.php"><i class="fas fa-users"></i> Manage Researchers</a>
    <a href="reports.php"><i class="fas fa-file-alt"></i> Reports</a>
    <a href="settings.php"><i class="fas fa-cogs"></i> Settings</a>
    <a href="login.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
</div>

    <div class="container report-container">
        <h1 class="text-center">Researcher Report</h1>
        <a href="generate_pdf.php" class="btn btn-primary mb-3">Download PDF</a>
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead class="thead-dark">
                    <tr>
                        <th>#</th>
                        <th>Email</th>
                        <th>Jenis Permohonan</th>
                        <th>Kategori</th>
                        <th>No Matriks</th>
                        <th>Tarikh Lahir</th>
                        <th>Jantina</th>
                        <th>Kewarganegaraan</th>
                        <th>Pembiayaan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($data)): ?>
                        <?php foreach ($data as $index => $row): ?>
                            <tr>
                                <td><?php echo $index + 1; ?></td>
                                <td><?php echo htmlspecialchars($row['email']); ?></td>
                                <td><?php echo htmlspecialchars($row['jenis_permohonan']); ?></td>
                                <td><?php echo htmlspecialchars($row['kategori']); ?></td>
                                <td><?php echo htmlspecialchars($row['no_matriks']); ?></td>
                                <td><?php echo htmlspecialchars($row['tarikh_lahir']); ?></td>
                                <td><?php echo htmlspecialchars($row['jantina']); ?></td>
                                <td><?php echo htmlspecialchars($row['kewarganegaraan']); ?></td>
                                <td><?php echo htmlspecialchars($row['pembiayaan']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="9" class="text-center">No researchers found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
