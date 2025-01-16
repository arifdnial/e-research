<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem E-Penyelidikan</title>
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
            background-color: #f8f9fa;
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

        @media (max-width: 768px) {
            .background {
                flex-direction: column;
                padding: 10px;
                gap: 20px;
            }

            .login-box {
                max-width: 90%;
                margin-bottom: 20px;
            }
        }
    </style>
</head>
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
