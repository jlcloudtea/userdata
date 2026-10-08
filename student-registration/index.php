<?php
header('Cache-Control: no-store, max-age=0');
session_start();
$studentFile = '/var/lib/aws-lab/student.json';
$student = '';
$error = '';
if (is_readable($studentFile)) {
    $record = json_decode((string)file_get_contents($studentFile), true);
    if (is_array($record)) $student = trim((string)($record['student'] ?? ''));
}
if (!isset($_SESSION['registration_token'])) {
    $_SESSION['registration_token'] = bin2hex(random_bytes(24));
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $student !== '') {
    http_response_code(403);
    $error = 'Student details have already been registered on this instance.';
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $submitted = trim((string)($_POST['student'] ?? ''));
    if (!hash_equals($_SESSION['registration_token'], (string)($_POST['token'] ?? ''))) {
        $error = 'Session expired. Please refresh and try again.';
    } elseif (strlen($submitted) < 3 || strlen($submitted) > 120 ||
              preg_match('/[\x00-\x1F\x7F]/', $submitted)) {
        $error = 'Enter a valid student name or email (3–120 characters).';
    } else {
        $payload = json_encode(['student' => $submitted, 'updated_at' => gmdate('c')]);
        if (file_put_contents($studentFile, $payload, LOCK_EX) === false) {
            $error = 'Could not save student details. Please contact your lecturer.';
        } else {
            header('Location: /');
            exit;
        }
    }
}
$showForm = ($student === '');
function h($s) { return htmlspecialchars((string)$s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); }
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>CLD401ACF Assessment 2 — AWS Lab</title>
<link href="css/bootstrap.min.css" rel="stylesheet">
<link href="css/style.css" rel="stylesheet">
<style>.student-panel{max-width:700px;margin:45px auto}.student-panel .panel-body{padding:28px}.student-label{font-weight:600}</style>
</head>
<body><div class="container">
<?php if ($showForm): ?>
<div class="panel panel-default student-panel"><div class="panel-body">
<h2>Welcome to CLD401ACF Assessment 2</h2>
<p>Please identify yourself before starting your lab.</p>
<?php if ($error !== ''): ?><div class="alert alert-danger"><?= h($error) ?></div><?php endif; ?>
<form method="post" action="/">
<input type="hidden" name="token" value="<?= h($_SESSION['registration_token']) ?>">
<div class="form-group">
<label for="student">Student Name or Email <span aria-hidden="true">*</span></label>
<input type="text" id="student" name="student" class="form-control" required minlength="3" maxlength="120"
autocomplete="name" placeholder="e.g. Jane.Smith@student.tafesa.edu.au" value="<?= h($student) ?>">
</div>
<button class="btn btn-primary" type="submit">Save &amp; Continue</button>
</form>
<p class="text-muted" style="margin-top:15px"><small>Entered details identify the student for this lab. They are not verified against AWS login.</small></p>
</div></div>
<?php else: ?>
<?php include 'menu.php'; ?>
<div class="jumbotron">
<h2>CLD401ACF Assessment 2</h2>
<p>Student: <strong><?= h($student) ?></strong></p>
<?php include 'get-index-meta-data.php'; ?>
<hr>
<?php include 'get-cpu-load.php'; ?>
</div>
<div class="panel panel-default"><div class="panel-heading"><h3 class="panel-title">Assessment 2 — Lab Tabs Guide</h3></div>
<div class="panel-body"><p>Use the tabs above to complete the corresponding assessment tasks:</p>
<ul class="list-group">
<li class="list-group-item"><strong>Load Test</strong> — Task 4: Auto Scaling task.</li>
<li class="list-group-item"><strong>RDS</strong> — Task 3: Database connection.</li>
<li class="list-group-item"><strong>S3</strong> — Tasks 5 &amp; 6: Online storage.</li>
<li class="list-group-item"><strong><a href="faq.php">FAQ &amp; Tips</a></strong> — Lab preparation, common questions and troubleshooting guidance.</li>
</ul></div></div>
<?php endif; ?>
</div>
<script src="js/jquery.min.js"></script><script src="js/bootstrap.min.js"></script><script src="js/scripts.js"></script>
</body></html>
