<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';

$page_title = 'All Courses';
$courses = get_all_courses();
include 'includes/header.php';
?>

<h2>All Courses</h2>
<p>Browse our available courses below. Login and enroll to access chapters and quizzes.</p>

<?php if (empty($courses)): ?>
    <p>No courses available yet.</p>
<?php else: ?>
    <?php foreach ($courses as $course): ?>
    <div class="course-box">
        <h3><a href="course_detail.php?id=<?php echo $course['id']; ?>"><?php echo htmlspecialchars($course['title']); ?></a></h3>
        <p><?php echo htmlspecialchars($course['description']); ?></p>
        <p>
            <strong>Instructor:</strong> <?php echo htmlspecialchars($course['instructor']); ?>
            &nbsp;|&nbsp;
            <strong>Duration:</strong> <?php echo (int)$course['duration_weeks']; ?> weeks
        </p>
        <p><a href="course_detail.php?id=<?php echo $course['id']; ?>">View Details &raquo;</a></p>
    </div>
    <?php endforeach; ?>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>
