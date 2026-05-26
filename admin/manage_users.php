<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

require_admin();

$message = '';

// Handle delete
if (isset($_GET['delete'])) {
    $del_id = (int)$_GET['delete'];
    // Prevent deleting yourself
    if ($del_id !== (int)$_SESSION['user_id']) {
        $conn->query("DELETE FROM users WHERE id = $del_id");
        $message = 'User deleted.';
    } else {
        $message = 'You cannot delete your own account.';
    }
}

$result = $conn->query("SELECT id, username, full_name, email, role, created_at FROM users ORDER BY created_at DESC");
$users  = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
}

$page_title = 'Manage Users';
include '../includes/header.php';
?>

<h2>Manage Users</h2>

<?php if ($message): ?>
    <div class="msg-success"><?php echo htmlspecialchars($message); ?></div>
<?php endif; ?>

<table>
    <tr>
        <th>ID</th>
        <th>Username</th>
        <th>Full Name</th>
        <th>Email</th>
        <th>Role</th>
        <th>Registered</th>
        <th>Action</th>
    </tr>
    <?php foreach ($users as $u): ?>
    <tr>
        <td><?php echo (int)$u['id']; ?></td>
        <td><?php echo htmlspecialchars($u['username']); ?></td>
        <td><?php echo htmlspecialchars($u['full_name']); ?></td>
        <td><?php echo htmlspecialchars($u['email']); ?></td>
        <td><?php echo htmlspecialchars($u['role']); ?></td>
        <td><?php echo htmlspecialchars($u['created_at']); ?></td>
        <td>
            <?php if ($u['id'] !== (int)$_SESSION['user_id']): ?>
                <a href="manage_users.php?delete=<?php echo $u['id']; ?>" onclick="return confirm('Delete user <?php echo htmlspecialchars($u['username'], ENT_QUOTES); ?>?');">Delete</a>
            <?php else: ?>
                &mdash;
            <?php endif; ?>
        </td>
    </tr>
    <?php endforeach; ?>
</table>

<?php include '../includes/footer.php'; ?>
