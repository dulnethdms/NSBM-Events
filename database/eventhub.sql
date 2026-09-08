-- ============================================================
-- NSBM EventHub - Database Schema & Sample Data
-- Database Engine: MySQL / MariaDB
-- ============================================================

CREATE DATABASE IF NOT EXISTS `nsbm_eventhub` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `nsbm_eventhub`;

-- ------------------------------------------------------------
-- Table 1: users
-- Stores registered account information for Admins and Students
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `users` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `full_name` VARCHAR(100) NOT NULL,
    `email` VARCHAR(100) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL, -- Hashed using PHP password_hash()
    `role` ENUM('admin', 'student') NOT NULL DEFAULT 'student',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- Table 2: categories
-- Event classifications (e.g. IT & Computing, Sports, Cultural)
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `categories` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL UNIQUE,
    `description` TEXT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- Table 3: events
-- Contains main details of planned university events
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `events` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `title` VARCHAR(150) NOT NULL,
    `description` TEXT NOT NULL,
    `category_id` INT NOT NULL,
    `event_date` DATE NOT NULL,
    `event_time` TIME NOT NULL,
    `venue` VARCHAR(150) NOT NULL,
    `capacity` INT NOT NULL DEFAULT 50,
    `status` ENUM('Upcoming', 'Ongoing', 'Completed', 'Cancelled') NOT NULL DEFAULT 'Upcoming',
    `created_by` INT NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT `fk_events_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `fk_events_creator` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- Table 4: registrations
-- Tracks student event signups with composite uniqueness check
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `registrations` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `event_id` INT NOT NULL,
    `student_id` INT NOT NULL,
    `registered_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY `unique_registration` (`event_id`, `student_id`),
    CONSTRAINT `fk_reg_event` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `fk_reg_student` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- Table 5: announcements
-- Broadcast news for general campus or event-specific updates
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `announcements` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `title` VARCHAR(150) NOT NULL,
    `content` TEXT NOT NULL,
    `event_id` INT NULL, -- NULL indicates general announcement
    `created_by` INT NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT `fk_ann_event` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `fk_ann_creator` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- SAMPLE SEED DATA
-- Default passwords:
-- Admin password:   admin123
-- Student password: student123
-- ============================================================

-- NOTE: plain-text passwords for these demo accounts are documented below the hashes,
-- so the team can actually log in and test. Do not ship real hashed passwords like this
-- in a real product; this is fine only because it's sample/demo data for a class project.
-- admin@nsbm.ac.lk           -> password: admin123
-- kamal@student.nsbm.ac.lk   -> password: student123
-- nimali@student.nsbm.ac.lk  -> password: student123
INSERT INTO `users` (`id`, `full_name`, `email`, `password`, `role`) VALUES
(1, 'System Admin', 'admin@nsbm.ac.lk', '$2y$10$cM3H7OZvrF3j5fv37FFf8.HgKNKWdM.wLbNIeXq/sqBdcRjeMoLkC', 'admin'),
(2, 'Kamal Perera', 'kamal@student.nsbm.ac.lk', '$2y$10$m2VDZX4cFvxorKzDscXCpe2A1kk8b1Avjr.GnlmdF2eIZca2OXS2i', 'student'),
(3, 'Nimali Fernando', 'nimali@student.nsbm.ac.lk', '$2y$10$m2VDZX4cFvxorKzDscXCpe2A1kk8b1Avjr.GnlmdF2eIZca2OXS2i', 'student');

INSERT INTO `categories` (`id`, `name`, `description`) VALUES
(1, 'Technology & Coding', 'Workshops, hackathons, and guest lectures on software & AI'),
(2, 'Sports & Fitness', 'Inter-faculty sports events, tournaments, and fitness sessions'),
(3, 'Cultural & Arts', 'Music festivals, drama club performances, and art showcases'),
(4, 'Academic & Career', 'Career fairs, resume building, and academic symposiums');

INSERT INTO `events` (`id`, `title`, `description`, `category_id`, `event_date`, `event_time`, `venue`, `capacity`, `status`, `created_by`) VALUES
(1, 'NSBM Hackathon 2026', 'Annual 24-hour university hackathon. Build innovative software solutions and win exciting prizes!', 1, DATE_ADD(CURDATE(), INTERVAL 14 DAY), '09:00:00', 'Computing Auditorium & Lab 4', 100, 'Upcoming', 1),
(2, 'Inter-Faculty Cricket Championship', 'Witness the ultimate battle of faculties at the university main grounds.', 2, DATE_ADD(CURDATE(), INTERVAL 20 DAY), '08:30:00', 'NSBM Main Sports Complex', 200, 'Upcoming', 1),
(3, 'Career Guidance & Resume Clinic', 'Meet top tech recruiters, refine your CV, and get mock interview practice.', 4, DATE_ADD(CURDATE(), INTERVAL 5 DAY), '13:30:00', 'Auditorium A', 50, 'Upcoming', 1);

INSERT INTO `registrations` (`event_id`, `student_id`) VALUES
(1, 2),
(3, 2),
(1, 3);

INSERT INTO `announcements` (`id`, `title`, `content`, `event_id`, `created_by`) VALUES
(1, 'Welcome to NSBM EventHub!', 'We are excited to launch our official student event scheduling platform. Browse upcoming events and reserve your seats early!', NULL, 1),
(2, 'Hackathon Pre-workshop Details', 'All participants registered for NSBM Hackathon 2026 are requested to join the Slack channel sent via email.', 1, 1);

-- Add image_url column if not already added
ALTER TABLE announcements 
ADD COLUMN image_url VARCHAR(255) DEFAULT NULL AFTER content;

-- Insert 2 announcements using your existing images
INSERT INTO announcements (title, content, image_url, event_id, created_by, created_at)
VALUES 
('Hackathon Registration Open', 
 'Register now for the NSBM Hackathon 2026. Limited seats available!', 
 'assets/images/image1.webp', 
 NULL, 
 1, 
 NOW()),

('Guest Lecture on AI Ethics', 
 'Join us for a lecture on AI Ethics by Dr. Perera, Sept 10 at Auditorium A.', 
 'assets/images/Image2.jpeg', 
 NULL, 
 1, 
 NOW());