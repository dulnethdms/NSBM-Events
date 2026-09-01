# NSBM EventHub — Restructured (Frontend / Backend)

This is the original NSBM Event Planning & Scheduling System, reorganized into
two top-level folders so the static site and the server-side logic are kept
separate.

## Structure

```
frontend/           Static pages & assets (no server logic)
  index.html
  auth/              login.html, register.html, logout.html
  admin/             admin-facing HTML pages (dashboard, events, categories, etc.)
  student/           student-facing HTML pages (dashboard, browse events, schedule, etc.)
  assets/            css/, js/ (Bootstrap-style custom.css, GSAP/Lenis animations)

backend/             PHP application logic & database
  admin/             announcements_manage.php (admin CRUD logic)
  student/           announcements_view.php
  student_events/    essentials/ (header.php, footer.php), pages/ (event_browse.php, event_details.php)
  database/          db_connect.php, eventsdb_connect.php, eventsdb_init.php, install.php, eventhub.sql
  includes/          db_connect.php, functions.php, session_check.php, header.php, footer.php
```

## One thing I changed, and why

In the original project, `includes/db_connect.html`, `functions.html`,
`session_check.html`, `header.html`, and `footer.html` all contain **PHP
code** (they open with `<?php ... ?>`) but were saved with an `.html`
extension — one even has the comment `<!--Dulneth, create this in php-->`.
Meanwhile the real backend pages (e.g. `admin/announcements_manage.php`)
already do `require_once '../includes/db_connect.php'`, expecting a `.php`
file that didn't exist in the original zip.

So when moving these into `backend/includes/`, I renamed them from `.html`
to `.php` to match what the code already expects. This is the only content
change — everything else was moved, not edited.

## Note

The project mixes two overlapping implementations of some pages (e.g. static
`admin/announcements_manage.html` mockup vs. the working
`admin/announcements_manage.php`, and `student/events_browse.html` vs.
`student_events/pages/event_browse.php`). I kept both, split by type
(HTML → frontend, PHP → backend) rather than merging or deleting either,
since I didn't want to guess which one you intend to keep as the source of
truth.
