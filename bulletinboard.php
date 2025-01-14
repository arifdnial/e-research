<?php
include 'config.php';
session_start();

// Ensure admin is logged in
if ($_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Handle bulletin insertion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
    $title = $_POST['title'];
    $posted_by = $_POST['posted_by'];
    $date = $_POST['date'];

    $query = "INSERT INTO bulletin (title, posted_by, date) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sss", $title, $posted_by, $date);

    if ($stmt->execute()) {
        $message = "Bulletin added successfully.";
        $message_type = "success";
    } else {
        $message = "Error adding bulletin: " . $stmt->error;
        $message_type = "danger";
    }

    $stmt->close();
}

// Handle bulletin deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    $id = $_POST['id'];

    $query = "DELETE FROM bulletin WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $message = "Bulletin deleted successfully.";
        $message_type = "success";
    } else {
        $message = "Error deleting bulletin: " . $stmt->error;
        $message_type = "danger";
    }

    $stmt->close();
}

// Fetch bulletins
$query = "SELECT * FROM bulletin ORDER BY date DESC";
$result = $conn->query($query);
$bulletins = $result->fetch_all(MYSQLI_ASSOC);

$conn->close();
?>

<<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Majlis Agama Islam Negeri Sembilan</title>
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
        .navbar {
            background-color: #343a40;
        }
        .navbar .navbar-brand {
            color: white;
        }
        .navbar .navbar-brand:hover {
            color: #ffc107;
        }
        .btn-action {
            margin: 0 2px;
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
        <a href="bulletinboard.php"><i class="fas fa-cogs"></i> Bulletin Board</a>
        <a href="login.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>
    <div class="container mt-5">
        <h3 class="mb-4">Bulletin Board</h3>

        <!-- Display message -->
        <?php if (isset($message)): ?>
            <div class="alert alert-<?php echo $message_type; ?> alert-dismissible fade show" role="alert">
                <?php echo htmlspecialchars($message); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <!-- Bulletin Form -->
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <h5 class="card-title mb-3">Add a New Bulletin</h5>
                <form method="POST" action="bulletinboard.php">
                    <input type="hidden" name="action" value="add">
                    <div class="mb-3">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" class="form-control" id="title" name="title" placeholder="Enter bulletin title" required>
                    </div>
                    <div class="mb-3">
                        <label for="posted_by" class="form-label">Posted By</label>
                        <input type="text" class="form-control" id="posted_by" name="posted_by" placeholder="Enter poster's name" required>
                    </div>
                    <div class="mb-3">
                        <label for="date" class="form-label">Date</label>
                        <input type="date" class="form-control" id="date" name="date" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>

        <!-- Display Bulletins -->
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="card-title mb-3">Existing Bulletins</h5>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead class="thead-dark">
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
                                        <td>
                                            <form method="POST" action="bulletinboard.php" style="display:inline;">
                                                <input type="hidden" name="action" value="delete">
                                                <input type="hidden" name="id" value="<?php echo $bulletin['id']; ?>">
                                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="text-center">No bulletins found.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>