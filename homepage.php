<?php
include 'config.php';

// Fetch bulletins
$query = "SELECT * FROM bulletin ORDER BY date DESC";
$result = $conn->query($query);
$bulletins = $result->fetch_all(MYSQLI_ASSOC);

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Researcher Homepage</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f8f9fa;
        }

        .navbar-custom {
            background-color: #113c25;
        }

        .navbar-custom .navbar-brand,
        .navbar-custom .nav-link {
            color: white;
        }

        .dashboard-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border-radius: 15px;
            background: linear-gradient(135deg, #739945, #4b752a);
            color: white;
        }

        .dashboard-card:hover {
            transform: translateY(-10px);
            box-shadow: 0px 15px 30px rgba(0, 0, 0, 0.2);
        }

        .bulletin-table {
            border-radius: 15px;
            background-color: #ffffff;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }

        .bulletin-table th {
            background-color: #739945;
            color: white;
        }

        footer {
            background-color: #343a40;
            color: white;
            padding: 20px 0;
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-custom fixed-top shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="#">MAJLIS AGAMA ISLAM NEGERI SEMBILAN</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">Menu</a>
                        <ul class="dropdown-menu dropdown-menu-end shadow-lg" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="senaraikajian.php">Senarai Kajian</a></li>
                            <li><a class="dropdown-item" href="borangpermohonan.php">Borang Permohonan</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="login.php">Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5 pt-5">
        <!-- Dashboard Section -->
        <h2 class="text-center my-4">Dashboard</h2>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card dashboard-card text-center p-4">
                    <div class="card-body">
                        <h5 class="card-title">Total Studies</h5>
                        <p class="card-text display-6">50</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card dashboard-card text-center p-4">
                    <div class="card-body">
                        <h5 class="card-title">Pending Applications</h5>
                        <p class="card-text display-6">10</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card dashboard-card text-center p-4">
                    <div class="card-body">
                        <h5 class="card-title">Completed Projects</h5>
                        <p class="card-text display-6">25</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="container mt-5 pt-5">
        <h2 class="text-center my-4">Bulletin Board</h2>
        <div class="table-responsive">
            <table class="table table-bordered bulletin-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Title</th>
                        <th>Posted By</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($bulletins)): ?>
                        <?php foreach ($bulletins as $index => $bulletin): ?>
                            <tr>
                                <td><?php echo $index + 1; ?></td>
                                <td><?php echo htmlspecialchars($bulletin['title']); ?></td>
                                <td><?php echo htmlspecialchars($bulletin['posted_by']); ?></td>
                                <td><?php echo htmlspecialchars($bulletin['date']); ?></td>
                                <td><a href="#" class="btn btn-primary btn-sm">View</a></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center">No bulletins available.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    </div>

    <!-- Footer -->
    <footer class="text-center">
        <p>&copy; 2025 Majlis Agama Islam Negeri Sembilan. All Rights Reserved.</p>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>