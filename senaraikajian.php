<?php
include 'config.php';
session_start();

$researcher_id = $_SESSION['user_id'];
$data = [];

if ($researcher_id) {
    // Query to join 'penyelia' (supervisor), 'pengajian' (study), 'research', and 'permission' tables based on researcher_id
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

    // Fetch all the data from the tables
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    $stmt->close();
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Senarai Kajian</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/buttons/1.7.1/css/buttons.bootstrap4.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bulma@0.9.3/css/bulma.min.css" rel="stylesheet">
    <style>
        .dataTables_wrapper .dataTables_paginate .paginate_button {
            padding: 0.2rem 0.5rem;
            margin: 0.1rem;
        }

        .btn-action {
            margin: 0 2px;
        }

        .info-cell {
            font-size: 0.9rem;
            line-height: 1.5;
        }

        .info-cell strong {
            font-weight: 600;
        }
    </style>
</head>

<body>

    <div class="container mt-5">

        <div class="table-responsive">
            <table id="researcherTable" class="table table-striped table-bordered table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th>#</th>
                        <th>Information</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($data)): ?>
                        <?php foreach ($data as $index => $row): ?>
                            <tr>
                                <td><?php echo $index + 1; ?></td>
                                <td class="info-cell">
                                    <strong>Researcher Information:</strong><br>
                                    <strong>Email:</strong> <?php echo htmlspecialchars($row['email']); ?><br>
                                    <strong>Jenis Permohonan:</strong> <?php echo htmlspecialchars($row['jenis_permohonan']); ?><br>
                                    <strong>Kategori:</strong> <?php echo htmlspecialchars($row['kategori']); ?><br>
                                    <strong>No Matriks:</strong> <?php echo htmlspecialchars($row['no_matriks']); ?><br>
                                    <strong>Tarikh Lahir:</strong> <?php echo htmlspecialchars($row['tarikh_lahir']); ?><br>
                                    <strong>Jantina:</strong> <?php echo htmlspecialchars($row['jantina']); ?><br>
                                    <strong>Kewarganegaraan:</strong> <?php echo htmlspecialchars($row['kewarganegaraan']); ?><br>
                                    <strong>Pembiayaan:</strong> <?php echo htmlspecialchars($row['pembiayaan']); ?><br><br>

                                    <strong>Pengajian Information:</strong><br>
                                    <strong>Kategori Pengajian:</strong> <?php echo htmlspecialchars($row['kategoripengajian']); ?><br>
                                    <strong>Peringkat Pengajian:</strong> <?php echo htmlspecialchars($row['peringkatpengajian']); ?><br>
                                    <strong>Universiti:</strong> <?php echo htmlspecialchars($row['universiti']); ?><br>
                                    <strong>Alamat:</strong> <?php echo htmlspecialchars($row['alamat']); ?><br>
                                    <strong>Poskod:</strong> <?php echo htmlspecialchars($row['poskod']); ?><br>
                                    <strong>Negeri:</strong> <?php echo htmlspecialchars($row['negeri']); ?><br>
                                    <strong>Fakulti/Jabatan:</strong> <?php echo htmlspecialchars($row['jabatan']); ?><br>
                                    <strong>Kursus Pengajian:</strong> <?php echo htmlspecialchars($row['kursuspengajian']); ?><br><br>

                                    <strong>Penyelia Information:</strong><br>
                                    <strong>Nama Penyelia:</strong> <?php echo htmlspecialchars($row['namapenyelia']); ?><br>
                                    <strong>Jawatan:</strong> <?php echo htmlspecialchars($row['jawatan']); ?><br>
                                    <strong>Universiti:</strong> <?php echo htmlspecialchars($row['penyelia_universiti']); ?><br>
                                    <strong>No Telefon:</strong> <?php echo htmlspecialchars($row['nophone']); ?><br>
                                    <strong>Email Penyelia:</strong> <?php echo htmlspecialchars($row['penyelia_email']); ?><br><br>
                                  
                                    <strong>Research Information:</strong><br>
                                    <strong>Kod Kajian:</strong> <?php echo htmlspecialchars($row['research_code']); ?><br>
                                    <strong>Kategori Kajian:</strong> <?php echo htmlspecialchars($row['research_category']); ?><br>
                                    <strong>Tajuk Kajian:</strong> <?php echo htmlspecialchars($row['research_title']); ?><br>
                                    <strong>Bajet:</strong> <?php echo htmlspecialchars($row['budget']); ?><br>
                                    <strong>Tarikh Mula:</strong> <?php echo htmlspecialchars($row['start_date']); ?><br>
                                    <strong>Tarikh Tamat:</strong> <?php echo htmlspecialchars($row['end_date']); ?><br>
                                    <strong>Tempoh Kajian:</strong> <?php echo htmlspecialchars($row['research_period']); ?><br><br>
                                
                                    <strong>Permission Information:</strong><br>
                                    <strong>Tarikh Terima:</strong> <?php echo htmlspecialchars($row['accept_date']); ?><br>
                                    <strong>Tarikh Lantikan:</strong> <?php echo htmlspecialchars($row['appoint_date']); ?><br>
                                    <strong>Tarikh Tanda Tangan:</strong> <?php echo htmlspecialchars($row['sign_date']); ?><br>
                                    <strong>Surat Terima:</strong> <a href="<?php echo htmlspecialchars($row['accept_letter']); ?>" target="_blank">View</a><br>
                                    <strong>Surat Lantikan:</strong> <a href="<?php echo htmlspecialchars($row['appoint_letter']); ?>" target="_blank">View</a><br>
                                    <strong>Tanda Tangan:</strong> <a href="<?php echo htmlspecialchars($row['sign']); ?>" target="_blank">View</a>
                                </td>
                                <td class="text-center">
                                    <a href="result.php" class="btn btn-sm btn-success btn-action" title="Upload">
                                        <i class="fas fa-upload"></i> Upload
                                    </a>
                                    <a href="extension.php" class="btn btn-sm btn-warning btn-action" title="Extension">
                                        <i class="fas fa-clock"></i> Extension
                                    </a>
                                    <button class="btn btn-sm btn-danger btn-action" title="Delete">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                    
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3" class="text-center">No records found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div class="progress-section mt-5">
    <h4 class="text-center">Research Progress (Time Left)</h4>
    <progress id="progress-bar" class="progress is-info" value="0" max="100">0%</progress>
    <div class="columns mt-3">
        <div class="column is-half">
            <strong>Start Date:</strong> <span id="start-date"></span>
        </div>
        <div class="column is-half has-text-right">
            <strong>End Date:</strong> <span id="end-date"></span>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.min.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.7.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.html5.min.js"></script>
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

