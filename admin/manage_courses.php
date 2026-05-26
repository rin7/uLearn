<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

require_admin();

$message = '';
$error   = '';

// Handle delete
if (isset($_GET['delete'])) {
    $del_id = (int)$_GET['delete'];
    $conn->query("DELETE FROM courses WHERE id = $del_id");
    $message = 'Course deleted.';
}

$courses = get_all_courses();
$page_title = 'Manage Courses';
include '../includes/header.php';
?>

<h2>Manage Courses</h2>
<p><a href="add_course.php" class="btn">+ Add New Course</a></p>

<?php if ($message): ?>
    <div class="msg-success"><?php echo htmlspecialchars($message); ?></div>
<?php endif; ?>

<?php if (empty($courses)): ?>
    <p>No courses found.</p>
<?php else: ?>
<table>
    <tr>
        <th>ID</th>
        <th>Title</th>
        <th>Instructor</th>
        <th>Weeks</th>
        <th>Actions</th>
    </tr>
    <?php foreach ($courses as $c): ?>
    <tr>
        <td><?php echo (int)$c['id']; ?></td>
        <td><?php echo htmlspecialchars($c['title']); ?></td>
        <td><?php echo htmlspecialchars($c['instructor']); ?></td>
        <td><?php echo (int)$c['duration_weeks']; ?></td>
        <td>
            <a href="edit_course.php?id=<?php echo $c['id']; ?>">Edit</a>
            &nbsp;|&nbsp;
            <a href="manage_courses.php?delete=<?php echo $c['id']; ?>" onclick="return confirm('Delete this course?');">Delete</a>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
<?php endif; ?>

<?php include '../includes/footer.php'; ?>
