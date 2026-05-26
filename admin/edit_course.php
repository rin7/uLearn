<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

require_admin();

$id     = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$course = get_course_by_id($id);

if (!$course) {
    redirect(SITE_URL . '/admin/manage_courses.php');
}

$error   = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $desc  = trim($_POST['description'] ?? '');
    $instr = trim($_POST['instructor'] ?? '');
    $weeks = (int)($_POST['duration_weeks'] ?? 1);

    if ($title === '' || $instr === '') {
        $error = 'Title and Instructor are required.';
    } else {
        $stmt = $conn->prepare("UPDATE courses SET title=?, description=?, instructor=?, duration_weeks=? WHERE id=?");
        $stmt->bind_param('sssii', $title, $desc, $instr, $weeks, $id);
        $stmt->execute();
        $stmt->close();
        $success = 'Course updated successfully.';
        $course  = get_course_by_id($id);
    }
}

$page_title = 'Edit Course';
include '../includes/header.php';
?>

<h2>Edit Course</h2>

<?php if ($error): ?>
    <div class="msg-error"><?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>
<?php if ($success): ?>
    <div class="msg-success"><?php echo htmlspecialchars($success); ?></div>
<?php endif; ?>

<form method="post" action="edit_course.php?id=<?php echo $id; ?>">
<table class="form-table">
    <tr>
        <td>Title:</td>
        <td><input type="text" name="title" value="<?php echo htmlspecialchars($course['title']); ?>"></td>
    </tr>
    <tr>
        <td>Description:</td>
        <td><textarea name="description"><?php echo htmlspecialchars($course['description']); ?></textarea></td>
    </tr>
    <tr>
        <td>Instructor:</td>
        <td><input type="text" name="instructor" value="<?php echo htmlspecialchars($course['instructor']); ?>"></td>
    </tr>
    <tr>
        <td>Duration (weeks):</td>
        <td><input type="text" name="duration_weeks" value="<?php echo (int)$course['duration_weeks']; ?>"></td>
    </tr>
    <tr>
        <td></td>
        <td><input type="submit" value="Update Course"></td>
    </tr>
</table>
</form>

<p><a href="manage_courses.php">&laquo; Back to Courses</a></p>

<?php include '../includes/footer.php'; ?>
