<?php
session_start();
require 'config/db.php';

$error = "";

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $password = $_POST['password'] ?? '';

    if (!$email || !$password) {
        $error = "Please enter a valid email and password";
    } else {
        $stmt = $pdo->prepare("SELECT * FROM admin WHERE email = ?");
        $stmt->execute([$email]);
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($admin && password_verify($password, $admin['password'])) {
            session_regenerate_id(true);

            // Make keys match admin_dashboard check
            $_SESSION['user_id'] = $admin['id'];   // must be user_id
            $_SESSION['name'] = $admin['name'];
            $_SESSION['role'] = 'admin';

            header("Location: admin_dashboard.php");
            exit;
        } else {
            $error = "Invalid email or password";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Login</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<div class="center-form">
    <div class="form-container">
        <h2>Admin Login</h2>
        <form method="POST">
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" placeholder="Enter your email" required
                       value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>">
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" placeholder="Enter your password" required>
            </div>
            <button type="submit" class="submit-btn">Log In</button>
        </form>
        <?php if ($error): ?>
            <p style="color:red; text-align:center; margin-top:10px;"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>
    </div>
</div>
</body>
</html>