<script>
    $(document).ready(function () {
        // Initialize DataTable
        $('#researcherTable').DataTable({
            dom: 'Bfrtip',
            buttons: [
                {
                    extend: 'copy',
                    className: 'btn btn-sm btn-outline-primary',
                    text: '<i class="fas fa-copy"></i> Copy'
                },
                {
                    extend: 'csv',
                    className: 'btn btn-sm btn-outline-success',
                    text: '<i class="fas fa-file-csv"></i> CSV'
                },
                {
                    extend: 'excel',
                    className: 'btn btn-sm btn-outline-success',
                    text: '<i class="fas fa-file-excel"></i> Excel'
                },
                {
                    extend: 'pdf',
                    className: 'btn btn-sm btn-outline-danger',
                    text: '<i class="fas fa-file-pdf"></i> PDF'
                }
            ]
        });

        // Initialize Progress Bar
        var startDate = new Date("<?php echo $data[0]['start_date'] ?? ''; ?>");
        var endDate = new Date("<?php echo $data[0]['end_date'] ?? ''; ?>");
        var today = new Date();

        var progress = 0;
        if (startDate && endDate && today <= endDate) {
            var totalDuration = endDate - startDate;
            var remainingDuration = endDate - today;

            progress = (remainingDuration / totalDuration) * 100;
            progress = Math.min(Math.max(progress, 0), 100);
        }

        $('#progress-bar').val(100 - progress);
        $('#progress-bar').text(Math.round(100 - progress) + '%');
        $('#start-date').text(startDate.toDateString());
        $('#end-date').text(endDate.toDateString());
    });
</script>

</body>

</html>