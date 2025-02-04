<?php
session_start();

if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: signup.php");
    exit();
}

// Checking of user whether signed in or not
if (!isset($_SESSION['user_email'])) {
    header("Location: signup.php");
    exit();
}


$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ilovation_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstName = $_POST['first_name'];
    $lastName = $_POST['last_name'];
    $eventName = $_POST['event_name'];
    $email = $_POST['email'];
    $eventType = $_POST['event_type'];
    $startDate = $_POST['start_date'];
    $endDate = $_POST['end_date'];
    $eventLocation = $_POST['event_location'];
    $eventDescription = $_POST['event_description'];

    $targetDir = "../uploads/";
    $targetFile = $targetDir . basename($_FILES["event_image"]["name"]);
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    $check = getimagesize($_FILES["event_image"]["tmp_name"]);
    if ($check !== false) {
        if (move_uploaded_file($_FILES["event_image"]["tmp_name"], $targetFile)) {
            $sql = "INSERT INTO events (first_name, last_name, event_name, email, event_type, start_date, end_date, event_location, event_description, event_image) 
                    VALUES ('$firstName', '$lastName', '$eventName', '$email', '$eventType', '$startDate', '$endDate', '$eventLocation', '$eventDescription', '$targetFile')";

            if ($conn->query($sql) === TRUE) {
                echo "Event created successfully!";
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    } else {
        echo "File is not an image.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ILOVATION - Publish Event</title>
    <link rel="stylesheet" href="../css/demo.css">
</head>
<body>
    <div class="header">
        <div class="logo">ILOVATION</div>
        <nav class="nav-links">
            <a href="./app.php">Home</a>
            <a href="./about.php">About</a>
            <a href="./contact.php">Contact</a>
            <a href="./demo.php"><button type="button">Book Event</button></a>
            <?php if (isset($_SESSION['user_email'])): ?>
                <span class="user-icon">👤 <?php echo $_SESSION['user_email']; ?></span>
                <a href="?logout=true"><button type="button">Logout</button></a>
            <?php else: ?>
                <a href="./signup.php"><button type="button">Sign Up</button></a>
            <?php endif; ?>
        </nav>
    </div>

    <div class="container">
        <h1>ILOVATION</h1>
        <h3>Publish an Event</h3>

        <form action="" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label>Name</label>
                <div class="name-inputs">
                    <input type="text" name="first_name" placeholder="First Name" required>
                    <input type="text" name="last_name" placeholder="Last Name" required>
                </div>
            </div>

            <div class="form-group">
                <label>Event Name *</label>
                <input type="text" name="event_name" placeholder="Event Name" required>
            </div>

            <div class="form-group">
                <label>Email *</label>
                <input type="email" name="email" value="<?php echo $_SESSION['user_email']; ?>" readonly required>
            </div>

            <div class="form-group">
                <label>Event Type *</label>
                <select name="event_type" required>
                    <option value="" disabled selected>Select Here</option>
                    <option value="venue">Venue</option>
                    <option value="online">Online</option>
                </select>
            </div>

            <div class="form-group">
                <label>Start Date and Time *</label>
                <input type="datetime-local" name="start_date" required>
            </div>

            <div class="form-group">
                <label>End Date and Time*</label>
                <input type="datetime-local" name="end_date" required>
            </div>

            <div class="form-group">
                <label>Event Location *</label>
                <input type="text" name="event_location" placeholder="Enter event location" required>
            </div>

            <div class="form-group">
                <label>Event Description *</label>
                <textarea name="event_description" placeholder="Event details" required></textarea>
            </div>

            <div class="form-group">
                <label>Upload Event Image *</label>
                <input type="file" name="event_image" accept="image/*" required>
            </div>

            <button type="submit" class="submit-button">Create Event</button>
        </form>
    </div>
</body>
</html>
