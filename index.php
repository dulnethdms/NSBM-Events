<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'includes/db_connect.php';

try {
    $stmt = $pdo->query("
        SELECT a.*, e.title AS event_title, u.full_name AS author_name
        FROM announcements a
        LEFT JOIN events e ON a.event_id = e.id
        JOIN users u ON a.created_by = u.id
        ORDER BY a.created_at DESC
        LIMIT 5
    ");
    $announcements = $stmt->fetchAll();
} catch (PDOException $e) {
    $announcements = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Welcome to NSBM EventHub</title>

  <link rel="icon" href="/assets/images/favicon.ico" type="image/x-icon">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
  <link rel="stylesheet" href="assets/css/custom.css">
</head>
<body>

<section class="navsec">
<nav class="navbar-nsbm">
  <div class="container">
    <a class="navbar-brand" href="index.php">
      <span>NSBM EventHub</span>
    </a>
    <div class="nav-actions">
<?php if (isset($_SESSION['user_id'])): ?>
      <?php if ($_SESSION['user_role'] === 'admin'): ?>
      <a class="btn" href="admin/dashboard.php">
        <i class="bi bi-speedometer2"></i> Dashboard
      </a>
      <a class="btn" href="admin/announcements_manage.php">
        <i class="bi bi-megaphone"></i> Announcements
      </a>
      <?php else: ?>
      <a class="btn" href="student/dashboard.php">
        <i class="bi bi-speedometer2"></i> Dashboard
      </a>
      <a class="btn" href="student/announcements_view.php">
        <i class="bi bi-megaphone"></i> Announcements
      </a>
      <a class="btn" href="student/events_browse.php">
        <i class="bi bi-compass"></i> Event Browser
      </a>
      <?php endif; ?>
      <a class="btn" href="auth/logout.php">
        <i class="bi bi-box-arrow-right"></i> Logout
      </a>
<?php else: ?>
      <a class="btn" href="auth/login.php">
        <i class="bi bi-box-arrow-in-right"></i> Login
      </a>
      <a class="btn" href="auth/register.php">
        <i class="bi bi-person-plus"></i> Register
      </a>
      <a class="btn" href="auth/login.php">
        <i class="bi bi-megaphone"></i> Announcements
      </a>
      <a class="btn" href="auth/login.php">
        <i class="bi bi-compass"></i> Event Browser
      </a>
<?php endif; ?>
    </div>
  </div>
</nav>
</section>
<section class="photo-section">
  <div class="photo-wrapper">
    <img src="assets/images/image1.webp" alt="First photo" class="photo base-photo">
    <img src="assets/images/Image2.jpeg" alt="Second photo" class="photo overlay-photo">
</section>

<div class="main-wrapper">
  <div class="container">

    <section class="herosec">
      <div class="hero-section">
        <div class="hero-text">
          <h1>Discover, Schedule &amp; Attend Campus Events</h1>
          <p>NSBM EventHub connects students with faculty workshops, sports championships, hackathons, and cultural festivals. Reserve seats in real-time!</p>
          <div class="hero-actions">
<?php if (isset($_SESSION['user_id'])): ?>
            <a href="<?php echo $_SESSION['user_role'] === 'admin' ? 'admin/events_manage.php' : 'student/events_browse.php'; ?>" class="btn">Browse Events</a>
            <a href="<?php echo $_SESSION['user_role'] === 'admin' ? 'admin/dashboard.php' : 'student/dashboard.php'; ?>" class="btn">Dashboard</a>
<?php else: ?>
            <a href="auth/login.php" class="btn">Browse Events</a>
            <a href="auth/login.php" class="btn">Login / Register</a>
<?php endif; ?>
          </div>
        </div>
      </div>
    </section>

    <section class="browse-events">
      <div class="section-header">
        <h2>Browse Campus Events</h2>
        <p class="text-muted small">Explore upcoming workshops, sports events, and cultural festivals</p>
      </div>
      <div class="event-cards">
        <div class="event-card">

          <div class="event-card-body">
            <h5 class="event-card-title">NSBM Hackathon 2026</h5>
            <p class="event-card-text">Join the annual coding marathon and showcase your programming skills. Compete for prizes and recognition!</p>
            <a href="<?php echo isset($_SESSION['user_id']) ? ($_SESSION['user_role'] === 'admin' ? 'admin/events_manage.php' : 'student/events_browse.php') : 'auth/login.php'; ?>" class="btn">Browse Events</a>
          </div>
        </div>
        <div class="event-card">

          <div class="event-card-body">
            <h5 class="event-card-title">Sports Championship 2026</h5>
            <p class="event-card-text">Cheer for your favorite teams in the inter-college sports championship. Reserve your seats for the finals now!</p>
            <a href="<?php echo isset($_SESSION['user_id']) ? ($_SESSION['user_role'] === 'admin' ? 'admin/events_manage.php' : 'student/events_browse.php') : 'auth/login.php'; ?>" class="btn">Browse Events</a>
          </div>
        </div>
        <div class="event-card">

          <div class="event-card-body">
            <h5 class="event-card-title">Cultural Festival 2026</h5>
            <p class="event-card-text">Experience the vibrant cultural festival with music, dance, and food from around the world. Reserve your spot today!</p>
            <a href="<?php echo isset($_SESSION['user_id']) ? ($_SESSION['user_role'] === 'admin' ? 'admin/events_manage.php' : 'student/events_browse.php') : 'auth/login.php'; ?>" class="btn">Browse Events</a>
          </div>
        </div>
      </div>
    </section>

    <div class="see-all">
      <a href="<?php echo isset($_SESSION['user_id']) ? ($_SESSION['user_role'] === 'admin' ? 'admin/events_manage.php' : 'student/events_browse.php') : 'auth/login.php'; ?>" class="btn">See All Events</a>
    </div>

    <section class="announcements-section">
      <h3 class="mb-4"><i class="bi bi-megaphone-fill text-warning me-2"></i> Recent Announcements</h3>
      <div class="announcement-grid">
    <?php if (empty($announcements)): ?>
        <div class="announcement-card text-muted text-center py-4">
            No announcements posted yet.
        </div>
    <?php else: ?>
        <?php foreach ($announcements as $ann): ?>
            <?php
                $isNew = (strtotime($ann['created_at']) >= strtotime('-2 days'));

                if ($ann['event_id']) {
                    $badgeText  = 'Event';
                    $badgeClass = 'bg-info';
                } elseif ($isNew) {
                    $badgeText  = 'New';
                    $badgeClass = 'bg-success';
                } else {
                    $badgeText  = 'Notice';
                    $badgeClass = 'bg-warning';
                }
            ?>
            <div class="announcement-card d-flex justify-content-between align-items-start">
                <div class="ms-2 me-auto">
                    <div class="fw-bold"><?php echo htmlspecialchars($ann['title']); ?></div>
                    <div class="announcement-content mb-2 mt-1">
                        <?php echo nl2br(htmlspecialchars($ann['content'])); ?>
                    </div>
                    <div class="text-muted small mt-1">
                        <?php echo date('M d, Y - h:i A', strtotime($ann['created_at'])); ?>
                        &middot; by <?php echo htmlspecialchars($ann['author_name']); ?>
                        <?php if ($ann['event_id']): ?>
                            &middot; <?php echo htmlspecialchars($ann['event_title']); ?>
                        <?php endif; ?>
                    </div>
                </div>
                <span class="badge <?php echo $badgeClass; ?> rounded-pill"><?php echo $badgeText; ?></span>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
      </div>
    </section>
<?php require_once 'includes/footer.php'; ?>
