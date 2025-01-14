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
        r.researcher_id, r.email, r.jenis_permohonan, r.kategori, r.no_matriks, r.tarikh_lahir, r.jantina, 
        r.kewarganegaraan, r.pembiayaan
    FROM researcher r
";

$stmt = $conn->prepare($query);
$stmt->execute();
$result = $stmt->get_result();

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}
$stmt->close();
$conn->close();
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
    <!-- Sidebar -->
    <div class="sidebar">
        <h4 class="text-center py-3">Dashboard</h4>
        <a href="admin_dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
        <a href="manageresearchers.php"><i class="fas fa-users"></i> Manage Researchers</a>
        <a href="reports.php"><i class="fas fa-file-alt"></i> Reports</a>
        <a href="settings.php"><i class="fas fa-cogs"></i> Settings</a>
        <a href="bulletinboard.php"><i class="fas fa-cogs"></i> Bulletin Board</a>
        <a href="login.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>

    

    <!-- Content -->
    <div class="content">
        <!-- Navbar -->
        <nav class="navbar navbar-expand-lg navbar-dark">
            <a class="navbar-brand" href="#">Majlis Agama Islam Negeri Sembilan</a>
        </nav>

        <div class="container mt-4">
            <h3>Researcher Submissions</h3>

            <div class="table-responsive">
                <table id="researcherTable" class="table table-striped table-bordered table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th>#</th>
                            <th>Researcher Information</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($data)): ?>
                            <?php foreach ($data as $index => $row): ?>
                                <tr>
                                    <td><?php echo $index + 1; ?></td>
                                    <td>
                                        <strong>Email:</strong> <?php echo htmlspecialchars($row['email']); ?><br>
                                        <strong>Jenis Permohonan:</strong> <?php echo htmlspecialchars($row['jenis_permohonan']); ?><br>
                                        <strong>Kategori:</strong> <?php echo htmlspecialchars($row['kategori']); ?><br>
                                        <strong>No Matriks:</strong> <?php echo htmlspecialchars($row['no_matriks']); ?><br>
                                    </td>
                                    <td class="text-center">
                                        <a href="senaraikajianadmin.php?researcher_id=<?php echo $row['researcher_id']; ?>" class="btn btn-sm btn-info">View Submissions</a>
                                        <a href="borangpermohonan.php?researcher_id=<?php echo $row['researcher_id']; ?>" class="btn btn-sm btn-warning">Edit</a>
                                        <a href="mailto:<?php echo htmlspecialchars($row['email']); ?>" class="btn btn-sm btn-primary">Email</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="3" class="text-center">No researchers found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
