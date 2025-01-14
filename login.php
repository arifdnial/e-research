<?php
session_start(); // Start the session
// Database connection details
$servername = "127.0.0.1";
$username = "root";
$password = "";
$dbname = "e_research";
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Capture form data
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Verify login for Admin
    $sql = "SELECT * FROM admin WHERE admin_email = ? LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            $_SESSION['user_id'] = $row['admin_id'];
            $_SESSION['role'] = 'admin';
            $_SESSION['email'] = $row['admin_email'];

            // Redirect to admin homepage
            header("Location: homepageadmin.php");
            exit();
        } else {
            $_SESSION['error_message'] = "Invalid admin password";
        }
    } else {
        // Check for researcher if not admin
        $sql = "SELECT * FROM researcher WHERE email = ? LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email); // Bind the email parameter
        $stmt->execute();
        $result = $stmt->get_result();
    
        if ($result->num_rows > 0) {
            // Fetch user data  
            $row = $result->fetch_assoc();
    
            // Compare the stored password directly with the entered password
            if ($password === $row['password']) {
                // Successful login, store session data
                $_SESSION['user_id'] = $row['researcher_id'];
                $_SESSION['email'] = $row['email'];
    
                // Redirect to homepage
                header("Location: homepage.php");
                exit(); // Make sure to exit after redirect
            } else {
                $_SESSION['error_message'] = "Invalid password"; // Set error message
            }
        } else {
            $_SESSION['error_message'] = "User not found"; // Set error message
        }
    }
}

$conn->close();
?>



<!-- HTML code for the login page remains the same as in your previous example -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        body, html {
            height: 100%;
            display: flex;
            flex-direction: column;
            font-family: 'Roboto', sans-serif;
            background-color: #f0f2f5;
        }

        .header {
            background-color: #739945;
            color: white;
            padding: 15px 20px;
            display: flex;
            align-items: center;
        }

        .header .logo {
            height: 50px;
            margin-right: 15px;
        }

        .background {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
            gap: 30px;
            background-image: url('pinterestbaru.jpeg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }

        .login-box {
            background: white;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            text-align: center;
            transition: all 0.3s;
        }

        .login-box h2 {
            margin-bottom: 20px;
            font-size: 26px;
            font-weight: 600;
            color: #333;
        }

        .login-box input[type="email"],
        .login-box input[type="password"] {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border-radius: 8px;
            border: 1px solid #ddd;
            font-size: 16px;
        }

        .role-selection {
            margin: 15px 0;
            display: flex;
            justify-content: space-around;
        }

        .login-button {
            background-color: #4a90e2;
            color: white;
            padding: 12px;
            width: 100%;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 500;
            transition: background-color 0.3s ease;
        }

        .login-button:hover {
            background-color: #357abd;
        }

        .register-link {
            margin-top: 20px;
            font-size: 14px;
        }

        /* Carousel styling */
        #carouselExampleCaptions {
            width: 100%;
            max-width: 600px;
            margin-top: 20px;
        }

        .carousel-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            max-height: 600px;
            border-radius: 10px;
        }

        .carousel-caption {
            background: rgba(0, 0, 0, 0.5);
            padding: 15px;
            border-radius: 8px;
        }

        .carousel-control-prev,
        .carousel-control-next {
            filter: invert(1);
        }

        @media (max-width: 768px) {
            .background {
                flex-direction: column;
                padding: 10px;
                gap: 20px;
            }

            .login-box, #carouselExampleCaptions {
                max-width: 90%;
                margin-bottom: 20px;
            }
        }
        <style>
    .box-header {
        text-align: center;
        margin-bottom: 20px;
    }

    .box-logo {
        width: 300px;
        height: auto;
        margin-bottom: 10px;
    }

    .box-title {
        font-size: 30px;
        font-weight: 600;
        color: #4a90e2;
    }
</style>
    </style>
</head>

<?php if (isset($_SESSION['error_message'])): ?>
    <div aria-live="polite" aria-atomic="true" class="position-relative">
        <div class="toast-container top-0 start-50 translate-middle-x p-3">
            <div class="toast align-items-center text-bg-danger border-0 show" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="toast-header">
                    <strong class="me-auto">Error</strong>
                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
                <div class="toast-body">
                    <?php
                        echo $_SESSION['error_message'];
                        unset($_SESSION['error_message']); // Clear the message after displaying it
                    ?>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>
<body>
    <div class="header">
        <img src="logomains.png" alt="Logo" class="logo">
        <h1 class="h4">MAJLIS AGAMA ISLAM NEGERI SEMBILAN</h1>
    </div>
    <div class="background">
        <!-- Login Box -->
        <div class="login-box">
            <!-- Add the logo and text above the login form -->
            <div class="box-header">
                <img src="mains.png" alt="Logo" class="box-logo">
                <h3 class="box-title">Sistem E-Penyelidikan</h3>
            </div>
        
            <form action="login.php" method="post">
                <div class="mb-3">
                    <input type="email" name="email" class="form-control" placeholder="Enter Email" required>
                </div>
                <div class="mb-3">
                    <input type="password" name="password" class="form-control" placeholder="Enter Password" required>
                </div>
                <button type="submit" class="login-button">Login</button>
            </form>
            <p class="register-link">Don't have an account? <a href="register.php">Register</a></p>
        </div>
    </div>
    
 

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
