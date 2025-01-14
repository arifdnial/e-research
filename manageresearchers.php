<?php
include 'config.php';
session_start();

// Ensure admin is logged in
if ($_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Handle approve/reject/delete actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];
    $researcherId = $_POST['researcher_id'];

    if ($action === 'approve') {
        $updateQuery = "UPDATE researcher SET registration_status = 'registered' WHERE researcher_id = ?";
    } elseif ($action === 'reject') {
        $updateQuery = "UPDATE researcher SET registration_status = 'rejected' WHERE researcher_id = ?";
    } elseif ($action === 'delete') {
        $updateQuery = "DELETE FROM researcher WHERE researcher_id = ?";
    }

    if (isset($updateQuery)) {
        $stmt = $conn->prepare($updateQuery);
        $stmt->bind_param("i", $researcherId);
        $stmt->execute();
        $stmt->close();

        // Get the updated total of registered researchers
        $totalResearchersQuery = "SELECT COUNT(*) AS total FROM researcher WHERE registration_status = 'registered'";
        $resultResearchers = $conn->query($totalResearchersQuery);
        $totalResearchers = ($resultResearchers->num_rows > 0) ? $resultResearchers->fetch_assoc()['total'] : 0;

        // Return the updated count as JSON
        echo json_encode(['totalResearchers' => $totalResearchers]);

        exit();
    }
}

// Fetch all researchers' data (for displaying)
$query = "
    SELECT 
        r.researcher_id, r.email, r.jenis_permohonan, r.kategori, r.no_matriks, r.tarikh_lahir, 
        r.jantina, r.kewarganegaraan, r.pembiayaan, r.registration_status
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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Researchers - Majlis Agama Islam Negeri Sembilan</title>
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
        <a href="login.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>

    <!-- Content -->
    <div class="content">
        <div class="container mt-4">
            <h3>Manage Researchers</h3>
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th>#</th>
                            <th>Researcher Information</th>
                            <th>Status</th>
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
                                    <td>
                                        <?php echo ucfirst(htmlspecialchars($row['registration_status'])); ?>
                                    </td>
                                    <td class="text-center">
                                        <form method="post" style="display: inline;" class="action-form">
                                            <input type="hidden" name="researcher_id" value="<?php echo $row['researcher_id']; ?>">
                                            <button name="action" value="approve" class="btn btn-sm btn-success">Approve</button>
                                            <button name="action" value="reject" class="btn btn-sm btn-warning">Reject</button>
                                            <button name="action" value="delete" class="btn btn-sm btn-danger">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="text-center">No researchers found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).on('submit', '.action-form', function(e) {
            e.preventDefault(); // Prevent default form submission
            var form = $(this);
            var researcherId = form.find('input[name="researcher_id"]').val();
            var action = form.find('button[name="action"]').val();

            $.ajax({
                url: 'manageresearchers.php',
                type: 'POST',
                data: {
                    action: action,
                    researcher_id: researcherId
                },
                success: function(response) {
                    // Parse JSON response
                    var data = JSON.parse(response);
                    // Update the total registered researchers count dynamically
                    $('#totalResearchers').text(data.totalResearchers);
                }
            });
        });
    </script>
</body>
</html>
