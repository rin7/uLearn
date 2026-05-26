<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';

$id     = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$course = get_course_by_id($id);

if (!$course) {
    header('HTTP/1.0 404 Not Found');
    $page_title = 'Course Not Found';
    include 'includes/header.php';
    echo '<p class="msg-error">Course not found.</p>';
    include 'includes/footer.php';
    exit;
}

$chapters = get_chapters_by_course($id);
$quizzes  = get_quizzes_by_course($id);
$enrolled = false;
$message  = '';

if (is_logged_in()) {
    $enrolled = is_enrolled($_SESSION['user_id'], $id);

    // Handle enroll action
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['enroll']) && !$enrolled) {
        $uid = (int)$_SESSION['user_id'];
        $conn->query("INSERT IGNORE INTO enrollments (user_id, course_id) VALUES ($uid, $id)");
        $enrolled = true;
        $message  = 'You have successfully enrolled in this course!';
    }
}

$page_title = $course['title'];
include 'includes/header.php';
?>

<h2><?php echo htmlspecialchars($course['title']); ?></h2>
<p><?php echo htmlspecialchars($course['description']); ?></p>
<p>
    <strong>Instructor:</strong> <?php echo htmlspecialchars($course['instructor']); ?>
    &nbsp;|&nbsp;
    <strong>Duration:</strong> <?php echo (int)$course['duration_weeks']; ?> weeks
</p>

<?php if ($message): ?>
    <div class="msg-success"><?php echo htmlspecialchars($message); ?></div>
<?php endif; ?>

<?php if (!is_logged_in()): ?>
    <p><a href="login.php" class="btn">Login to Enroll</a></p>
<?php elseif (!$enrolled): ?>
    <form method="post" action="course_detail.php?id=<?php echo $id; ?>">
        <input type="submit" name="enroll" value="Enroll in this Course">
    </form>
<?php else: ?>
    <div class="msg-success">You are enrolled in this course.</div>
<?php endif; ?>

<h2>Course Chapters</h2>
<?php if (empty($chapters)): ?>
    <p>No chapters available for this course yet.</p>
<?php else: ?>
    <?php foreach ($chapters as $ch): ?>
    <div class="chapter-block">
        <h4><?php echo (int)$ch['chapter_order']; ?>. <?php echo htmlspecialchars($ch['title']); ?></h4>
        <?php if ($enrolled || is_admin()): ?>
            <p><?php echo nl2br(htmlspecialchars($ch['content'])); ?></p>
        <?php else: ?>
            <p><em>Enroll in this course to view chapter content.</em></p>
        <?php endif; ?>
    </div>
    <?php endforeach; ?>
<?php endif; ?>

<?php if (!empty($quizzes) && ($enrolled || is_admin())): ?>
<h2>Quizzes</h2>
<?php foreach ($quizzes as $quiz): ?>
    <?php
    $result = is_logged_in() ? get_quiz_result($_SESSION['user_id'], $quiz['id']) : null;
    ?>
    <div class="course-box">
        <h3><?php echo htmlspecialchars($quiz['title']); ?></h3>
        <?php if ($result): ?>
            <p>Your last score: <strong><?php echo (int)$result['score']; ?> / <?php echo (int)$result['total']; ?></strong></p>
        <?php endif; ?>
        <p><a href="quiz.php?id=<?php echo $quiz['id']; ?>">Take Quiz &raquo;</a></p>
    </div>
<?php endforeach; ?>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>
