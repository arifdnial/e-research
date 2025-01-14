<?php
include 'config.php';
session_start();

// Check if the researcher ID is set in the session
$researcher_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

// Initialize form variables
$date_1 = $date_2 = $date_3 = '';
$report_1_name = $report_2_name = $report_3_name = $final_report_name = '';

// Directory for uploads
$upload_dir = __DIR__ . '/result/';
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

// Check if there's existing data for this researcher
if ($researcher_id) {
    $sql = "SELECT * FROM result WHERE researcher_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $researcher_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $date_1 = $row['date_1'];
        $date_2 = $row['date_2'];
        $date_3 = $row['date_3'];
        $report_1_name = basename($row['report_1']);
        $report_2_name = basename($row['report_2']);
        $report_3_name = basename($row['report_3']);
        $final_report_name = basename($row['final_report']);
    }
    $stmt->close();
}

// Check if the form is submitted
$form_submitted = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $report_1_path = $report_2_path = $report_3_path = $final_report_path = null;

    // Handle file uploads
    if (!empty($_FILES['report_1']['name'])) {
        $report_1_name = time() . '_' . basename($_FILES['report_1']['name']);
        $report_1_path = $upload_dir . $report_1_name;
        move_uploaded_file($_FILES['report_1']['tmp_name'], $report_1_path);
    }

    if (!empty($_FILES['report_2']['name'])) {
        $report_2_name = time() . '_' . basename($_FILES['report_2']['name']);
        $report_2_path = $upload_dir . $report_2_name;
        move_uploaded_file($_FILES['report_2']['tmp_name'], $report_2_path);
    }

    if (!empty($_FILES['report_3']['name'])) {
        $report_3_name = time() . '_' . basename($_FILES['report_3']['name']);
        $report_3_path = $upload_dir . $report_3_name;
        move_uploaded_file($_FILES['report_3']['tmp_name'], $report_3_path);
    }

    if (!empty($_FILES['final_report']['name'])) {
        $final_report_name = time() . '_' . basename($_FILES['final_report']['name']);
        $final_report_path = $upload_dir . $final_report_name;
        move_uploaded_file($_FILES['final_report']['tmp_name'], $final_report_path);
    }

    // Collect other form data
    $date_1 = $_POST['date_1'] ?? '';
    $date_2 = $_POST['date_2'] ?? '';
    $date_3 = $_POST['date_3'] ?? '';

    if ($researcher_id) {
        $sql = "INSERT INTO result 
                (researcher_id, report_1, date_1, report_2, date_2, report_3, date_3, final_report)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)
                ON DUPLICATE KEY UPDATE 
                report_1 = VALUES(report_1),
                date_1 = VALUES(date_1),
                report_2 = VALUES(report_2),
                date_2 = VALUES(date_2),
                report_3 = VALUES(report_3),
                date_3 = VALUES(date_3),
                final_report = VALUES(final_report)";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param(
            "isssssss",
            $researcher_id,
            $report_1_path,
            $date_1,
            $report_2_path,
            $date_2,
            $report_3_path,
            $date_3,
            $final_report_path
        );

        if ($stmt->execute()) {
            $form_submitted = true;
        } else {
            die("Error: " . $stmt->error);
        }

        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>File Upload Form</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script>
        function showSuccessMessage() {
            alert('Your file has been submitted successfully!');
        }
    </script>
    <style>
        /* Navbar styling */
        .navbar {
            background-color: #1f1f1f;
            /* Dark background */
        }

        .navbar-brand {
            color: #f8f9fa !important;
            font-weight: bold;
        }

        .navbar-nav .nav-link {
            color: #f8f9fa !important;
            /* Light text color */
            font-weight: 500;
            padding: 10px 15px;
            border-radius: 5px;
            transition: all 0.3s ease;
        }

        .navbar-nav .nav-link:hover {
            background-color: #343a40;
            color: #e9ecef !important;
            /* Light hover effect */
        }

        .navbar-toggler {
            border-color: #f8f9fa;
        }

        .navbar-toggler-icon {
            color: #f8f9fa;
        }

        .navbar-nav .nav-item.active .nav-link {
            background-color: #495057;
            color: #ffffff !important;
        }
    </style>
</head>

