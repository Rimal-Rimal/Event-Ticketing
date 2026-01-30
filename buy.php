<?php
require 'config/db.php';
$message = '';
$events = $pdo->query("SELECT id, title FROM events ORDER BY event_date ASC")->fetchAll();

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = $_POST['full_name'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $gender = $_POST['gender'] ?? '';
    $event_id = $_POST['event_id'] ?? '';

    $stmt = $pdo->prepare("INSERT INTO tickets (full_name, email, phone, gender, event_id) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$full_name, $email, $phone, $gender, $event_id]);

    $message = "Ticket bought successfully!";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nepa Ticket | Buy Tickets</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php include 'user_navbar.php'; ?>

<div class="center-form">
    <div class="form-container">
        <h2>Buy Ticket</h2>
        <?php if($message): ?>
            <p style="color:green;"><?php echo $message; ?></p>
        <?php endif; ?>
        <form method="POST">
            <div class="form-group">
                <label>Full Name</label>
                <input type="text" name="full_name" placeholder="Enter your name" required>
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" placeholder="Enter your email" required>
            </div>
            <div class="form-group">
                <label>Phone Number</label>
                <input type="tel" name="phone" required>
            </div>
            <div class="form-group">
                <label>Gender</label>
                <div class="gender-options">
                    <label><input type="radio" name="gender" value="Male" required> Male</label>
                    <label><input type="radio" name="gender" value="Female"> Female</label>
                    <label><input type="radio" name="gender" value="Other"> Other</label>
                </div>
            </div>
            <div class="form-group">
                <label>Select Event</label>
                <select name="event_id" required>
                    <option value="">--Select Event--</option>
                    <?php foreach($events as $event): ?>
                        <option value="<?php echo $event['id']; ?>"><?php echo htmlspecialchars($event['title']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="submit-btn">Buy Ticket</button>
        </form>
    </div>
</div>
</body>
</html>
