<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <style>
        /* Styles remain unchanged */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        body {
            display: flex;
            min-height: 100vh;
            align-items: center;
            justify-content: center;
            background-color: #f4f7fa;
        }

        .container {
            display: flex;
            max-width: 900px;
            width: 100%;
            box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
            background-color: white;
            margin: 0 20px;
        }

        .form-section {
            padding: 40px;
            width: 50%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            overflow-y: auto;
        }

        .form-section h1 {
            font-size: 24px;
            margin-bottom: 20px;
            color: #333;
            text-align: center;
        }

        .form-section label {
            font-size: 14px;
            color: #555;
            margin-bottom: 5px;
        }

        .form-section input[type="text"],
        .form-section input[type="email"],
        .form-section input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }

        .form-section button {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            color: white;
            background-color: #4a90e2;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 10px;
        }

        .form-section button:hover {
            background-color: #357ab8;
        }

        .form-section .login-link {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
            color: #555;
        }

        .form-section .login-link a {
            color: #4a90e2;
            text-decoration: none;
        }

        .form-section .login-link a:hover {
            text-decoration: underline;
        }

        .side-image {
            width: 50%;
            background: url('register.jpg') no-repeat center center;
            background-size: cover;
            height: 100vh;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .container {
                flex-direction: column;
                max-width: 100%;
            }

            .form-section {
                width: 100%;
                padding: 20px;
            }

            .side-image {
                width: 100%;
                height: 300px;
            }
        }

        @media (max-width: 480px) {
            .form-section h1 {
                font-size: 20px;
            }

            .form-section input[type="text"],
            .form-section input[type="email"],
            .form-section input[type="password"],
            .form-section button {
                padding: 12px;
                font-size: 14px;
            }

            .form-section .login-link {
                font-size: 12px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="side-image"></div>
        <div class="form-section">
            <h1>Researcher Registration</h1>
            <?php
            session_start();

            // Database connection
            $servername = "127.0.0.1";
            $username = "root"; 
            $password = ""; 
            $dbname = "e_research"; 

            $conn = new mysqli($servername, $username, $password, $dbname);

            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $name = $_POST['name'];
                $phone = $_POST['phone'];
                $email = $_POST['email'];
                $fakulti = $_POST['fakulti'] ?? null;
                $info = $_POST['info'] ?? null;
                $institusi = $_POST['institusi'] ?? null;
                $password = $_POST['password'];
                $confirm_password = $_POST['confirm_password'];

                if ($password !== $confirm_password) {
                    echo "<p style='color: red; text-align: center;'>Passwords do not match!</p>";
                } else {
                    $sql = "INSERT INTO researcher (name, phone, email, fakulti, info, institusi, password)
                            VALUES ('$name', '$phone', '$email', '$fakulti', '$info', '$institusi', '$password')";

                    if ($conn->query($sql) === TRUE) {
                        echo "<p style='color: green; text-align: center;'>Registration successful. Please <a href='login.php'>login</a>.</p>";
                        header("Location: login.php");
                        exit();
                    } else {
                        echo "<p style='color: red; text-align: center;'>Error: " . $conn->error . "</p>";
                    }
                }
            }

            $conn->close();
            ?>
            <form action="register.php" method="POST">
                <label for="name">Nama Penuh</label>
                <input type="text" id="name" name="name" placeholder="Nama Penuh" required>

                <label for="email">Emel</label>
                <input type="email" id="email" name="email" placeholder="Email address" required>

                <label for="phone">No Telefon</label>
                <input type="text" id="phone" name="phone" placeholder="No Telefon" required>

                <label for="password">Katalaluan</label>
                <input type="password" id="password" name="password" placeholder="Katalaluan" required>

                <label for="confirm_password">Pengesahan Katalaluan</label>
                <input type="password" id="confirm_password" name="confirm_password" placeholder="Pengesahan Katalaluan" required>

                <label for="fakulti">Fakulti</label>
                <input type="text" id="fakulti" name="fakulti" placeholder="Fakulti" required>

                <label for="info">Info</label>
                <input type="text" id="info" name="info" placeholder="Info" required>

                <label for="institusi">Institusi</label>
                <input type="text" id="institusi" name="institusi" placeholder="Institusi" required>

                <button type="submit">Sign Up</button>
            </form>
            <div class="login-link">
                Sudah ada Akaun? <a href="login.php">Daftar Masuk</a>
            </div>
        </div>
    </div>
</body>
</html>
