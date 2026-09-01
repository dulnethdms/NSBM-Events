<?php
// Main events listing. We just render every event with data-* attributes
// and let the little script at the bottom filter them client-side - no
// need to hit the server again for search/filter.
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';
require_once '../includes/session_check.php';

require_role('student');

$student_id = $_SESSION['user_id'];
$page_title = "Browse Events";

try {
    $categories = $pdo->query("SELECT * FROM categories ORDER BY name ASC")->fetchAll();

    $stmt = $pdo->query("
        SELECT e.*, c.name AS category_name
        FROM events e
        JOIN categories c ON e.category_id = c.id
        ORDER BY e.event_date ASC, e.event_time ASC
    ");
    $events = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Database error loading events: " . $e->getMessage());
}

require_once '../includes/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-1"><i class="bi bi-compass text-success me-2"></i>Browse Campus Events</h2>
        <p class="text-muted small mb-0">Discover workshops, sports events, and cultural festivals happening around campus</p>
    </div>
</div>

<!-- Search & Filter Bar -->
<div class="glass-card p-4 mb-4">
    <div class="row g-3">
        <div class="col-md-7">
            <div class="input-group">
                <span class="input-group-text border-end-0 bg-transparent"><i class="bi bi-search text-success"></i></span>
                <input type="text" id="eventSearchInput" class="form-control border-start-0" placeholder="Search by title, venue, or description...">
            </div>
        </div>
        <div class="col-md-5">
            <div class="input-group">
                <span class="input-group-text border-end-0 bg-transparent"><i class="bi bi-funnel text-success"></i></span>
                <select id="categoryFilterSelect" class="form-select border-start-0">
                    <option value="0">All Categories</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?php echo $cat['id']; ?>"><?php echo htmlspecialchars($cat['name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
    </div>
</div>

<?php if (empty($events)): ?>
    <div class="glass-card p-5 text-center text-muted my-4">
        <i class="bi bi-calendar-x display-4 mb-3 d-block text-secondary"></i>
        <h5>No events scheduled</h5>
        <p class="small mb-0">Check back soon for new campus announcements.</p>
    </div>
<?php else: ?>
    <div class="row g-4" id="eventsGrid">
        <?php foreach ($events as $evt):
            $reg_count     = get_event_registration_count($pdo, $evt['id']);
            $is_registered = is_student_registered($pdo, $evt['id'], $student_id);
            $is_full       = ($reg_count >= $evt['capacity']);
        ?>
            <div class="col-md-6 col-lg-4 event-card-item"
                 data-title="<?php echo htmlspecialchars(strtolower($evt['title'])); ?>"
                 data-venue="<?php echo htmlspecialchars(strtolower($evt['venue'])); ?>"
                 data-desc="<?php echo htmlspecialchars(strtolower($evt['description'])); ?>"
                 data-category-id="<?php echo $evt['category_id']; ?>">

                <div class="glass-card p-4 h-100 d-flex flex-column">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2 py-1"><?php echo htmlspecialchars($evt['category_name']); ?></span>
                        <?php echo get_status_badge($evt['status']); ?>
                    </div>

                    <h5 class="fw-bold mb-2"><?php echo htmlspecialchars($evt['title']); ?></h5>
                    <p class="small text-muted mb-3 flex-grow-1" style="display:-webkit-box; -webkit-line-clamp:3; -webkit-box-orient:vertical; overflow:hidden;">
                        <?php echo htmlspecialchars($evt['description']); ?>
                    </p>

                    <div class="border-top pt-3 mt-auto" style="border-color: var(--border-soft) !important;">
                        <div class="small text-muted mb-1"><i class="bi bi-calendar3 me-2 text-success"></i><?php echo date('F d, Y', strtotime($evt['event_date'])); ?></div>
                        <div class="small text-muted mb-1"><i class="bi bi-clock me-2 text-success"></i><?php echo date('h:i A', strtotime($evt['event_time'])); ?></div>
                        <div class="small text-muted mb-3"><i class="bi bi-geo-alt me-2 text-success"></i><?php echo htmlspecialchars($evt['venue']); ?></div>

                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center small mb-1">
                                <span class="fw-semibold text-muted">Availability</span>
                                <span class="fw-bold <?php echo $is_full ? 'text-danger' : 'text-success'; ?>"><?php echo $reg_count; ?>/<?php echo $evt['capacity']; ?> seats</span>
                            </div>
                            <div class="progress progress-seat">
                                <div class="progress-bar <?php echo $is_full ? 'bg-danger' : 'bg-success'; ?>" role="progressbar" style="width: <?php echo min(100, round(($reg_count / $evt['capacity']) * 100)); ?>%"></div>
                            </div>
                        </div>

                        <div class="d-grid">
                            <?php if ($is_registered): ?>
                                <a href="event_details.php?id=<?php echo $evt['id']; ?>" class="btn btn-nsbm-outline">
                                    <i class="bi bi-check-circle-fill me-1"></i> Registered — View Details
                                </a>
                            <?php elseif ($is_full): ?>
                                <a href="event_details.php?id=<?php echo $evt['id']; ?>" class="btn btn-outline-secondary disabled">
                                    <i class="bi bi-slash-circle me-1"></i> Fully Booked
                                </a>
                            <?php else: ?>
                                <a href="event_details.php?id=<?php echo $evt['id']; ?>" class="btn btn-nsbm">
                                    <i class="bi bi-ticket-perforated me-1"></i> Reserve Seat
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <div id="noResultsMsg" class="glass-card p-5 text-center text-muted my-4" style="display:none;">
        <i class="bi bi-search display-4 mb-3 d-block text-secondary"></i>
        <h5>No matching events found</h5>
        <p class="small mb-0">Try a different keyword or reset the category filter.</p>
    </div>
<?php endif; ?>

<script>
// filters the cards above as you type / change the dropdown, no reload
(function () {
    const searchInput = document.getElementById('eventSearchInput');
    const categorySelect = document.getElementById('categoryFilterSelect');
    const cards = document.querySelectorAll('.event-card-item');
    const noResults = document.getElementById('noResultsMsg');

    function applyFilters() {
        const keyword = (searchInput?.value || '').trim().toLowerCase();
        const categoryId = categorySelect?.value || '0';
        let visibleCount = 0;

        cards.forEach(card => {
            const matchesKeyword = !keyword
                || card.dataset.title.includes(keyword)
                || card.dataset.venue.includes(keyword)
                || card.dataset.desc.includes(keyword);
            const matchesCategory = categoryId === '0' || card.dataset.categoryId === categoryId;
            const show = matchesKeyword && matchesCategory;

            card.style.display = show ? '' : 'none';
            if (show) visibleCount++;
        });

        if (noResults) {
            noResults.style.display = visibleCount === 0 ? '' : 'none';
        }
    }

    searchInput?.addEventListener('input', applyFilters);
    categorySelect?.addEventListener('change', applyFilters);
})();
</script>

<?php require_once '../includes/footer.php'; ?>
