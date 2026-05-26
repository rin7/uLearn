<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

require_admin();

$page_title = 'Admin Dashboard';

// Override header to use correct relative URL for sub-directory
define('IN_ADMIN', true);

include '../includes/header.php';
?>

<h2>Admin Dashboard</h2>

<div>
    <div class="stats-box">
        <div class="num"><?php echo count_students(); ?></div>
        <div class="label">Students</div>
    </div>
    <div class="stats-box">
        <div class="num"><?php echo count_courses(); ?></div>
        <div class="label">Courses</div>
    </div>
    <div class="stats-box">
        <div class="num"><?php echo count_enrollments(); ?></div>
        <div class="label">Enrollments</div>
    </div>
</div>

<br>
<h2>Quick Links</h2>
<ul>
    <li><a href="manage_courses.php">Manage Courses</a></li>
    <li><a href="add_course.php">Add New Course</a></li>
    <li><a href="manage_users.php">Manage Users</a></li>
</ul>

<?php include '../includes/footer.php'; ?>
