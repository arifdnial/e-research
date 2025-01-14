<?php
session_start();
require 'config.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch the current researcher's information from the "researcher" table
$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM researcher WHERE researcher_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$researcher = $result->fetch_assoc();

// Generate dynamic registration number if not already set
$year = date("y"); // Get the current year in two-digit format (e.g., "21" for 2021)
$registration_number = "PPM-" . $researcher['researcher_id'] . "-" . $year;

// If the registration number is not set, update it in the database
if (empty($researcher['no_daftar'])) {
    $researcher['no_daftar'] = $registration_number;

    $updateQuery = "UPDATE researcher SET no_daftar = ? WHERE researcher_id = ?";
    $updateStmt = $conn->prepare($updateQuery);
    $updateStmt->bind_param("si", $registration_number, $user_id);
    $updateStmt->execute();
    $updateStmt->close();
}

// Check if form data is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and fetch form data
    $jenis_permohonan = $_POST['jenis_permohonan'] ?? null;
    $kategori = $_POST['kategori'] ?? null;
    $no_matriks = $_POST['no_matriks'] ?? null;
    $tarikh_lahir = $_POST['tarikh_lahir'] ?? null;
    $jantina = $_POST['jantina'] ?? null;
    $kewarganegaraan = $_POST['kewarganegaraan'] ?? null;
    $pembiayaan = $_POST['pembiayaan'] ?? null;

    // Check if the data already exists (update) or needs to be inserted (new)
    if ($researcher) {
        // Update the existing data
        $query = "UPDATE researcher SET jenis_permohonan = ?, kategori = ?, no_matriks = ?, tarikh_lahir = ?, jantina = ?, kewarganegaraan = ?, pembiayaan = ? WHERE researcher_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sssssssi", $jenis_permohonan, $kategori, $no_matriks, $tarikh_lahir, $jantina, $kewarganegaraan, $pembiayaan, $user_id);
    } else {
        // Insert new data
        $query = "INSERT INTO researcher (researcher_id, jenis_permohonan, kategori, no_matriks, tarikh_lahir, jantina, kewarganegaraan, pembiayaan) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("isssssss", $user_id, $jenis_permohonan, $kategori, $no_matriks, $tarikh_lahir, $jantina, $kewarganegaraan, $pembiayaan);
    }

    // Execute the query
    if ($stmt->execute()) {
        // Update session variables
        $_SESSION['jenis_permohonan'] = $jenis_permohonan;
        $_SESSION['kategori'] = $kategori;
        $_SESSION['no_matriks'] = $no_matriks;
        $_SESSION['tarikh_lahir'] = $tarikh_lahir;
        $_SESSION['jantina'] = $jantina;
        $_SESSION['kewarganegaraan'] = $kewarganegaraan;
        $_SESSION['pembiayaan'] = $pembiayaan;

        echo "<script>alert('Data has been saved successfully!');</script>";
    } else {
        echo "<script>alert('Error: " . $stmt->error . "');</script>";
    }
    $stmt->close();
}

// Set session data or fallback to database values for the form
$jenis_permohonan = $_SESSION['jenis_permohonan'] ?? $researcher['jenis_permohonan'];
$kategori = $_SESSION['kategori'] ?? $researcher['kategori'];
$no_matriks = $_SESSION['no_matriks'] ?? $researcher['no_matriks'];
$tarikh_lahir = $_SESSION['tarikh_lahir'] ?? $researcher['tarikh_lahir'];
$jantina = $_SESSION['jantina'] ?? $researcher['jantina'];
$kewarganegaraan = $_SESSION['kewarganegaraan'] ?? $researcher['kewarganegaraan'];
$pembiayaan = $_SESSION['pembiayaan'] ?? $researcher['pembiayaan'];
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
<nav class="navbar navbar-expand-lg navbar-custom fixed-top shadow-sm">
    <div class="container">
        <a class="navbar-brand text-white" href="#">MAJLIS AGAMA ISLAM NEGERI SEMBILAN</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <!-- Dropdown Menu -->
                <li class="nav-item dropdown">
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <a class="dropdown-item text-danger" href="login.php">
                                <i class="bi bi-box-arrow-right"></i> Logout
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>

<style>
    /* Dropdown Menu Customizations */
    .dropdown-menu {
        background-color: #113c25;
        border: none;
        border-radius: 8px;
    }

    .dropdown-menu .dropdown-item {
        color: white;
        transition: all 0.3s ease;
    }

    .dropdown-menu .dropdown-item:hover {
        background-color: #296745;
        color: #ffd700;
    }

    .dropdown-menu .dropdown-divider {
        border-color: #296745;
    }

    .dropdown-menu .text-danger {
        font-weight: bold;
    }