<body>
     <!-- Custom Navbar Styles -->
     <style>
        .navbar-custom {
            background-color: #113c25;
            /* Custom green background */
            border-bottom: 2px solid #113c25;
            /* Add a bottom border for separation */
        }

        .navbar-custom .navbar-brand {
            font-size: 1.5rem;
            font-weight: bold;
            letter-spacing: 2px;
        }

        .navbar-custom .nav-link {
            font-size: 1.1rem;
            margin-left: 20px;
            transition: all 0.3s ease;
        }

        .navbar-custom .nav-link:hover {
            color: #296745;
            /* Hover color on links */
            text-decoration: underline;
        }

        .navbar-custom .navbar-toggler-icon {
            background-color: white;
            /* Custom color for hamburger icon */
        }

        /* Make sure the navbar is always on top */
        .navbar-custom.fixed-top {
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1050;
        }

        /* Optional: Change color for active link */
        .navbar-custom .nav-item.active .nav-link {
            color: #ffd700;
            font-weight: bold;
        }
    </style>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-custom fixed-top">
        <div class="container">
            <a class="navbar-brand" href="#">Majlis Agama Islam Negeri Sembilan</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                    <li class="nav-item active">
                        <a class="nav-link" href="homepage.php">Back</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <?php if ($form_submitted): ?>
        <script>
            showSuccessMessage();
        </script>
    <?php endif; ?>

    <div class="container mt-5 pt-5">
        <div class="card shadow-lg border-0">
            <div class="card-body p-5">
                <h2 class="text-center mb-4">Upload Report</h2>
                <form action="" method="POST" enctype="multipart/form-data">
                    <div class="form-group mb-4">
                        <label for="report_1">Upload Laporan 1</label>
                        <input type="file" class="form-control" id="report_1" name="report_1">
                        <?php if ($report_1_name): ?>
                            <small>Uploaded file: 
                                <a href="result/<?php echo htmlspecialchars($report_1_name); ?>" target="_blank"><?php echo htmlspecialchars($report_1_name); ?></a>
                            </small>
                        <?php endif; ?>
                    </div>
                    <div class="mb-4">
                        <label for="date_1">Tarikh Upload Laporan 1:</label>
                        <input type="date" class="form-control" id="date_1" name="date_1" value="<?php echo htmlspecialchars($date_1); ?>">
                    </div>
                    <div class="form-group mb-4">
                        <label for="report_2">Upload Laporan 2</label>
                        <input type="file" class="form-control" id="report_2" name="report_2">
                        <?php if ($report_2_name): ?>
                            <small>Uploaded file: 
                                <a href="result/<?php echo htmlspecialchars($report_2_name); ?>" target="_blank"><?php echo htmlspecialchars($report_2_name); ?></a>
                            </small>
                        <?php endif; ?>
                    </div>
                    <div class="mb-4">
                        <label for="date_2">Tarikh Upload Laporan 2:</label>
                        <input type="date" class="form-control" id="date_2" name="date_2" value="<?php echo htmlspecialchars($date_2); ?>">
                    </div>
                    <div class="form-group mb-4">
                        <label for="report_3">Upload Laporan 3</label>
                        <input type="file" class="form-control" id="report_3" name="report_3">
                        <?php if ($report_3_name): ?>
                            <small>Uploaded file: 
                                <a href="result/<?php echo htmlspecialchars($report_3_name); ?>" target="_blank"><?php echo htmlspecialchars($report_3_name); ?></a>
                            </small>
                        <?php endif; ?>
                    </div>
                    <div class="form-group mb-3">
                        <label for="date_3">Tarikh Upload Laporan 3</label>
                        <input type="date" class="form-control" id="date_3" name="date_3" value="<?php echo htmlspecialchars($date_3); ?>">
                    </div>
                    <div class="form-group mb-4">
                        <label for="final_report">Upload Laporan Akhir</label>
                        <input type="file" class="form-control" id="final_report" name="final_report">
                        <?php if ($final_report_name): ?>
                            <small>Uploaded file: 
                                <a href="result/<?php echo htmlspecialchars($final_report_name); ?>" target="_blank"><?php echo htmlspecialchars($final_report_name); ?></a>
                            </small>
                        <?php endif; ?>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Submit</button>
                </form>
            </div>
        </div>
    </div>
</body>

</html>
