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
$research_code = '';
$research_category = '';
$research_title = '';
$budget = '';
$status = '';
$start_date = '';
$end_date = '';
$research_period = '';
$balance_day = '';

// Check if there's existing data for this researcher
if ($researcher_id) {
    $sql = "SELECT * FROM research WHERE researcher_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $researcher_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // If data exists, populate form variables
    if ($row = $result->fetch_assoc()) {
        $research_code = $row['research_code'];
        $research_category = $row['research_category'];
        $research_title = $row['research_title'];
        $budget = $row['budget'];
        $status = $row['status'];
        $start_date = $row['start_date'];
        $end_date = $row['end_date'];
        $research_period = $row['research_period'];
        $balance_day = $row['balance_day'];
    }
    $stmt->close();
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect form data
    $research_code = isset($_POST['research_code']) ? $_POST['research_code'] : '';
    $research_category = isset($_POST['research_category']) ? $_POST['research_category'] : '';
    $research_title = isset($_POST['research_title']) ? $_POST['research_title'] : '';
    $budget = isset($_POST['budget']) ? $_POST['budget'] : '';
    $status = isset($_POST['status']) ? $_POST['status'] : '';
    $start_date = isset($_POST['start_date']) ? $_POST['start_date'] : '';
    $end_date = isset($_POST['end_date']) ? $_POST['end_date'] : '';
    $research_period = isset($_POST['research_period']) ? $_POST['research_period'] : '';
    $balance_day = isset($_POST['balance_day']) ? $_POST['balance_day'] : '';

    if ($researcher_id) {
        // Check if the researcher already has a record
        $sql = "SELECT * FROM research WHERE researcher_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $researcher_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Update existing record
            $sql = "UPDATE research SET research_code = ?, research_category = ?, research_title = ?, budget = ?, status = ?, start_date = ?, end_date = ?, research_period = ?, balance_day = ? WHERE researcher_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssisssis", $research_code, $research_category, $research_title, $budget, $status, $start_date, $end_date, $research_period, $balance_day, $researcher_id);
        } else {
            // Insert new record if no existing record is found
            $sql = "INSERT INTO research (research_code, research_category, research_title, budget, status, start_date, end_date, research_period, balance_day, researcher_id)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql); 
            $stmt->bind_param("ssssisssii", $research_code, $research_category, $research_title, $budget, $status, $start_date, $end_date, $research_period, $balance_day, $researcher_id);
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
                    <h2 class="text-center mb-4">Kajian Form</h2>

                    <form action="research.php" method="POST">
                        <div class="mb-4">
                            <label class="form-label fw-bold">Kod Jenis Kajian:</label>
                            <div class="d-flex gap-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="research_code" id="PPM"
                                        value="PPM" <?php echo isset($research_code) && $research_code == 'PPM' ? 'checked' : ''; ?> required>
                                    <label class="form-check-label" for="PPM">PPM</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="research_code" id="RFP"
                                        value="RFP" <?php echo isset($research_code) && $research_code == 'RFP' ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="RFP">RFP</label>
                                </div>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-bold">Kategori Kajian:</label>
                            <div class="d-flex gap-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="research_category"
                                        id="KAJIAN DALAMAN" value="KAJIAN DALAMAN" <?php echo isset($research_category) && $research_category == 'KAJIAN DALAMAN' ? 'checked' : ''; ?> required>
                                    <label class="form-check-label" for="KAJIAN DALAMAN">KAJIAN DALAMAN</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="research_category"
                                        id="KAJIAN LUARAN" value="KAJIAN LUARAN" <?php echo isset($research_category) && $research_category == 'KAJIAN LUARAN' ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="KAJIAN LUARAN">KAJIAN LUARAN</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group mb-4">
                            <label for="research_title" class="form-label">Tajuk Kajian</label>
                            <input type="text" class="form-control" id="research_title" name="research_title"
                                value="<?php echo htmlspecialchars($research_title); ?>" required>
                        </div>
                        <div class="form-group mb-4">
                            <label for="budget" class="form-label">Bajet Kajian</label>
                            <input type="text" class="form-control" id="budget" name="budget"
                                value="<?php echo htmlspecialchars($budget); ?>" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="start_date" class="form-label">Tarikh Mula Latihan</label>
                            <input type="date" class="form-control" id="start_date" name="start_date"
                                value="<?php echo htmlspecialchars($start_date); ?>" onchange="calculatePeriod()"
                                required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="end_date" class="form-label">Tarikh Tamat Latihan</label>
                            <input type="date" class="form-control" id="end_date" name="end_date"
                                value="<?php echo htmlspecialchars($end_date); ?>" onchange="calculatePeriod()"
                                required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="research_period" class="form-label">Tempoh Kajian (Hari)</label>
                            <input type="text" class="form-control" id="research_period" name="research_period"
                                value="<?php echo htmlspecialchars($research_period); ?>" readonly>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary btn-lg">Submit</button>
                        </div>
                    </form>
                </div>

                <script>
                    function calculatePeriod() {
                        const startDate = new Date(document.getElementById('start_date').value);
                        const endDate = new Date(document.getElementById('end_date').value);
                        if (startDate && endDate) {
                            const timeDifference = endDate - startDate;
                            const dayDifference = Math.ceil(timeDifference / (1000 * 60 * 60 * 24));
                            if (dayDifference >= 0) {
                                document.getElementById('research_period').value = dayDifference;
                            } else {
                                document.getElementById('research_period').value = "End date must be after start date";
                            }
                        }
                    }
                </script>
                <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
                <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.6.0/dist/umd/popper.min.js"></script>
                <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    </body>

</html>