<?php
include 'config.php';
session_start();

// Check if the researcher ID is set in the session
$researcher_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
$researcher_name = '';
$researcher_email = '';

// Fetch the researcher's details
if ($researcher_id) {
    $sql = "SELECT name, email FROM researcher WHERE researcher_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $researcher_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $researcher_name = $row['name'];
        $researcher_email = $row['email'];
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Borang Permohonan</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        .navbar {
            background-color: #1f1f1f;
        }
        .navbar-brand, .nav-link {
            color: #f8f9fa !important;
        }
        .offcanvas {
            background-color: #f8f9fa;
        }
        .offcanvas-body img {
            border-radius: 50%;
            width: 80px;
            height: 80px;
            object-fit: cover;
        }
        .offcanvas-body .menu-item {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="#">Majlis Agama Islam Negeri Sembilan</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>
    </nav>

    <div class="container-fluid">
    <div class="row">
        <!-- Offcanvas (Sidebar) -->
        <div class="col-md-3 bg-light p-3">
        <div class="text-center">
    <!-- Greeting Message -->
    <h5 class="mb-3" style="color: #007bff; font-weight: bold;">Welcome to E-research</h5>

    <!-- Display Profile Information -->
    <h6 class="mt-3"><?php echo htmlspecialchars($researcher_name); ?></h6>
    <p><?php echo htmlspecialchars($researcher_email); ?></p>
</div>
                <div class="menu-item">
                    <a class="btn btn-outline-primary w-100 mb-2" href="homepage.php">Home</a>
                    <a class="btn btn-outline-primary w-100 mb-2" href="pengajian.php">Pengajian</a>
                    <a class="btn btn-outline-primary w-100 mb-2" href="penyelia.php">Penyelia</a>
                    <a class="btn btn-outline-primary w-100 mb-2" href="research.php">Kajian</a>
                    <a class="btn btn-outline-primary w-100 mb-2" href="submitform.php">Dokumen Sokongan</a>
                    <a class="btn btn-outline-primary w-100" href="perakuan.php">Perakuan</a>
                </div>
            </div>
    <body>


    <div class="col-md-9 p-4">
        <div class="card shadow-lg border-0">
            <div class="card-body p-5">
                <h2 class="text-center mb-4">Perakuan Form</h2>

                <div class="container">
                    <form action="submit_perakuan.php" method="POST">
                        <div class="box">
                            <input type="checkbox" id="checkbox" name="perakuan" value="1">
                            <label for="checkbox" class="text-placeholder">SAYA BERSETUJU UNTUK MEMBUAT KAJIAN INI DAN AKAN MEMENUHI SEGALA SYARAT YANG DIBERIKAN</label>
                        </div>
                        <button type="submit" class="submit-button">SUBMIT</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>