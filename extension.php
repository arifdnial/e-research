<?php
include 'config.php';
session_start();

// Check if the researcher ID is set in the session
$researcher_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

// Initialize form variables
$extension_date_1 = $extension_date_2 = $extension_date_3 = $end_date = '';
$extension_1_name = $extension_2_name = $extension_3_name = '';

// Directory for uploads
$upload_dir = __DIR__ . '/extension/';
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

// Check if there's existing data for this researcher
if ($researcher_id) {
    $sql = "SELECT * FROM extension WHERE research_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $researcher_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $extension_date_1 = $row['extension_date_1'];
        $extension_date_2 = $row['extension_date_2'];
        $extension_date_3 = $row['extension_date_3'];
        $end_date = $row['end_date'];
        $extension_1_name = basename($row['extension_1']);
        $extension_2_name = basename($row['extension_2']);
        $extension_3_name = basename($row['extension_3']);
    }
    $stmt->close();
}

// Check if the form is submitted
$form_submitted = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $extension_1_path = $extension_2_path = $extension_3_path = null;

    // Handle file uploads
    if (!empty($_FILES['extension_1']['name'])) {
        $extension_1_name = time() . '_' . basename($_FILES['extension_1']['name']);
        $extension_1_path = $upload_dir . $extension_1_name;
        move_uploaded_file($_FILES['extension_1']['tmp_name'], $extension_1_path);
    }

    if (!empty($_FILES['extension_2']['name'])) {
        $extension_2_name = time() . '_' . basename($_FILES['extension_2']['name']);
        $extension_2_path = $upload_dir . $extension_2_name;
        move_uploaded_file($_FILES['extension_2']['tmp_name'], $extension_2_path);
    }

    if (!empty($_FILES['extension_3']['name'])) {
        $extension_3_name = time() . '_' . basename($_FILES['extension_3']['name']);
        $extension_3_path = $upload_dir . $extension_3_name;
        move_uploaded_file($_FILES['extension_3']['tmp_name'], $extension_3_path);
    }

    // Collect other form data
    $extension_date_1 = $_POST['extension_date_1'] ?? '';
    $extension_date_2 = $_POST['extension_date_2'] ?? '';
    $extension_date_3 = $_POST['extension_date_3'] ?? '';
    $end_date = $_POST['end_date'] ?? '';

    if ($researcher_id) {
        $sql = "INSERT INTO extension 
                (researcher_id, extension_1, extension_date_1, extension_2, extension_date_2, extension_3, extension_date_3, end_date)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)
                ON DUPLICATE KEY UPDATE 
                extension_1 = VALUES(extension_1),
                extension_date_1 = VALUES(extension_date_1),
                extension_2 = VALUES(extension_2),
                extension_date_2 = VALUES(extension_date_2),
                extension_3 = VALUES(extension_3),
                extension_date_3 = VALUES(extension_date_3),
                end_date = VALUES(end_date)";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param(
            "isssssss",
            $researcher_id,
            $extension_1_path,
            $extension_date_1,
            $extension_2_path,
            $extension_date_2,
            $extension_3_path,
            $extension_date_3,
            $end_date
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
                <h2 class="text-center mb-4">Mohon Lanjutan</h2>
                <form action="" method="POST" enctype="multipart/form-data">
                    <div class="form-group mb-4">
                        <label for="extension_1">Mohon Lanjutan Fasa 1</label>
                        <input type="file" class="form-control" id="extension_1" name="extension_1">
                        <?php if ($extension_1_name): ?>
                            <small>Uploaded file: 
                                <a href="extension/<?php echo htmlspecialchars($extension_1_name); ?>" target="_blank"><?php echo htmlspecialchars($extension_1_name); ?></a>
                            </small>
                        <?php endif; ?>
                    </div>
                    <div class="mb-4">
                        <label for="date_1">Tarikh Mohon Lanjutan 1:</label>
                        <input type="date" class="form-control" id="extension_date_1" name="extension_date_1" value="<?php echo htmlspecialchars($extension_date_1); ?>">
                    </div>
                    <div class="form-group mb-4">
                        <label for="extension_2">Mohon Lanjutan Fasa 2</label>
                        <input type="file" class="form-control" id="extension_2" name="extension_2">
                        <?php if ($extension_2_name): ?>
                            <small>Uploaded file: 
                                <a href="extension/<?php echo htmlspecialchars($extension_2_name); ?>" target="_blank"><?php echo htmlspecialchars($extension_2_name); ?></a>
                            </small>
                        <?php endif; ?>
                    </div>
                    <div class="mb-4">
                        <label for="extension_date_2">Tarikh Mohon Lanjutan Fasa 2:</label>
                        <input type="date" class="form-control" id="extension_date_2" name="extension_date_2" value="<?php echo htmlspecialchars($extension_date_2); ?>">
                    </div>
                    <div class="form-group mb-4">
                        <label for="extension_3">Mohon Lanjutan Fasa 3</label>
                        <input type="file" class="form-control" id="extension_3" name="extension_3">
                        <?php if ($extension_3_name): ?>
                            <small>Uploaded file: 
                                <a href="extension/<?php echo htmlspecialchars($extension_3_name); ?>" target="_blank"><?php echo htmlspecialchars($extension_3_name); ?></a>
                            </small>
                        <?php endif; ?>
                    </div>
                    <div class="form-group mb-3">
                        <label for="extension_date_3">Tarikh Mohon Lanjutan Fasa 3</label>
                        <input type="date" class="form-control" id="extension_date_3" name="extension_date_3" value="<?php echo htmlspecialchars($extension_date_3); ?>">
                    </div>
                    <div class="form-group mb-3">
                        <label for="end_date">Tarikh Akhir</label>
                        <input type="date" class="form-control" id="end_date" name="end_date" value="<?php echo htmlspecialchars($end_date); ?>">
                    </div>_
                    <button type="submit" class="btn btn-primary w-100">Submit</button>
                </form>
            </div>
        </div>
    </div>
</body>

</html>
