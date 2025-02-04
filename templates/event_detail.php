<?php
session_start();

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ilovation_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if ID is passed
if (!isset($_GET['id'])) {
    die("Event ID is missing.");
}

$event_id = intval($_GET['id']);
if ($event_id <= 0) {
    die("Invalid Event ID: " . htmlspecialchars($_GET['id']));
}

// Fetch event details using prepared statement
$stmt = $conn->prepare("SELECT * FROM events WHERE id = ?");
$stmt->bind_param("i", $event_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $event = $result->fetch_assoc();
} else {
    echo "Event not found for ID: " . $event_id;
    exit();
}

$conn->close();

$previous_page = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'app.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($event['event_name']); ?> - Event Details</title>
    <link rel="stylesheet" href="../css/event_detail.css">
    <style>
        .hero-section {
            background: url('<?php echo htmlspecialchars($event['event_image']); ?>') no-repeat center center/cover;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">ILOVATION</div>
        <nav class="nav-links">
            <a href="./app.php">Home</a>
            <a href="#about-events">About</a>
            <a href="#popular-events">Event</a>
            <a href="./demo.php"><button type="button">Book Event</button></a>
            <?php if (isset($_SESSION['user_email'])): ?>
                <span class="user-icon">👤 <?php echo $_SESSION['user_email']; ?></span>
                <a href="?logout=true"><button type="button">Logout</button></a>
            <?php else: ?>
                <a href="./signup.php"><button type="button">Sign Up</button></a>
            <?php endif; ?>
        </nav>
    </div>

    
    <div class="hero-section">
        <div class="hero-content">
        <a href="<?php echo $previous_page; ?>" class="back-button">&larr; Back</a>
            <h1><?php echo htmlspecialchars($event['event_name']); ?></h1>
            <p><?php echo nl2br(htmlspecialchars($event['event_description'])); ?></p>
            <p><strong>📍</strong> <?php echo htmlspecialchars($event['event_location']); ?></p>
            <button>I'M INTERESTED</button>
        </div>
    </div>

    <div class="event-info">
        <h2>Description</h2>
        <p><?php echo nl2br(htmlspecialchars($event['event_description'])); ?></p>

        <h2>Hours</h2>
        <p><strong>Start Date/Time:</strong> <?php echo date("F j, Y / g:i A", strtotime($event['start_date'])); ?></p>
        <p><strong>End Date/Time:</strong> <?php echo date("F j, Y / g:i A", strtotime($event['end_date'])); ?></p>

        <h2>Event Location</h2>
        <iframe src="https://www.google.com/maps?q=<?php echo urlencode($event['event_location']); ?>&output=embed" width="100%" height="300" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
    </div>

    <div class="social-share">
        <h3>Share With Friends</h3>
        <a href="#"><img src="../assets/img/facebook.png" alt="Facebook"></a>
        <a href="#"><img src="../assets/img/instagram.png" alt="Instagram"></a>
        <a href="#"><img src="../assets/img/linkedin.png" alt="LinkedIn"></a>
    </div>
</body>
</html>
