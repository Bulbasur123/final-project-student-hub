<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/app.php';

security_headers();
header('Cache-Control: no-store');

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

if ($method !== 'POST') {
    redirect_to('index.php#contact');
}

$name = post_string('name');
$email = post_string('email');
$role = post_string('role');
$message = post_string('message');
$token = post_string('csrf');

$allowedRoles = ['Classmate', 'Instructor', 'Collaborator', 'Other'];
$errors = [];
$csrfFailed = !verify_csrf($token);

if ($csrfFailed) {
    $errors[] = 'Your session expired. Refresh the page and try again.';
}

if ($name === '') {
    $errors[] = 'Please enter your name.';
} elseif (text_length($name) > 80) {
    $errors[] = 'Your name must be 80 characters or fewer.';
}

if ($email === '') {
    $errors[] = 'Please enter your email address.';
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Please enter a valid email address.';
} elseif (text_length($email) > 120) {
    $errors[] = 'Your email address must be 120 characters or fewer.';
}

if ($role === '') {
    $errors[] = 'Please choose who you are.';
} elseif (!in_array($role, $allowedRoles, true)) {
    $errors[] = 'Please choose one of the listed options.';
}

if ($message === '') {
    $errors[] = 'Please write a message.';
} elseif (text_length($message) > 500) {
    $errors[] = 'Your message must be 500 characters or fewer.';
}

$hasErrors = $errors !== [];
$statusCode = $csrfFailed ? 403 : ($hasErrors ? 422 : 200);
http_response_code($statusCode);
$pageTitle = $hasErrors ? 'Check your message · Student Hub' : 'Message received · Student Hub';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="robots" content="noindex">
  <meta name="theme-color" content="#163b32">
  <title><?= e($pageTitle) ?></title>
  <link rel="icon" type="image/svg+xml" href="assets/favicon.svg">
  <link rel="stylesheet" href="assets/styles.css">
</head>
<body class="result-page<?= $hasErrors ? ' is-error' : '' ?>">
  <header class="site-header result-header">
    <div class="nav-shell">
      <a class="brand" href="index.php" aria-label="Student Hub home">
        <span class="brand-mark" aria-hidden="true">ML<span>.</span></span>
        <span class="brand-copy">
          <strong>Student Hub</strong>
          <span>Final Project</span>
        </span>
      </a>
      <a class="back-link" href="index.php#contact">← Back to the site</a>
    </div>
  </header>

  <main class="result-main">
    <p class="eyebrow"><span class="eyebrow-dot"></span> Contact form · POST</p>
    <h1>
      <?php if ($hasErrors): ?>
        Let’s try<br><em>that again.</em>
      <?php else: ?>
        Message<br><em>received.</em>
      <?php endif; ?>
    </h1>
    <p class="result-lede">
      <?php if ($hasErrors): ?>
        The form reached <code>contact.php</code> through a POST request, but the server found a few things to fix. Nothing was saved.
      <?php else: ?>
        Your note arrived through a native POST request and passed server-side validation. No personal data was stored.
      <?php endif; ?>
    </p>

    <div class="result-grid">
      <section class="result-card" aria-labelledby="result-title">
        <div class="result-badge">
          <span class="result-icon" aria-hidden="true"><?= $hasErrors ? '!' : '✓' ?></span>
          <div>
            <strong><?= $hasErrors ? 'Validation failed' : 'Validation passed' ?></strong>
            <span>HTTP <?= e((string) $statusCode) ?> · POST received</span>
          </div>
        </div>

        <?php if ($hasErrors): ?>
          <h2 id="result-title">A few fields<br>need attention.</h2>
          <p>The POST request was received, but the form could not be accepted yet. Please fix the following and send it again.</p>
          <ul class="error-list">
            <?php foreach ($errors as $error): ?>
              <li><?= e($error) ?></li>
            <?php endforeach; ?>
          </ul>
          <div class="result-actions">
            <a class="button button-dark" href="index.php#contact">Back to the form <span aria-hidden="true">↗</span></a>
          </div>
        <?php else: ?>
          <h2 id="result-title">Thanks, <?= e($name) ?>.</h2>
          <p>Your message made it through the form and passed server-side validation. Here is what <code>contact.php</code> received.</p>

          <dl class="result-details">
            <div>
              <dt>Name</dt>
              <dd><?= e($name) ?></dd>
            </div>
            <div>
              <dt>Email</dt>
              <dd><?= e($email) ?></dd>
            </div>
            <div>
              <dt>I am a</dt>
              <dd><?= e($role) ?></dd>
            </div>
          </dl>

          <div class="result-message">
            <span>Message</span>
            <p><?= e($message) ?></p>
          </div>

          <div class="result-actions">
            <a class="button button-dark" href="index.php#contact">Send another note <span aria-hidden="true">↗</span></a>
            <span>No data was stored by this form.</span>
          </div>
        <?php endif; ?>
      </section>

      <aside class="result-aside" aria-label="Request details">
        <p class="aside-kicker">Request details</p>
        <dl class="request-details">
          <div>
            <dt>Method</dt>
            <dd><span class="method-badge">POST</span></dd>
          </div>
          <div>
            <dt>Endpoint</dt>
            <dd><code>contact.php</code></dd>
          </div>
          <div>
            <dt>Status</dt>
            <dd><?= e((string) $statusCode) ?> · <?= $hasErrors ? 'Needs attention' : 'OK' ?></dd>
          </div>
          <div>
            <dt>Storage</dt>
            <dd>None — nothing saved</dd>
          </div>
        </dl>
        <p class="aside-note"><strong>Under the hood:</strong> the browser sends the fields in the request body. PHP reads them from <code>$_POST</code>, validates each field, and renders this response. Opening <code>contact.php</code> directly with GET sends you back to the contact section.</p>
      </aside>
    </div>
  </main>
</body>
</html>
