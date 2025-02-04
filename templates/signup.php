<?php
session_start();

// Redirect logged-in users to demo.php
if (isset($_SESSION['user_email'])) {
    header("Location: demo.php");
    exit();
}

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ilovation_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Form submission handling
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstName = $_POST['first_name'];
    $lastName = $_POST['last_name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    $sql = "INSERT INTO users (first_name, last_name, email, password) 
            VALUES ('$firstName', '$lastName', '$email', '$password')";

    if ($conn->query($sql) === TRUE) {
        $_SESSION['user_email'] = $email;
        header("Location: demo.php");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ILOVATION - Sign Up</title>
    <link rel="stylesheet" href="../css/demo.css">
</head>
<body>
    <div class="header">
        <div class="logo">ILOVATION</div>
        <nav class="nav-links">
            <a href="./app.php">Home</a>
            <a href="./about.html">About</a>
            <a href="./contact.html">Contact</a>
            <a href="./demo.php"><button type="button">Book Event</button></a>
            <a href="./signup.php"><button type="button">Sign Up</button></a>
        </nav>
    </div>

    <div class="container">
        <h1>ILOVATION</h1>
        <h3>Sign Up</h3>

        <form action="" method="POST">
            <div class="form-group">
                <label>First Name *</label>
                <input type="text" name="first_name" placeholder="First Name" required>
            </div>

            <div class="form-group">
                <label>Last Name *</label>
                <input type="text" name="last_name" placeholder="Last Name" required>
            </div>

            <div class="form-group">
                <label>Email *</label>
                <input type="email" name="email" placeholder="Email" required>
            </div>

            <div class="form-group">
                <label>Password *</label>
                <input type="password" name="password" placeholder="Password" required>
            </div>

            <div class="form-group">
                <label>Confirm Password *</label>
                <input type="password" name="confirm_password" placeholder="Confirm Password" required>
            </div>

            <button type="submit" class="submit-button">Sign Up</button>
            <div class="register-link">
                    <p>Already have an account? <a href="./signin.php">Sign In</a></p>
                </div>
        </form>
    </div>
</body>
</html>