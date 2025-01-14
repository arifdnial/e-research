<html>
    <body>


    <div class="container-fluid">
    <div class="row">
        <!-- Offcanvas (Sidebar) -->
        <div class="col-md-3 bg-light p-3">
            <div class="text-center">
                <!-- Display Profile Picture -->
                <!-- Wrap the profile picture in a label to make it clickable -->
                <label for="profile_picture_input">
                    <img src="<?php echo isset($researcher['profile_picture']) && file_exists($researcher['profile_picture']) ? $researcher['profile_picture'] : 'https://via.placeholder.com/150'; ?>" alt="Profile Picture" class="rounded-circle" style="width: 150px; height: 150px; object-fit: cover; cursor: pointer;">
                </label>
                
                <!-- Hidden file input -->
                <input type="file" name="profile_picture" id="profile_picture_input" style="display: none;" onchange="this.form.submit();">

                <h6 class="mt-3"><?php echo htmlspecialchars($researcher['name']); ?></h6>
                <p><?php echo htmlspecialchars($researcher['email']); ?></p>
            </div>

            <!-- Other Sidebar Content (Menu) -->
            <div class="menu-item mt-4">
                <a class="btn btn-outline-primary w-100 mb-2" href="homepage.php">Home</a>
                <a class="btn btn-outline-primary w-100 mb-2" href="pengajian.php">Pengajian</a>
                <a class="btn btn-outline-primary w-100 mb-2" href="penyelia.php">Penyelia</a>
                <a class="btn btn-outline-primary w-100 mb-2" href="research.php">Kajian</a>
                <a class="btn btn-outline-primary w-100 mb-2" href="submitform.php">Dokumen Sokongan</a>
                <a class="btn btn-outline-primary w-100" href="perakuan.php">Perakuan</a>
            </div>
        </div>
    </div>
</div>
</body>
</html>
