<?php
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Welcome to NSBM EventHub</title>

  <link rel="stylesheet" href="assets/css/custom.css">
  <script src="assets/js/main.js" defer></script>
</head>
<body>

<!-- Navbar -->
<section class="navsec">
<nav class="navbar-nsbm">
  <div class="container">
    <a class="navbar-brand" href="index.html">
      <span>NSBM EventHub</span>
    </a>
    <div class="nav-actions">
      <a class="btn" href="auth/login.html">
        <i class="bi bi-box-arrow-in-right"></i> Login
      </a>
      <a class="btn" href="auth/register.html">
        <i class="bi bi-person-plus"></i> Register
      </a>
      <a class="btn" href="auth/register.html">
        <i class="bi bi-person-plus"></i> Announcments
      </a>
      <a class="btn" href="auth/register.html">
        <i></i> Event Browser
      </a>
    </div>
  </div>
</nav>
</section>
<!-- Full-bleed photo swap on scroll, handled in main.js -->
<section class="photo-section">
  <div class="photo-wrapper">
    <img src="assets/images/image1.webp" alt="First photo" class="photo base-photo">
    <img src="assets/images/Image2.jpeg" alt="Second photo" class="photo overlay-photo">
</section>

<!-- Main Wrapper -->
<main class="main-wrapper">
  <div class="container">

    <!-- Hero Section -->
    <section class="herosec">
      <div class="hero-section">
        <div class="hero-text">
          <h1>Discover, Schedule &amp; Attend Campus Events</h1>
          <p>NSBM EventHub connects students with faculty workshops, sports championships, hackathons, and cultural festivals. Reserve seats in real-time!</p>
          <div class="hero-actions">
            <a href="student/events_browse.php" class="btn">Browse Events</a>
            <a href="auth/login.php" class="btn">Login / Register</a>
          </div>
        </div>

        <!--<div class="hero-box">
          <i class="bi bi-calendar-week hero-box-icon"></i>
          <h4>Seamless Seat Reservations</h4>
          <p>Automatic seat tracking prevents double-booking and ensures fair access to all events.</p>
        </div>-->
      </div>
    </section>



    
    <?php
// Browse Events preview - pulls 3 soonest upcoming events from DB
require_once 'includes/db_connect.php'; // adjust path to match homepage location

try {
    $stmt = $pdo->query("
        SELECT e.*, c.name AS category_name
        FROM events e
        JOIN categories c ON e.category_id = c.id
        WHERE e.event_date >= CURDATE()
        ORDER BY e.event_date ASC, e.event_time ASC
        LIMIT 3
    ");
    $preview_events = $stmt->fetchAll();
} catch (PDOException $e) {
    $preview_events = [];
}

$default_image = 'assets/images/image1.webp'; // fallback if event has no image
?>

<section class="browse-events">
    <div class="section-header">
        <h2>Browse Campus Events</h2>
        <p class="text-muted small">Explore upcoming workshops, sports events, and cultural festivals</p>
    </div>

    <?php if (empty($preview_events)): ?>
        <p class="text-muted text-center">No upcoming events right now. Check back soon!</p>
    <?php else: ?>
        <div class="event-cards">
            <?php foreach ($preview_events as $evt): ?>
                <div class="event-card">
                    <img src="<?php echo htmlspecialchars($evt['image_url'] ?: $default_image); ?>" 
                         alt="<?php echo htmlspecialchars($evt['title']); ?>">
                    <div class="event-card-body">
                        <h5 class="event-card-title"><?php echo htmlspecialchars($evt['title']); ?></h5>
                        <p class="event-card-text">
                            <?php echo htmlspecialchars(mb_strimwidth($evt['description'], 0, 120, '...')); ?>
                        </p>
                        <a href="student/events_browse.php" class="btn">Browse Events</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>






    <div class="see-all">
      <a href="student/events_browse.php" class="btn">See All Events</a>
    </div>

    
    
    <!-- Announcement List -->
<div class="list-group shadow-sm rounded-3">
    <?php if (empty($announcements)): ?>
        <div class="list-group-item text-muted text-center py-4">
            No announcements posted yet.
        </div>
    <?php else: ?>
        <?php foreach ($announcements as $ann): ?>
            <?php
                // Decide badge based on type + recency
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
            <div class="list-group-item d-flex justify-content-between align-items-start">
                <div class="ms-2 me-auto">
                    <div class="fw-bold"><?php echo htmlspecialchars($ann['title']); ?></div>
                    <?php echo nl2br(htmlspecialchars($ann['content'])); ?>
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

    <!--
    <section class="alert-info">
      <i class="bi bi-info-circle-fill alert-icon"></i>
      <div>
        <h6>Learning Project - Demo Credentials:</h6>
        <ul>
          <li><strong>Admin:</strong> <code>admin@nsbm.ac.lk</code> / <code>admin123</code></li>
          <li><strong>Student:</strong> <code>kamal@student.nsbm.ac.lk</code> / <code>student123</code></li>
        </ul>
      </div>
    </section>
    -->

  </div>
</main>

<!-- Footer -->
<footer>
  <div class="container">
    <p>NSBM EventHub &copy; 2026 NSBM Green University. All rights reserved.</p>
  </div>
</footer>

</body>
</html>
