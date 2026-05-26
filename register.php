<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';

if (is_logged_in()) {
    redirect(SITE_URL . '/index.php');
}

$error   = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username  = trim($_POST['username'] ?? '');
    $password  = trim($_POST['password'] ?? '');
    $confirm   = trim($_POST['confirm'] ?? '');
    $full_name = trim($_POST['full_name'] ?? '');
    $email     = trim($_POST['email'] ?? '');

    if ($username === '' || $password === '' || $full_name === '' || $email === '') {
        $error = 'All fields are required.';
    } elseif (strlen($username) < 4) {
        $error = 'Username must be at least 4 characters.';
    } elseif ($password !== $confirm) {
        $error = 'Passwords do not match.';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } else {
        $check_stmt = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ? LIMIT 1");
        $check_stmt->bind_param('ss', $username, $email);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();
        $check_stmt->close();
        if ($check_result && $check_result->num_rows > 0) {
            $error = 'Username or email is already taken.';
        } else {
            $hash = password_hash($password, PASSWORD_BCRYPT);
            $ins  = $conn->prepare("INSERT INTO users (username, password, full_name, email, role) VALUES (?, ?, ?, ?, 'student')");
            $ins->bind_param('ssss', $username, $hash, $full_name, $email);
            $ins->execute();
            if ($ins->affected_rows === 1) {
                $success = 'Registration successful! You can now <a href="login.php">login</a>.';
            } else {
                $error = 'Registration failed. Please try again.';
            }
            $ins->close();
        }
    }
}

$page_title = 'Register';
include 'includes/header.php';
?>

<h2>Student Registration</h2>

<?php if ($error): ?>
    <div class="msg-error"><?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>
<?php if ($success): ?>
    <div class="msg-success"><?php echo $success; ?></div>
<?php endif; ?>

<?php if (!$success): ?>
<form method="post" action="register.php">
<table class="form-table">
    <tr>
        <td>Full Name:</td>
        <td><input type="text" name="full_name" value="<?php echo htmlspecialchars($_POST['full_name'] ?? ''); ?>"></td>
    </tr>
    <tr>
        <td>Username:</td>
        <td><input type="text" name="username" value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>"></td>
    </tr>
    <tr>
        <td>Email:</td>
        <td><input type="email" name="email" value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>"></td>
    </tr>
    <tr>
        <td>Password:</td>
        <td><input type="password" name="password"></td>
    </tr>
    <tr>
        <td>Confirm Password:</td>
        <td><input type="password" name="confirm"></td>
    </tr>
    <tr>
        <td></td>
        <td><input type="submit" value="Register"></td>
    </tr>
</table>
</form>
<p>Already have an account? <a href="login.php">Login here</a></p>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>
