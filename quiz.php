<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';

require_login();

$quiz_id   = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$quiz_row  = $quiz_id ? $conn->query("SELECT * FROM quizzes WHERE id = $quiz_id LIMIT 1")->fetch_assoc() : null;

if (!$quiz_row) {
    header('HTTP/1.0 404 Not Found');
    $page_title = 'Quiz Not Found';
    include 'includes/header.php';
    echo '<p class="msg-error">Quiz not found.</p>';
    include 'includes/footer.php';
    exit;
}

$course  = get_course_by_id($quiz_row['course_id']);
$user_id = (int)$_SESSION['user_id'];

// Only enrolled students (or admin) may take the quiz
if (!is_admin() && !is_enrolled($user_id, $quiz_row['course_id'])) {
    $page_title = 'Access Denied';
    include 'includes/header.php';
    echo '<p class="msg-error">You must be enrolled in the course to take this quiz.</p>';
    include 'includes/footer.php';
    exit;
}

$questions = get_questions_by_quiz($quiz_id);
$score     = null;
$total     = count($questions);
$answers   = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($questions)) {
    $score = 0;
    foreach ($questions as $q) {
        $chosen = $_POST['q_' . $q['id']] ?? '';
        $answers[$q['id']] = $chosen;
        if ($chosen === $q['correct_option']) {
            $score++;
        }
    }
    // Save result
    $conn->query("INSERT INTO quiz_results (user_id, quiz_id, score, total) VALUES ($user_id, $quiz_id, $score, $total)");
}

$page_title = $quiz_row['title'];
include 'includes/header.php';
?>

<h2><?php echo htmlspecialchars($quiz_row['title']); ?></h2>
<?php if ($course): ?>
<p>Course: <a href="course_detail.php?id=<?php echo $course['id']; ?>"><?php echo htmlspecialchars($course['title']); ?></a></p>
<?php endif; ?>

<?php if ($score !== null): ?>
    <div class="msg-success">
        Quiz submitted! Your score: <strong><?php echo $score; ?> / <?php echo $total; ?></strong>
        (<?php echo $total > 0 ? round($score / $total * 100) : 0; ?>%)
    </div>
    <h3>Answers Review</h3>
    <?php foreach ($questions as $i => $q): ?>
    <div class="chapter-block">
        <strong>Q<?php echo ($i + 1); ?>: <?php echo htmlspecialchars($q['question_text']); ?></strong><br>
        <?php
        $options = ['a' => $q['option_a'], 'b' => $q['option_b'], 'c' => $q['option_c'], 'd' => $q['option_d']];
        foreach ($options as $key => $opt):
            $chosen  = $answers[$q['id']] ?? '';
            $correct = $q['correct_option'];
            $style   = '';
            if ($key === $correct) {
                $style = 'color:green; font-weight:bold;';
            } elseif ($key === $chosen && $key !== $correct) {
                $style = 'color:red;';
            }
        ?>
        <div class="quiz-option" style="<?php echo $style; ?>">
            <label><?php echo strtoupper($key); ?>. <?php echo htmlspecialchars($opt); ?></label>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endforeach; ?>
    <p><a href="course_detail.php?id=<?php echo $quiz_row['course_id']; ?>">&laquo; Back to Course</a></p>
<?php elseif (empty($questions)): ?>
    <p>No questions available for this quiz yet.</p>
<?php else: ?>
    <form method="post" action="quiz.php?id=<?php echo $quiz_id; ?>">
    <?php foreach ($questions as $i => $q): ?>
    <div class="chapter-block">
        <strong>Q<?php echo ($i + 1); ?>: <?php echo htmlspecialchars($q['question_text']); ?></strong><br>
        <?php
        $options = ['a' => $q['option_a'], 'b' => $q['option_b'], 'c' => $q['option_c'], 'd' => $q['option_d']];
        foreach ($options as $key => $opt):
        ?>
        <div class="quiz-option">
            <label>
                <input type="radio" name="q_<?php echo $q['id']; ?>" value="<?php echo $key; ?>" required>
                <?php echo strtoupper($key); ?>. <?php echo htmlspecialchars($opt); ?>
            </label>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endforeach; ?>
    <input type="submit" value="Submit Quiz">
    </form>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>
