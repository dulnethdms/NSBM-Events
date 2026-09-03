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
    <a class="navbar-brand" href="index.php">
      <span>NSBM EventHub</span>
    </a>
    <div class="nav-actions">
      <a class="btn" href="auth/register.html">
        <i class="bi bi-person-plus"></i> Announcements
      </a>
      <a class="btn" href="auth/register.html">
        <i></i> Event Browser
      </a>
      <a class="btn" href="auth/login.html">
        <i class="bi bi-box-arrow-in-right"></i> Login
      </a>
      <a class="btn" href="auth/register.html">
        <i class="bi bi-person-plus"></i> Register
      </a>
    </div>
  </div>
</nav>
</section>

<section class="photo-section">
  <div class="photo-wrapper">
    <img src="assets/images/image1.webp" alt="First photo" class="photo base-photo">
    <img src="assets/images/Image2.jpeg" alt="Second photo" class="photo overlay-photo">
</section>


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

       
      </div>
    </section>

    <!-- Browse Events preview cards - just a teaser, the real listing is student/events_browse.php -->
    <section class="browse-events">
      <div class="section-header">
        <h2>Browse Campus Events</h2>
        <p class="text-muted small">Explore upcoming workshops, sports events, and cultural festivals</p>
      </div>
      <div class="event-cards">
        <div class="event-card">
          <img src="https://students.nsbm.ac.lk/_next/image?url=%2Fhome2%2FFront-Globe.jpg&w=3840&q=75" alt="Event 1">
          <div class="event-card-body">
            <h5 class="event-card-title">NSBM Hackathon 2026</h5>
            <p class="event-card-text">Join the annual coding marathon and showcase your programming skills. Compete for prizes and recognition!</p>
            
          </div>
        </div>
        <div class="event-card">
          <img src="https://media.licdn.com/dms/image/v2/C561BAQEOoczaGxpdNg/company-background_10000/company-background_10000/0/1628311829240/human_resource_circle_of_nsbm_green_university_cover?e=2147483647&v=beta&t=lTFSwYGtxTxqdjSKT9gQqSd5BybpeKxa0beuTa-MaV0" alt="Event 2">
          <div class="event-card-body">
            <h5 class="event-card-title">Sports Championship 2026</h5>
            <p class="event-card-text">Cheer for your favorite teams in the inter-college sports championship. Reserve your seats for the finals now!</p>
            
          </div>
        </div>
        <div class="event-card">
          <img src="https://students.nsbm.ac.lk/_next/image?url=%2Fhome2%2FFront-Globe.jpg&w=3840&q=75" alt="Event 3">
          <div class="event-card-body">
            <h5 class="event-card-title">Cultural Festival 2026</h5>
            <p class="event-card-text">Experience the vibrant cultural festival with music, dance, and food from around the world. Reserve your spot today!</p>
            
          </div>
        </div>
      </div>
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
