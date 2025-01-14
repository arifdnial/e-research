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
$kategori_pengajian = '';
$peringkat_pengajian = '';
$universiti = '';
$alamat = '';
$poskod = '';
$negeri = '';
$fakulti_jabatan = '';
$kursus_pengajian = '';

// Check if there's existing data for this researcher
if ($researcher_id) {
    $sql = "SELECT * FROM pengajian WHERE researcher_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $researcher_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    // If data exists, populate form variables
    if ($row = $result->fetch_assoc()) {
        $kategori_pengajian = $row['kategoripengajian'];
        $peringkat_pengajian = $row['peringkatpengajian'];
        $universiti = $row['universiti'];
        $alamat = $row['alamat'];
        $poskod = $row['poskod'];
        $negeri = $row['negeri'];
        $fakulti_jabatan = $row['jabatan'];
        $kursus_pengajian = $row['kursuspengajian'];
    }
    $stmt->close();
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect form data with isset checks
    $kategori_pengajian = isset($_POST['kategoripengajian']) ? $_POST['kategoripengajian'] : '';
    $peringkat_pengajian = isset($_POST['peringkatpengajian']) ? $_POST['peringkatpengajian'] : '';
    $universiti = isset($_POST['universiti']) ? $_POST['universiti'] : '';
    $alamat = isset($_POST['alamat']) ? $_POST['alamat'] : '';
    $poskod = isset($_POST['poskod']) ? $_POST['poskod'] : '';
    $negeri = isset($_POST['negeri']) ? $_POST['negeri'] : '';
    $fakulti_jabatan = isset($_POST['jabatan']) ? $_POST['jabatan'] : '';
    $kursus_pengajian = isset($_POST['kursuspengajian']) ? $_POST['kursuspengajian'] : '';

    if ($researcher_id) {
        // Check if the researcher already has a record
        $sql = "SELECT * FROM pengajian WHERE researcher_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $researcher_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Update existing record
            $sql = "UPDATE pengajian SET kategoripengajian = ?, peringkatpengajian = ?, universiti = ?, alamat = ?, poskod = ?, negeri = ?, jabatan = ?, kursuspengajian = ? WHERE researcher_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssisssi", $kategori_pengajian, $peringkat_pengajian, $universiti, $alamat, $poskod, $negeri, $fakulti_jabatan, $kursus_pengajian, $researcher_id);
        } else {
            // Insert new record if no existing record is found
            $sql = "INSERT INTO pengajian (kategoripengajian, peringkatpengajian, universiti, alamat, poskod, negeri, jabatan, kursuspengajian, researcher_id)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssisssi", $kategori_pengajian, $peringkat_pengajian, $universiti, $alamat, $poskod, $negeri, $fakulti_jabatan, $kursus_pengajian, $researcher_id);
        }

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
            <h2 class="text-center mb-4">Pengajian Registration Form</h2>
            
            <form action="pengajian.php" method="POST">
                <!-- Form Fields with Prefilled Values -->
                <div class="form-group mb-4">
                    <label for="kategori_pengajian" class="form-label">Kategori Pengajian</label>
                    <input type="text" class="form-control" id="kategoripengajian" name="kategoripengajian" value="<?php echo htmlspecialchars($kategori_pengajian); ?>" required>
                </div>
                
                <div class="form-group mb-4">
                    <label for="peringkat_pengajian" class="form-label">Peringkat Pengajian</label>
                    <input type="text" class="form-control" id="peringkatpengajian" name="peringkatpengajian" value="<?php echo htmlspecialchars($peringkat_pengajian); ?>" required>
                </div>
                
                <div class="form-group mb-4">
                    <label for="universiti" class="form-label">Universiti</label>
                    <input type="text" class="form-control" id="universiti" name="universiti" value="<?php echo htmlspecialchars($universiti); ?>" required>
                </div>
                
                <div class="form-group mb-4">
                    <label for="alamat" class="form-label">Alamat</label>
                    <input type="text" class="form-control" id="alamat" name="alamat" value="<?php echo htmlspecialchars($alamat); ?>" required>
                </div>
                
                <div class="form-group mb-4">
                    <label for="poskod" class="form-label">Poskod</label>
                    <input type="text" class="form-control" id="poskod" name="poskod" value="<?php echo htmlspecialchars($poskod); ?>" required>
                </div>
                
                <div class="form-group mb-4">
                    <label for="negeri" class="form-label">Negeri</label>
                    <input type="text" class="form-control" id="negeri" name="negeri" value="<?php echo htmlspecialchars($negeri); ?>" required>
                </div>
                
                <div class="form-group mb-4">
                    <label for="fakulti_jabatan" class="form-label">Fakulti/Jabatan</label>
                    <input type="text" class="form-control" id="jabatan" name="jabatan" value="<?php echo htmlspecialchars($fakulti_jabatan); ?>" required>
                </div>
                
                <div class="form-group mb-4">
                    <label for="kursus_pengajian" class="form-label">Kursus Pengajian</label>
                    <input type="text" class="form-control" id="kursuspengajian" name="kursuspengajian" value="<?php echo htmlspecialchars($kursus_pengajian); ?>" required>
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
