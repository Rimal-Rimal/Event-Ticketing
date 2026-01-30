<?php
require 'config/db.php';

$search = $_GET['search'] ?? '';
$params = [];
$search_sql = "";

if(trim($search) !== "") {
    $search_sql = " WHERE title LIKE ?";
    $params[] = "%$search%";
}

$stmt = $pdo->prepare("SELECT * FROM events $search_sql ORDER BY event_date DESC");
$stmt->execute($params);
$events = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Nepa Tickets</title>
<link rel="stylesheet" href="style.css">
<link rel="icon" type="image/x-icon" href="assets/Company-Logo.png">
</head>
<body>

<?php include 'user_navbar.php'; ?>

<!-- HERO SECTION -->
<main>
    <div class="box"></div>
    <div class="contents">
        <h1>Online Workshop</h1>
        <div class="dateAndTime">
            <img class="calendar" src="assets/calendar.png" alt="">
            <p>Tue Jan 27 | </p>
            <img class="clock" src="assets/clock.png" alt="">
            <p> 10 am Onwards | </p>
            <img class="location" src="assets/location-pin.png" alt="">
            <p> Kathmandu, Kalanki </p>
        </div>
        <div class="buttons">
            <div class="button1">
               <a href="buy.php"><button>View Details</button></a>
            </div>
            <div class="button2">
                <a href="buy.php"><button>Buy Tickets</button></a>
            </div>
        </div>
    </div>
</main>

<!-- EVENTS SECTION -->
<div class="article-contents">
    <div class="events-heading">
        <h1>Popular Events</h1>
        <form method="GET">
            <div class="searchbar">
                <span><img src="assets/search-icon.png" alt=""></span>
                <input type="text" name="search" class="input-search" placeholder="Search events" value="<?php echo htmlspecialchars($search); ?>">
            </div>
        </form>
    </div>

    <div class="card">
        <?php if(count($events) > 0): ?>
            <?php foreach($events as $event): ?>
                <div class="card-box">
                    <img class="event-Image" src="<?php echo htmlspecialchars($event['image']); ?>" alt="">
                    <div class="card-Contents">
                        <h1><?php echo htmlspecialchars($event['title']); ?></h1>
                        <p style="color: rgb(255, 0, 85);"><?php echo htmlspecialchars($event['price']); ?></p>
                        <div class="card-dateAndTime">
                            <img class="calendar" src="assets/calendar.png" alt="">
                            <p><?php echo htmlspecialchars($event['event_date']); ?> | </p>
                            <img class="clock" src="assets/clock.png" alt="">
                            <p><?php echo htmlspecialchars($event['event_time']); ?> | </p>
                            <img class="location" src="assets/location-pin.png" alt="">
                            <p><?php echo htmlspecialchars($event['location']); ?></p>
                        </div>
                    </div>
                    <div class="button2">
                        <a href="buy.php"><button>Buy Tickets</button></a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No events found.</p>
        <?php endif; ?>
    </div>
</div>

<footer>
    <p>© 2026 Nepa Events. All rights reserved.</p>
</footer>

</body>
</html>
