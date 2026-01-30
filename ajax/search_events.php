<?php
require '../config/db.php';

$q = $_GET['q'] ?? '';
if(trim($q) === "") exit;

$stmt = $pdo->prepare("SELECT * FROM events WHERE title LIKE ? ORDER BY event_date DESC");
$stmt->execute(["%$q%"]);

while ($event = $stmt->fetch()):
?>
<div class="card-box">
    <img class="event-Image" src="assets/<?php echo htmlspecialchars($event['image']); ?>">
    <div class="card-Contents">
        <h1><?php echo htmlspecialchars($event['title']); ?></h1>
        <p style="color: rgb(255,0,85)"><?php echo htmlspecialchars($event['price']); ?></p>
        <div class="card-dateAndTime">
            <img class="calendar" src="assets/calendar.png">
            <p><?php echo date('D, M d', strtotime($event['event_date'])); ?> | </p>
            <img class="clock" src="assets/clock.png">
            <p><?php echo date('h:i A', strtotime($event['event_time'])); ?> | </p>
            <img class="location" src="assets/location-pin.png">
            <p><?php echo htmlspecialchars($event['location']); ?></p>
        </div>
    </div>
    <div class="button2">
        <a href="../buy.php?event_id=<?php echo $event['id']; ?>"><button>Buy Tickets</button></a>
    </div>
</div>
<?php endwhile; ?>
