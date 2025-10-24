-- ================================================
-- STEP 1: First run this in phpMyAdmin SQL tab
-- ================================================

-- Drop existing tables (in correct order due to foreign keys)
SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS `submissions`;
DROP TABLE IF EXISTS `quizzes`;
DROP TABLE IF EXISTS `lessons`;
DROP TABLE IF EXISTS `enrollments`;
DROP TABLE IF EXISTS `announcements`;
DROP TABLE IF EXISTS `courses`;
DROP TABLE IF EXISTS `users`;
DROP TABLE IF EXISTS `migrations`;
SET FOREIGN_KEY_CHECKS = 1;

-- ================================================
-- Create migrations table
-- ================================================
CREATE TABLE `migrations` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `version` varchar(255) NOT NULL,
  `class` varchar(255) NOT NULL,
  `group` varchar(255) NOT NULL,
  `namespace` varchar(255) NOT NULL,
  `time` int(11) NOT NULL,
  `batch` int(11) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ================================================
-- Create users table
-- ================================================
CREATE TABLE `users` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','teacher','student') NOT NULL DEFAULT 'student',
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ================================================
-- Create courses table
-- ================================================
CREATE TABLE `courses` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ================================================
-- Create enrollments table
-- ================================================
CREATE TABLE `enrollments` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int(11) UNSIGNED NOT NULL,
  `course_id` int(11) UNSIGNED NOT NULL,
  `enrollment_date` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `course_id` (`course_id`),
  CONSTRAINT `enrollments_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `enrollments_ibfk_2` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ================================================
-- Create lessons table
-- ================================================
CREATE TABLE `lessons` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `course_id` int(11) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `course_id` (`course_id`),
  CONSTRAINT `lessons_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ================================================
-- Create quizzes table
-- ================================================
CREATE TABLE `quizzes` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `lesson_id` int(11) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `lesson_id` (`lesson_id`),
  CONSTRAINT `quizzes_ibfk_1` FOREIGN KEY (`lesson_id`) REFERENCES `lessons` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ================================================
-- Create submissions table
-- ================================================
CREATE TABLE `submissions` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `quiz_id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `score` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `quiz_id` (`quiz_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `submissions_ibfk_1` FOREIGN KEY (`quiz_id`) REFERENCES `quizzes` (`id`) ON DELETE CASCADE,
  CONSTRAINT `submissions_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ================================================
-- Create announcements table
-- ================================================
CREATE TABLE `announcements` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `content` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ================================================
-- Insert sample admin user
-- Email: admin@example.com
-- Password: admin123
-- ================================================
INSERT INTO `users` (`name`, `email`, `password`, `role`, `status`, `created_at`, `updated_at`) VALUES
('Admin User', 'admin@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 'active', NOW(), NOW());

-- ================================================
-- Insert migration records
-- ================================================
INSERT INTO `migrations` (`version`, `class`, `group`, `namespace`, `time`, `batch`) VALUES
('2025-10-10-105034', 'App\\Database\\Migrations\\CreateUsersTable', 'default', 'App', UNIX_TIMESTAMP(), 1),
('2025-10-10-105035', 'App\\Database\\Migrations\\CreateCoursesTable', 'default', 'App', UNIX_TIMESTAMP(), 1),
('2025-10-10-105036', 'App\\Database\\Migrations\\CreateEnrollmentsTable', 'default', 'App', UNIX_TIMESTAMP(), 1),
('2025-10-10-105037', 'App\\Database\\Migrations\\CreateLessonsTable', 'default', 'App', UNIX_TIMESTAMP(), 1),
('2025-10-10-105038', 'App\\Database\\Migrations\\CreateQuizzesTable', 'default', 'App', UNIX_TIMESTAMP(), 1),
('2025-10-10-105038', 'App\\Database\\Migrations\\CreateSubmissionsTable', 'default', 'App', UNIX_TIMESTAMP(), 1);

-- ================================================
-- Done! You can now login with:
-- Email: admin@example.com
-- Password: admin123
-- ================================================
