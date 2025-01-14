<?php
include 'config.php';
session_start();

// Ensure admin is logged in
if ($_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}




// If form is submitted, update settings
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $theme = $_POST['theme'];
    $notifications = isset($_POST['notifications']) ? 1 : 0;
    $language = $_POST['language'];

    $updateQuery = "
        UPDATE admin_settings 
        SET theme = ?, notifications = ?, language = ? 
        WHERE admin_id = ?
    ";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param('sisi', $theme, $notifications, $language, $admin_id);
    if ($stmt->execute()) {
        $message = "Settings updated successfully!";
        $stmt->close();
    } else {
        $message = "Failed to update settings.";
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Settings</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h2>Admin Settings</h2>
        <?php if ($message): ?>
            <div class="alert alert-info"><?php echo $message; ?></div>
        <?php endif; ?>
        <form method="POST">
            <div class="form-group">
                <label for="theme">Theme</label>
                <select name="theme" id="theme" class="form-control">
                    <option value="light" <?php echo ($settings['theme'] == 'light') ? 'selected' : ''; ?>>Light</option>
                    <option value="dark" <?php echo ($settings['theme'] == 'dark') ? 'selected' : ''; ?>>Dark</option>
                </select>
            </div>
            <div class="form-group">
                <label for="notifications">Notifications</label>
                <input type="checkbox" name="notifications" id="notifications" <?php echo ($settings['notifications'] == 1) ? 'checked' : ''; ?>>
                <label for="notifications">Enable Notifications</label>
            </div>
            <div class="form-group">
                <label for="language">Preferred Language</label>
                <select name="language" id="language" class="form-control">
                    <option value="English" <?php echo ($settings['language'] == 'English') ? 'selected' : ''; ?>>English</option>
                    <option value="Bahasa Melayu" <?php echo ($settings['language'] == 'Bahasa Melayu') ? 'selected' : ''; ?>>Bahasa Melayu</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Save Changes</button>
        </form>
    </div>
</body>
</html>
