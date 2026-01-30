<?php
session_start();
require 'config/db.php';

// Only allow logged-in admins
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// Get the event ID from URL
if (!isset($_GET['id'])) {
    header("Location: admin_dashboard.php");
    exit;
}

$event_id = (int)$_GET['id'];

// Delete the event
$stmt = $pdo->prepare("DELETE FROM events WHERE id = ?");
if ($stmt->execute([$event_id])) {
    header("Location: admin_dashboard.php?msg=Event+deleted+successfully");
    exit;
} else {
    echo "Error deleting event.";
}
