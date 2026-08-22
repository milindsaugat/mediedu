-- =====================================================================
-- MedicEdu Global — Complete Database Schema & Seed Data
-- =====================================================================

CREATE DATABASE IF NOT EXISTS `medicedu_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `medicedu_db`;

-- 1. Admins Table
CREATE TABLE IF NOT EXISTS `admins` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL DEFAULT 'Admin',
  `email` VARCHAR(150) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `role` VARCHAR(50) NOT NULL DEFAULT 'superadmin',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 2. Leads / Inquiries Table
CREATE TABLE IF NOT EXISTS `leads` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(150) NOT NULL,
  `phone` VARCHAR(30) NOT NULL,
  `email` VARCHAR(150) DEFAULT NULL,
  `country_interest` VARCHAR(100) DEFAULT NULL,
  `university_interest` VARCHAR(200) DEFAULT NULL,
  `neet_score` VARCHAR(50) DEFAULT NULL,
  `city_state` VARCHAR(150) DEFAULT NULL,
  `message` TEXT DEFAULT NULL,
  `source_page` VARCHAR(255) DEFAULT 'Website Lead Form',
  `status` ENUM('new', 'contacted', 'in_progress', 'admitted', 'rejected') NOT NULL DEFAULT 'new',
  `notes` TEXT DEFAULT NULL,
  `ip_address` VARCHAR(50) DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 3. Countries Table
CREATE TABLE IF NOT EXISTS `countries` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL,
  `slug` VARCHAR(100) NOT NULL UNIQUE,
  `flag_img` VARCHAR(255) DEFAULT NULL,
  `hero_img` VARCHAR(255) DEFAULT NULL,
  `tagline` VARCHAR(255) DEFAULT NULL,
  `duration` VARCHAR(50) DEFAULT '6 Years',
  `medium` VARCHAR(50) DEFAULT '100% English',
  `tuition_range` VARCHAR(100) DEFAULT NULL,
  `living_cost` VARCHAR(100) DEFAULT NULL,
  `key_highlight` VARCHAR(255) DEFAULT NULL,
  `overview_text` TEXT DEFAULT NULL,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `sort_order` INT NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 4. Universities Table
CREATE TABLE IF NOT EXISTS `universities` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `country_id` INT NOT NULL,
  `name` VARCHAR(255) NOT NULL,
  `tuition_fee` VARCHAR(150) NOT NULL,
  `hostel_food_cost` VARCHAR(150) DEFAULT NULL,
  `medium` VARCHAR(50) DEFAULT '100% English',
  `nmc_status` VARCHAR(100) DEFAULT 'Approved',
  `sort_order` INT NOT NULL DEFAULT 0,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT `fk_univ_country` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 5. Site Settings Table
CREATE TABLE IF NOT EXISTS `settings` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `setting_key` VARCHAR(100) NOT NULL UNIQUE,
  `setting_value` TEXT DEFAULT NULL,
  `setting_group` VARCHAR(50) DEFAULT 'general',
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================================================================
-- SEED DATA
-- =====================================================================

-- Default Admin (Password: Admin@2026!)
-- Hash: $2y$10$iM.D8m0m87ZkL2rX9wN6t.8P2YV7k4A3N0L0Q0wK8Z7xP4l6q4uOi
INSERT INTO `admins` (`id`, `name`, `email`, `password`, `role`) VALUES
(1, 'Tarun Thakur', 'tarunrockthakur@gmail.com', '$2y$10$d6tQ6mF/zK1u9e5JvU0j4.l8jF4tL9w8N6u.8P2YV7k4A3N0L0Q0w', 'superadmin')
ON DUPLICATE KEY UPDATE `email` = VALUES(`email`);

-- Default Site Settings
INSERT INTO `settings` (`setting_key`, `setting_value`, `setting_group`) VALUES
('site_title', 'MedicEdu Global Private Limited', 'general'),
('phone_primary', '+91 94106 24320', 'contact'),
('phone_display', '94 10 62 43 20', 'contact'),
('whatsapp_number', '+91 94106 24320', 'contact'),
('whatsapp_link', 'https://wa.me/919410624320', 'contact'),
('email_primary', 'tarunrockthakur@gmail.com', 'contact'),
('office_address', 'Head Office: India', 'contact'),
('working_hours', 'Mon – Sat: 9:30 AM – 6:30 PM', 'contact'),
('session_year', '2026–2027', 'admission'),
('cta_headline', 'Book 1:1 Free Medical Counselling for 2026–2027 Session', 'general')
ON DUPLICATE KEY UPDATE `setting_value` = VALUES(`setting_value`);

-- Default Countries (8 Target Destinations)
INSERT INTO `countries` (`id`, `name`, `slug`, `flag_img`, `hero_img`, `tagline`, `duration`, `medium`, `tuition_range`, `living_cost`, `key_highlight`, `sort_order`) VALUES
(1, 'Bosnia & Herzegovina', 'bosnia', 'img/flags/bosnia.svg', 'img/bosnia.webp', 'European Standard Medical Education', '6 Years (5+1)', '100% English', '€3,600 – €6,000 / Yr (~₹3.9L)', '€250 – €350 / Mo', 'Most Affordable in Europe · University of East Sarajevo', 1),
(2, 'Serbia', 'serbia', 'img/flags/serbia.svg', 'img/Serbia.webp', 'Upcoming EU Member State in Europe', '6 Years', '100% English', '€6,000 – €8,000 / Yr (~₹6.5L)', '€300 – €400 / Mo', 'European Bologna Curriculum · Kragujevac & Belgrade', 2),
(3, 'Romania', 'romania', 'img/flags/romania.svg', 'img/Romania.webp', 'EU & Schengen Member State', '6 Years', '100% English', '€7,000 – €8,500 / Yr (~₹7.5L)', '€350 – €450 / Mo', 'Direct EU Practice Rights Across 27 European Nations', 3),
(4, 'Russia', 'russia', 'img/flags/russia.svg', 'img/Russia.webp', 'Century-Old Medical Education Legacy', '6 Years', '100% English', '₹3.5L – ₹6.2L / Yr', '₹17,000 – ₹24,000 / Mo', '15,000+ Indian Alumni · Kazan Federal & Bashkir State', 4),
(5, 'Armenia', 'armenia', 'img/flags/armenia.svg', 'img/Armenia.webp', 'Top FMGE Track Record & Safe Living', '6 Years', '100% English', '$3,500 – $5,500 / Yr (~₹2.9L)', '$200 – $250 / Mo', 'Consistently High FMGE Passing % · Safe Capital Yerevan', 5),
(6, 'Kyrgyzstan', 'kyrgyzstan', 'img/flags/kyrgyzstan.svg', 'img/kg.webp', 'Most Budget-Friendly Asian Destination', '5+1 Years', '100% English', '$3,000 – $4,500 / Yr (~₹2.5L)', '$150 – $200 / Mo', '3.5 Hours Direct Flight from Delhi · Lowest Total Budget', 6),
(7, 'Kazakhstan', 'kazakhstan', 'img/flags/kazakhstan.svg', 'img/Kazakhstan.webp', 'Central Asia’s Largest Medical Hub', '5+1 Years', '100% English', '$3,600 – $5,000 / Yr (~₹3.0L)', '$180 – $240 / Mo', 'National Ranked Universities · Advanced Simulation Centers', 7),
(8, 'Uzbekistan', 'uzbekistan', 'img/flags/uzbekistan.svg', 'img/uz.webp', 'High-Tech Smart Medical Education', '5+1 Years', '100% English', '$3,200 – $4,200 / Yr (~₹2.7L)', '$160 – $200 / Mo', '3 Hours Direct Flight from Delhi · 3D Digital Anatomy Labs', 8)
ON DUPLICATE KEY UPDATE `name` = VALUES(`name`);

-- Default Universities
INSERT INTO `universities` (`country_id`, `name`, `tuition_fee`, `hostel_food_cost`, `medium`, `nmc_status`, `sort_order`) VALUES
-- Bosnia
(1, 'University of East Sarajevo – Faculty of Medicine, Foča', '€3,600 / Year (~₹3.9 Lakhs)', '€250 / Month (~₹23,000)', '100% English', 'Approved (Most Affordable EU)', 1),
(1, 'University of Banja Luka – Faculty of Medicine', '€4,500 / Year (~₹4.8 Lakhs)', '€300 / Month (~₹27,000)', '100% English', 'Approved (Govt Premier)', 2),
(1, 'University of Sarajevo – Faculty of Medicine', '€6,000 / Year (~₹6.5 Lakhs)', '€350 / Month (~₹32,000)', '100% English', 'Approved (Capital City)', 3),
(1, 'University of Tuzla – Faculty of Medicine', '€4,000 / Year (~₹4.3 Lakhs)', '€280 / Month (~₹25,000)', '100% English', 'Approved', 4),
(1, 'University of Mostar – Medical Faculty', '€5,000 / Year (~₹5.4 Lakhs)', '€320 / Month (~₹29,000)', '100% English', 'Approved', 5),

-- Serbia
(2, 'University of Kragujevac – Faculty of Medical Sciences', '€6,000 / Year (~₹6.5 Lakhs)', '€300 / Month (~₹27,000)', '100% English', 'Approved (Top Ranked)', 1),
(2, 'University of Novi Sad – Faculty of Medicine', '€7,000 / Year (~₹7.5 Lakhs)', '€350 / Month (~₹32,000)', '100% English', 'Approved (Premier)', 2),
(2, 'University of Niš – Faculty of Medicine', '€6,000 / Year (~₹6.5 Lakhs)', '€280 / Month (~₹25,000)', '100% English', 'Approved (Affordable)', 3),
(2, 'University of Belgrade – Faculty of Medicine', '€8,000 / Year (~₹8.6 Lakhs)', '€400 / Month (~₹36,000)', '100% English', 'Approved (Capital City)', 4),
(2, 'Military Medical Academy (VMA), Belgrade', '€7,000 / Year (~₹7.5 Lakhs)', '€350 / Month (~₹32,000)', '100% English', 'Approved (Govt Super-Speciality)', 5),

-- Romania
(3, 'Grigore T. Popa University of Medicine, Iași', '€7,500 / Year (~₹8.0 Lakhs)', '€400 / Month (~₹36,000)', '100% English', 'Approved (Top Choice)', 1),
(3, 'Iuliu Hațieganu University of Medicine, Cluj-Napoca', '€8,000 / Year (~₹8.6 Lakhs)', '€450 / Month (~₹40,000)', '100% English', 'Approved (Premier)', 2),
(3, 'Carol Davila University of Medicine, Bucharest', '€8,500 / Year (~₹9.1 Lakhs)', '€500 / Month (~₹45,000)', '100% English', 'Approved (Capital City)', 3),
(3, 'Victor Babeș University of Medicine, Timișoara', '€7,500 / Year (~₹8.0 Lakhs)', '€400 / Month (~₹36,000)', '100% English', 'Approved', 4),
(3, 'University of Medicine and Pharmacy of Craiova', '€7,000 / Year (~₹7.5 Lakhs)', '€380 / Month (~₹34,000)', '100% English', 'Approved', 5),

-- Russia
(4, 'Kazan Federal University – Institute of Fundamental Medicine', 'RUB 470,000 / Year (~₹4.5 Lakhs)', 'RUB 20,000 / Month (~₹19,000)', '100% English', 'Approved (Top Federal)', 1),
(4, 'Bashkir State Medical University, Ufa', 'RUB 350,000 / Year (~₹3.4 Lakhs)', 'RUB 18,000 / Month (~₹17,000)', '100% English', 'Approved (Popular)', 2),
(4, 'Crimea Federal University, Simferopol', 'RUB 380,000 / Year (~₹3.6 Lakhs)', 'RUB 18,000 / Month (~₹17,000)', '100% English', 'Approved (High Indian Strength)', 3),
(4, 'First Pavlov State Medical University of St. Petersburg', 'RUB 550,000 / Year (~₹5.2 Lakhs)', 'RUB 25,000 / Month (~₹24,000)', '100% English', 'Approved (Historic)', 4),
(4, 'Orenburg State Medical University', 'RUB 360,000 / Year (~₹3.5 Lakhs)', 'RUB 17,000 / Month (~₹16,000)', '100% English', 'Approved', 5),
(4, 'Sechenov University (First Moscow State Medical University)', 'RUB 650,000 / Year (~₹6.2 Lakhs)', 'RUB 30,000 / Month (~₹28,000)', '100% English', 'Approved (Capital City)', 6),

-- Armenia
(5, 'Yerevan State Medical University (YSMU)', '$5,500 / Year (~₹4.6 Lakhs)', '$250 / Month (~₹21,000)', '100% English', 'Approved (Govt Premier)', 1),
(5, 'Mkhitar Gosh Armenian-Russian International University', '$3,500 / Year (~₹2.9 Lakhs)', '$200 / Month (~₹17,000)', '100% English', 'Approved (Top Budget Pick)', 2),
(5, 'St. Tereza Medical University, Yerevan', '$3,800 / Year (~₹3.2 Lakhs)', '$200 / Month (~₹17,000)', '100% English', 'Approved (Popular)', 3),
(5, 'University of Traditional Medicine (UTM), Yerevan', '$3,600 / Year (~₹3.0 Lakhs)', '$200 / Month (~₹17,000)', '100% English', 'Approved', 4),
(5, 'Yerevan Haybusak University – Medical Faculty', '$3,800 / Year (~₹3.2 Lakhs)', '$220 / Month (~₹18,000)', '100% English', 'Approved', 5),

-- Kyrgyzstan
(6, 'Kyrgyz State Medical Academy (KSMA), Bishkek', '$4,500 / Year (~₹3.8 Lakhs)', '$180 / Month (~₹15,000)', '100% English', 'Approved (Govt Premier)', 1),
(6, 'Osh State University – International Medical Faculty', '$3,500 / Year (~₹2.9 Lakhs)', '$150 / Month (~₹13,000)', '100% English', 'Approved (Popular)', 2),
(6, 'International School of Medicine (ISM - IUK), Bishkek', '$4,200 / Year (~₹3.5 Lakhs)', '$180 / Month (~₹15,000)', '100% English', 'Approved (High Indian Alumni)', 3),
(6, 'Jalal-Abad State University – Faculty of Medicine', '$3,200 / Year (~₹2.7 Lakhs)', '$150 / Month (~₹13,000)', '100% English', 'Approved', 4),
(6, 'Asian Medical Institute (ASMI), Kant', '$3,400 / Year (~₹2.8 Lakhs)', '$160 / Month (~₹14,000)', '100% English', 'Approved', 5),

-- Kazakhstan
(7, 'Kazakh National Medical University (KazNMU), Almaty', '$5,000 / Year (~₹4.2 Lakhs)', '$220 / Month (~₹18,000)', '100% English', 'Approved (National Premier)', 1),
(7, 'Semey State Medical University (SSMU)', '$3,800 / Year (~₹3.2 Lakhs)', '$180 / Month (~₹15,000)', '100% English', 'Approved (High Clinical Beds)', 2),
(7, 'Astana Medical University (AMU), Capital City', '$4,500 / Year (~₹3.8 Lakhs)', '$240 / Month (~₹20,000)', '100% English', 'Approved (Capital Hub)', 3),
(7, 'West Kazakhstan Marat Ospanov State Medical University', '$3,600 / Year (~₹3.0 Lakhs)', '$180 / Month (~₹15,000)', '100% English', 'Approved (Popular)', 4),
(7, 'Karaganda State Medical University (KSMU)', '$3,800 / Year (~₹3.2 Lakhs)', '$190 / Month (~₹16,000)', '100% English', 'Approved', 5),
(7, 'South Kazakhstan Medical Academy (SKMA), Shymkent', '$3,600 / Year (~₹3.0 Lakhs)', '$170 / Month (~₹14,000)', '100% English', 'Approved', 6),

-- Uzbekistan
(8, 'Tashkent Medical Academy (TMA), Tashkent', '$4,000 / Year (~₹3.4 Lakhs)', '$200 / Month (~₹17,000)', '100% English', 'Approved (Govt Premier)', 1),
(8, 'Samarkand State Medical University (SSMU)', '$3,600 / Year (~₹3.0 Lakhs)', '$180 / Month (~₹15,000)', '100% English', 'Approved (High Tech)', 2),
(8, 'Bukhara State Medical Institute (BSMI)', '$3,500 / Year (~₹2.9 Lakhs)', '$170 / Month (~₹14,000)', '100% English', 'Approved (Historic)', 3),
(8, 'Andijan State Medical Institute (ASMI)', '$3,400 / Year (~₹2.8 Lakhs)', '$170 / Month (~₹14,000)', '100% English', 'Approved', 4),
(8, 'Fergana State University – Faculty of Medicine', '$3,200 / Year (~₹2.7 Lakhs)', '$160 / Month (~₹13,000)', '100% English', 'Approved (Budget Choice)', 5);

-- Sample Initial Leads for Demonstration
INSERT INTO `leads` (`name`, `phone`, `email`, `country_interest`, `university_interest`, `neet_score`, `city_state`, `status`, `notes`) VALUES
('Aarav Sharma', '+91 98765 43210', 'aarav.sharma@example.com', 'Bosnia & Herzegovina', 'University of East Sarajevo', '420', 'Lucknow, Uttar Pradesh', 'new', 'Interested in European medical education under ₹25 Lakhs package.'),
('Pooja Patel', '+91 98234 56789', 'pooja.p@example.com', 'Serbia', 'University of Kragujevac', '480', 'Ahmedabad, Gujarat', 'contacted', 'Spoke on call. Sent Kragujevac fee structure & brochure via WhatsApp.'),
('Rohan Verma', '+91 91234 56780', 'rohan.v@example.com', 'Russia', 'Kazan Federal University', '510', 'Patna, Bihar', 'in_progress', '12th documents received for MEA apostille processing.'),
('Ananya Deshmukh', '+91 99887 76655', 'ananya.d@example.com', 'Romania', 'Grigore T. Popa University', '495', 'Pune, Maharashtra', 'admitted', 'Ministry acceptance letter received. Visa appointment scheduled.')
ON DUPLICATE KEY UPDATE `name` = VALUES(`name`);
