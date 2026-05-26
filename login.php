<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';

if (is_logged_in()) {
    redirect(SITE_URL . '/index.php');
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($username === '' || $password === '') {
        $error = 'Please enter username and password.';
    } else {
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? LIMIT 1");
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result && $result->num_rows === 1) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id']  = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role']     = $user['role'];
                if ($user['role'] === 'admin') {
                    redirect(SITE_URL . '/admin/index.php');
                } else {
                    redirect(SITE_URL . '/index.php');
                }
            } else {
                $error = 'Invalid username or password.';
            }
        } else {
            $error = 'Invalid username or password.';
        }
        $stmt->close();
    }
}

$page_title = 'Login';
include 'includes/header.php';
?>

<h2>Student / Admin Login</h2>

<?php if ($error): ?>
    <div class="msg-error"><?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>

<form method="post" action="login.php">
<table class="form-table">
    <tr>
        <td>Username:</td>
        <td><input type="text" name="username" value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>"></td>
    </tr>
    <tr>
        <td>Password:</td>
        <td><input type="password" name="password"></td>
    </tr>
    <tr>
        <td></td>
        <td><input type="submit" value="Login"></td>
    </tr>
</table>
</form>
<p>Don't have an account? <a href="register.php">Register here</a></p>

<?php include 'includes/footer.php'; ?>
