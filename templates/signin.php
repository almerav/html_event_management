<?php
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ilovation_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$error = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Query to check user credentials
    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // Verify password
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_email'] = $user['email'];  // Store email in session
            header("Location: app.php");               // Redirect to the homepage
            exit();
        } else {
            $error = "Invalid password. Please try again.";
        }
    } else {
        $error = "No account found with that email.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ilovation - Sign In</title>
    <link rel="stylesheet" href="../css/signin.css">
</head>
<body>
    <div class="header">
        <div class="logo">ILOVATION</div>
        <nav class="nav-links">
            <a href="./app.php">Home</a>
            <a href="./about.html">About</a>
            <a href="./contact.html">Contact</a>
            <a href="./demo.php"><button type="button" class="demo">Book Event</button></a>
            <a href="./signup.php"><button type="button" class="demo">Sign Up</button></a>
        </nav>
    </div>

    <div class="container">
        <div class="wrapper">
            <form id="signin-form" method="POST">
                <h1>Sign In</h1>

                <?php if ($error): ?>
                    <p style="color: red;"><?php echo $error; ?></p>
                <?php endif; ?>

                <div class="input-box">
                    <input type="email" id="signin-email" name="email" placeholder="Email" required autocomplete="email">
                </div>

                <div class="input-box">
                    <input type="password" id="signin-password" name="password" placeholder="Password" required autocomplete="current-password">
                </div>

                <div class="remember-me">
                    <label><input type="checkbox" name="remember"> Remember Me</label>
                </div>

                <button type="submit" class="btn">Sign In</button>

                <div class="register-link">
                    <p>Don't have an account yet? <a href="./signup.php">Sign Up</a></p>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
