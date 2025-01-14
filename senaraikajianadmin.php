
<?php
include 'config.php';
session_start();

// Check if the user is logged in as an admin
$isAdmin = ($_SESSION['role'] === 'admin');

// Get the researcher ID (if provided)
$researcher_id = $isAdmin && isset($_GET['researcher_id']) ? $_GET['researcher_id'] : ($_SESSION['user_id'] ?? null);

$data = [];

if ($researcher_id) {
    $query = "
        SELECT 
            r.email, r.jenis_permohonan, r.kategori, r.no_matriks, r.tarikh_lahir, r.jantina, 
            r.kewarganegaraan, r.pembiayaan, 
            p.kategoripengajian, p.peringkatpengajian, p.universiti, p.alamat, p.poskod, p.negeri, p.jabatan, p.kursuspengajian,
            s.namapenyelia, s.jawatan, s.universiti AS penyelia_universiti, s.nophone, s.emailpenyelia AS penyelia_email,
            re.research_code, re.research_category, re.research_title, re.budget, re.status, re.start_date, re.end_date, re.research_period, re.balance_day,
            pm.accept_date, pm.appoint_date, pm.sign_date, pm.accept_letter, pm.appoint_letter, pm.sign
        FROM researcher r
        LEFT JOIN pengajian p ON r.researcher_id = p.researcher_id
        LEFT JOIN penyelia s ON r.researcher_id = s.researcher_id
        LEFT JOIN research re ON r.researcher_id = re.researcher_id
        LEFT JOIN permission pm ON r.researcher_id = pm.researcher_id
        WHERE r.researcher_id = ?
    ";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $researcher_id);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    $stmt->close();
}

// Handle delete request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
    $researcher_id_to_delete = $_POST['researcher_id'];
    $delete_query = "DELETE FROM researcher WHERE researcher_id = ?";
    $stmt = $conn->prepare($delete_query);
    $stmt->bind_param("i", $researcher_id_to_delete);
    $stmt->execute();
    $stmt->close();
    header("Location: homepage_admin.php");
    exit();
}

// Close connection
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin: Senarai Kajian</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h3>Admin: Manage Research Submissions</h3>


<div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Researcher Info</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($data)): ?>
                        <?php foreach ($data as $index => $row): ?>
                            <tr>
                                <td><?php echo $index + 1; ?></td>
                                <td>
                                    <strong>Email:</strong> <?php echo htmlspecialchars($row['email'] ?? 'N/A'); ?><br>
                                    <strong>Research Title:</strong> <?php echo htmlspecialchars($row['research_title'] ?? 'N/A'); ?><br>
                                    <strong>Start Date:</strong> <?php echo htmlspecialchars($row['start_date'] ?? 'N/A'); ?><br>
                                    <strong>End Date:</strong> <?php echo htmlspecialchars($row['end_date'] ?? 'N/A'); ?><br>
                                </td>
                                <td>
                                    <a href="borangpermohonan.php?researcher_id=<?php echo htmlspecialchars($row['researcher_id'] ?? ''); ?>" class="btn btn-sm btn-warning">Edit</a>
                                    <form method="POST" style="display:inline-block;">
                                        <input type="hidden" name="researcher_id" value="<?php echo htmlspecialchars($row['researcher_id'] ?? ''); ?>">
                                        <button type="submit" name="delete" class="btn btn-sm btn-danger">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3">No records found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>