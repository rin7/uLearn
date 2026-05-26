<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';

$page_title = 'Welcome';
$courses = get_all_courses();
include 'includes/header.php';
?>

<h2>Welcome to <?php echo SITE_NAME; ?></h2>
<p>uLearn is an online learning management system. Browse our courses, enroll and take quizzes to test your knowledge.</p>

<?php if (!is_logged_in()): ?>
<p>
    <a href="<?php echo SITE_URL; ?>/login.php" class="btn">Login</a>
    &nbsp;
    <a href="<?php echo SITE_URL; ?>/register.php" class="btn">Register</a>
</p>
<?php endif; ?>

<h2>Available Courses</h2>
<?php if (empty($courses)): ?>
    <p>No courses available yet.</p>
<?php else: ?>
    <?php foreach ($courses as $course): ?>
    <div class="course-box">
        <h3><a href="course_detail.php?id=<?php echo $course['id']; ?>"><?php echo htmlspecialchars($course['title']); ?></a></h3>
        <p><?php echo htmlspecialchars($course['description']); ?></p>
        <p><strong>Instructor:</strong> <?php echo htmlspecialchars($course['instructor']); ?> &nbsp;|&nbsp; <strong>Duration:</strong> <?php echo (int)$course['duration_weeks']; ?> weeks</p>
        <p><a href="course_detail.php?id=<?php echo $course['id']; ?>">View Course &raquo;</a></p>
    </div>
    <?php endforeach; ?>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>
