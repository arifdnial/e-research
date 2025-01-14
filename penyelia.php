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
$nama_penyelia = '';
$jawatan = '';
$universiti = '';
$no_phone = '';
$email_penyelia = '';

// Check if there's existing data for this researcher
if ($researcher_id) {
    $sql = "SELECT * FROM penyelia WHERE researcher_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $researcher_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    // If data exists, populate form variables
    if ($row = $result->fetch_assoc()) {
        $nama_penyelia = $row['namapenyelia'];
        $jawatan = $row['jawatan'];
        $universiti = $row['universiti'];
        $no_phone = $row['nophone'];
        $email_penyelia = $row['emailpenyelia'];  // Fetch the email
    }
    $stmt->close();
}
// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect form data with isset checks
    $nama_penyelia = isset($_POST['namapenyelia']) ? $_POST['namapenyelia'] : '';
    $jawatan = isset($_POST['jawatan']) ? $_POST['jawatan'] : '';
    $universiti = isset($_POST['universiti']) ? $_POST['universiti'] : '';
    $no_phone = isset($_POST['nophone']) ? $_POST['nophone'] : '';
    $email_penyelia = isset($_POST['emailpenyelia']) ? $_POST['emailpenyelia'] : '';  // Collect the email

    // Debugging: Check form data
    error_log("Email Penyelia: " . $email_penyelia);  // This will show the email value
    error_log("Form Data: " . print_r($_POST, true));  // This shows all the form data

    // Ensure the researcher ID exists before proceeding with insert or update
    if ($researcher_id) {
        // Check if the researcher already has a record
        $sql = "SELECT * FROM penyelia WHERE researcher_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $researcher_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Update existing record
            $sql = "UPDATE penyelia SET namapenyelia = ?, jawatan = ?, universiti = ?, nophone = ?, emailpenyelia = ? WHERE researcher_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssss", $nama_penyelia, $jawatan, $universiti, $no_phone, $email_penyelia, $researcher_id);
        } else {
            // Insert new record if no existing record is found
            $sql = "INSERT INTO penyelia (namapenyelia, jawatan, universiti, nophone, emailpenyelia, researcher_id)
                    VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssss", $nama_penyelia, $jawatan, $universiti, $no_phone, $email_penyelia, $researcher_id);
        }

        // Debugging: Check the SQL query
        error_log("SQL Query: " . $sql);

        if ($stmt->execute()) {
            echo "<div class='alert alert-success'>Form submitted successfully!</div>";
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

<!-- Form Card -->
<div class="col-md-9 p-4">
    <div class="card shadow-lg border-0">
        <div class="card-body p-5">
            <h2 class="text-center mb-4">Penyelia Registration Form</h2>
            
            <form action="penyelia.php" method="POST">
                <!-- Form Fields with Prefilled Values -->
                <div class="form-group mb-4">
                    <label for="nama_penyelia" class="form-label">Nama Penyelia</label>
                    <input type="text" class="form-control" id="namapenyelia" name="namapenyelia" value="<?php echo htmlspecialchars($nama_penyelia); ?>" required>
                </div>
                
                <div class="form-group mb-4">
                    <label for="jawatan" class="form-label">Jawatan</label>
                    <input type="text" class="form-control" id="jawatan" name="jawatan" value="<?php echo htmlspecialchars($jawatan); ?>" required>
                </div>
                
                <div class="form-group mb-4">
                    <label for="universiti" class="form-label">Universiti</label>
                    <input type="text" class="form-control" id="universiti" name="universiti" value="<?php echo htmlspecialchars($universiti); ?>" required>
                </div>
                
                <div class="form-group mb-4">
                    <label for="nophone" class="form-label">Nombor Telefon</label>
                    <input type="text" class="form-control" id="nophone" name="nophone" value="<?php echo htmlspecialchars($no_phone); ?>" required>
                </div>
                
                <div class="form-group mb-4">
                    <label for="emailpenyelia" class="form-label">Email</label>
                    <input type="text" class="form-control" id="emailpenyelia" name="emailpenyelia" value="<?php echo htmlspecialchars($email_penyelia); ?>" required>
                </div>

                <div class="text-center">
                    <button type="submit" class="btn btn-primary btn-lg mt-3 px-5">Submit</button>
                </div> 
            </form>
        </div>
    </div>
</div>

<!-- Bootstrap JS and dependencies -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.6.0/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>