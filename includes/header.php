<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title><?php echo isset($page_title) ? htmlspecialchars($page_title) . ' - ' . SITE_NAME : SITE_NAME; ?></title>
    <link rel="stylesheet" type="text/css" href="<?php echo SITE_URL; ?>/css/style.css">
</head>
<body>
<div id="wrapper">

<div id="header">
    <h1><a href="<?php echo SITE_URL; ?>/index.php"><?php echo SITE_NAME; ?></a></h1>
    <p>Online Learning Management System</p>
</div>

<div id="nav">
    <a href="<?php echo SITE_URL; ?>/index.php">Home</a>
    <a href="<?php echo SITE_URL; ?>/courses.php">Courses</a>
    <?php if (is_logged_in()): ?>
        <?php if (is_admin()): ?>
            <a href="<?php echo SITE_URL; ?>/admin/index.php">Admin Panel</a>
        <?php endif; ?>
        <a href="<?php echo SITE_URL; ?>/logout.php">Logout (<?php echo htmlspecialchars($_SESSION['username']); ?>)</a>
    <?php else: ?>
        <a href="<?php echo SITE_URL; ?>/login.php">Login</a>
        <a href="<?php echo SITE_URL; ?>/register.php">Register</a>
    <?php endif; ?>
</div>

<div id="content">
