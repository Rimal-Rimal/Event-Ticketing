<?php
session_start();
require 'config/db.php';

// Only allow logged-in admins
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// Get event ID
if (!isset($_GET['id'])) {
    header("Location: admin_dashboard.php");
    exit;
}

$event_id = (int)$_GET['id'];

// Fetch existing event
$stmt = $pdo->prepare("SELECT * FROM events WHERE id = ?");
$stmt->execute([$event_id]);
$event = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$event) {
    header("Location: admin_dashboard.php");
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING);
    $price = filter_input(INPUT_POST, 'price', FILTER_SANITIZE_STRING);
    $date = $_POST['event_date'];
    $time = $_POST['event_time'];
    $location = filter_input(INPUT_POST, 'location', FILTER_SANITIZE_STRING);

    // Handle image upload if new file provided
    $image = $event['image']; // keep old image by default
    if (isset($_FILES['image']) && $_FILES['image']['tmp_name']) {
        $target_dir = "../assets/";
        $image = $target_dir . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], $image);
    }

    $update = $pdo->prepare("UPDATE events SET title=?, price=?, event_date=?, event_time=?, location=?, image=? WHERE id=?");
    if ($update->execute([$title, $price, $date, $time, $location, $image, $event_id])) {
        header("Location: admin_dashboard.php?msg=Event+updated+successfully");
        exit;
    } else {
        $error = "Error updating event.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Edit Event</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<div class="center-form">
    <div class="form-container">
        <h2>Edit Event</h2>
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label>Event Title</label>
                <input type="text" name="title" value="<?= htmlspecialchars($event['title']) ?>" required>
            </div>
            <div class="form-group">
                <label>Price</label>
                <input type="text" name="price" value="<?= htmlspecialchars($event['price']) ?>" required>
            </div>
            <div class="form-group">
                <label>Date</label>
                <input type="date" name="event_date" value="<?= $event['event_date'] ?>" required>
            </div>
            <div class="form-group">
                <label>Time</label>
                <input type="time" name="event_time" value="<?= $event['event_time'] ?>" required>
            </div>
            <div class="form-group">
                <label>Location</label>
                <input type="text" name="location" value="<?= htmlspecialchars($event['location']) ?>" required>
            </div>
            <div class="form-group">
                <label>Event Image</label>
                <input type="file" name="image">
                <small>Current: <?= htmlspecialchars($event['image']) ?></small>
            </div>
            <button type="submit" class="submit-btn">Update Event</button>
        </form>
        <?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>
    </div>
</div>
</body>
</html>