</style>

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
        /* Hover color on links (golden) */
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
<div class="container-fluid">
    <div class="row">
        <!-- Fixed Sidebar -->
        <div class="col-md-3 bg-light p-3" style="position: fixed; top: 70px; bottom: 0; left: 0; overflow-y: auto;">
            <div class="text-center">
                <!-- Greeting Message -->
                <h5 class="mb-3" style="color: #007bff; font-weight: bold;">Welcome to E-research</h5>
                <h6 class="mt-3"><?php echo htmlspecialchars($researcher['name']); ?></h6>
                <p><?php echo htmlspecialchars($researcher['email']); ?></p>
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


            <!-- Form Section -->
            <div class="col-md-9 offset-md-3"><br><br><br><br>
                <div class="card shadow-lg border-0">
        <div class="card-body p-5">
                <h4 class="text-center">Researcher Information</h4>
                <p><strong>Name:</strong> <?php echo htmlspecialchars($researcher['name']); ?></p>
                <p><strong>Phone:</strong> <?php echo htmlspecialchars($researcher['phone']); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($researcher['email']); ?></p>
                <p><strong>Faculty:</strong> <?php echo htmlspecialchars($researcher['fakulti']); ?></p>
                <p><strong>Institution:</strong> <?php echo htmlspecialchars($researcher['institusi']); ?></p>
                <p><strong>Registration Number:</strong> <?php echo htmlspecialchars($researcher['no_daftar']); ?></p>

                <form method="POST" action="">
    <!-- Form Fields -->
    <div class="mb-4">
        <label class="form-label fw-bold">Jenis Permohonan:</label>
        <div class="d-flex gap-3">
            <div class="form-check">
                <input class="form-check-input" type="radio" name="jenis_permohonan" id="individu" value="individu" <?php echo $jenis_permohonan == 'individu' ? 'checked' : ''; ?> required>
                <label class="form-check-label" for="individu">Individu</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="jenis_permohonan" id="kumpulan" value="kumpulan" <?php echo $jenis_permohonan == 'kumpulan' ? 'checked' : ''; ?>>
                <label class="form-check-label" for="kumpulan">Kumpulan</label>
            </div>
        </div>
    </div>

    <div class="mb-4">
        <label class="form-label fw-bold">Kategori:</label>
        <div class="d-flex gap-3">
            <div class="form-check">
                <input class="form-check-input" type="radio" name="kategori" id="pelajar" value="pelajar" <?php echo $kategori == 'pelajar' ? 'checked' : ''; ?> required>
                <label class="form-check-label" for="pelajar">Pelajar</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="kategori" id="pensyarah" value="pensyarah" <?php echo $kategori == 'pensyarah' ? 'checked' : ''; ?>>
                <label class="form-check-label" for="pensyarah">Pensyarah</label>
            </div>
        </div>
    </div>

    <div class="mb-4">
        <label for="no_matriks" class="form-label fw-bold">No Matriks:</label>
        <input type="text" class="form-control" id="no_matriks" name="no_matriks" value="<?php echo htmlspecialchars($no_matriks); ?>" placeholder="Enter No Matriks" required>
    </div>

    <div class="mb-4">
        <label for="tarikh_lahir" class="form-label fw-bold">Tarikh Lahir:</label>
        <input type="date" class="form-control" id="tarikh_lahir" name="tarikh_lahir" value="<?php echo htmlspecialchars($tarikh_lahir); ?>" required>
    </div>

    <div class="mb-4">
        <label class="form-label fw-bold">Jantina:</label>
        <div class="d-flex gap-3">
            <div class="form-check">
                <input class="form-check-input" type="radio" name="jantina" id="lelaki" value="lelaki" <?php echo $jantina == 'lelaki' ? 'checked' : ''; ?> required>
                <label class="form-check-label" for="lelaki">Lelaki</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="jantina" id="perempuan" value="perempuan" <?php echo $jantina == 'perempuan' ? 'checked' : ''; ?>>
                <label class="form-check-label" for="perempuan">Perempuan</label>
            </div>
        </div>
    </div>

    <div class="mb-4">
        <label for="kewarganegaraan" class="form-label fw-bold">Kewarganegaraan:</label>
        <select class="form-select" id="kewarganegaraan" name="kewarganegaraan" required>
            <option value="" disabled>Select Country</option>
            <option value="Malaysia" <?php echo $kewarganegaraan == 'Malaysia' ? 'selected' : ''; ?>>Malaysia</option>
            <option value="Indonesia" <?php echo $kewarganegaraan == 'Indonesia' ? 'selected' : ''; ?>>Indonesia</option>
            <option value="Singapore" <?php echo $kewarganegaraan == 'Singapore' ? 'selected' : ''; ?>>Singapore</option>
            <option value="Thailand" <?php echo $kewarganegaraan == 'Thailand' ? 'selected' : ''; ?>>Thailand</option>
        </select>
    </div>

    <div class="mb-4">
        <label class="form-label fw-bold">Pembiayaan:</label>
        <div class="d-flex gap-3">
            <div class="form-check">
                <input class="form-check-input" type="radio" name="pembiayaan" id="biasiswa" value="biasiswa" <?php echo $pembiayaan == 'biasiswa' ? 'checked' : ''; ?> required>
                <label class="form-check-label" for="biasiswa">Biasiswa</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="pembiayaan" id="sendiri" value="sendiri" <?php echo $pembiayaan == 'sendiri' ? 'checked' : ''; ?>>
                <label class="form-check-label" for="sendiri">Sendiri</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="pembiayaan" id="pinjaman" value="pinjaman" <?php echo $pembiayaan == 'pinjaman' ? 'checked' : ''; ?>>
                <label class="form-check-label" for="pinjaman">Pinjaman</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="pembiayaan" id="lainlain" value="lainlain" <?php echo $pembiayaan == 'lainlain' ? 'checked' : ''; ?>>
                <label class="form-check-label" for="lainlain">Lain-lain</label>
            </div>
        </div>
    </div>

            <div class="text-center">
                    <button type="submit" class="btn btn-primary btn-lg mt-3 px-5">Submit</button>
                </div>
        </form>
    </div>
</div>


    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
