<?php
session_start();

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

// Logout handling
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: signup.php");
    exit();
}

// Fetch all events
$sql = "SELECT event_name, event_location, start_date, event_image FROM events ORDER BY start_date ASC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ILOVATION - Event Management</title>
    <link rel="stylesheet" href="../css/app.css">

    <script>
        function toggleEvents() {
            var eventsContainer = document.getElementById('all-events');
            var toggleButton = document.getElementById('toggle-button');

            if (eventsContainer.classList.contains('hidden')) {
                eventsContainer.classList.remove('hidden');
                toggleButton.textContent = 'Hide Events';
            } else {
                eventsContainer.classList.add('hidden');
                toggleButton.textContent = 'Check Out All Events For You';
            }
        }
    </script>
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
            <div class="hero-text">
                <h1>ILOVATION</h1>
                <p>Event Management</p>
                <a href="./demo.php"><button class="manage-event">Management Event</button></a>
            </div>
            <div class="hero-image">
                
            </div>
        </div>
    </div>

    <div class="popular-events" id="popular-events">
        <h2>Popular Events <span>Near You:</span></h2>
        <div class="events-container">
            <?php
            $counter = 0;
            if ($result->num_rows > 0):
                while ($row = $result->fetch_assoc()):
                    if ($counter < 3): ?>
                        <div class="event-card">
                            <img src="<?php echo $row['event_image']; ?>" alt="Event Image">
                            <h3><?php echo $row['event_name']; ?></h3>
                            <p><?php echo $row['event_location']; ?></p>
                            <p><?php echo date("D, M d", strtotime($row['start_date'])); ?></p>
                        </div>
                    <?php endif; $counter++;
                endwhile;
            else: ?>
                <p>No events found.</p>
            <?php endif; ?>
        </div>

        <button id="toggle-button" class="view-all" onclick="toggleEvents()">Check Out All Events For You</button>

        <div id="all-events" class="hidden">
            <?php
            $result->data_seek(0); // Reset result pointer
            while ($row = $result->fetch_assoc()): ?>
                <div class="event-card">
                    <img src="<?php echo $row['event_image']; ?>" alt="Event Image">
                    <h3><?php echo $row['event_name']; ?></h3>
                    <p><?php echo $row['event_location']; ?></p>
                    <p><?php echo date("D, M d", strtotime($row['start_date'])); ?></p>
                </div>
            <?php endwhile; ?>
        </div>
    </div>

    <div class="about-section" id="about-events">
        <h1>ILOVATION</h1>
        <p>Connecting ideas, people, and possibilities—ILOVATION makes every event more than just a gathering; it’s an experience.</p>
    </div>

    <div class="cta-section">
        <h2>Need to Plan an Event?</h2>
        <button class="create-event">Create an Event</button>
        <button class="find-event">Find Event</button>
    </div>
</body>
</html>

<?php
$conn->close();
?>