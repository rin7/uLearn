<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

require_admin();

$error   = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title    = trim($_POST['title'] ?? '');
    $desc     = trim($_POST['description'] ?? '');
    $instr    = trim($_POST['instructor'] ?? '');
    $weeks    = (int)($_POST['duration_weeks'] ?? 1);

    if ($title === '' || $instr === '') {
        $error = 'Title and Instructor are required.';
    } else {
        $stmt = $conn->prepare("INSERT INTO courses (title, description, instructor, duration_weeks) VALUES (?, ?, ?, ?)");
        $stmt->bind_param('sssi', $title, $desc, $instr, $weeks);
        $stmt->execute();
        if ($stmt->affected_rows === 1) {
            $success = 'Course added successfully. <a href="manage_courses.php">View all courses</a>.';
        } else {
            $error = 'Failed to add course.';
        }
        $stmt->close();
    }
}

$page_title = 'Add Course';
include '../includes/header.php';
?>

<h2>Add New Course</h2>

<?php if ($error): ?>
    <div class="msg-error"><?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>
<?php if ($success): ?>
    <div class="msg-success"><?php echo $success; ?></div>
<?php endif; ?>

<?php if (!$success): ?>
<form method="post" action="add_course.php">
<table class="form-table">
    <tr>
        <td>Title:</td>
        <td><input type="text" name="title" value="<?php echo htmlspecialchars($_POST['title'] ?? ''); ?>"></td>
    </tr>
    <tr>
        <td>Description:</td>
        <td><textarea name="description"><?php echo htmlspecialchars($_POST['description'] ?? ''); ?></textarea></td>
    </tr>
    <tr>
        <td>Instructor:</td>
        <td><input type="text" name="instructor" value="<?php echo htmlspecialchars($_POST['instructor'] ?? ''); ?>"></td>
    </tr>
    <tr>
        <td>Duration (weeks):</td>
        <td><input type="text" name="duration_weeks" value="<?php echo (int)($_POST['duration_weeks'] ?? 4); ?>"></td>
    </tr>
    <tr>
        <td></td>
        <td><input type="submit" value="Add Course"></td>
    </tr>
</table>
</form>
<?php endif; ?>

<p><a href="manage_courses.php">&laquo; Back to Courses</a></p>

<?php include '../includes/footer.php'; ?>
