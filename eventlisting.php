<?php
require 'config/db.php';
$message = '';

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $email = $_POST['email'] ?? '';
    $title = $_POST['title'] ?? '';
    $event_date = $_POST['event_date'] ?? '';
    $event_time = $_POST['event_time'] ?? '';
    $location = $_POST['location'] ?? '';

    if(isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $imagePath = 'assets/' . uniqid() . '.' . $ext;
        move_uploaded_file($_FILES['image']['tmp_name'], $imagePath);

        $stmt = $pdo->prepare("INSERT INTO events (title, event_date, event_time, location, image) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$title, $event_date, $event_time, $location, $imagePath]);

        $message = "Event added successfully!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nepa Events | Event Listing</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php include 'user_navbar.php'; ?>

<div class="eventlisting-page">
    <div class="eventlisting-container">
        <h2 class="eventlisting-title">List Your Event</h2>
        <?php if($message): ?>
            <p style="color:green;"><?php echo $message; ?></p>
        <?php endif; ?>
        <form class="eventlisting-form" method="POST" enctype="multipart/form-data">

            <div class="eventlisting-form-group">
                <label>Full Name</label>
                <input type="text" name="name" placeholder="Enter your name" required>
            </div>

            <div class="eventlisting-form-group">
                <label>Phone Number</label>
                <input type="tel" name="phone" placeholder="+977" required>
            </div>

            <div class="eventlisting-form-group">
                <label>Email</label>
                <input type="email" name="email" placeholder="Enter your email" required>
            </div>

            <div class="eventlisting-form-group">
                <label>Event Name</label>
                <input type="text" name="title" placeholder="Enter your event name" required>
            </div>

            <div class="eventlisting-form-group">
                <label>Event Photo</label>
                <input type="file" name="image" accept="image/*" required>
            </div>

            <div class="eventlisting-form-group">
                <label>Event Date</label>
                <input type="date" name="event_date" required>
            </div>

            <div class="eventlisting-form-group">
                <label>Event Time</label>
                <input type="time" name="event_time" required>
            </div>

            <div class="eventlisting-form-group">
                <label>Event Location</label>
                <input type="text" name="location" placeholder="Enter event location" required>
            </div>

            <button type="submit" class="eventlisting-submit-btn">Submit Event</button>
        </form>
    </div>
</div>
</body>
</html>
