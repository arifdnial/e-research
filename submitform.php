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


// Initialize form variables
$accept_date = '';
$appoint_date = '';
$sign_date = '';

// Check if there's existing data for this researcher
if ($researcher_id) {
    $sql = "SELECT * FROM permission WHERE researcher_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $researcher_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // If data exists, populate form variables
    if ($row = $result->fetch_assoc()) {
        $accept_date = $row['accept_date'];
        $appoint_date = $row['appoint_date'];
        $sign_date = $row['sign_date'];
    }
    $stmt->close();
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $upload_dir = __DIR__ . '/uploads/'; // Directory for uploads
    $accept_letter_path = $appoint_letter_path = $sign_path = null;

    // Handle file uploads only if files are provided
    if (!empty($_FILES['accept_letter']['name'])) {
        $accept_letter_path = $upload_dir . basename($_FILES['accept_letter']['name']);
        if (!move_uploaded_file($_FILES['accept_letter']['tmp_name'], $accept_letter_path)) {
            echo "Error: Unable to upload accept_letter.";
        }
    }
    if (!empty($_FILES['appoint_letter']['name'])) {
        $appoint_letter_path = $upload_dir . basename($_FILES['appoint_letter']['name']);
        if (!move_uploaded_file($_FILES['appoint_letter']['tmp_name'], $appoint_letter_path)) {
            echo "Error: Unable to upload appoint_letter.";
        }
    }
    if (!empty($_FILES['sign']['name'])) {
        $sign_path = $upload_dir . basename($_FILES['sign']['name']);
        if (!move_uploaded_file($_FILES['sign']['tmp_name'], $sign_path)) {
            echo "Error: Unable to upload sign.";
        }
    }

    // Collect other form data
    $accept_date = $_POST['accept_date'] ?? '';
    $appoint_date = $_POST['appoint_date'] ?? '';
    $sign_date = $_POST['sign_date'] ?? '';
    if ($researcher_id) {
        // Update or insert database records
        $sql = "INSERT INTO permission (researcher_id, accept_letter, accept_date, appoint_date, appoint_letter, sign, sign_date)
        VALUES (?, ?, ?, ?, ?, ?, ?)
        ON DUPLICATE KEY UPDATE 
        accept_letter = IFNULL(?, accept_letter),
        accept_date = VALUES(accept_date),
        appoint_date = VALUES(appoint_date),
        appoint_letter = IFNULL(?, appoint_letter),
        sign = IFNULL(?, sign),
        sign_date = VALUES(sign_date)";
    
        $stmt = $conn->prepare($sql);
    
        $stmt->bind_param(
            "isssssssss", // 1 integer and 9 strings
            $researcher_id,
            $accept_letter_path,
            $accept_date,
            $appoint_date,
            $appoint_letter_path,
            $sign_path,
            $sign_date,
            $accept_letter_path, // Update statement
            $appoint_letter_path,
            $sign_path
        );
    
        if ($stmt->execute()) {
            echo "<div class='alert alert-success'>Data saved successfully!</div>";
        } else {
            echo "<div class='alert alert-danger'>Error: " . $stmt->error . "</div>";
        }
    
        $stmt->close();
    } else {
        echo "<div class='alert alert-warning'>No researcher ID found in session.</div>";
    }
    
}

$conn->close();


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
                <h2 class="text-center mb-4">Dokumen Sokongan</h2>
                <form action="" method="POST" enctype="multipart/form-data">
                    <div class="form-group mb-4">
                        <label for="accept_letter" class="form-label">Upload Surat Terima Kajian</label>
                        <input type="file" class="form-control" id="accept_letter" name="accept_letter" required>
                    </div>

                    <div class="mb-4">
                        <label for="accept_date" class="form-label">Tarikh Terima Kajian:</label>
                        <input type="date" class="form-control" id="accept_date" name="accept_date"
                            value="<?php echo htmlspecialchars($accept_date); ?>" required>
                    </div>

                    <div class="form-group mb-4">
                        <label for="appoint_letter" class="form-label">Upload Surat Lantikan Kajian</label>
                        <input type="file" class="form-control" id="appoint_letter" name="appoint_letter" required>
                    </div>

                    <div class="mb-4">
                        <label for="appoint_date" class="form-label">Tarikh Lantikan:</label>
                        <input type="date" class="form-control" id="appoint_date" name="appoint_date"
                            value="<?php echo htmlspecialchars($appoint_date); ?>" required>
                    </div>

                    <div class="form-group mb-4">
                        <label for="sign" class="form-label">Upload Tanda Tangan</label>
                        <input type="file" class="form-control" id="sign" name="sign" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="sign_date" class="form-label">Tarikh Tanda Tangan</label>
                        <input type="date" class="form-control" id="sign_date" name="sign_date"
                            value="<?php echo htmlspecialchars($sign_date); ?>" required>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Submit</button>
                </form>
            </div>
        </div>
    </div>
</body>

</html>
