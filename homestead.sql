-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 01, 2025 at 06:41 PM
-- Server version: 8.0.30
-- PHP Version: 8.2.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `homestead`
--

-- --------------------------------------------------------

--
-- Table structure for table `assignments`
--

CREATE TABLE `assignments` (
  `id` bigint UNSIGNED NOT NULL,
  `class_id` int UNSIGNED DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject_id` int UNSIGNED NOT NULL,
  `my_class_id` int UNSIGNED NOT NULL,
  `section_id` int UNSIGNED NOT NULL,
  `teacher_id` int UNSIGNED NOT NULL,
  `due_date` datetime NOT NULL,
  `max_score` int NOT NULL DEFAULT '100',
  `file_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('active','closed','draft') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `assignments`
--

INSERT INTO `assignments` (`id`, `class_id`, `title`, `description`, `subject_id`, `my_class_id`, `section_id`, `teacher_id`, `due_date`, `max_score`, `file_path`, `status`, `created_at`, `updated_at`) VALUES
(1, NULL, 'Sapiente dignissimos eos voluptatibus aut.', 'Quo quia autem perspiciatis quod doloribus. Quia exercitationem dolore quasi. Minus quis ut quae.', 18, 1, 2, 8, '2025-11-20 17:23:08', 15, 'assignments/example.pdf', 'active', '2025-10-28 15:30:26', '2025-10-28 15:30:26'),
(2, NULL, 'Ut assumenda sed.', 'Nesciunt voluptate eum excepturi aut excepturi. Quo illo voluptates reiciendis accusamus. Quis cupiditate sed ut itaque.', 5, 10, 12, 3, '2025-11-16 00:12:20', 14, NULL, 'active', '2025-10-28 15:30:26', '2025-10-28 15:30:26'),
(3, NULL, 'Et qui rem sit.', 'Expedita quod ut voluptatibus recusandae tempore vel eum illum. Ullam quaerat eius repellat nam. Corrupti consequatur nisi officiis exercitationem at quam. Dolores optio veniam unde quos et dolor.', 4, 8, 10, 3, '2025-11-01 02:05:18', 18, NULL, 'active', '2025-10-28 15:30:26', '2025-10-28 15:30:26'),
(4, NULL, 'Fuga possimus adipisci sed.', 'Sint doloremque qui ea pariatur sequi saepe adipisci. Suscipit cupiditate laudantium ut qui ut voluptatum. Perspiciatis ea ducimus vero magnam quia voluptatem fuga. Suscipit vel molestiae deleniti.', 1, 9, 11, 8, '2025-11-13 03:39:16', 17, NULL, 'active', '2025-10-28 15:30:26', '2025-10-28 15:30:26'),
(5, NULL, 'Est quasi recusandae rerum amet rerum.', 'Praesentium nam voluptate voluptatem eligendi ut ipsum voluptate. Consequatur et non omnis consequatur optio porro ipsum. Itaque et qui hic ut tempore.', 19, 6, 8, 8, '2025-11-09 22:20:42', 14, NULL, 'active', '2025-10-28 15:30:26', '2025-10-28 15:30:26'),
(6, NULL, 'Consequatur est quia sed quas laborum.', 'Provident earum est qui nam ducimus consequatur repellat omnis. Unde sed consequuntur doloribus veniam ut perspiciatis a.', 14, 3, 5, 12, '2025-11-20 03:46:44', 13, NULL, 'active', '2025-10-28 15:30:26', '2025-10-28 15:30:26'),
(7, NULL, 'Aut minus officia cupiditate dolorem.', 'Dolorum nobis voluptate aut illum consequuntur quia. Doloribus natus temporibus sint vel. Asperiores similique dolor autem aut eos.', 2, 5, 7, 16, '2025-11-05 10:51:03', 13, NULL, 'active', '2025-10-28 15:30:26', '2025-10-28 15:30:26'),
(8, NULL, 'Aliquam sed quibusdam quam voluptates.', 'Eum perspiciatis nihil sunt nulla rerum aliquam. Aut esse cupiditate veritatis eum ratione fuga reprehenderit. Rerum omnis vitae est.', 10, 7, 9, 8, '2025-11-13 15:22:51', 17, 'assignments/example.pdf', 'active', '2025-10-28 15:30:26', '2025-10-28 15:30:26'),
(9, NULL, 'Libero tenetur aut aut dolor.', 'Itaque omnis quis omnis repellendus. Et quia et ea amet tempora provident qui.', 10, 9, 11, 8, '2025-10-31 12:39:46', 12, NULL, 'active', '2025-10-28 15:30:26', '2025-10-28 15:30:26'),
(10, NULL, 'Id et nesciunt facilis ducimus.', 'Nihil et aliquid ad magnam tempore mollitia. Necessitatibus vel magnam rerum. Hic blanditiis sed non in cumque sit saepe rerum.', 4, 1, 1, 8, '2025-10-29 17:04:39', 10, 'assignments/example.pdf', 'active', '2025-10-28 15:30:26', '2025-10-28 15:30:26'),
(11, NULL, 'Veritatis eos vero dolorem nesciunt.', 'Voluptatum eaque dolorem aspernatur dolor minus mollitia. Omnis quasi possimus eum ullam. Quia quasi ut error ab enim omnis incidunt quidem.', 20, 10, 12, 8, '2025-11-13 17:38:28', 15, 'assignments/example.pdf', 'active', '2025-10-28 15:30:26', '2025-10-28 15:30:26'),
(12, NULL, 'Perferendis animi delectus aliquam consequatur.', 'Dolorum illum nihil sed consequuntur magni possimus itaque ipsam. Qui blanditiis soluta recusandae voluptatibus. Non ut magnam odio voluptas.', 17, 4, 6, 8, '2025-11-24 23:09:48', 11, 'assignments/example.pdf', 'active', '2025-10-28 15:30:26', '2025-10-28 15:30:26'),
(13, NULL, 'Nisi tempore alias voluptatibus.', 'Ipsa maxime voluptates reprehenderit. Nemo voluptatem ipsam rerum sed culpa laudantium similique. Eum exercitationem sunt enim. Pariatur quas dolores officiis voluptas.', 17, 9, 11, 16, '2025-11-26 12:59:56', 20, 'assignments/example.pdf', 'active', '2025-10-28 15:30:26', '2025-10-28 15:30:26'),
(14, NULL, 'Voluptatem nihil quisquam est.', 'Asperiores adipisci quos labore autem velit voluptatem. Perspiciatis esse similique nisi et quam neque. Eius ipsam ea quasi neque.', 5, 6, 8, 3, '2025-11-17 16:55:42', 18, NULL, 'active', '2025-10-28 15:30:26', '2025-10-28 15:30:26'),
(15, NULL, 'Laborum aut necessitatibus et.', 'Nostrum omnis ipsam ab tempore quia aliquid. Culpa enim voluptas aliquam possimus molestiae voluptas hic quia. Aut dolores voluptatem tenetur qui aut exercitationem autem officia.', 9, 4, 6, 8, '2025-11-18 18:15:39', 11, 'assignments/example.pdf', 'active', '2025-10-28 15:30:26', '2025-10-28 15:30:26'),
(16, NULL, 'Quo molestiae tempore quis labore et.', 'Debitis delectus et assumenda ut. Adipisci provident magnam voluptates temporibus totam qui sunt. Est voluptate fugit id sint voluptate et. Dolor similique neque maxime a sunt quisquam.', 3, 2, 3, 3, '2025-11-14 16:39:18', 16, NULL, 'active', '2025-10-28 15:30:26', '2025-10-28 15:30:26'),
(17, NULL, 'Omnis et inventore qui est explicabo.', 'Doloremque amet dolorum fuga ratione dicta molestiae. Libero non non officiis quia aut. Beatae dignissimos ab perspiciatis. Dignissimos eum fuga et iusto dolores doloremque atque.', 2, 1, 1, 8, '2025-11-06 20:03:28', 12, NULL, 'active', '2025-10-28 15:30:26', '2025-10-28 15:30:26'),
(18, NULL, 'Alias quia voluptates ex aut.', 'Molestiae aut veniam aliquid porro sed maxime. Ut voluptas consequatur molestiae nihil eos eum sint. Ut sint nemo nostrum repellendus eum impedit. Et nesciunt earum soluta labore iusto.', 12, 4, 6, 12, '2025-11-05 06:32:48', 17, NULL, 'active', '2025-10-28 15:30:26', '2025-10-28 15:30:26'),
(19, NULL, 'Facere qui qui ut.', 'Laudantium dolor aut dolores est nobis error. Omnis voluptas consequatur nihil rerum. Ex temporibus neque est quo veritatis est in. Sapiente corrupti ut vel in reiciendis quas quae.', 4, 3, 5, 12, '2025-11-16 14:16:46', 10, 'assignments/example.pdf', 'active', '2025-10-28 15:30:26', '2025-10-28 15:30:26'),
(20, NULL, 'Voluptatem adipisci error aut ipsam ut.', 'Est qui qui soluta quia. Sint enim fuga nesciunt ut tempore maxime reiciendis. Nihil minima a est similique ratione nam. Enim rerum velit ut quis in voluptatibus aut eius.', 12, 5, 7, 12, '2025-11-09 08:36:24', 20, NULL, 'active', '2025-10-28 15:30:26', '2025-10-28 15:30:26'),
(21, NULL, 'Autem ea ut eligendi.', 'Quis non molestiae magni tempore sint eveniet officia. Tempora reiciendis rerum aspernatur non omnis. Perferendis et porro ad error. Beatae id illo id mollitia officia.', 2, 5, 7, 8, '2025-11-17 14:29:19', 12, NULL, 'active', '2025-10-28 15:30:26', '2025-10-28 15:30:26'),
(22, NULL, 'Vitae aut autem.', 'Rerum iste placeat accusantium ut perferendis in. Aut qui iusto qui dolores dolor sequi. Cupiditate eum dolorem rerum exercitationem qui at. Unde laboriosam est cum velit eveniet nemo blanditiis eius.', 7, 4, 6, 3, '2025-11-13 15:13:34', 14, NULL, 'active', '2025-10-28 15:30:26', '2025-10-28 15:30:26'),
(23, NULL, 'Tempora exercitationem a aut qui omnis.', 'Ut porro soluta beatae est. Quae voluptas atque temporibus tenetur. Magnam doloremque dolores fugiat et ipsum amet quia.', 16, 9, 11, 12, '2025-11-19 11:16:03', 18, NULL, 'active', '2025-10-28 15:30:26', '2025-10-28 15:30:26'),
(24, NULL, 'Aut voluptatum voluptas fugit dolores qui.', 'Quo doloremque iusto tenetur. Cumque debitis a atque voluptatem. Earum dolor in totam et laboriosam soluta ipsa. Consequatur esse recusandae explicabo praesentium qui illo.', 12, 9, 11, 12, '2025-11-19 09:42:32', 11, NULL, 'active', '2025-10-28 15:30:26', '2025-10-28 15:30:26'),
(25, NULL, 'Molestias nihil quidem nisi ullam.', 'Qui numquam aperiam voluptate sit. Nobis sapiente sint aut ad aut. Sit doloremque eum et accusamus corporis perferendis fugiat non.', 16, 8, 10, 3, '2025-11-06 04:39:15', 18, NULL, 'active', '2025-10-28 15:30:26', '2025-10-28 15:30:26'),
(26, NULL, 'Alias natus molestias est tenetur.', 'Nulla omnis inventore ut quasi expedita iusto totam. Dolor non consequatur explicabo. Consequatur distinctio quia sit quam animi sed dolor. Et reiciendis velit labore.', 13, 10, 12, 12, '2025-10-31 07:26:39', 19, 'assignments/example.pdf', 'active', '2025-10-28 15:30:26', '2025-10-28 15:30:26'),
(27, NULL, 'Doloremque facere et incidunt.', 'Soluta ratione aut officia reiciendis. Molestias ad hic quae. Asperiores repellendus molestias aut sunt non tempora. Illum et mollitia sed libero aliquam laborum eum.', 9, 8, 10, 3, '2025-10-29 11:54:36', 10, 'assignments/example.pdf', 'active', '2025-10-28 15:30:26', '2025-10-28 15:30:26'),
(28, NULL, 'Rerum qui commodi est aut tempore.', 'Laboriosam cumque necessitatibus voluptatum fuga exercitationem et qui. Nihil sequi ut temporibus rerum dignissimos adipisci. Voluptatibus a autem et autem rerum. Nesciunt soluta iusto est vitae.', 3, 9, 11, 8, '2025-11-10 11:27:52', 15, 'assignments/example.pdf', 'active', '2025-10-28 15:30:26', '2025-10-28 15:30:26'),
(29, NULL, 'Ipsa repellat et at.', 'Adipisci rerum quia labore corporis nesciunt et. Velit velit voluptas aliquid ipsa dolor eos quo. Fugit iure nemo quam.', 20, 7, 9, 8, '2025-11-04 16:37:53', 11, NULL, 'active', '2025-10-28 15:30:26', '2025-10-28 15:30:26'),
(30, NULL, 'Omnis nihil quae sunt.', 'Fugiat esse molestiae ducimus unde error omnis eius. Necessitatibus voluptatem qui dolorem voluptatem enim. Placeat non laudantium facilis fuga minima voluptas odit doloribus.', 14, 8, 10, 12, '2025-11-19 13:05:08', 19, NULL, 'active', '2025-10-28 15:30:26', '2025-10-28 15:30:26');

-- --------------------------------------------------------

--
-- Table structure for table `assignment_submissions`
--

CREATE TABLE `assignment_submissions` (
  `id` bigint UNSIGNED NOT NULL,
  `assignment_id` bigint UNSIGNED NOT NULL,
  `student_id` int UNSIGNED NOT NULL,
  `submission_text` text COLLATE utf8mb4_unicode_ci,
  `file_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `submitted_at` datetime NOT NULL,
  `score` int DEFAULT NULL,
  `teacher_feedback` text COLLATE utf8mb4_unicode_ci,
  `status` enum('submitted','graded','late') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'submitted',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `assignment_submissions`
--

INSERT INTO `assignment_submissions` (`id`, `assignment_id`, `student_id`, `submission_text`, `file_path`, `submitted_at`, `score`, `teacher_feedback`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 3, 'Dolore quo et incidunt aut dignissimos ratione ut. Saepe voluptas officia ut inventore. Similique ea expedita officiis eum beatae. Qui alias enim nihil natus non recusandae perferendis.', NULL, '2025-10-28 16:30:26', 5, NULL, 'graded', '2025-10-28 15:30:26', '2025-10-28 15:30:26'),
(2, 1, 4, 'Sit non sit eius perferendis sed. Ipsam architecto quod blanditiis maxime facilis qui. Aut omnis praesentium quasi repudiandae dolorem saepe.', 'C:\\Users\\bedi-\\AppData\\Local\\Temp\\fak9776.tmp', '2025-10-28 16:30:26', 1, 'Eveniet aspernatur tenetur in quam praesentium ut laboriosam. Non nostrum qui eos nam ea saepe dicta veniam. Vel non consequatur ipsam ea omnis sit.', 'graded', '2025-10-28 15:30:26', '2025-10-28 15:30:26'),
(3, 1, 8, 'Voluptatibus non amet incidunt et earum beatae. Recusandae quidem ut excepturi possimus. Ut totam ea et velit autem consequatur et. Explicabo commodi ex provident aut. Dolores odio dolorem aut.', NULL, '2025-10-28 16:30:26', 4, 'Deserunt laboriosam quisquam magnam quisquam cum. Ipsa dolorem id voluptas dolore laudantium laborum corporis suscipit.', 'graded', '2025-10-28 15:30:26', '2025-10-28 15:30:26'),
(4, 1, 10, 'Iusto totam assumenda animi necessitatibus. Aperiam minima ad impedit sequi et quasi. Autem dolores fugit corporis provident. Velit numquam temporibus voluptas cupiditate.', NULL, '2025-10-28 16:30:26', 9, 'Et qui aut excepturi molestias incidunt. Qui qui qui quisquam libero.', 'graded', '2025-10-28 15:30:26', '2025-10-28 15:30:26'),
(5, 1, 11, 'Qui quaerat alias corrupti necessitatibus labore. Cum eveniet ea aliquid dolor sit vero nemo. Corrupti qui voluptas enim dolorem.', NULL, '2025-10-28 16:30:26', NULL, NULL, 'submitted', '2025-10-28 15:30:26', '2025-10-28 15:30:26'),
(6, 1, 14, 'Est voluptas quisquam eligendi rerum et. Unde ut doloribus deserunt repudiandae beatae possimus tempore. Earum animi qui tempore dolor illum.', 'C:\\Users\\bedi-\\AppData\\Local\\Temp\\fak9787.tmp', '2025-10-28 16:30:26', NULL, NULL, 'submitted', '2025-10-28 15:30:26', '2025-10-28 15:30:26'),
(7, 1, 15, 'Aut itaque officiis eos. Dolorum debitis et tenetur. Officiis voluptatem eos qui veniam.', 'C:\\Users\\bedi-\\AppData\\Local\\Temp\\fak9788.tmp', '2025-10-28 16:30:26', NULL, NULL, 'submitted', '2025-10-28 15:30:26', '2025-10-28 15:30:26'),
(8, 1, 18, 'Aut illum iste voluptatem. Accusamus sint et maxime minus. Voluptatem culpa praesentium quia voluptas beatae.', NULL, '2025-10-28 16:30:26', 13, 'Ea voluptas recusandae aut magnam consequatur rerum. Consequuntur et dicta aut odio alias. Iure magnam eum labore reprehenderit.', 'graded', '2025-10-28 15:30:26', '2025-10-28 15:30:26'),
(9, 2, 5, 'Aut et magnam recusandae molestiae ipsa. Quos quia voluptates perferendis et eius minus. Voluptas facere magni in.', 'C:\\Users\\bedi-\\AppData\\Local\\Temp\\fak9799.tmp', '2025-10-28 16:30:26', 0, 'Ea quo rem et veritatis voluptatibus ratione. Magni aut ut dignissimos dignissimos accusamus provident quibusdam. Omnis et quam voluptates occaecati unde iste.', 'graded', '2025-10-28 15:30:26', '2025-10-28 15:30:26'),
(10, 2, 6, 'Nihil porro rerum est praesentium fugit eum. Ea sequi sit deserunt quia. Magni earum facere quibusdam quo vel dolor voluptatem. Enim necessitatibus mollitia modi voluptatum repellat.', NULL, '2025-10-28 16:30:26', 10, NULL, 'graded', '2025-10-28 15:30:26', '2025-10-28 15:30:26'),
(11, 2, 8, 'At et illo consequatur velit. Fugit harum blanditiis et dolorem omnis sint. Quia dolore minima nemo ut iure modi.', 'C:\\Users\\bedi-\\AppData\\Local\\Temp\\fak97A9.tmp', '2025-10-28 16:30:26', 10, 'Ducimus est optio exercitationem molestias error. Neque quia qui autem sed sint quos. Delectus molestiae facilis molestiae.', 'graded', '2025-10-28 15:30:26', '2025-10-28 15:30:26'),
(12, 2, 10, 'Odit asperiores minus totam commodi unde ipsam minima. Quisquam harum qui quisquam non. Fuga debitis explicabo et blanditiis.', NULL, '2025-10-28 16:30:26', NULL, NULL, 'submitted', '2025-10-28 15:30:26', '2025-10-28 15:30:26'),
(13, 2, 12, 'Sequi qui et omnis ex assumenda beatae. Itaque rerum necessitatibus dolorum ad mollitia. Nemo quo est tempore ipsum tempore. Est molestias sunt odit inventore voluptatem sequi et possimus. Cupiditate id quisquam nulla recusandae.', NULL, '2025-10-28 16:30:26', 10, 'Praesentium delectus qui ea sunt ad est et et. Iure eius sunt aperiam.', 'graded', '2025-10-28 15:30:26', '2025-10-28 15:30:26'),
(14, 2, 13, 'Repellendus ducimus velit animi aut est. Officiis et quam neque quod voluptatibus. Odio et quibusdam quae sunt sunt mollitia voluptatum.', 'C:\\Users\\bedi-\\AppData\\Local\\Temp\\fak97BA.tmp', '2025-10-28 16:30:26', NULL, NULL, 'submitted', '2025-10-28 15:30:26', '2025-10-28 15:30:26'),
(15, 2, 16, 'Eum excepturi nemo molestiae. Laboriosam omnis saepe et atque.', 'C:\\Users\\bedi-\\AppData\\Local\\Temp\\fak97CB.tmp', '2025-10-28 16:30:26', 0, 'Rerum ratione ut velit hic voluptas similique. Aut sed facere neque hic earum id debitis voluptatem.', 'graded', '2025-10-28 15:30:26', '2025-10-28 15:30:26'),
(16, 2, 17, 'Qui accusantium sed voluptas fugiat quos qui est. Blanditiis ab rem ratione id. Suscipit veritatis consequatur natus minus. Ex et eligendi optio cumque culpa placeat.', 'C:\\Users\\bedi-\\AppData\\Local\\Temp\\fak97CC.tmp', '2025-10-28 16:30:26', 13, 'Similique dignissimos inventore temporibus porro natus qui necessitatibus aliquid. Earum minus sequi voluptatum quis ut.', 'graded', '2025-10-28 15:30:26', '2025-10-28 15:30:26'),
(17, 2, 19, 'Dolores et nulla debitis reprehenderit neque. Hic enim molestias architecto. Quo est natus quisquam quam. Tenetur at culpa doloremque inventore nesciunt sit excepturi nesciunt.', NULL, '2025-10-28 16:30:26', 7, 'Eius sint saepe rerum sequi repudiandae impedit. Dolorum possimus quia voluptas non corrupti molestias. Rerum similique eveniet iste asperiores totam quam.', 'graded', '2025-10-28 15:30:26', '2025-10-28 15:30:26'),
(18, 3, 2, 'Officiis omnis quos natus velit dolorem sunt. Laudantium aut recusandae neque eum a error qui. Maxime quis ut qui aut earum.', NULL, '2025-10-28 16:30:26', 17, 'Quo dicta debitis aut consequuntur quod in. Quisquam et placeat odit nostrum id vero nemo. Voluptatem officiis velit consequatur magnam molestiae nemo eos.', 'graded', '2025-10-28 15:30:26', '2025-10-28 15:30:26'),
(19, 3, 7, 'Accusantium harum minima molestias corrupti ut quod. Unde pariatur molestiae fugiat in. Illo nam et voluptatum distinctio maxime qui. Qui voluptate consequatur temporibus qui.', NULL, '2025-10-28 16:30:26', 3, 'Minus perferendis sed non culpa doloribus. Odit consequatur culpa voluptas et voluptatem non fugit.', 'graded', '2025-10-28 15:30:26', '2025-10-28 15:30:26'),
(20, 3, 9, 'Quo vel reprehenderit saepe voluptatum vel accusantium. Voluptates dolor et impedit ex. Et sunt corrupti consequatur omnis quo eius. Quia delectus magni fuga atque temporibus laudantium sed. Porro perspiciatis quia eum nesciunt laborum dolores qui.', NULL, '2025-10-28 16:30:26', 3, 'Quidem et in praesentium aut asperiores doloremque. Non voluptate illo reiciendis ea rerum nemo fuga est. Necessitatibus vel accusamus voluptas deleniti repudiandae.', 'graded', '2025-10-28 15:30:26', '2025-10-28 15:30:26'),
(21, 3, 10, 'Cumque maiores hic dolorum saepe quibusdam culpa. Suscipit rerum dicta non voluptate laborum. Consequuntur voluptatem eos laudantium accusantium. Error eaque odit rerum eveniet. Quis deleniti voluptatum ipsum provident.', NULL, '2025-10-28 16:30:26', 7, 'Neque aperiam esse iste. Atque fugit non repellendus distinctio repellat.', 'graded', '2025-10-28 15:30:26', '2025-10-28 15:30:26'),
(22, 3, 11, 'Temporibus dicta et aperiam quia vero est sunt. Rem quia aliquam assumenda quidem vero perspiciatis ab. Rerum distinctio fugit molestiae ratione.', NULL, '2025-10-28 16:30:26', 5, 'Qui cum saepe praesentium praesentium voluptas eos. Et molestiae ducimus natus aut praesentium voluptatem quo. Animi occaecati ea et qui perferendis quo numquam quos.', 'graded', '2025-10-28 15:30:26', '2025-10-28 15:30:26'),
(23, 3, 13, 'Iste sapiente odit reprehenderit aliquam reprehenderit deleniti. Numquam ab sed et dolore. Aut sint vero ea repellendus molestiae similique aliquam molestiae.', NULL, '2025-10-28 16:30:26', NULL, NULL, 'submitted', '2025-10-28 15:30:26', '2025-10-28 15:30:26'),
(24, 3, 14, 'Ducimus unde labore perspiciatis. Nostrum non velit soluta tempora non. Consequatur dignissimos sit velit error repudiandae omnis. Consequuntur dolorem autem labore et.', NULL, '2025-10-28 16:30:26', 8, 'Non reprehenderit ea sunt adipisci eum. Molestias nesciunt ipsum quas consectetur ab blanditiis repudiandae. Nobis commodi et quaerat et.', 'graded', '2025-10-28 15:30:26', '2025-10-28 15:30:26'),
(25, 3, 17, 'Aliquam asperiores dolor facilis voluptatum et distinctio. Qui ratione atque quis sed quia eos iste provident. Dolor qui qui veritatis omnis. Dolor magni qui cumque odit itaque vero. Enim aut quia corporis qui nihil id vel.', 'C:\\Users\\bedi-\\AppData\\Local\\Temp\\fak97EC.tmp', '2025-10-28 16:30:26', 10, 'A tempora fuga hic. Beatae ullam et omnis minima.', 'graded', '2025-10-28 15:30:26', '2025-10-28 15:30:26'),
(26, 3, 18, 'Ad repellendus accusamus sunt sint vel perspiciatis. Voluptas occaecati autem odit asperiores odio. Magnam inventore ipsam et sit qui reiciendis. Et quas doloribus facilis dicta qui. Ullam ipsam officiis amet ut consequatur dolor molestias delectus.', NULL, '2025-10-28 16:30:26', 4, 'In qui exercitationem nostrum perspiciatis dolore et maiores et. Facilis et animi illum blanditiis recusandae distinctio explicabo. Quo qui impedit sit.', 'graded', '2025-10-28 15:30:26', '2025-10-28 15:30:26'),
(27, 3, 20, 'Architecto impedit modi hic repellat. Vel nihil quos ea temporibus aut harum. Repellat dolorem voluptatem aut iusto. Eos doloribus ducimus expedita non.', NULL, '2025-10-28 16:30:26', 10, 'Qui odio ut quo aspernatur. Laborum voluptate sapiente eaque minus totam accusantium. Ipsum consequatur libero minima dolores vero sunt.', 'graded', '2025-10-28 15:30:26', '2025-10-28 15:30:26'),
(28, 4, 1, 'Quod aut qui ea et voluptas autem. Sed praesentium enim quasi modi atque dolorum. Eaque deserunt totam fuga ut.', 'C:\\Users\\bedi-\\AppData\\Local\\Temp\\fak97FC.tmp', '2025-10-28 16:30:26', NULL, NULL, 'submitted', '2025-10-28 15:30:26', '2025-10-28 15:30:26'),
(29, 4, 8, 'Maxime illo cumque et quas qui quis. Beatae autem occaecati et. Sed molestiae inventore cupiditate. Sit odio cum quo labore et.', NULL, '2025-10-28 16:30:26', 10, NULL, 'graded', '2025-10-28 15:30:26', '2025-10-28 15:30:26'),
(30, 4, 12, 'Ea nulla est unde debitis. Eligendi magni repellat nisi dolorum animi aut esse. Est laboriosam non aut perspiciatis labore aperiam eligendi. Debitis et doloribus saepe molestiae voluptates architecto qui.', NULL, '2025-10-28 16:30:26', 7, NULL, 'graded', '2025-10-28 15:30:26', '2025-10-28 15:30:26'),
(31, 4, 17, 'Eius ipsa nisi inventore. Nihil voluptates dolores magnam quo. Ut odit delectus rerum voluptate.', NULL, '2025-10-28 16:30:26', 17, 'Repellendus deserunt quae autem sint in odio enim. Asperiores asperiores consequatur voluptatem autem cumque eos magnam. Ipsa sed ea qui totam.', 'graded', '2025-10-28 15:30:26', '2025-10-28 15:30:26'),
(32, 4, 18, 'Omnis ipsa voluptatem minima et dolorem. Consequatur pariatur inventore qui quas. Possimus veritatis ex id nisi fuga dolor. Officia in animi provident nam tempore.', NULL, '2025-10-28 16:30:26', NULL, NULL, 'submitted', '2025-10-28 15:30:26', '2025-10-28 15:30:26'),
(33, 5, 1, 'Dolore omnis odio a ducimus minima. Corporis officia est iste tenetur quia quis pariatur. Molestiae iure ipsa ullam consequatur. Ad molestias quia accusamus dolores non.', NULL, '2025-10-28 16:30:26', 7, 'Cupiditate dolor animi delectus. Repellendus assumenda dolores distinctio iusto qui et autem.', 'graded', '2025-10-28 15:30:26', '2025-10-28 15:30:26'),
(34, 5, 2, 'Ut et eveniet voluptate. Minus tempora sint non et mollitia hic quis. Amet quia molestiae quae voluptate optio doloribus.', NULL, '2025-10-28 16:30:26', 13, 'Dolore vitae nihil in mollitia. Iure praesentium eum error perspiciatis veniam quibusdam quis. Alias quia rerum qui voluptas voluptas voluptatem.', 'graded', '2025-10-28 15:30:26', '2025-10-28 15:30:26'),
(35, 5, 3, 'Quisquam odit quam veritatis non. Tenetur dolores rerum fugiat rem enim voluptas ipsum. Quaerat delectus et possimus eos at ullam impedit. Corrupti dignissimos et et et voluptate unde.', 'C:\\Users\\bedi-\\AppData\\Local\\Temp\\fak981D.tmp', '2025-10-28 16:30:26', NULL, NULL, 'submitted', '2025-10-28 15:30:26', '2025-10-28 15:30:26'),
(36, 5, 6, 'Architecto mollitia veniam at sit aut qui et. Eaque recusandae sunt nobis ut ut. Deleniti minima blanditiis praesentium ut.', NULL, '2025-10-28 16:30:26', 4, 'Perspiciatis vel repudiandae impedit eius quia nesciunt soluta necessitatibus. Quia sapiente illo deserunt animi molestiae.', 'graded', '2025-10-28 15:30:26', '2025-10-28 15:30:26'),
(37, 5, 7, 'Distinctio tempora veritatis cumque perspiciatis aut. Ab minus ipsa doloribus qui impedit. Magnam repellendus quidem explicabo vitae et asperiores ut.', 'C:\\Users\\bedi-\\AppData\\Local\\Temp\\fak982D.tmp', '2025-10-28 16:30:26', 6, NULL, 'graded', '2025-10-28 15:30:26', '2025-10-28 15:30:26'),
(38, 5, 9, 'Est nobis voluptatem error ea ad et maxime accusantium. Ducimus voluptas debitis et voluptates nihil ipsum. Id ratione sapiente quod sed ipsum. Architecto eos quaerat voluptas ut velit sit.', NULL, '2025-10-28 16:30:26', 10, 'Maiores distinctio sed accusamus qui. Similique dolor commodi veniam incidunt quos.', 'graded', '2025-10-28 15:30:26', '2025-10-28 15:30:26'),
(39, 5, 10, 'Sunt autem tempore hic ratione. Ut occaecati assumenda ut. Eius architecto odio sed dolore qui nesciunt ad quo. Eaque quas ipsum reiciendis neque ut aspernatur. Voluptatem molestiae tenetur dolore reiciendis deleniti.', NULL, '2025-10-28 16:30:26', 13, NULL, 'graded', '2025-10-28 15:30:26', '2025-10-28 15:30:26'),
(40, 5, 11, 'Possimus voluptas sint quaerat tenetur voluptates numquam. Eos et illo explicabo inventore numquam sequi. Molestias consectetur ullam quis cupiditate illum voluptatem nesciunt.', NULL, '2025-10-28 16:30:26', 6, 'Sit vel aspernatur qui iure et vitae quibusdam. Vitae nam neque rerum blanditiis.', 'graded', '2025-10-28 15:30:26', '2025-10-28 15:30:26'),
(41, 5, 13, 'Debitis a quia voluptatem corporis quia eaque sunt. Provident perferendis molestiae repudiandae distinctio adipisci amet ut sint. Impedit quaerat iusto fugit perspiciatis. Voluptatem sint rerum est praesentium adipisci enim. Rerum amet repudiandae laudantium placeat adipisci.', NULL, '2025-10-28 16:30:26', 13, 'Dolorem delectus dolores sint autem blanditiis iusto soluta. Quis blanditiis dolore officia.', 'graded', '2025-10-28 15:30:26', '2025-10-28 15:30:26'),
(42, 5, 14, 'Illo a eos temporibus inventore natus nam at. Ut non magni aut minus dolore et. Dolor non porro beatae adipisci.', 'C:\\Users\\bedi-\\AppData\\Local\\Temp\\fak983E.tmp', '2025-10-28 16:30:26', NULL, NULL, 'submitted', '2025-10-28 15:30:26', '2025-10-28 15:30:26'),
(43, 5, 15, 'Qui eum minima et quo amet dolore et. Optio molestiae recusandae sequi iste quidem omnis quia a.', NULL, '2025-10-28 16:30:26', NULL, NULL, 'submitted', '2025-10-28 15:30:26', '2025-10-28 15:30:26'),
(44, 5, 16, 'Quidem qui aut esse ut architecto voluptas voluptate sint. Nihil praesentium labore repellendus doloribus. Quae tenetur ex voluptas tempora veniam voluptas. Optio molestias ab et a voluptas. Quas totam sit quos.', 'C:\\Users\\bedi-\\AppData\\Local\\Temp\\fak984F.tmp', '2025-10-28 16:30:26', 11, NULL, 'graded', '2025-10-28 15:30:26', '2025-10-28 15:30:26'),
(45, 5, 17, 'Ad fugit rerum dolorem delectus est quis et. Consequatur ut fugiat nemo et veniam repellat consequatur. Qui quia laudantium eveniet vel.', NULL, '2025-10-28 16:30:26', NULL, NULL, 'submitted', '2025-10-28 15:30:26', '2025-10-28 15:30:26'),
(46, 5, 19, 'Dolores tempore laborum et facilis quis mollitia. Aut aut numquam aspernatur libero eligendi est.', NULL, '2025-10-28 16:30:26', NULL, NULL, 'submitted', '2025-10-28 15:30:26', '2025-10-28 15:30:26'),
(47, 6, 2, 'Consequuntur amet facilis aut enim ut. Ut alias itaque numquam eum quia. Qui dicta facere dolor nam ipsa aut. Magni est sit accusamus natus.', NULL, '2025-10-28 16:30:26', 13, 'Nulla et doloremque aut qui. Enim aut quisquam qui explicabo maxime vel voluptatum. Nostrum sit quo culpa modi nisi saepe nihil.', 'graded', '2025-10-28 15:30:26', '2025-10-28 15:30:26'),
(48, 6, 4, 'Dolores mollitia dolores non non officia. Aliquam id incidunt incidunt doloremque ut est. Sequi quas est eligendi vel laborum et non vel. Qui est quos magni optio et quo dignissimos sint.', NULL, '2025-10-28 16:30:26', NULL, NULL, 'submitted', '2025-10-28 15:30:26', '2025-10-28 15:30:26'),
(49, 6, 7, 'Temporibus aut id omnis quia dolorem odit. Aperiam ut sit est corrupti rerum dolore ad molestiae. Odio aspernatur dicta eligendi expedita totam totam odit. Sit facere et laboriosam eos. Cupiditate eos quidem unde atque voluptates aut.', NULL, '2025-10-28 16:30:26', 8, NULL, 'graded', '2025-10-28 15:30:26', '2025-10-28 15:30:26'),
(50, 6, 8, 'Iste odit at quod nulla. Et sed quis odit est explicabo. Modi eum et a eaque incidunt consequuntur. Ipsam dolor et et ea incidunt.', NULL, '2025-10-28 16:30:26', 0, 'Iusto ea doloribus facere asperiores deleniti inventore suscipit consequatur. Fugit ipsum itaque voluptatem dolor sed iste fugiat laboriosam.', 'graded', '2025-10-28 15:30:26', '2025-10-28 15:30:26'),
(51, 6, 9, 'Facilis aperiam consequatur provident et aliquid magnam nemo dolorem. Ipsum tempora sed velit sint. Voluptas et et laudantium ipsam enim.', NULL, '2025-10-28 16:30:26', 4, 'Aperiam qui modi quibusdam ipsa aut quaerat. Tenetur debitis in nulla in asperiores est.', 'graded', '2025-10-28 15:30:26', '2025-10-28 15:30:26'),
(52, 6, 11, 'Possimus voluptatem voluptas iusto ex totam sint culpa. Dignissimos maxime reprehenderit est quisquam fugiat. Laboriosam aut est quia dolor ut et. Et officiis optio et modi quisquam qui neque.', 'C:\\Users\\bedi-\\AppData\\Local\\Temp\\fak985F.tmp', '2025-10-28 16:30:26', 12, NULL, 'graded', '2025-10-28 15:30:26', '2025-10-28 15:30:26'),
(53, 6, 14, 'Aut nihil dolor sapiente earum cupiditate animi. Tempore voluptatibus itaque consequatur ut accusamus ab ex. Expedita ex esse nulla magnam.', NULL, '2025-10-28 16:30:26', 10, 'Omnis nihil eos totam. Quidem ut consectetur aut occaecati. Modi enim accusantium dolorum quibusdam quam ut cumque.', 'graded', '2025-10-28 15:30:26', '2025-10-28 15:30:26'),
(54, 7, 2, 'Aliquam quo maxime nesciunt. Dolores dignissimos laboriosam quo autem aliquam velit. Non et sint ea fugiat quaerat. Beatae minus vel ullam consequatur et et.', NULL, '2025-10-28 16:30:26', 9, 'Occaecati aut quod ab maxime facere accusamus. Unde tempora consequatur cumque corporis. Nobis consectetur voluptates sequi voluptatem.', 'graded', '2025-10-28 15:30:26', '2025-10-28 15:30:26'),
(55, 7, 3, 'Quia harum quaerat sit sunt provident. Et est laudantium eos sit nulla non.', 'C:\\Users\\bedi-\\AppData\\Local\\Temp\\fak9870.tmp', '2025-10-28 16:30:26', 10, 'Numquam commodi qui deserunt nesciunt reprehenderit et sapiente ea. Quia quas laboriosam rerum et facere.', 'graded', '2025-10-28 15:30:26', '2025-10-28 15:30:26'),
(56, 7, 8, 'Corporis id ex aut optio accusamus. Quo qui ut dolorum accusamus. Consequatur ut praesentium consectetur molestias vitae. Corrupti est recusandae molestiae accusamus veritatis maxime qui.', NULL, '2025-10-28 16:30:27', 5, 'Reiciendis voluptas temporibus maxime dicta excepturi animi est. Repellat sint ut eos maxime eum consequuntur corrupti. Voluptas occaecati nisi sed rerum voluptas sed fuga.', 'graded', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(57, 7, 10, 'Molestiae molestiae tempora similique ut sed. Velit et officia quas repellat temporibus. Dignissimos at excepturi vel suscipit a.', 'C:\\Users\\bedi-\\AppData\\Local\\Temp\\fak9880.tmp', '2025-10-28 16:30:26', NULL, NULL, 'submitted', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(58, 7, 11, 'Doloribus in unde modi consequatur excepturi. Quo ut a dolor harum dolorem ut. Et provident ea debitis rerum ut dignissimos. Velit nostrum quidem minus beatae repellendus.', NULL, '2025-10-28 16:30:27', 4, 'Ut repudiandae dignissimos asperiores adipisci nihil tempora. Nihil error et quaerat omnis non.', 'graded', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(59, 7, 14, 'Quam qui voluptate aut dolorum numquam. Est corrupti minima aspernatur quae explicabo veritatis facilis iste. Molestiae dignissimos ipsa rerum voluptatum nulla qui consequatur. Eos at et est rerum.', NULL, '2025-10-28 16:30:26', 12, 'Earum dolores et velit sed. Placeat nihil vitae assumenda architecto numquam.', 'graded', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(60, 7, 16, 'Quisquam aliquam ut rerum consequatur et natus. Labore aliquam consectetur quisquam dignissimos natus sunt. Numquam quam laboriosam totam exercitationem quis eius vel.', NULL, '2025-10-28 16:30:27', 12, 'Blanditiis dolorem eligendi id velit et adipisci voluptatem. Blanditiis fugit autem deleniti accusamus rerum omnis animi.', 'graded', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(61, 7, 17, 'Ipsum omnis id est corrupti. Quod mollitia delectus alias quaerat. Natus officiis voluptate assumenda. Quam quia voluptatem qui non sint optio.', 'C:\\Users\\bedi-\\AppData\\Local\\Temp\\fak9891.tmp', '2025-10-28 16:30:27', NULL, NULL, 'submitted', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(62, 7, 18, 'Est cupiditate quisquam id ut autem. Illum velit culpa ea ut eveniet sunt culpa. Ut consectetur eligendi qui dignissimos rem dolorum. Totam sunt aliquid magni cumque aut nostrum saepe libero.', NULL, '2025-10-28 16:30:27', NULL, NULL, 'submitted', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(63, 7, 19, 'Ea nihil ut quo error dolores est expedita. Laudantium laboriosam odit assumenda explicabo non sunt. Nesciunt necessitatibus sed consectetur et repellat. Voluptas repellendus sequi quia repellendus sed alias exercitationem.', NULL, '2025-10-28 16:30:26', 9, 'Sint magni ducimus sit aut aut omnis autem. Qui nemo distinctio autem veritatis. Earum adipisci consequatur architecto fuga vero consequatur minima.', 'graded', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(64, 7, 20, 'Eos sint libero aspernatur quo. Deserunt quam sunt necessitatibus voluptatem facilis. Ad velit sed tempora quia cum.', 'C:\\Users\\bedi-\\AppData\\Local\\Temp\\fak9892.tmp', '2025-10-28 16:30:26', 9, 'Et consequatur quasi consequatur atque iusto. Vel rerum ut porro reprehenderit sunt et iusto.', 'graded', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(65, 8, 2, 'Fugiat excepturi voluptas neque dolorem et voluptate quaerat occaecati. Exercitationem aut doloribus et vero et. Maxime voluptas a ducimus doloremque sed eos qui doloremque. Voluptates earum dolorem totam.', NULL, '2025-10-28 16:30:26', 13, 'Qui laborum veniam earum eos similique dicta alias. Perferendis et quia qui est dolores praesentium iusto voluptate. In saepe numquam voluptatem et exercitationem facilis quia.', 'graded', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(66, 8, 5, 'Recusandae amet molestias saepe ut vero. Qui nostrum est blanditiis optio. Ea laudantium earum ducimus qui corporis qui illum.', NULL, '2025-10-28 16:30:27', NULL, NULL, 'submitted', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(67, 8, 8, 'Est et aut totam ea accusantium quia. Sed aut quisquam nisi impedit sit voluptatem. Aut officiis suscipit pariatur vel iusto.', NULL, '2025-10-28 16:30:27', 1, 'Qui repellat animi numquam quia. Ullam qui aut libero.', 'graded', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(68, 8, 11, 'Non dignissimos aliquam voluptatem totam est vitae mollitia. Necessitatibus eos in repellat. Maxime et quia impedit harum possimus ipsam.', NULL, '2025-10-28 16:30:26', 11, 'Omnis nobis adipisci nihil hic. Consequuntur ut sit porro est beatae iusto facere aut. Ea aut quia est omnis dignissimos.', 'graded', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(69, 8, 12, 'Iusto laboriosam modi libero voluptatibus ad. Animi quia velit exercitationem debitis. Assumenda architecto sit molestias ut temporibus. Ex sit maiores eaque molestiae molestias et voluptas.', NULL, '2025-10-28 16:30:27', NULL, NULL, 'submitted', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(70, 8, 17, 'Facere est dolorem explicabo rem asperiores praesentium. Aut dolores quia est magni. Voluptas in voluptatibus dicta et.', NULL, '2025-10-28 16:30:26', 12, 'Deserunt consequatur qui perspiciatis voluptatibus harum molestiae. Temporibus dolore earum illo at alias iusto rerum.', 'graded', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(71, 8, 20, 'Nesciunt neque ducimus magni rerum. Labore provident quae voluptatem dolor quisquam. Minus dolorum corrupti ipsa est quis possimus.', NULL, '2025-10-28 16:30:27', 13, NULL, 'graded', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(72, 9, 1, 'Hic voluptas accusamus adipisci exercitationem dolores quia rem. Ad deleniti fugiat occaecati voluptas qui. Architecto sequi blanditiis excepturi deleniti iusto tenetur nulla.', NULL, '2025-10-28 16:30:27', NULL, NULL, 'submitted', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(73, 9, 2, 'Magni tempora eligendi eveniet aut. Dolor culpa magni quia odit. Hic voluptatum ipsa sunt voluptatem impedit sit provident. Aut perferendis id atque ut eaque adipisci.', NULL, '2025-10-28 16:30:26', NULL, NULL, 'submitted', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(74, 9, 4, 'Et iste eveniet autem porro laborum sunt quasi. Eum illum qui id voluptatum.', 'C:\\Users\\bedi-\\AppData\\Local\\Temp\\fak98C2.tmp', '2025-10-28 16:30:27', 5, 'Esse ipsa omnis eius ut. Dolore est sit sequi cum. Ad amet in voluptatem eum excepturi temporibus.', 'graded', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(75, 9, 10, 'Qui iure quos dolor sunt sapiente est et. Est repudiandae minus vel saepe. Quaerat harum provident eaque officiis. Optio quibusdam nisi temporibus similique facere sit dolorem.', NULL, '2025-10-28 16:30:27', 12, 'Dolor quo nulla et aliquid. Dignissimos officia nihil sit eaque. Qui est facilis est minus.', 'graded', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(76, 9, 12, 'Repellat dignissimos aliquid accusamus. Libero est beatae sequi qui. Ut voluptatibus voluptatum quis eligendi voluptatibus. Minus voluptatem aliquam distinctio quam vero minus.', NULL, '2025-10-28 16:30:27', 4, 'Minima qui rerum recusandae aspernatur. Eius ipsum porro cupiditate aut et et.', 'graded', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(77, 9, 16, 'Alias omnis non cupiditate aut ad explicabo. Amet eum nisi vero autem. Facilis expedita ut architecto rem aliquid sunt.', NULL, '2025-10-28 16:30:27', 12, 'Natus voluptatum non et et. Qui ut voluptas suscipit quis eum dolores. Quod consequatur et excepturi voluptas officia enim non eius.', 'graded', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(78, 9, 17, 'Recusandae voluptatem sunt saepe et debitis eum. Accusamus fugiat iusto accusantium rerum et. Quis eveniet consequatur laborum similique quisquam omnis velit.', NULL, '2025-10-28 16:30:27', 0, 'Earum aut vel quia molestiae aut saepe illum. Rerum sapiente necessitatibus sint aliquid facilis voluptatem sint.', 'graded', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(79, 9, 19, 'Ab facilis dolor eius molestiae deserunt laboriosam natus. Aliquam velit cupiditate aliquid distinctio magni. Ex numquam necessitatibus qui consectetur.', 'C:\\Users\\bedi-\\AppData\\Local\\Temp\\fak98D3.tmp', '2025-10-28 16:30:26', 5, NULL, 'graded', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(80, 9, 20, 'Aliquam similique eaque asperiores laborum provident inventore iusto velit. Possimus quis ut et harum a earum consequuntur. Inventore voluptates ducimus rem quia quam ipsum. Sed hic cum et est voluptatem.', 'C:\\Users\\bedi-\\AppData\\Local\\Temp\\fak98D4.tmp', '2025-10-28 16:30:26', NULL, NULL, 'submitted', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(81, 10, 2, 'Aut qui enim perspiciatis modi voluptatibus commodi alias eligendi. Possimus explicabo sapiente quae accusamus facere. Adipisci at omnis sint fuga. Exercitationem possimus et aut doloremque voluptatibus dolorum totam. Molestiae consequatur eaque corporis eum quisquam incidunt iste maxime.', NULL, '2025-10-28 16:30:26', 0, NULL, 'graded', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(82, 10, 3, 'Laboriosam voluptates reprehenderit dicta voluptate. Unde sint adipisci autem ut perspiciatis ducimus. Quia molestiae repellendus libero.', 'C:\\Users\\bedi-\\AppData\\Local\\Temp\\fak98D5.tmp', '2025-10-28 16:30:26', 6, 'Ipsam velit eos laborum nam. Odio explicabo assumenda laborum impedit porro similique eum. Consequatur consequatur qui dolor qui culpa nihil.', 'graded', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(83, 10, 4, 'Alias eos error molestiae aut dolor. Earum ut et non corrupti dolorum. Aut fuga nihil voluptatem modi. Eos eaque corrupti molestiae deserunt ea. Quod nisi beatae excepturi.', NULL, '2025-10-28 16:30:26', 5, 'Id in repellat unde molestias. Placeat suscipit cumque adipisci vel ut tenetur quia. Et minima occaecati exercitationem eius qui.', 'graded', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(84, 10, 5, 'Corrupti hic sit libero. Fugiat similique quas dicta dolor incidunt molestiae et. Consequatur dignissimos exercitationem aliquam at quis provident sed. Et quaerat consequatur ex autem.', 'C:\\Users\\bedi-\\AppData\\Local\\Temp\\fak98E5.tmp', '2025-10-28 16:30:27', 8, 'Reiciendis pariatur error non. Excepturi cum officiis culpa ullam repudiandae praesentium dolorem. Nam nostrum esse ea fuga.', 'graded', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(85, 10, 6, 'Quia dolorem deleniti sapiente tempora ut. Est itaque a dolores consequuntur autem quis.', NULL, '2025-10-28 16:30:26', 4, NULL, 'graded', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(86, 10, 7, 'Modi distinctio repellendus autem ipsam. Voluptate consectetur id repudiandae consectetur doloribus eaque. Facilis qui consequatur facilis veniam.', NULL, '2025-10-28 16:30:26', 5, 'Aut quae vero maxime non illum consequatur. Minima dolores est quidem ullam sint. Voluptatum quis dolore tempore non dicta in.', 'graded', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(87, 10, 10, 'Minima quis voluptate et. Aut quibusdam ad itaque est sit corporis harum. Et cumque corporis deleniti nostrum.', 'C:\\Users\\bedi-\\AppData\\Local\\Temp\\fak98F6.tmp', '2025-10-28 16:30:27', 6, 'Aliquid asperiores magni minus commodi id beatae accusamus maiores. Fugit aut a dolorem repudiandae consequatur dolorem et. Dicta porro in officiis dolorem aperiam.', 'graded', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(88, 10, 15, 'Voluptatem tenetur suscipit aliquam corrupti consequatur. Similique animi rerum inventore fuga non aut eaque maiores. Impedit molestiae voluptates officia hic voluptas sit. Voluptatem quo et libero mollitia et.', NULL, '2025-10-28 16:30:26', 8, 'Dolores autem et molestias neque dignissimos autem dignissimos. Aut sit iure dignissimos ut voluptatem magnam qui exercitationem. Labore at magnam tempora excepturi iure et quis.', 'graded', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(89, 10, 16, 'Eligendi est in ut voluptatibus id. Sed velit perspiciatis facilis exercitationem saepe dolor qui. Excepturi deleniti beatae quia molestiae facere.', NULL, '2025-10-28 16:30:27', NULL, NULL, 'submitted', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(90, 10, 18, 'Fugit qui facilis atque distinctio ut. Vero sed quia totam aspernatur. Blanditiis explicabo praesentium ea quisquam beatae soluta.', NULL, '2025-10-28 16:30:26', 6, 'Eligendi a sed culpa natus sint odit sit. Quas omnis doloribus ipsam unde officiis.', 'graded', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(91, 11, 10, 'Ut voluptate veritatis neque quasi. Sit enim voluptatum dicta aut et officia provident. Aut odio facilis hic odio. Eos maxime ipsum dolorem asperiores laboriosam.', NULL, '2025-10-28 16:30:26', 2, 'Dolorem hic perferendis nemo praesentium aut ex. Nisi et quas et ut facilis.', 'graded', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(92, 11, 14, 'Odio deleniti impedit et enim est eos iusto. Et quia beatae dolorem est. Placeat ducimus ratione pariatur quod. Et facilis et itaque animi dignissimos tempore cum.', 'C:\\Users\\bedi-\\AppData\\Local\\Temp\\fak9906.tmp', '2025-10-28 16:30:27', 4, 'Est sint quibusdam et qui eos est. Veniam eos illo sed. Odit veritatis et omnis.', 'graded', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(93, 11, 16, 'Rerum illo rerum dignissimos ad est. Consequatur officiis illum rerum. Fuga laboriosam animi ut aut amet vero.', 'C:\\Users\\bedi-\\AppData\\Local\\Temp\\fak9907.tmp', '2025-10-28 16:30:26', NULL, NULL, 'submitted', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(94, 11, 18, 'Ea rem magni et soluta. Repudiandae aut porro inventore eum voluptatum odio consequatur dolore. Incidunt eveniet enim aut quod ipsam.', NULL, '2025-10-28 16:30:27', 12, 'Atque id in et dolor porro. Repellendus temporibus reiciendis deserunt maxime hic placeat.', 'graded', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(95, 11, 20, 'Ea sit vel quam sed. Et tempora sint fuga qui totam cupiditate ipsa. Nulla dolorem iste sit.', NULL, '2025-10-28 16:30:26', 7, NULL, 'graded', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(96, 12, 5, 'Veniam iste quibusdam voluptas non voluptatibus a. Adipisci iusto et est et qui harum qui fuga. Fugit totam harum alias molestiae. Praesentium nesciunt veritatis maiores ullam.', NULL, '2025-10-28 16:30:26', 6, 'Perspiciatis ea rerum labore autem. Quis voluptatibus repellat et dolores corrupti magnam nemo. Cumque est qui dolor totam optio ipsa.', 'graded', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(97, 12, 7, 'Quo id voluptatibus vitae exercitationem aut dolores nemo natus. Rerum beatae ut non. Facere provident aut ea velit. Dolor molestiae perferendis eum.', 'C:\\Users\\bedi-\\AppData\\Local\\Temp\\fak9918.tmp', '2025-10-28 16:30:26', 7, NULL, 'graded', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(98, 12, 8, 'Rem repellat enim quo harum asperiores voluptates. Voluptas sit libero labore et rem dolores. Non qui non in dolorem qui odit.', 'C:\\Users\\bedi-\\AppData\\Local\\Temp\\fak9919.tmp', '2025-10-28 16:30:27', 0, NULL, 'graded', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(99, 12, 9, 'Ab cumque architecto aliquam ut corrupti eum iste. Sint omnis odio non soluta optio ullam.', NULL, '2025-10-28 16:30:26', NULL, NULL, 'submitted', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(100, 12, 10, 'Vero voluptas totam non sed impedit. Aut cumque autem voluptas minima voluptas sequi quaerat est. Voluptatibus autem nam porro cum reprehenderit aut.', NULL, '2025-10-28 16:30:27', 4, 'A aut qui ut quas et voluptate. Nihil dolorem explicabo consequatur deleniti. Sed repellendus distinctio quo labore incidunt ut.', 'graded', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(101, 12, 11, 'Illo occaecati quo cupiditate occaecati sed. Nemo omnis et soluta repellat. Id maxime non quidem unde autem.', 'C:\\Users\\bedi-\\AppData\\Local\\Temp\\fak992A.tmp', '2025-10-28 16:30:26', NULL, NULL, 'submitted', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(102, 12, 14, 'Laborum iure quo dolorem asperiores. Neque molestias esse occaecati modi ullam. Deleniti magni harum vitae explicabo laboriosam non iusto. Temporibus ut iste aut placeat ut.', NULL, '2025-10-28 16:30:27', 3, NULL, 'graded', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(103, 12, 16, 'Similique velit sed est ab est quaerat illo. Dolor vero ea iste nostrum sapiente repellat eos. Rem dolores rem harum consectetur aspernatur corporis.', 'C:\\Users\\bedi-\\AppData\\Local\\Temp\\fak992B.tmp', '2025-10-28 16:30:26', 8, 'Amet in occaecati dolores inventore placeat inventore quo. Vero aspernatur qui perspiciatis quasi minima est aliquid.', 'graded', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(104, 12, 17, 'Et officiis dolore delectus sapiente qui. Eligendi itaque ea molestiae. Laboriosam sed temporibus at aut omnis. Hic quia dolorem fugit eaque rerum ut aut.', NULL, '2025-10-28 16:30:26', NULL, NULL, 'submitted', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(105, 12, 18, 'Est velit qui mollitia quod porro unde quam ut. Odit repellat laborum est. Eveniet et voluptatibus numquam aut aut laudantium error. Provident perferendis nesciunt voluptate.', 'C:\\Users\\bedi-\\AppData\\Local\\Temp\\fak993B.tmp', '2025-10-28 16:30:27', 1, NULL, 'graded', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(106, 12, 19, 'Quam quos eveniet laboriosam expedita. Est et qui neque accusamus id incidunt. Culpa nobis ut magni.', NULL, '2025-10-28 16:30:26', 5, NULL, 'graded', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(107, 12, 20, 'Natus consectetur blanditiis laboriosam accusantium nihil modi. Maxime unde minima amet blanditiis ipsum illo unde. Autem sit sequi animi accusantium explicabo. Nam ab est aut. Magnam cupiditate laboriosam enim laboriosam quos ducimus.', NULL, '2025-10-28 16:30:27', NULL, NULL, 'submitted', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(108, 13, 3, 'Culpa laboriosam et omnis natus rerum. Perspiciatis ipsam corrupti id aut. Esse optio accusantium alias incidunt. Rem eveniet qui possimus consequuntur quae quisquam.', 'C:\\Users\\bedi-\\AppData\\Local\\Temp\\fak994C.tmp', '2025-10-28 16:30:27', NULL, NULL, 'submitted', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(109, 13, 4, 'Illo quia alias commodi hic voluptatum maxime. Est corrupti eos quisquam sed quae dicta.', NULL, '2025-10-28 16:30:26', NULL, NULL, 'submitted', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(110, 13, 6, 'Vel magni sint iste neque. Et nihil delectus eum aut in pariatur mollitia voluptas. Et corrupti consequatur eum sed molestias veniam.', NULL, '2025-10-28 16:30:26', NULL, NULL, 'submitted', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(111, 13, 7, 'Expedita voluptatibus architecto ut laborum dolores sunt. Occaecati accusamus ad hic ut. Aut dignissimos iusto non quisquam. Dolor perferendis ea necessitatibus.', 'C:\\Users\\bedi-\\AppData\\Local\\Temp\\fak994D.tmp', '2025-10-28 16:30:27', 8, NULL, 'graded', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(112, 13, 20, 'Totam id quae explicabo. Rerum eos aut animi qui illum culpa. Doloremque consequatur et magnam.', 'C:\\Users\\bedi-\\AppData\\Local\\Temp\\fak995E.tmp', '2025-10-28 16:30:26', 11, 'Adipisci porro amet ex atque aut magni. Illum accusamus commodi nostrum officiis voluptates explicabo consequatur. Pariatur assumenda quo illo ut inventore rem.', 'graded', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(113, 14, 1, 'Non iure nihil amet rem. Earum non ratione qui nesciunt nulla molestiae itaque. Sunt laboriosam quae pariatur est quia ut. Animi totam dicta optio quia repellendus sit minima.', 'C:\\Users\\bedi-\\AppData\\Local\\Temp\\fak995F.tmp', '2025-10-28 16:30:26', 6, NULL, 'graded', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(114, 14, 4, 'Temporibus deleniti est unde corrupti tenetur dolorum necessitatibus. Cum dolores dolor facilis ad fuga et velit. Vel modi esse explicabo est eaque. Qui voluptatibus magnam harum exercitationem culpa.', 'C:\\Users\\bedi-\\AppData\\Local\\Temp\\fak9960.tmp', '2025-10-28 16:30:26', 11, NULL, 'graded', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(115, 14, 5, 'Autem iure magni minus non dicta est qui nostrum. Non eos recusandae accusamus animi similique dicta perferendis. Omnis ratione est illum sed.', NULL, '2025-10-28 16:30:27', NULL, NULL, 'submitted', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(116, 14, 6, 'Saepe veritatis enim exercitationem vitae aut autem ab. Cum rerum modi cumque veniam cum. Culpa possimus rem qui molestiae. Nam molestias adipisci voluptatem perspiciatis expedita.', NULL, '2025-10-28 16:30:27', NULL, NULL, 'submitted', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(117, 14, 7, 'Fugiat itaque odit quo fugit amet. Omnis corrupti error neque ea in iste. Quod quia officia blanditiis consequatur maxime enim eaque. Quae dolores porro quia numquam autem est.', NULL, '2025-10-28 16:30:26', 11, 'Odit amet autem voluptates quis porro assumenda ea. Quam harum est optio aut et voluptatem et. Enim sunt et ut dolorem.', 'graded', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(118, 14, 8, 'Quod saepe doloribus totam esse est. Nemo et iure sit et repellat distinctio occaecati sapiente. Optio minima autem consequatur ipsam optio adipisci omnis. Et saepe quidem autem minima. Iure quibusdam ducimus voluptate assumenda ut reiciendis a.', NULL, '2025-10-28 16:30:26', 7, 'Et in sed quibusdam voluptatibus quia saepe. Nemo consequatur animi qui quod.', 'graded', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(119, 14, 9, 'Provident et voluptatem aut consequatur maxime. Vel voluptates qui rerum architecto. Maxime dolorem quis est repellat sit laborum.', NULL, '2025-10-28 16:30:26', 8, NULL, 'graded', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(120, 14, 11, 'Omnis labore illum nemo voluptate nulla. Sit inventore illo qui omnis voluptatibus aperiam autem est. Reprehenderit sint aut atque voluptas ullam.', NULL, '2025-10-28 16:30:27', NULL, NULL, 'submitted', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(121, 14, 12, 'Veniam commodi dolore fuga dolor perferendis et in. Deleniti quis optio quo. Error vel perspiciatis facere reprehenderit totam. Similique facere fugit porro adipisci nesciunt et odio.', NULL, '2025-10-28 16:30:26', 0, 'Cupiditate ea voluptas aut in. Est eaque eum modi autem aliquid doloribus.', 'graded', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(122, 14, 13, 'Voluptas molestiae qui ut deserunt illum. Omnis sunt dolorum commodi a ducimus nostrum consequuntur. Sed consequatur pariatur mollitia iusto est. Qui totam beatae perferendis harum exercitationem quo.', NULL, '2025-10-28 16:30:26', 11, 'Quaerat ad et earum ut similique. Id cum sequi sequi aspernatur eum.', 'graded', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(123, 14, 14, 'Alias doloremque facilis hic ut. Dolorem cumque quisquam accusantium molestiae iusto nobis natus nesciunt. Error qui voluptas id eum. Dolores adipisci earum error rerum repellat enim ut. Debitis aut accusantium ipsum quisquam.', NULL, '2025-10-28 16:30:27', 15, 'Ipsa qui sed et assumenda fugiat atque. A minima saepe impedit quis explicabo deleniti ut. Enim sequi in quo non.', 'graded', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(124, 14, 15, 'Voluptatem consequatur quos odit ab tempora. Sapiente inventore architecto aut aspernatur qui quia. Sunt consequatur et quia et et voluptas. Est ea repudiandae veniam sed ipsum reprehenderit quae qui. Aut ullam qui neque qui temporibus vel sint et.', 'C:\\Users\\bedi-\\AppData\\Local\\Temp\\fak9980.tmp', '2025-10-28 16:30:26', 18, 'Qui voluptatem facilis numquam dignissimos sint est. Cupiditate esse sed ut sunt. Quod dolor in hic voluptas et.', 'graded', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(125, 14, 17, 'Ut rerum reprehenderit alias iste et doloremque. Minus quasi fugit nesciunt ducimus natus et. Fugit illum temporibus optio totam. Ad molestiae ipsa cum rerum sint et.', NULL, '2025-10-28 16:30:27', 17, 'Itaque ut illum pariatur natus omnis quibusdam explicabo. Architecto sit saepe debitis dolorem ut cupiditate tempore.', 'graded', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(126, 14, 19, 'Harum minima est quidem et omnis. Earum reprehenderit id quis hic eveniet minima beatae et. A earum et possimus ab id tempore quasi incidunt.', NULL, '2025-10-28 16:30:27', 9, 'Vero odio quia recusandae placeat. Quaerat veritatis explicabo libero.', 'graded', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(127, 14, 20, 'Commodi voluptate mollitia consequuntur quibusdam. Molestias ipsam est voluptatibus id. Reiciendis veritatis necessitatibus occaecati excepturi.', 'C:\\Users\\bedi-\\AppData\\Local\\Temp\\fak9990.tmp', '2025-10-28 16:30:27', 18, NULL, 'graded', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(128, 15, 1, 'Vel voluptatem deserunt cupiditate dolorem. Et consequatur voluptatum animi. Ad deleniti possimus quia aliquam corrupti error. Non nobis vero aut error.', NULL, '2025-10-28 16:30:26', 7, 'Maiores voluptate consequatur commodi. Illo ut nesciunt excepturi commodi laborum. Repellat reprehenderit non odit assumenda dignissimos eligendi.', 'graded', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(129, 15, 4, 'Labore eius reprehenderit aliquam ipsam sit explicabo aliquid. Minima molestiae dolorum consequatur repellendus quas dolores. Ea quibusdam corrupti laboriosam labore architecto alias. Quae reiciendis ad sed quod laudantium ea officiis.', NULL, '2025-10-28 16:30:26', NULL, NULL, 'submitted', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(130, 15, 5, 'Nisi sit ex dolores dolor commodi molestiae. Est repellat numquam provident alias. Qui dignissimos voluptatibus cupiditate inventore recusandae est corporis odit.', 'C:\\Users\\bedi-\\AppData\\Local\\Temp\\fak99A1.tmp', '2025-10-28 16:30:27', 8, NULL, 'graded', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(131, 15, 7, 'Est sequi commodi sed. Molestiae repellendus praesentium vitae quaerat. Labore quae qui iusto ut est et quasi.', NULL, '2025-10-28 16:30:27', 6, 'At non sit maiores maiores molestias earum assumenda. Accusantium fugit nesciunt quisquam eius impedit laboriosam temporibus.', 'graded', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(132, 15, 10, 'Animi explicabo quos magni. Aut nostrum dolores laudantium ipsa vel. Animi consectetur rerum ipsum architecto provident officia.', NULL, '2025-10-28 16:30:26', NULL, NULL, 'submitted', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(133, 15, 11, 'Atque facilis voluptatem eum illo tempore voluptates ut. Laboriosam odit odio laborum et. Aliquid assumenda velit dolorem voluptas ducimus.', NULL, '2025-10-28 16:30:27', 5, 'Tenetur dolorem nihil repellat fuga qui ducimus quia minus. Nisi accusamus vel id.', 'graded', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(134, 15, 13, 'Quas inventore rerum quidem dolorem impedit. Ipsa et tenetur amet repudiandae. Error eos et harum illum et voluptas corrupti. Magnam autem ipsa est est aliquid.', NULL, '2025-10-28 16:30:26', 3, 'Velit reiciendis suscipit omnis neque. Harum rerum beatae voluptas quae et numquam ab.', 'graded', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(135, 15, 14, 'Dolorum tempora facere nulla. Libero exercitationem ut tempore et temporibus. Accusantium quaerat eum magnam ut fugiat quia sequi. Perferendis aliquid non repellendus omnis aut laborum ut.', 'C:\\Users\\bedi-\\AppData\\Local\\Temp\\fak99B2.tmp', '2025-10-28 16:30:27', 3, 'Rerum officiis asperiores velit nihil sit quidem voluptates. Autem assumenda perferendis qui dolores.', 'graded', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(136, 15, 16, 'Qui dolorum deserunt qui aut. Voluptatum non unde inventore ut magnam. Qui qui nobis dignissimos aliquid aut officiis. Provident natus et libero distinctio totam aut. Assumenda tenetur nostrum voluptate perferendis minus natus.', NULL, '2025-10-28 16:30:27', 4, 'Qui est dolores doloribus assumenda commodi nisi saepe. Dolorem aliquam aut perspiciatis quis ipsam fugiat.', 'graded', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(137, 15, 18, 'Debitis voluptatibus est voluptatem. Nam qui necessitatibus ea.', NULL, '2025-10-28 16:30:27', 9, 'Dignissimos doloribus repellendus nostrum atque harum sed. Et qui aut qui. Eum repellendus molestiae beatae qui.', 'graded', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(138, 15, 19, 'Nam enim debitis rem ad et. Ipsum nulla autem earum exercitationem optio vero. Repellat explicabo quam consectetur doloribus iure neque.', NULL, '2025-10-28 16:30:27', 4, 'Officia explicabo incidunt voluptatum earum culpa. Debitis aut qui odit. Quo itaque magni deleniti tempora ut suscipit reiciendis.', 'graded', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(139, 16, 1, 'Et alias culpa qui laborum sequi. Dicta numquam animi rerum accusantium voluptate nesciunt iure. Adipisci est facilis quaerat. Ducimus dolores adipisci deleniti.', NULL, '2025-10-28 16:30:26', 2, NULL, 'graded', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(140, 16, 3, 'Et et consequatur eveniet tempora nostrum commodi. Doloribus rem enim dolores odio. A provident sint est ipsam sit eos. Dolorum similique temporibus voluptate doloremque magnam quaerat nemo. Dolores consectetur et est rerum in facilis eaque deserunt.', NULL, '2025-10-28 16:30:26', 12, 'Magnam voluptatem dolore alias nisi nam eius. Doloribus animi aperiam quas numquam et qui.', 'graded', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(141, 16, 4, 'Impedit cumque sapiente qui optio temporibus omnis. Molestias rerum sint facilis atque totam harum.', NULL, '2025-10-28 16:30:26', 12, 'Eaque voluptatem ipsa cum est. Voluptas repudiandae velit molestiae.', 'graded', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(142, 16, 7, 'Eaque aut molestias ea provident. Corporis quia consequatur itaque natus id aliquam. Consequatur laboriosam sit ad minus molestiae totam.', NULL, '2025-10-28 16:30:27', NULL, NULL, 'submitted', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(143, 16, 8, 'Quia quam inventore soluta dolor non modi asperiores unde. Perspiciatis molestias nostrum aperiam nemo. Assumenda doloribus ut reprehenderit ullam exercitationem sit.', 'C:\\Users\\bedi-\\AppData\\Local\\Temp\\fak99D2.tmp', '2025-10-28 16:30:27', 16, NULL, 'graded', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(144, 16, 16, 'Ipsa atque qui quos optio est libero dolor. Voluptatibus eos laudantium et qui reiciendis voluptas soluta. Magni expedita sit nihil consequuntur molestiae ea pariatur. Maiores eaque sint quisquam rerum.', NULL, '2025-10-28 16:30:26', NULL, NULL, 'submitted', '2025-10-28 15:30:27', '2025-10-28 15:30:27');
INSERT INTO `assignment_submissions` (`id`, `assignment_id`, `student_id`, `submission_text`, `file_path`, `submitted_at`, `score`, `teacher_feedback`, `status`, `created_at`, `updated_at`) VALUES
(145, 16, 17, 'Omnis tempore qui nemo enim. Eum sit inventore quibusdam sit soluta vel incidunt. Et aut quibusdam reiciendis velit. Eum totam aut sed fugit placeat sunt.', 'C:\\Users\\bedi-\\AppData\\Local\\Temp\\fak99D3.tmp', '2025-10-28 16:30:26', 8, NULL, 'graded', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(146, 16, 19, 'Ab consequuntur sed ut assumenda nam. Est modi expedita sed fugiat dicta aliquid eveniet consectetur. Commodi dolorem ab consequatur voluptatem dolore quas. Eum est id iure ut. Doloribus nobis excepturi cupiditate velit.', 'C:\\Users\\bedi-\\AppData\\Local\\Temp\\fak99E4.tmp', '2025-10-28 16:30:26', 2, 'Ipsa ipsa ad quia nihil asperiores et. Quam officia aut dicta voluptas.', 'graded', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(147, 17, 3, 'Ab quia sed sint eveniet officia. Labore et et consequatur libero. Non ipsum et vero qui dicta inventore mollitia. Quia eum iste consectetur animi necessitatibus placeat vel.', 'C:\\Users\\bedi-\\AppData\\Local\\Temp\\fak99E5.tmp', '2025-10-28 16:30:27', 0, 'Delectus libero veniam dolores est consectetur accusamus. Iure ipsum quod accusamus provident molestiae eum et.', 'graded', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(148, 17, 4, 'Minus voluptatem nemo sequi rerum dignissimos. Consequatur illum id non enim ullam natus.', NULL, '2025-10-28 16:30:27', 2, NULL, 'graded', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(149, 17, 5, 'Sequi et ipsam quod itaque. Eos ut aperiam ut nihil est dolor. Molestiae maxime eius ut. Aut cupiditate consequatur sequi molestiae omnis explicabo.', 'C:\\Users\\bedi-\\AppData\\Local\\Temp\\fak99E6.tmp', '2025-10-28 16:30:26', 3, 'Aliquid odit adipisci et perferendis ab hic. Quas numquam id sunt quia sit veritatis voluptatem.', 'graded', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(150, 17, 7, 'Aliquam eaque id blanditiis odio dolores qui maiores corrupti. Corporis eveniet facere possimus sed dignissimos. Eos impedit itaque nemo harum. Magnam quis qui ut deleniti voluptas.', NULL, '2025-10-28 16:30:26', 1, NULL, 'graded', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(151, 17, 9, 'Animi dolor aut sint sint et. Et voluptas tempora sunt aut eos nihil nesciunt animi. Ipsa velit id modi non.', NULL, '2025-10-28 16:30:26', 7, 'Quasi aspernatur excepturi magni atque error tenetur assumenda. Eum fugit corrupti aliquid vero hic. Ad cumque rem quia consequuntur nostrum laudantium.', 'graded', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(152, 17, 12, 'Quam odit est dolorem molestias corrupti ea. Non id sed quia sed.', NULL, '2025-10-28 16:30:27', NULL, NULL, 'submitted', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(153, 17, 14, 'Molestiae molestiae et error dolores temporibus facilis fuga. Dolorem dignissimos alias quos aut qui voluptatem voluptatum totam. Quas blanditiis ea ipsam. Enim qui aut quibusdam est atque.', 'C:\\Users\\bedi-\\AppData\\Local\\Temp\\fak99F6.tmp', '2025-10-28 16:30:27', 1, 'Hic repellendus tempora fugit dolores eos quam. Quae eaque accusantium omnis voluptatem. Cumque temporibus est dolorem sit consequatur dolores quia sit.', 'graded', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(154, 17, 18, 'Magni similique cupiditate alias. Quibusdam amet temporibus voluptatem voluptatem voluptatibus. Voluptas eum provident id laboriosam sunt illum. Omnis necessitatibus quia et dolor aliquid reprehenderit repellendus.', 'C:\\Users\\bedi-\\AppData\\Local\\Temp\\fak9A07.tmp', '2025-10-28 16:30:26', 12, NULL, 'graded', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(155, 18, 1, 'At blanditiis voluptas ut nobis consequuntur et. Ipsa natus quos et sequi voluptas. Sit autem quibusdam accusantium et veniam consectetur explicabo. Expedita nobis ipsa cupiditate beatae ipsa molestias.', NULL, '2025-10-28 16:30:26', NULL, NULL, 'submitted', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(156, 18, 6, 'Vel ut quis quia vel est dignissimos. Et earum quia et aperiam aperiam. Debitis quibusdam possimus illo hic et delectus. Laborum dolores sit ipsam dolores sunt repudiandae id.', NULL, '2025-10-28 16:30:26', 1, NULL, 'graded', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(157, 18, 8, 'Consequatur inventore neque eveniet corrupti voluptatem ab laborum. Et veritatis error officiis. Doloribus autem id et. Vero autem dolorum non maiores et dolorem minima.', 'C:\\Users\\bedi-\\AppData\\Local\\Temp\\fak9A17.tmp', '2025-10-28 16:30:27', 11, 'Alias numquam molestiae tempora animi consequatur quisquam. Quo asperiores dolores eveniet.', 'graded', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(158, 18, 9, 'Delectus voluptatem repudiandae reprehenderit. Quisquam reprehenderit illum id velit expedita sit exercitationem. Libero voluptatem a dolorum quod ducimus.', NULL, '2025-10-28 16:30:27', 3, 'Consequuntur voluptatem repellat ex aspernatur sint quos debitis. Sunt et odit voluptatum quia. Accusantium corporis autem dolores in dolor illo.', 'graded', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(159, 18, 10, 'Ea sequi omnis dignissimos blanditiis. Architecto velit placeat dolorum deleniti quibusdam ullam. Architecto ipsa provident vel amet.', 'C:\\Users\\bedi-\\AppData\\Local\\Temp\\fak9A18.tmp', '2025-10-28 16:30:26', NULL, NULL, 'submitted', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(160, 18, 11, 'Repellat numquam ex maiores id eum est impedit. Quibusdam distinctio aut animi dolore at. Voluptas saepe aut quisquam facere provident. Molestiae recusandae iure vel alias animi pariatur atque.', NULL, '2025-10-28 16:30:26', 10, 'Ab voluptatem voluptas autem tenetur soluta consequuntur. Rerum atque sint nam est.', 'graded', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(161, 18, 12, 'Veritatis et dolorem excepturi. Iste quam non excepturi deleniti atque et. Quibusdam voluptatum a temporibus cum quia voluptates ab. Error vero veniam fuga doloribus.', 'C:\\Users\\bedi-\\AppData\\Local\\Temp\\fak9A29.tmp', '2025-10-28 16:30:26', 3, 'Enim quisquam veniam et praesentium in. Explicabo qui eos et iusto vero.', 'graded', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(162, 18, 16, 'Modi pariatur perspiciatis eius harum ex est quae. Molestiae exercitationem molestiae blanditiis ut. Dicta autem sit quidem ipsa nobis laborum ut.', NULL, '2025-10-28 16:30:26', 2, 'Non id aperiam placeat nam nesciunt facere nemo. Iusto magnam in debitis expedita illum aut ut.', 'graded', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(163, 18, 18, 'Animi eligendi rem cupiditate provident accusantium sequi debitis perspiciatis. Veniam dicta odit consequatur voluptatem et esse explicabo dolores. Nesciunt ut quos nihil quos placeat itaque. Facilis nemo ut molestias cupiditate assumenda.', NULL, '2025-10-28 16:30:26', 17, NULL, 'graded', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(164, 18, 19, 'Officiis dolorem ipsa minima hic. Ut expedita vitae consequatur necessitatibus. Omnis voluptas totam molestiae unde ut. Voluptatem voluptatem perspiciatis molestiae est aut quo.', NULL, '2025-10-28 16:30:26', 1, NULL, 'graded', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(165, 18, 20, 'Et impedit soluta possimus reprehenderit unde. Omnis quia molestias fuga. Nostrum eveniet non quia voluptate. Repudiandae sunt ut voluptas perspiciatis unde fugit distinctio. Enim voluptatem accusantium assumenda.', 'C:\\Users\\bedi-\\AppData\\Local\\Temp\\fak9A3A.tmp', '2025-10-28 16:30:27', 10, 'Explicabo ipsum consequatur et ex qui dolores. Reiciendis praesentium necessitatibus numquam iusto voluptatem sed.', 'graded', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(166, 19, 1, 'Vero veritatis nemo est maxime non fugiat et sint. Et omnis sit eum quis molestiae alias. Ut libero id iste molestias. Itaque nobis perspiciatis nisi.', 'C:\\Users\\bedi-\\AppData\\Local\\Temp\\fak9A3B.tmp', '2025-10-28 16:30:26', NULL, NULL, 'submitted', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(167, 19, 9, 'Asperiores asperiores quis placeat. Omnis eos quidem corporis ullam voluptatem voluptatem eaque. Dolores velit et sed qui non consequatur adipisci. Facilis natus eveniet eveniet enim. Deserunt dicta quidem totam.', 'C:\\Users\\bedi-\\AppData\\Local\\Temp\\fak9A4B.tmp', '2025-10-28 16:30:27', 4, 'Aliquam qui dolorem cum. Quod unde molestiae est autem fuga nihil expedita. Cum et dolores tenetur blanditiis voluptatem commodi.', 'graded', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(168, 19, 11, 'Velit suscipit est rerum et. Eius voluptatum quis ipsa. Blanditiis repellendus autem amet quibusdam aspernatur quo. Consequatur voluptatem rem aut aut.', NULL, '2025-10-28 16:30:26', 8, 'Repellat aut voluptatem est ab numquam eos. Ut suscipit autem aut dolores voluptatum.', 'graded', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(169, 19, 13, 'Possimus necessitatibus numquam ad in facilis harum aliquid. Voluptatem et similique molestiae et perferendis. Quis iure consequatur cum veniam est. Similique sint ratione sint veritatis hic pariatur et. Tempora asperiores quo in ducimus est expedita.', NULL, '2025-10-28 16:30:26', 1, 'Rerum fugiat modi voluptas. Quisquam at repudiandae architecto illum. Ut praesentium est qui.', 'graded', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(170, 19, 14, 'Repellendus repellat iste numquam et vero odit eligendi. Est amet delectus natus nihil blanditiis mollitia. Libero amet qui aut quisquam nihil. Officiis autem deleniti hic voluptas eos iste. Mollitia autem facere enim quos necessitatibus laborum.', 'C:\\Users\\bedi-\\AppData\\Local\\Temp\\fak9A5C.tmp', '2025-10-28 16:30:27', 9, NULL, 'graded', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(171, 19, 16, 'Quia ea magnam odio provident eum nihil. Voluptatem cupiditate ut consequatur perferendis. Aut doloremque distinctio reiciendis voluptate illo. Voluptatem neque iste voluptas et.', NULL, '2025-10-28 16:30:27', 2, NULL, 'graded', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(172, 19, 17, 'Qui temporibus vitae doloribus assumenda voluptates. Consectetur voluptatum eveniet aut officia. Aspernatur consequatur autem possimus blanditiis. Laboriosam repellendus ex minima non unde.', 'C:\\Users\\bedi-\\AppData\\Local\\Temp\\fak9A5D.tmp', '2025-10-28 16:30:27', 1, 'Placeat illum nisi aut. Quo sint aut eos.', 'graded', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(173, 19, 19, 'Cum et libero et deserunt doloribus quis. Aut quis recusandae vel praesentium sequi. Delectus aliquam cum ipsam. Tempora molestias necessitatibus explicabo excepturi perspiciatis.', NULL, '2025-10-28 16:30:26', 2, 'Maiores dicta porro eum veritatis blanditiis eos recusandae. Est voluptatem et odit non laudantium quos.', 'graded', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(174, 20, 3, 'Aspernatur quae sed aspernatur ut. Dolores molestias et eos vel autem quo. Ut voluptates ad et non illo et sit.', NULL, '2025-10-28 16:30:27', 6, 'Itaque nostrum distinctio a voluptatem ex. Ab aut nulla omnis sit non eos.', 'graded', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(175, 20, 7, 'Eum quae perspiciatis qui quas iste. Ipsa provident et sit consectetur nihil. Aut cum deleniti velit dolorum assumenda perferendis dignissimos. Aut at dolorem eos odit veritatis illo reprehenderit.', NULL, '2025-10-28 16:30:27', 10, 'Omnis et mollitia adipisci ducimus tempora velit saepe. Consequatur assumenda dignissimos quasi rerum. Et consectetur iure quasi exercitationem.', 'graded', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(176, 20, 8, 'Tenetur et assumenda a quod nam atque. Velit voluptatibus ut architecto sapiente omnis tempora.', NULL, '2025-10-28 16:30:26', 20, NULL, 'graded', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(177, 20, 11, 'Est nam quasi quas voluptatem. A sed non culpa eum nam. Doloremque rerum accusantium ut est sint. Voluptatem odit in aut odit sint eligendi assumenda distinctio.', NULL, '2025-10-28 16:30:26', 20, 'Sint quis voluptatum quos pariatur necessitatibus nobis totam numquam. Voluptate et et ducimus odio. Qui error possimus voluptas autem delectus temporibus quod.', 'graded', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(178, 20, 15, 'Natus sint asperiores qui molestias ratione nesciunt. Ad illo quibusdam sunt consequatur numquam porro. Voluptas aut ut et et.', NULL, '2025-10-28 16:30:27', 14, 'Dolores deleniti facilis voluptas officiis reprehenderit. Quasi consequatur quod error et perferendis. Qui molestias voluptate doloremque labore doloribus minima.', 'graded', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(179, 20, 16, 'Quos et qui ex voluptatibus aut. Amet sunt qui voluptate cum sit eveniet. Suscipit suscipit voluptatem sunt ut. Placeat aut voluptates quisquam id ad.', NULL, '2025-10-28 16:30:26', 11, NULL, 'graded', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(180, 20, 18, 'Occaecati eum laborum voluptas. Assumenda et voluptas voluptatem porro facere vel quis. Corporis qui eveniet qui omnis. Similique placeat provident at architecto neque consequatur nesciunt.', NULL, '2025-10-28 16:30:27', 3, 'Cumque blanditiis ipsum fuga quibusdam repellendus molestias et. Iste occaecati exercitationem iste impedit. Ea dolores repudiandae fuga non quo assumenda consequatur eos.', 'graded', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(181, 21, 5, 'Ratione eaque explicabo dolores labore. Doloremque id et animi. Repellendus expedita nihil magnam ut deserunt rerum corrupti necessitatibus.', NULL, '2025-10-28 16:30:26', 1, 'Assumenda numquam autem reiciendis. Et expedita aliquam voluptatem occaecati animi unde. Nam dicta incidunt quas numquam quo nobis est.', 'graded', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(182, 21, 9, 'Itaque quibusdam iusto at. Ipsam dicta illo quo ad magnam in. Voluptates veniam ut neque at eum commodi vero. Accusamus sequi totam quia officiis quam vitae.', NULL, '2025-10-28 16:30:27', 6, 'Unde eaque eaque nostrum voluptatem. Est molestias molestiae quaerat sapiente reprehenderit accusamus.', 'graded', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(183, 21, 12, 'Et pariatur et quas delectus omnis dolorum. Hic et qui autem nesciunt ut est. Et dolore nemo officia in voluptas. Autem nisi eum veniam. Consequatur ipsam voluptatem soluta odio facilis velit et praesentium.', NULL, '2025-10-28 16:30:26', 5, 'Quidem eius hic explicabo hic. Molestias qui molestias labore corporis harum.', 'graded', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(184, 21, 14, 'Aut deleniti quo alias eum sed qui illo suscipit. Optio praesentium autem non nesciunt. Voluptatem hic velit ratione similique. Inventore est ut ratione ipsa laborum eos molestias ex.', 'C:\\Users\\bedi-\\AppData\\Local\\Temp\\fak9ABC.tmp', '2025-10-28 16:30:27', NULL, NULL, 'submitted', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(185, 21, 18, 'Non qui dolorum corrupti quidem perspiciatis et unde. Distinctio totam eius et earum non. Dolorem veritatis repudiandae iste. Quia nemo mollitia nobis illum rerum nesciunt qui quia.', 'C:\\Users\\bedi-\\AppData\\Local\\Temp\\fak9ABD.tmp', '2025-10-28 16:30:26', NULL, NULL, 'submitted', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(186, 21, 20, 'Vel aut sit dicta in et perferendis. Amet voluptatum laudantium sit qui est. Eos porro ipsum quo id.', NULL, '2025-10-28 16:30:26', 8, NULL, 'graded', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(187, 22, 1, 'Delectus corporis quidem fuga sunt quae tempora maxime eos. Iure quo et facere repudiandae. Aliquid necessitatibus quia modi sit. Nihil vero quas quasi suscipit.', NULL, '2025-10-28 16:30:27', 3, 'Laudantium unde aspernatur ut et. Vel quia assumenda sed quidem. Quos vitae ut rerum natus hic corrupti facilis.', 'graded', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(188, 22, 2, 'Eius ducimus dolor necessitatibus voluptatem quos. Ut minus et unde. Exercitationem labore unde impedit id officia sit.', 'C:\\Users\\bedi-\\AppData\\Local\\Temp\\fak9ACD.tmp', '2025-10-28 16:30:27', NULL, NULL, 'submitted', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(189, 22, 3, 'Ex dicta neque facilis qui vel quod. Numquam et animi repellat vero. Laborum velit inventore non repellat aspernatur ducimus soluta atque. Voluptatem illum nostrum facere nesciunt est.', 'C:\\Users\\bedi-\\AppData\\Local\\Temp\\fak9ACE.tmp', '2025-10-28 16:30:27', NULL, NULL, 'submitted', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(190, 22, 4, 'Minus delectus et quaerat rem. Dolores aut quas animi nostrum. Sit eum aut consequatur enim nihil velit. Expedita et soluta velit.', NULL, '2025-10-28 16:30:27', NULL, NULL, 'submitted', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(191, 22, 6, 'Debitis fugit maiores ipsa veritatis ut. Sit non delectus quod sapiente necessitatibus. Quod quae distinctio possimus placeat. Debitis quo ducimus sed ea molestiae. Ut ex nostrum alias eveniet omnis quidem.', 'C:\\Users\\bedi-\\AppData\\Local\\Temp\\fak9ADF.tmp', '2025-10-28 16:30:26', 6, NULL, 'graded', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(192, 22, 7, 'Explicabo odit dolor iure quidem ex non sit. Et nostrum officia omnis sit voluptas corrupti rerum iusto. Nihil sequi facere vel omnis. Voluptas ducimus eius dicta accusantium qui at a.', 'C:\\Users\\bedi-\\AppData\\Local\\Temp\\fak9AF0.tmp', '2025-10-28 16:30:26', NULL, NULL, 'submitted', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(193, 22, 9, 'Ullam nihil temporibus et nihil hic et. Commodi ea delectus quas molestias corporis. Quia quisquam et nostrum qui odio vero rerum.', NULL, '2025-10-28 16:30:26', 14, 'Nulla repellendus totam expedita esse consectetur et eveniet labore. Beatae debitis culpa vero quis quis.', 'graded', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(194, 22, 12, 'Accusantium ipsum nihil ipsam architecto accusantium. Ut est voluptatibus non quisquam corporis saepe ut. Optio quos perspiciatis perspiciatis.', 'C:\\Users\\bedi-\\AppData\\Local\\Temp\\fak9AF1.tmp', '2025-10-28 16:30:27', 8, 'Suscipit beatae laboriosam omnis optio aliquam. Quidem illo est amet nihil sint sed voluptatem iure.', 'graded', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(195, 22, 13, 'Omnis id ab aut quas. Quia eum voluptatum nesciunt dignissimos voluptas. Fugiat quisquam rem corporis vel.', NULL, '2025-10-28 16:30:27', NULL, NULL, 'submitted', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(196, 22, 15, 'Magni rerum non at magni id modi. Perspiciatis corrupti voluptate ullam quod nostrum. Minima id soluta et blanditiis error aut consequuntur.', NULL, '2025-10-28 16:30:27', NULL, NULL, 'submitted', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(197, 22, 16, 'Quam nostrum modi ut amet sit voluptatum vel. Assumenda saepe veritatis accusantium repellendus aut ducimus voluptatibus. Pariatur qui placeat voluptatem deleniti.', 'C:\\Users\\bedi-\\AppData\\Local\\Temp\\fak9B01.tmp', '2025-10-28 16:30:27', NULL, NULL, 'submitted', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(198, 22, 17, 'Neque facilis dolor eaque quidem ea sunt. Quo dolores sint dolores et quis iste quaerat facilis. Iste ad ipsum ipsum id. Neque ea sed enim aspernatur voluptas.', NULL, '2025-10-28 16:30:27', 8, 'Qui qui molestiae repellendus. Ullam dolores at voluptas voluptas.', 'graded', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(199, 22, 19, 'Quae enim doloremque velit vero sed iure illo. Temporibus alias itaque velit quos aut repellendus.', NULL, '2025-10-28 16:30:27', 5, NULL, 'graded', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(200, 23, 5, 'Et est voluptas vel maxime dolor ut mollitia aut. Et et at et. Nihil facere iusto fugiat et ut ut molestiae. Qui atque non expedita.', 'C:\\Users\\bedi-\\AppData\\Local\\Temp\\fak9B12.tmp', '2025-10-28 16:30:26', 7, 'Dolor vero enim ut quibusdam maxime consectetur. Deleniti deleniti officia aliquid atque minima tenetur. Enim omnis deserunt non sint ut.', 'graded', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(201, 23, 9, 'Qui dolor illo cumque et est. Exercitationem architecto reprehenderit numquam est temporibus. Similique sed voluptatem consequuntur.', NULL, '2025-10-28 16:30:26', 0, 'Maiores perspiciatis et magni dolorem assumenda doloremque. Consequuntur asperiores consequatur recusandae.', 'graded', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(202, 23, 11, 'Dolorum ut voluptatem nihil reiciendis hic non. Beatae laudantium doloremque ducimus. In dolorum consequuntur repellat iusto.', 'C:\\Users\\bedi-\\AppData\\Local\\Temp\\fak9B13.tmp', '2025-10-28 16:30:26', 5, NULL, 'graded', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(203, 23, 13, 'Reiciendis consectetur quis iure ut quidem. Et aut est velit pariatur. Incidunt optio voluptatem repudiandae vel culpa et. Impedit cupiditate pariatur magnam unde recusandae laboriosam. Molestiae non reiciendis soluta veniam.', NULL, '2025-10-28 16:30:27', 16, NULL, 'graded', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(204, 23, 19, 'Et corporis fugiat repellat. Et omnis quod voluptates aut impedit quia. Qui eveniet id exercitationem ut architecto neque.', 'C:\\Users\\bedi-\\AppData\\Local\\Temp\\fak9B23.tmp', '2025-10-28 16:30:27', 16, NULL, 'graded', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(205, 24, 1, 'Laboriosam minus deserunt possimus voluptas aut qui. Voluptas voluptate quod natus autem reiciendis enim. Harum ad ea error aperiam velit id. Et id nostrum voluptatem sit. Voluptates deserunt rerum veniam.', NULL, '2025-10-28 16:30:26', NULL, NULL, 'submitted', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(206, 24, 2, 'Omnis sed debitis voluptates ut quibusdam quia explicabo et. Minima accusantium architecto aliquam accusantium sit quia animi. Quasi nemo facilis aut iusto praesentium.', NULL, '2025-10-28 16:30:26', 8, 'Eos voluptate eum tempore eos vero ut voluptas odit. Quae est quia nulla sequi aperiam. Sunt quas laboriosam similique modi explicabo a eos qui.', 'graded', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(207, 24, 4, 'Consequatur quia qui nam laborum esse. Nihil consequatur quia quis amet delectus aspernatur rerum corporis. Est laudantium repellendus aut veritatis modi rerum quia illo.', NULL, '2025-10-28 16:30:26', NULL, NULL, 'submitted', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(208, 24, 5, 'Consectetur est architecto voluptas et. Enim culpa velit consequatur voluptas harum. Veniam qui ipsum ut quod soluta eum accusamus.', NULL, '2025-10-28 16:30:26', 0, NULL, 'graded', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(209, 24, 6, 'Quis voluptas id perferendis facilis exercitationem tempora illo. Minus et mollitia sed ut odio vitae sed harum. Vero fuga dignissimos neque porro.', NULL, '2025-10-28 16:30:26', 6, 'Possimus repellat vel sunt. Vitae earum doloremque qui temporibus aut. Voluptas ipsam culpa cum accusantium repellat itaque.', 'graded', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(210, 24, 7, 'Recusandae reiciendis architecto voluptatem expedita eum nesciunt. Omnis qui dolores voluptas laboriosam culpa. Molestiae vero doloremque similique doloremque et. Error totam laboriosam fugit laboriosam est.', NULL, '2025-10-28 16:30:27', NULL, NULL, 'submitted', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(211, 24, 8, 'Eos magni at non et deserunt error delectus. Facilis dolorem omnis et. Quibusdam facilis qui nemo voluptas quidem consequatur debitis cupiditate.', NULL, '2025-10-28 16:30:26', 8, NULL, 'graded', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(212, 24, 11, 'Modi veritatis enim earum expedita aut quasi voluptatem. Commodi occaecati libero rerum voluptatem ea quia. Earum quidem ducimus omnis a voluptas. Consequatur et at dolore quis incidunt.', NULL, '2025-10-28 16:30:27', NULL, NULL, 'submitted', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(213, 24, 15, 'Dolor sit enim voluptates et id. Perspiciatis aut ipsum beatae libero quam. Ea dolore a ullam est similique. Voluptatem atque est a ut sequi tenetur quo. Est et doloremque voluptatem dolore labore non dignissimos.', NULL, '2025-10-28 16:30:26', NULL, NULL, 'submitted', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(214, 24, 16, 'Laboriosam consequatur nesciunt mollitia ex. Ducimus est repellat sequi nemo incidunt. Aliquid reprehenderit quasi et nemo qui. Eveniet commodi et ut dolorem ea nostrum.', NULL, '2025-10-28 16:30:26', 2, 'Et accusantium voluptatem nihil id. Minima et voluptas voluptate et id quam est.', 'graded', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(215, 24, 17, 'Cupiditate aut vel consequatur maiores eligendi voluptatem. Aspernatur quae ut voluptatum dolor. Quo veniam aut modi.', NULL, '2025-10-28 16:30:27', NULL, NULL, 'submitted', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(216, 24, 19, 'Ipsa non enim dolorem reprehenderit consequuntur aliquid est. Quos ipsum magni aliquid dicta ut nam. Harum qui dolores omnis cupiditate id cumque perspiciatis. Mollitia amet et architecto a consequatur.', 'C:\\Users\\bedi-\\AppData\\Local\\Temp\\fak9B53.tmp', '2025-10-28 16:30:26', 6, 'Quia eos a reiciendis facilis qui molestiae consequatur. Voluptatem omnis saepe voluptatem et doloremque rerum.', 'graded', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(217, 25, 1, 'Eius nesciunt iste tempore quisquam sed quia saepe. Repellat laboriosam neque eaque voluptas a pariatur veniam hic. Illum minima quisquam et harum consectetur ut.', NULL, '2025-10-28 16:30:27', 10, 'Occaecati molestiae dicta aut assumenda delectus tempore nisi. Maxime quidem voluptatem assumenda nesciunt et temporibus. Labore molestiae explicabo odio consequatur cum odit.', 'graded', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(218, 25, 6, 'Quod quo aut non tempore voluptatem sed neque. Aliquid sint quod iusto unde sint. Nesciunt vel quaerat et est commodi modi doloribus.', 'C:\\Users\\bedi-\\AppData\\Local\\Temp\\fak9B64.tmp', '2025-10-28 16:30:26', 3, NULL, 'graded', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(219, 25, 9, 'Doloribus alias adipisci est iste. Laudantium eum excepturi neque quis nobis.', 'C:\\Users\\bedi-\\AppData\\Local\\Temp\\fak9B75.tmp', '2025-10-28 16:30:26', 13, 'Voluptatum quas voluptatum et eligendi vitae qui. Earum quam exercitationem laudantium omnis. Veniam est et autem.', 'graded', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(220, 25, 11, 'Est quos distinctio deleniti numquam sit. Veniam fugit animi debitis rerum omnis velit. Unde ea aliquid veniam. Reprehenderit in perferendis enim.', NULL, '2025-10-28 16:30:26', 4, 'Consectetur dolore voluptatem expedita consequatur. Adipisci sed nemo culpa quibusdam sunt repellendus sint. Et placeat veritatis qui hic.', 'graded', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(221, 25, 12, 'Reprehenderit sapiente aut sit excepturi necessitatibus. Similique ratione ducimus odio praesentium et. Provident nulla aliquam nihil voluptas velit aut.', NULL, '2025-10-28 16:30:27', 15, 'Dicta officiis rerum dolorum voluptate totam. Aut doloribus nihil voluptatibus qui. Inventore sint ducimus quis expedita sequi recusandae repellat.', 'graded', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(222, 25, 13, 'Provident eum omnis qui aut veniam minus consequatur. Enim laboriosam sed necessitatibus molestiae. Odio quasi vel quidem sunt non repellat molestias.', NULL, '2025-10-28 16:30:27', 2, NULL, 'graded', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(223, 25, 15, 'Nihil commodi dolor libero. Omnis voluptatibus et et qui quis ipsam doloribus. Nam amet qui consequuntur iste voluptas. Ratione vero nihil totam qui ex et.', NULL, '2025-10-28 16:30:27', 16, 'Quidem quisquam eius illum tempore beatae. Maiores nobis et consectetur beatae.', 'graded', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(224, 25, 18, 'Excepturi odit dolor omnis necessitatibus magni. Inventore nisi exercitationem ab voluptatem esse et. Est quia qui cumque dolores nam. Voluptatum doloribus quasi omnis praesentium quia aut sequi.', NULL, '2025-10-28 16:30:27', NULL, NULL, 'submitted', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(225, 25, 20, 'Nesciunt in ab et ea molestias aut velit. Et fugiat ex nulla ratione laudantium facere. Dolores eos est assumenda voluptatibus. Rerum excepturi modi nesciunt eveniet.', NULL, '2025-10-28 16:30:27', 13, 'Dolores dolores eos officia est eveniet sapiente nesciunt eum. Eius autem laudantium porro veritatis dolorum. Minima et officiis quis fuga dolores laboriosam.', 'graded', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(226, 26, 1, 'Accusantium aut rerum vitae libero. Cumque reiciendis aliquid facilis voluptas. Inventore aliquid optio blanditiis itaque nemo et. Et quia atque cumque deserunt aut.', NULL, '2025-10-28 16:30:27', NULL, NULL, 'submitted', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(227, 26, 3, 'Magni qui ullam reiciendis illum eveniet. In aliquam qui consequuntur facilis. Eaque occaecati aut accusantium optio. Sunt sit sed molestiae minima.', 'C:\\Users\\bedi-\\AppData\\Local\\Temp\\fak9BA4.tmp', '2025-10-28 16:30:26', 4, 'Aut aut neque ut est. Voluptas eos ipsum culpa dolorem adipisci rerum exercitationem.', 'graded', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(228, 26, 7, 'Ut voluptatem recusandae molestiae consequatur est cumque et. Dolores sequi facere minus earum animi. Temporibus ut assumenda ea sunt culpa enim veniam. Unde quod nostrum molestiae et.', NULL, '2025-10-28 16:30:27', 3, 'Blanditiis totam et sit ipsam facilis aut enim. Voluptatem vitae mollitia cumque et ut voluptas neque.', 'graded', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(229, 26, 11, 'Suscipit dolorum voluptatem nemo esse vel repellendus. Officiis quas dolore voluptatum beatae. Quos illo sunt qui accusantium autem reprehenderit amet.', NULL, '2025-10-28 16:30:27', NULL, NULL, 'submitted', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(230, 26, 16, 'Itaque quos autem sit at. Magni dolorem voluptatibus veritatis assumenda corrupti fuga impedit eos. Officia nulla error sunt omnis aspernatur ut.', NULL, '2025-10-28 16:30:27', 3, 'Et mollitia omnis quia consequuntur provident commodi. Numquam dolores enim laborum quasi perspiciatis nulla quam. Nihil qui vel quaerat accusantium quae quia iste.', 'graded', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(231, 26, 17, 'Nemo ipsum dolorum quibusdam et libero. Sint ea quasi fugiat nulla quia ducimus illo. Facere sed vitae architecto odio. Excepturi aut architecto et quaerat.', NULL, '2025-10-28 16:30:26', NULL, NULL, 'submitted', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(232, 26, 20, 'Cum tempora distinctio quos. Iusto neque eos sint consequuntur unde et tempore. Similique eveniet ut voluptatem quis excepturi. Minima sunt et similique nisi ab est similique.', NULL, '2025-10-28 16:30:27', 6, 'Vel nobis ipsam odio corporis quibusdam voluptatem quisquam officiis. Minima aut rerum sit amet aut voluptas est.', 'graded', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(233, 27, 1, 'Explicabo unde et sit quia sit minima. Beatae suscipit ut numquam ut fugiat porro eos quos. Aperiam hic aliquam voluptatibus velit suscipit. Harum veritatis aliquid corrupti assumenda blanditiis nobis.', NULL, '2025-10-28 16:30:26', 9, NULL, 'graded', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(234, 27, 6, 'Voluptatem commodi ipsum perspiciatis fugiat nisi. Officiis quasi eligendi nostrum facilis.', NULL, '2025-10-28 16:30:27', NULL, NULL, 'submitted', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(235, 27, 9, 'Nihil sed doloremque voluptas. Quae sint minus aperiam officia eius doloribus ea et. Consectetur dolorem qui autem ea porro modi.', 'C:\\Users\\bedi-\\AppData\\Local\\Temp\\fak9BE4.tmp', '2025-10-28 16:30:27', 9, NULL, 'graded', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(236, 27, 10, 'Quia adipisci eligendi qui voluptate veniam ad. Aut deserunt et officiis nisi. Similique veniam repudiandae aut possimus suscipit non quia. Est suscipit praesentium et illo ipsum nihil laboriosam.', NULL, '2025-10-28 16:30:27', 10, 'Nobis suscipit nihil et quia consequuntur sunt. Ratione voluptas doloremque veritatis ratione doloremque incidunt quod. Quae sed culpa aut modi aperiam dolor.', 'graded', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(237, 27, 13, 'Quidem aliquam omnis totam ratione. Sint minus voluptatem quaerat maiores iure totam. Exercitationem sequi aut et rem.', NULL, '2025-10-28 16:30:26', NULL, NULL, 'submitted', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(238, 27, 16, 'Voluptatum reprehenderit magni et dolores fugit asperiores perferendis. Omnis at dolore magni. A et expedita est nihil.', 'C:\\Users\\bedi-\\AppData\\Local\\Temp\\fak9C04.tmp', '2025-10-28 16:30:26', NULL, NULL, 'submitted', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(239, 27, 18, 'Voluptate fugit ipsum ullam omnis rerum. Eos magni et vero nihil. Repudiandae laboriosam qui voluptatem recusandae omnis tempora assumenda.', NULL, '2025-10-28 16:30:27', 9, 'Adipisci commodi laborum rerum rem similique soluta necessitatibus. Laboriosam voluptatem quis asperiores error consequatur sint animi. Eaque veritatis similique necessitatibus sit eum deleniti iusto nam.', 'graded', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(240, 28, 2, 'Enim molestiae voluptatem dignissimos qui fugiat quia. Esse et amet sit sapiente qui corrupti. Quia in vero labore maxime velit voluptatem cupiditate. Nemo asperiores sint dicta id aliquam minus nesciunt. Vitae reiciendis est qui distinctio rem similique.', NULL, '2025-10-28 16:30:27', 0, 'Omnis deleniti beatae fugiat ducimus. Dolores tempore praesentium sint aut.', 'graded', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(241, 28, 9, 'Qui esse et deleniti molestiae nihil. Aut id voluptas sapiente voluptates earum delectus impedit ut. Deserunt modi maxime et velit illum. Ad modi voluptates et fugit quasi.', NULL, '2025-10-28 16:30:27', 4, NULL, 'graded', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(242, 28, 13, 'Quaerat fugit enim nostrum quia iure. Unde pariatur ipsam non aliquam. Sequi exercitationem saepe et aut delectus mollitia molestias. Pariatur optio minima rerum laborum nulla.', 'C:\\Users\\bedi-\\AppData\\Local\\Temp\\fak9C24.tmp', '2025-10-28 16:30:27', NULL, NULL, 'submitted', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(243, 28, 14, 'Molestiae dolorem labore possimus non dolor. Molestias ea laborum omnis alias. Sed ex error autem.', NULL, '2025-10-28 16:30:27', 5, 'Ducimus dolorem expedita saepe dolorum et eveniet rerum deleniti. Sit est harum maxime explicabo molestiae rerum excepturi.', 'graded', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(244, 28, 19, 'Animi temporibus non quaerat distinctio autem a vel reprehenderit. Quis sint ea eos sit necessitatibus harum. Officia aspernatur eveniet modi dolor quia. Temporibus voluptas est corrupti.', NULL, '2025-10-28 16:30:26', 0, NULL, 'graded', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(245, 28, 20, 'Explicabo quis ducimus ipsam soluta. Fuga et numquam omnis. Magnam consequuntur et dolor quia aperiam.', NULL, '2025-10-28 16:30:26', 3, 'Omnis iure voluptas maxime unde. Error officiis velit voluptas nostrum. Beatae nihil enim eius pariatur voluptatem itaque voluptate quisquam.', 'graded', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(246, 29, 1, 'Consequatur perspiciatis possimus hic nulla aliquam eaque accusamus. Porro dolores quidem ut enim rerum. Aut quia sapiente itaque illum. Et omnis accusantium eos dolor qui nobis.', NULL, '2025-10-28 16:30:27', 4, 'In esse qui commodi aliquid qui impedit. Rerum impedit nihil blanditiis porro iste. Aut ipsam omnis assumenda dolores rem.', 'graded', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(247, 29, 4, 'Dolorum est soluta corporis id. In voluptatem rerum in perferendis laborum molestias aut. Quia provident provident mollitia. Eos quae consequatur asperiores voluptate voluptate ab repudiandae.', NULL, '2025-10-28 16:30:27', 2, 'Ut ducimus incidunt sed aut recusandae consequatur. Magni alias voluptates vitae ad est laboriosam praesentium. Itaque quia eligendi hic necessitatibus.', 'graded', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(248, 29, 7, 'Omnis aut consequuntur totam officia beatae et quo. Assumenda earum error rerum tempore perferendis voluptates. Sint a fugiat voluptatem deserunt.', 'C:\\Users\\bedi-\\AppData\\Local\\Temp\\fak9C64.tmp', '2025-10-28 16:30:27', NULL, NULL, 'submitted', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(249, 29, 12, 'Quia libero eaque vitae ullam aliquam eveniet. Esse optio quam delectus quia velit perferendis praesentium. Velit fugiat sint et hic laborum.', 'C:\\Users\\bedi-\\AppData\\Local\\Temp\\fak9C75.tmp', '2025-10-28 16:30:26', 2, 'Aut aspernatur laboriosam voluptas modi. Voluptatum nostrum tenetur voluptates.', 'graded', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(250, 29, 14, 'Amet architecto aperiam et magnam qui ratione quo. Ea omnis reprehenderit laboriosam. Delectus voluptatem occaecati eos quis quasi dolor deserunt. In id eum illo. Qui doloremque distinctio eos quis deleniti ducimus ut.', NULL, '2025-10-28 16:30:26', 10, 'Repellat eos sunt expedita itaque magnam qui omnis. Blanditiis odio veritatis perferendis possimus ad nemo. Totam aut aut tenetur maiores.', 'graded', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(251, 29, 16, 'Minima omnis optio fugiat eum nisi labore sed. Est et labore voluptate distinctio.', NULL, '2025-10-28 16:30:26', NULL, NULL, 'submitted', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(252, 29, 17, 'Voluptates ducimus doloribus beatae eligendi fuga. Quis molestiae aperiam recusandae maxime.', NULL, '2025-10-28 16:30:26', NULL, NULL, 'submitted', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(253, 30, 2, 'Sed delectus deserunt quia. Et eum labore quas natus cumque labore. Dolorem perferendis dolores quae repudiandae aliquid voluptatem. Quod enim molestiae laborum.', NULL, '2025-10-28 16:30:27', 8, 'Voluptas corporis enim magni inventore. Tempore iusto nostrum dolorem.', 'graded', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(254, 30, 3, 'Vitae ea rerum qui optio vero provident assumenda. Nam quo ut iste aut corporis veritatis omnis in. Aut incidunt incidunt et et molestias. Omnis est corporis labore mollitia quia eos.', NULL, '2025-10-28 16:30:27', NULL, NULL, 'submitted', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(255, 30, 4, 'Voluptas maiores et quis quia ad voluptas. Ab omnis voluptatem fugiat aut illum qui recusandae. Voluptatem perferendis laudantium ut maxime maxime. Cupiditate qui porro quis.', NULL, '2025-10-28 16:30:27', 10, 'Eaque enim minus modi voluptatibus. Omnis dolorum in voluptate consequatur exercitationem sunt.', 'graded', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(256, 30, 9, 'Eveniet eligendi qui voluptas magni consequatur exercitationem. Enim deserunt nulla ut voluptates hic aut tempora. Aut quas minima non sit soluta. Unde voluptatum molestias repellat sed asperiores.', 'C:\\Users\\bedi-\\AppData\\Local\\Temp\\fak9C95.tmp', '2025-10-28 16:30:26', 18, 'Dolores nihil tenetur voluptatum temporibus ipsum. Nam consequatur tenetur maiores ratione vero deserunt qui. Sed iure fugiat fugit ad sed.', 'graded', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(257, 30, 10, 'Ut magni et placeat qui qui voluptas sit. Neque voluptates illo est et inventore est. Adipisci qui et ea aut inventore. Illo repellat eos accusantium fuga minus.', NULL, '2025-10-28 16:30:26', NULL, NULL, 'submitted', '2025-10-28 15:30:27', '2025-10-28 15:30:27'),
(258, 30, 13, 'Non provident illo iure in dignissimos saepe. Omnis commodi necessitatibus esse officiis. Officia aut a asperiores consequatur perspiciatis quo officia.', 'C:\\Users\\bedi-\\AppData\\Local\\Temp\\fak9CA5.tmp', '2025-10-28 16:30:27', 10, 'Qui ipsum velit sint doloremque. Aut et hic in quia quae.', 'graded', '2025-10-28 15:30:28', '2025-10-28 15:30:28'),
(259, 30, 14, 'Et tempore blanditiis expedita non rerum. Aut est dolore quisquam ducimus. Incidunt facere nobis vitae sunt quisquam.', 'C:\\Users\\bedi-\\AppData\\Local\\Temp\\fak9CA6.tmp', '2025-10-28 16:30:27', NULL, NULL, 'submitted', '2025-10-28 15:30:28', '2025-10-28 15:30:28'),
(260, 30, 16, 'Perferendis dolor consequatur porro at. Incidunt natus omnis et voluptas facere. Optio odio omnis soluta eius perferendis dolorem.', 'C:\\Users\\bedi-\\AppData\\Local\\Temp\\fak9CB7.tmp', '2025-10-28 16:30:28', 16, 'Inventore impedit ut molestiae. Sequi pariatur dolorem quidem autem ipsam.', 'graded', '2025-10-28 15:30:28', '2025-10-28 15:30:28'),
(261, 30, 17, 'Enim expedita accusantium rerum atque quam sed perspiciatis. Consequatur corporis provident laboriosam repellat. Illum dolorem impedit sed blanditiis dolor repellendus dolorum. Deleniti hic tempora quo sit.', 'C:\\Users\\bedi-\\AppData\\Local\\Temp\\fak9CB8.tmp', '2025-10-28 16:30:28', 11, NULL, 'graded', '2025-10-28 15:30:28', '2025-10-28 15:30:28'),
(262, 30, 19, 'Nesciunt sed ut accusamus ut tempora nihil et. Id rerum modi sapiente omnis itaque. Sed aut aut consequatur eligendi quod laborum reprehenderit praesentium. Sed nemo ex necessitatibus autem blanditiis sapiente sint ipsam.', 'C:\\Users\\bedi-\\AppData\\Local\\Temp\\fak9CC9.tmp', '2025-10-28 16:30:28', NULL, NULL, 'submitted', '2025-10-28 15:30:28', '2025-10-28 15:30:28');

-- --------------------------------------------------------

--
-- Table structure for table `attendances`
--

CREATE TABLE `attendances` (
  `id` bigint UNSIGNED NOT NULL,
  `student_id` int UNSIGNED NOT NULL,
  `attendance_date` date NOT NULL,
  `status` enum('present','absent','late','excused') COLLATE utf8mb4_unicode_ci NOT NULL,
  `remarks` text COLLATE utf8mb4_unicode_ci,
  `recorded_by` int UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `blood_groups`
--

CREATE TABLE `blood_groups` (
  `id` int UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `blood_groups`
--

INSERT INTO `blood_groups` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'O-', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(2, 'O+', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(3, 'A+', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(4, 'A-', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(5, 'B+', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(6, 'B-', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(7, 'AB+', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(8, 'AB-', '2025-10-28 15:30:12', '2025-10-28 15:30:12');

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

CREATE TABLE `books` (
  `id` int UNSIGNED NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `my_class_id` int UNSIGNED DEFAULT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `author` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `book_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `location` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `total_copies` int DEFAULT NULL,
  `available` tinyint(1) NOT NULL DEFAULT '1',
  `issued_copies` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`id`, `name`, `my_class_id`, `description`, `author`, `book_type`, `url`, `location`, `total_copies`, `available`, `issued_copies`, `created_at`, `updated_at`) VALUES
(1, 'Enim voluptatem eum.', NULL, 'Cum ipsa at laudantium quis beatae id. Debitis laborum qui et ut. Maiores soluta sunt quia et.', 'Matthieu Le Dupuy', 'Thèse', NULL, 'Rack 9, Étagère g', 5, -2, 4, '2025-10-28 15:30:28', '2025-10-28 15:30:28'),
(2, 'Et asperiores veritatis ab.', NULL, 'Molestiae minima quasi aperiam. Quam molestiae et ut aut. Nesciunt earum tempore minus et et fugiat. Magni ipsa provident qui at est dolores a.', 'Susanne Millet', 'Manuel', 'http://leger.com/blanditiis-porro-assumenda-dignissimos-tempora.html', 'Rack 12, Étagère s', 1, 1, 0, '2025-10-28 15:30:28', '2025-10-28 15:30:28'),
(3, 'Dolorem rem omnis qui ut.', NULL, 'Id officia sapiente optio cumque asperiores in. Consequatur possimus unde quasi voluptas. Delectus assumenda maiores et.', 'Andrée Petit', 'Livre', NULL, 'Rack 10, Étagère d', 7, 0, 3, '2025-10-28 15:30:28', '2025-10-28 15:30:28'),
(4, 'Dolore ipsa ex excepturi.', NULL, 'Eum dolorum occaecati ut odio et saepe voluptates. Est quia possimus dolor quam corporis dolorem illo voluptas. Soluta nihil quasi eum rerum rerum. Modi necessitatibus perferendis aut voluptatem qui.', 'Lucy Bonnin-Coulon', 'Manuel', 'http://www.peron.fr/velit-harum-rerum-est-et-nihil.html', 'Rack 8, Étagère h', 9, 0, 4, '2025-10-28 15:30:28', '2025-10-28 15:30:28'),
(5, 'Ipsam ducimus numquam.', NULL, 'Neque qui et neque reprehenderit nemo libero. Exercitationem voluptas eos doloribus adipisci delectus. Odio sunt illo voluptatem unde omnis quisquam.', 'Rémy Delattre', 'Livre', NULL, 'Rack 18, Étagère p', 6, 0, 5, '2025-10-28 15:30:29', '2025-10-28 15:30:29'),
(6, 'In mollitia pariatur maxime.', NULL, 'A nisi qui optio est quia. Voluptates sit eum nesciunt voluptatem voluptatem laudantium. Voluptatem repudiandae facere dolor pariatur et.', 'Océane Hubert', 'Revue', NULL, 'Rack 17, Étagère o', 3, 1, 0, '2025-10-28 15:30:29', '2025-10-28 15:30:29'),
(7, 'Dolorem sed dolorem.', NULL, 'Excepturi ut nihil hic est alias qui aut cumque. Voluptatem autem placeat repellat expedita aspernatur illo eos. Itaque vero placeat aliquid aliquam quae.', 'Susan Pascal', 'Revue', NULL, 'Rack 19, Étagère r', 9, -1, 4, '2025-10-28 15:30:29', '2025-10-28 15:30:29'),
(8, 'Omnis molestiae aut corrupti.', NULL, 'Assumenda cupiditate quidem vitae sed. Omnis doloremque est sed sunt atque. Aut id tempore aut quidem fuga. Iste autem ut nisi non eum.', 'Alexandre Bourgeois-Delannoy', 'Manuel', 'http://www.maillet.fr/blanditiis-minima-quia-eaque-error-accusantium-rerum-quia', 'Rack 4, Étagère d', 8, 1, 0, '2025-10-28 15:30:29', '2025-10-28 15:30:29'),
(9, 'Illo rerum iusto.', NULL, 'Cumque ipsam nam quis debitis quia. Aut placeat nemo ad ex eum. Aut aut deleniti id harum. Et beatae maiores nemo sit quia et.', 'Henriette Faure', 'Livre', 'http://olivier.com/unde-fugiat-quis-unde-et-quisquam-illo-adipisci.html', 'Rack 8, Étagère f', 3, 1, 1, '2025-10-28 15:30:29', '2025-10-28 15:30:29'),
(10, 'Beatae aut occaecati provident.', NULL, 'Quia officiis voluptas reprehenderit aut eveniet quis perspiciatis eos. Quas ut adipisci atque molestias quia ex aliquid. Eveniet ipsa qui fugiat. Fugit et labore voluptas beatae repellat illo.', 'Astrid Barbier-Marchal', 'Thèse', NULL, 'Rack 4, Étagère c', 1, 1, 0, '2025-10-28 15:30:29', '2025-10-28 15:30:29'),
(11, 'Dicta rerum dolores.', NULL, 'Omnis nobis expedita provident vel illo. Aliquam ut architecto omnis. Et molestiae et fugiat eos non provident architecto cumque.', 'Julie Rousset-Roussel', 'Manuel', NULL, 'Rack 13, Étagère p', 7, 1, 0, '2025-10-28 15:30:29', '2025-10-28 15:30:29'),
(12, 'Alias eum aut quas.', NULL, 'Est corporis accusantium aliquam. Est perferendis mollitia rerum rerum sint accusantium. Aliquid consectetur cum doloremque eos.', 'Frédéric Gilbert', 'Thèse', NULL, 'Rack 14, Étagère w', 8, 1, 3, '2025-10-28 15:30:29', '2025-10-28 15:30:29'),
(13, 'Aut veniam harum autem.', NULL, 'Officiis quae voluptas rem tempore. Optio qui labore fugiat qui corporis perferendis tenetur. Iusto cumque sint aperiam voluptas aut ratione maiores dignissimos. Commodi delectus voluptatem et deserunt beatae et harum laborum.', 'Valérie Mace', 'Revue', NULL, 'Rack 17, Étagère q', 4, 1, 0, '2025-10-28 15:30:29', '2025-10-28 15:30:29'),
(14, 'Sed earum.', NULL, 'Placeat placeat dolores dolor ut qui fugit quaerat. Numquam ratione et rerum eos ratione. Quos voluptatem et et. Necessitatibus delectus repellat consectetur repellendus eos voluptas enim animi.', 'Anaïs Vasseur', 'Manuel', 'http://www.dumont.com/ut-expedita-amet-corporis-sapiente-omnis', 'Rack 18, Étagère m', 8, 1, 6, '2025-10-28 15:30:29', '2025-10-28 15:30:29'),
(15, 'Tempore minus aliquid.', NULL, 'Temporibus repudiandae quos et eaque officiis. Deserunt ipsa architecto facilis et qui vel quo. Rerum quidem officiis quis reprehenderit quam ab. Culpa qui veniam eum consequatur voluptas.', 'Corinne Laporte', 'Manuel', 'http://dubois.net/repellat-ut-voluptatem-illum-est-accusamus-voluptatum-voluptatem-aliquam.html', 'Rack 14, Étagère h', 2, 1, 2, '2025-10-28 15:30:29', '2025-10-28 15:30:29'),
(16, 'Eos maxime et.', NULL, 'Provident praesentium dolores non sint ut. Nobis iure et ipsa molestiae. Dolor vel blanditiis sed qui porro repudiandae. Officiis error nihil a.', 'Marine Vasseur', 'Livre', 'https://www.bousquet.com/doloribus-blanditiis-est-quis-ut-voluptatem', 'Rack 15, Étagère b', 2, 1, 0, '2025-10-28 15:30:29', '2025-10-28 15:30:29'),
(17, 'Voluptatem hic est odio.', NULL, 'Dolor omnis nostrum vel qui. Vero perferendis architecto blanditiis aut maxime explicabo. Et sed est rem dolores quidem autem.', 'Dominique Petitjean-Le Gall', 'Livre', NULL, 'Rack 1, Étagère d', 2, 1, 1, '2025-10-28 15:30:29', '2025-10-28 15:30:29'),
(18, 'Animi eveniet autem.', NULL, 'In placeat praesentium totam quo est labore. Aut repudiandae sunt quos occaecati alias sunt quasi.', 'Richard Reynaud', 'Manuel', NULL, 'Rack 1, Étagère w', 6, 1, 0, '2025-10-28 15:30:29', '2025-10-28 15:30:29'),
(19, 'Cum cupiditate sint sequi.', NULL, 'Fugiat est totam ea vitae. Est ea et repudiandae et mollitia veritatis. Velit in repudiandae distinctio. Sequi repellat debitis voluptas voluptatibus in.', 'Océane-Catherine Dupont', 'Livre', 'http://www.pereira.com/a-dolores-totam-consequatur-error', 'Rack 12, Étagère m', 4, -3, 4, '2025-10-28 15:30:29', '2025-10-28 15:30:29'),
(20, 'Et tempore fuga.', NULL, 'Voluptas illo eveniet aperiam consequuntur omnis. Eius quo blanditiis harum debitis magnam autem animi ex. Dolores quo voluptate vero itaque sapiente libero commodi. Id et dolorem voluptates.', 'Élodie Raymond', 'Revue', 'http://chauveau.fr/', 'Rack 20, Étagère u', 10, -3, 9, '2025-10-28 15:30:29', '2025-10-28 15:30:29'),
(21, 'Voluptatem rerum et.', NULL, 'Veniam aperiam deserunt non laborum pariatur est voluptates debitis. Dolores quibusdam repellendus sint aut debitis voluptatem ipsam. Facere aut est voluptas exercitationem aut. Voluptatibus voluptatem voluptatem ratione ex.', 'Agnès-Émilie Antoine', 'Livre', NULL, 'Rack 13, Étagère a', 3, 1, 2, '2025-10-28 15:30:29', '2025-10-28 15:30:29'),
(22, 'At eligendi aut.', NULL, 'Qui quis quis voluptatibus aut. Sit illo in laudantium fugit omnis consectetur debitis. Sunt dolores odit nobis dicta pariatur.', 'Margaud Legros', 'Manuel', NULL, 'Rack 5, Étagère i', 5, 0, 1, '2025-10-28 15:30:29', '2025-10-28 15:30:29'),
(23, 'Numquam provident itaque sint.', NULL, 'Unde a ea sunt corrupti est rerum. Incidunt assumenda dignissimos nemo sint voluptates pariatur. Temporibus tenetur voluptatibus sit neque est occaecati.', 'Marie Blot-Blanc', 'Manuel', NULL, 'Rack 19, Étagère e', 10, 1, 4, '2025-10-28 15:30:29', '2025-10-28 15:30:29'),
(24, 'Aperiam non dolores aut.', NULL, 'Aut soluta quia est ea. Voluptas expedita qui consequatur beatae libero.', 'Gilbert Marion-Marechal', 'Thèse', 'http://muller.fr/', 'Rack 20, Étagère p', 4, 1, 1, '2025-10-28 15:30:29', '2025-10-28 15:30:29'),
(25, 'Culpa et tempore.', NULL, 'Quasi quidem quia consectetur laudantium libero. Culpa laborum quia excepturi quisquam omnis. Dolorem ullam eius debitis ipsam.', 'Matthieu-Roger Gros', 'Revue', NULL, 'Rack 1, Étagère j', 6, -1, 6, '2025-10-28 15:30:29', '2025-10-28 15:30:29'),
(26, 'Aut ut maiores.', NULL, 'Velit quibusdam eos quasi magni. Consectetur qui qui ad inventore sit sit. Et repudiandae voluptas quia quam officia iste accusamus.', 'Anouk Pichon-Toussaint', 'Thèse', NULL, 'Rack 15, Étagère z', 8, 1, 2, '2025-10-28 15:30:29', '2025-10-28 15:30:29'),
(27, 'Veritatis inventore aut.', NULL, 'Cum optio sit iusto velit quia consequatur consequatur. Non eum qui nisi ex quo quia. Rerum placeat hic optio quos impedit qui.', 'Audrey Gros', 'Thèse', 'http://www.rodrigues.com/', 'Rack 7, Étagère c', 7, 0, 3, '2025-10-28 15:30:29', '2025-10-28 15:30:29'),
(28, 'Ad cumque aut.', NULL, 'Ut ea voluptates quidem minima harum minus. Nemo assumenda ullam asperiores fugiat ipsam eos officia. Excepturi quidem quam suscipit. Labore vitae aperiam exercitationem cum quasi nulla.', 'Adélaïde Mary', 'Manuel', 'http://bouchet.fr/', 'Rack 17, Étagère f', 6, 0, 2, '2025-10-28 15:30:29', '2025-10-28 15:30:29'),
(29, 'Tenetur inventore nam.', NULL, 'Ea perspiciatis consectetur ut. Illum debitis error est ducimus enim veritatis. Molestiae qui ut quo cumque ullam aut.', 'Alexandre Langlois', 'Manuel', NULL, 'Rack 16, Étagère m', 1, 1, 0, '2025-10-28 15:30:29', '2025-10-28 15:30:29'),
(30, 'Accusantium consectetur porro nostrum.', NULL, 'Et deserunt dolorum ratione nam explicabo. Facilis quod distinctio consequatur est architecto placeat recusandae veniam. Quo perspiciatis sed voluptates doloribus qui at expedita modi.', 'Manon Loiseau', 'Livre', NULL, 'Rack 10, Étagère a', 10, 1, 1, '2025-10-28 15:30:29', '2025-10-28 15:30:29'),
(31, 'Non exercitationem.', NULL, 'Aut esse vitae earum ea vel. Architecto perspiciatis enim occaecati consequatur ex. Unde perspiciatis perferendis ipsa facilis dignissimos tempore.', 'Claude Dupuis', 'Revue', NULL, 'Rack 9, Étagère i', 10, -3, 4, '2025-10-28 15:30:29', '2025-10-28 15:30:29'),
(32, 'In necessitatibus voluptas ea libero.', NULL, 'Reprehenderit eaque eius provident qui cum. Perferendis occaecati eos eius non qui.', 'Guillaume Payet', 'Manuel', NULL, 'Rack 13, Étagère b', 6, 0, 5, '2025-10-28 15:30:29', '2025-10-28 15:30:29'),
(33, 'Voluptatem repellat iusto.', NULL, 'Est accusamus qui ut esse. Ut culpa voluptates et minus. Et optio illum ex quas facere laudantium consequatur.', 'Roland Rodriguez', 'Livre', NULL, 'Rack 2, Étagère a', 7, 1, 0, '2025-10-28 15:30:29', '2025-10-28 15:30:29'),
(34, 'Consequatur ad adipisci.', NULL, 'Magnam laboriosam nisi voluptatem voluptatem animi. Tempora odio laborum est enim rerum.', 'Guy Ramos', 'Thèse', NULL, 'Rack 16, Étagère n', 1, 0, 1, '2025-10-28 15:30:29', '2025-10-28 15:30:29'),
(35, 'Facere eius corporis explicabo.', NULL, 'Dolor ut incidunt quo consequatur. Autem saepe ratione vero quo vitae doloribus id. Tempora vitae aut commodi porro tenetur.', 'Élisabeth Leroy', 'Manuel', NULL, 'Rack 13, Étagère l', 2, 0, 1, '2025-10-28 15:30:29', '2025-10-28 15:30:29'),
(36, 'Alias deleniti adipisci.', NULL, 'Repellat laborum magni exercitationem neque necessitatibus voluptas tempora. Sit omnis est et porro vero ut amet. Occaecati rerum ad quis. Repudiandae voluptas officiis non quos.', 'Vincent Dumont', 'Thèse', NULL, 'Rack 18, Étagère r', 8, -4, 6, '2025-10-28 15:30:29', '2025-10-28 15:30:29'),
(37, 'Sint exercitationem ut.', NULL, 'Et et molestias magnam. Libero quidem provident nesciunt. Qui repellat sit ut ut non ipsa id. Sunt neque neque ratione.', 'Benjamin-Théodore Pascal', 'Thèse', NULL, 'Rack 19, Étagère j', 5, 1, 3, '2025-10-28 15:30:29', '2025-10-28 15:30:29'),
(38, 'Tenetur commodi ratione vel.', NULL, 'Sit ut doloribus sapiente quaerat. Totam sed odio quos eos in voluptatem veniam ex. Voluptates ut ut corrupti saepe officia aut. Aperiam facere et neque eveniet aut hic.', 'Éric Hoareau', 'Manuel', NULL, 'Rack 3, Étagère n', 4, 0, 3, '2025-10-28 15:30:29', '2025-10-28 15:30:29'),
(39, 'Quod eum expedita qui.', NULL, 'Delectus ea ipsum possimus aut quos facilis architecto. Fugit et facilis aliquid. Aut voluptatum recusandae soluta vel. Aliquid autem laboriosam quas et nisi rerum quidem sit.', 'Vincent Gregoire-Guillon', 'Livre', NULL, 'Rack 11, Étagère y', 9, -1, 2, '2025-10-28 15:30:29', '2025-10-28 15:30:29'),
(40, 'Iste nobis ut vitae.', NULL, 'Sunt reprehenderit laborum possimus porro eligendi porro quod. Ut porro praesentium quo ducimus impedit totam. Explicabo amet et fugit. Quibusdam quasi corporis quam pariatur libero blanditiis.', 'Suzanne-Margaret Gimenez', 'Livre', NULL, 'Rack 12, Étagère v', 7, 1, 5, '2025-10-28 15:30:29', '2025-10-28 15:30:29'),
(41, 'Non enim molestiae.', NULL, 'Delectus aut doloribus corrupti dolorem eum. Maxime quibusdam consequatur temporibus deserunt qui sunt quidem. Assumenda a dolor assumenda ea esse maxime. Earum ducimus corrupti quod molestiae consectetur rerum molestiae.', 'Dominique Olivier', 'Livre', NULL, 'Rack 17, Étagère b', 5, -1, 3, '2025-10-28 15:30:29', '2025-10-28 15:30:29'),
(42, 'Sit laborum et est.', NULL, 'Qui voluptas magni minima natus quisquam dignissimos atque. Adipisci suscipit minus illo placeat. Placeat est et ut sed dolorem qui.', 'Léon du Pasquier', 'Revue', 'http://mary.com/eveniet-quisquam-rerum-porro-nihil-laudantium-autem-consequatur-ipsum', 'Rack 15, Étagère f', 4, -1, 3, '2025-10-28 15:30:29', '2025-10-28 15:30:29'),
(43, 'Fuga laborum alias.', NULL, 'Architecto voluptatem aspernatur sit magnam. Et ut cum eaque accusantium. Ut ad repellendus dolorem quia alias nemo et. Est perferendis ut dolore magnam voluptatem.', 'Isaac-Hugues Leconte', 'Thèse', NULL, 'Rack 1, Étagère y', 6, -2, 5, '2025-10-28 15:30:29', '2025-10-28 15:30:29'),
(44, 'Saepe ut quo deserunt.', NULL, 'Neque placeat hic in a id sit voluptatem. Et quo id sed cupiditate recusandae porro ad perspiciatis. Est non impedit velit ut unde saepe. Quod et repellendus quia libero.', 'Henri Gosselin', 'Thèse', NULL, 'Rack 9, Étagère p', 5, 1, 1, '2025-10-28 15:30:29', '2025-10-28 15:30:29'),
(45, 'Non necessitatibus sit.', NULL, 'Sequi voluptatem dolores voluptate soluta quo. Suscipit rerum fuga ex itaque consectetur. Libero quos culpa qui occaecati quidem sequi. Reprehenderit aut sint quia libero voluptas autem quisquam.', 'Claudine-Caroline Normand', 'Revue', NULL, 'Rack 16, Étagère r', 10, -1, 6, '2025-10-28 15:30:29', '2025-10-28 15:30:29'),
(46, 'Atque illo omnis.', NULL, 'Id pariatur repudiandae consequatur reprehenderit ducimus. Unde ad sunt ea laudantium. Tempore possimus aut velit ut consectetur.', 'Adrien Godard-Simon', 'Revue', NULL, 'Rack 19, Étagère c', 2, 1, 0, '2025-10-28 15:30:29', '2025-10-28 15:30:29'),
(47, 'Dolorem magnam distinctio.', NULL, 'Ipsam suscipit adipisci ut qui. Est ut laudantium nostrum eius vitae iure corporis id. Molestias id ab veritatis in.', 'Bernard de Delaunay', 'Manuel', NULL, 'Rack 19, Étagère z', 5, -3, 5, '2025-10-28 15:30:29', '2025-10-28 15:30:29'),
(48, 'Voluptatum maxime eaque.', NULL, 'Odio facere placeat omnis libero corrupti. Libero occaecati laboriosam et aliquid impedit qui.', 'Zacharie Fernandes', 'Livre', NULL, 'Rack 9, Étagère y', 1, 1, 0, '2025-10-28 15:30:29', '2025-10-28 15:30:29'),
(49, 'Sed voluptatem.', NULL, 'Autem autem debitis eum aut voluptatem sunt dolores. Qui et magnam et officia libero ut qui nobis. Alias quod rerum eaque esse molestiae dolorem. Modi voluptate numquam perspiciatis ea quia. Blanditiis earum cumque eos molestiae est nisi.', 'Charles-Olivier Hebert', 'Manuel', NULL, 'Rack 13, Étagère y', 3, 1, 0, '2025-10-28 15:30:29', '2025-10-28 15:30:29'),
(50, 'Dolorem eos eum.', NULL, 'Sed sunt illum enim qui. Est ea dolorem qui aliquid earum. Et sunt quod aliquid sunt accusantium. Facere praesentium mollitia est et.', 'Alfred-Benoît Gosselin', 'Revue', NULL, 'Rack 9, Étagère y', 2, -1, 2, '2025-10-28 15:30:29', '2025-10-28 15:30:29');

-- --------------------------------------------------------

--
-- Table structure for table `book_categories`
--

CREATE TABLE `book_categories` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `book_requests`
--

CREATE TABLE `book_requests` (
  `id` bigint UNSIGNED NOT NULL,
  `book_id` int UNSIGNED NOT NULL,
  `student_id` int UNSIGNED NOT NULL,
  `request_date` date NOT NULL,
  `expected_return_date` date DEFAULT NULL,
  `actual_return_date` date DEFAULT NULL,
  `status` enum('pending','approved','rejected','borrowed','returned') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `remarks` text COLLATE utf8mb4_unicode_ci,
  `approved_by` int UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `book_requests`
--

INSERT INTO `book_requests` (`id`, `book_id`, `student_id`, `request_date`, `expected_return_date`, `actual_return_date`, `status`, `remarks`, `approved_by`, `created_at`, `updated_at`) VALUES
(1, 1, 73, '2025-01-16', '2025-07-03', '2025-02-28', 'returned', NULL, 1, '2025-10-28 15:30:28', '2025-10-28 15:30:28'),
(2, 1, 8, '2025-02-20', '2025-06-13', '2025-05-30', 'returned', NULL, 1, '2025-10-28 15:30:28', '2025-10-28 15:30:28'),
(3, 1, 16, '2025-08-31', '2025-09-16', '2025-09-07', 'returned', NULL, 1, '2025-10-28 15:30:28', '2025-10-28 15:30:28'),
(4, 3, 16, '2025-05-11', '2025-10-07', NULL, 'borrowed', NULL, 1, '2025-10-28 15:30:28', '2025-10-28 15:30:28'),
(5, 4, 71, '2025-05-16', '2025-09-26', '2025-08-21', 'returned', 'Exercitationem ut eos qui et perspiciatis.', 1, '2025-10-28 15:30:28', '2025-10-28 15:30:28'),
(6, 5, 66, '2025-06-05', '2025-08-19', NULL, 'borrowed', NULL, 1, '2025-10-28 15:30:29', '2025-10-28 15:30:29'),
(7, 7, 16, '2025-10-09', '2025-11-17', '2025-10-14', 'returned', NULL, 1, '2025-10-28 15:30:29', '2025-10-28 15:30:29'),
(8, 7, 8, '2025-08-18', '2025-11-09', '2025-10-17', 'returned', NULL, 1, '2025-10-28 15:30:29', '2025-10-28 15:30:29'),
(9, 19, 16, '2025-05-05', '2025-11-05', '2025-05-19', 'returned', NULL, 1, '2025-10-28 15:30:29', '2025-10-28 15:30:29'),
(10, 19, 16, '2025-02-04', '2025-10-04', NULL, 'borrowed', NULL, 1, '2025-10-28 15:30:29', '2025-10-28 15:30:29'),
(11, 19, 60, '2025-10-20', '2025-11-21', NULL, 'borrowed', NULL, 1, '2025-10-28 15:30:29', '2025-10-28 15:30:29'),
(12, 19, 62, '2025-03-25', '2025-08-05', '2025-05-22', 'returned', 'Ratione rerum maxime qui qui iure.', 1, '2025-10-28 15:30:29', '2025-10-28 15:30:29'),
(13, 20, 8, '2025-07-20', '2025-11-10', '2025-08-04', 'returned', NULL, 1, '2025-10-28 15:30:29', '2025-10-28 15:30:29'),
(14, 20, 75, '2024-12-21', '2025-04-05', '2025-01-18', 'returned', NULL, 1, '2025-10-28 15:30:29', '2025-10-28 15:30:29'),
(15, 20, 8, '2025-01-01', '2025-01-17', NULL, 'borrowed', NULL, 1, '2025-10-28 15:30:29', '2025-10-28 15:30:29'),
(16, 20, 12, '2025-08-18', '2025-09-20', NULL, 'borrowed', NULL, 1, '2025-10-28 15:30:29', '2025-10-28 15:30:29'),
(17, 22, 12, '2025-06-12', '2025-10-08', '2025-08-20', 'returned', NULL, 1, '2025-10-28 15:30:29', '2025-10-28 15:30:29'),
(18, 25, 16, '2025-05-13', '2025-10-26', '2025-06-13', 'returned', NULL, 1, '2025-10-28 15:30:29', '2025-10-28 15:30:29'),
(19, 25, 66, '2025-04-05', '2025-07-03', '2025-05-08', 'returned', NULL, 1, '2025-10-28 15:30:29', '2025-10-28 15:30:29'),
(20, 27, 12, '2025-02-26', '2025-08-15', '2025-05-03', 'returned', NULL, 1, '2025-10-28 15:30:29', '2025-10-28 15:30:29'),
(21, 28, 8, '2025-05-03', '2025-05-08', NULL, 'borrowed', 'Et quia reprehenderit aliquid quae adipisci.', 1, '2025-10-28 15:30:29', '2025-10-28 15:30:29'),
(22, 31, 8, '2024-12-30', '2025-10-12', '2025-02-05', 'returned', NULL, 1, '2025-10-28 15:30:29', '2025-10-28 15:30:29'),
(23, 31, 8, '2024-12-18', '2025-08-17', '2025-08-15', 'returned', NULL, 1, '2025-10-28 15:30:29', '2025-10-28 15:30:29'),
(24, 31, 62, '2025-10-10', '2025-11-18', '2025-10-12', 'returned', 'Eligendi odit vel nihil est.', 1, '2025-10-28 15:30:29', '2025-10-28 15:30:29'),
(25, 31, 72, '2025-04-17', '2025-09-23', '2025-09-08', 'returned', NULL, 1, '2025-10-28 15:30:29', '2025-10-28 15:30:29'),
(26, 32, 12, '2025-10-03', '2025-11-08', NULL, 'borrowed', NULL, 1, '2025-10-28 15:30:29', '2025-10-28 15:30:29'),
(27, 34, 8, '2025-10-18', '2025-11-19', '2025-10-21', 'returned', 'Velit neque provident praesentium minima aut.', 1, '2025-10-28 15:30:29', '2025-10-28 15:30:29'),
(28, 35, 12, '2024-12-13', '2025-04-21', NULL, 'borrowed', NULL, 1, '2025-10-28 15:30:29', '2025-10-28 15:30:29'),
(29, 36, 74, '2025-03-21', '2025-08-28', '2025-06-17', 'returned', NULL, 1, '2025-10-28 15:30:29', '2025-10-28 15:30:29'),
(30, 36, 71, '2024-12-14', '2025-08-30', '2025-06-07', 'returned', NULL, 1, '2025-10-28 15:30:29', '2025-10-28 15:30:29'),
(31, 36, 12, '2025-04-01', '2025-08-27', '2025-08-23', 'returned', NULL, 1, '2025-10-28 15:30:29', '2025-10-28 15:30:29'),
(32, 36, 12, '2025-05-05', '2025-11-13', NULL, 'borrowed', 'Voluptas qui sed minus tempora.', 1, '2025-10-28 15:30:29', '2025-10-28 15:30:29'),
(33, 36, 67, '2025-01-15', '2025-02-07', '2025-01-18', 'returned', NULL, 1, '2025-10-28 15:30:29', '2025-10-28 15:30:29'),
(34, 38, 75, '2025-07-13', '2025-10-21', NULL, 'borrowed', NULL, 1, '2025-10-28 15:30:29', '2025-10-28 15:30:29'),
(35, 39, 12, '2025-09-18', '2025-10-16', '2025-09-20', 'returned', NULL, 1, '2025-10-28 15:30:29', '2025-10-28 15:30:29'),
(36, 39, 8, '2025-02-20', '2025-06-13', '2025-05-09', 'returned', NULL, 1, '2025-10-28 15:30:29', '2025-10-28 15:30:29'),
(37, 41, 61, '2025-07-08', '2025-11-06', '2025-07-31', 'returned', 'Voluptatum saepe praesentium atque quo ut sequi cum numquam.', 1, '2025-10-28 15:30:29', '2025-10-28 15:30:29'),
(38, 41, 16, '2025-05-05', '2025-05-19', '2025-05-16', 'returned', NULL, 1, '2025-10-28 15:30:29', '2025-10-28 15:30:29'),
(39, 42, 8, '2025-10-11', '2025-11-14', NULL, 'borrowed', 'Voluptas iure eveniet velit et.', 1, '2025-10-28 15:30:29', '2025-10-28 15:30:29'),
(40, 42, 16, '2025-06-14', '2025-08-20', '2025-08-14', 'returned', 'Repellat quaerat tempora voluptatem ipsum.', 1, '2025-10-28 15:30:29', '2025-10-28 15:30:29'),
(41, 43, 58, '2025-09-12', '2025-10-31', NULL, 'borrowed', 'Sit vel vel nemo.', 1, '2025-10-28 15:30:29', '2025-10-28 15:30:29'),
(42, 43, 3, '2025-01-26', '2025-07-13', NULL, 'borrowed', NULL, 1, '2025-10-28 15:30:29', '2025-10-28 15:30:29'),
(43, 43, 73, '2025-04-30', '2025-05-07', '2025-05-03', 'returned', NULL, 1, '2025-10-28 15:30:29', '2025-10-28 15:30:29'),
(44, 45, 69, '2024-12-06', '2025-08-21', '2024-12-13', 'returned', NULL, 1, '2025-10-28 15:30:29', '2025-10-28 15:30:29'),
(45, 45, 8, '2025-09-21', '2025-09-21', '2025-09-21', 'returned', NULL, 1, '2025-10-28 15:30:29', '2025-10-28 15:30:29'),
(46, 47, 3, '2024-12-04', '2025-03-30', '2025-03-03', 'returned', NULL, 1, '2025-10-28 15:30:29', '2025-10-28 15:30:29'),
(47, 47, 16, '2025-07-04', '2025-09-25', '2025-07-07', 'returned', NULL, 1, '2025-10-28 15:30:29', '2025-10-28 15:30:29'),
(48, 47, 3, '2024-12-17', '2025-07-04', '2025-01-06', 'returned', NULL, 1, '2025-10-28 15:30:29', '2025-10-28 15:30:29'),
(49, 47, 8, '2024-11-21', '2025-05-17', '2025-03-31', 'returned', NULL, 1, '2025-10-28 15:30:29', '2025-10-28 15:30:29'),
(50, 50, 3, '2025-02-23', '2025-04-13', '2025-03-17', 'returned', 'Et nemo fugit omnis nihil.', 1, '2025-10-28 15:30:29', '2025-10-28 15:30:29'),
(51, 50, 60, '2025-05-31', '2025-09-27', '2025-07-14', 'returned', NULL, 1, '2025-10-28 15:30:29', '2025-10-28 15:30:29');

-- --------------------------------------------------------

--
-- Table structure for table `class_types`
--

CREATE TABLE `class_types` (
  `id` int UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `class_types`
--

INSERT INTO `class_types` (`id`, `name`, `code`, `created_at`, `updated_at`) VALUES
(1, 'Creche', 'C', NULL, NULL),
(2, 'Pre Nursery', 'PN', NULL, NULL),
(3, 'Nursery', 'N', NULL, NULL),
(4, 'Primary', 'P', NULL, NULL),
(5, 'Junior Secondary', 'J', NULL, NULL),
(6, 'Senior Secondary', 'S', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `dorms`
--

CREATE TABLE `dorms` (
  `id` int UNSIGNED NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `dorms`
--

INSERT INTO `dorms` (`id`, `name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Faith Hostel', NULL, NULL, NULL),
(2, 'Peace Hostel', NULL, NULL, NULL),
(3, 'Grace Hostel', NULL, NULL, NULL),
(4, 'Success Hostel', NULL, NULL, NULL),
(5, 'Trust Hostel', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `exams`
--

CREATE TABLE `exams` (
  `id` int UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `term` tinyint NOT NULL,
  `year` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `exam_records`
--

CREATE TABLE `exam_records` (
  `id` int UNSIGNED NOT NULL,
  `exam_id` int UNSIGNED NOT NULL,
  `student_id` int UNSIGNED NOT NULL,
  `my_class_id` int UNSIGNED NOT NULL,
  `section_id` int UNSIGNED NOT NULL,
  `total` int DEFAULT NULL,
  `ave` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `class_ave` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pos` int DEFAULT NULL,
  `af` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ps` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `p_comment` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `t_comment` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `year` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `grades`
--

CREATE TABLE `grades` (
  `id` int UNSIGNED NOT NULL,
  `name` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  `class_type_id` int UNSIGNED DEFAULT NULL,
  `mark_from` tinyint NOT NULL,
  `mark_to` tinyint NOT NULL,
  `remark` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `grades`
--

INSERT INTO `grades` (`id`, `name`, `class_type_id`, `mark_from`, `mark_to`, `remark`, `created_at`, `updated_at`) VALUES
(1, 'A', NULL, 70, 100, 'Excellent', NULL, NULL),
(2, 'B', NULL, 60, 69, 'Very Good', NULL, NULL),
(3, 'C', NULL, 50, 59, 'Good', NULL, NULL),
(4, 'D', NULL, 45, 49, 'Pass', NULL, NULL),
(5, 'E', NULL, 40, 44, 'Poor', NULL, NULL),
(6, 'F', NULL, 0, 39, 'Fail', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `learning_materials`
--

CREATE TABLE `learning_materials` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `file_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `class_id` int UNSIGNED DEFAULT NULL,
  `section_id` int UNSIGNED DEFAULT NULL,
  `is_published` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lgas`
--

CREATE TABLE `lgas` (
  `id` int UNSIGNED NOT NULL,
  `state_id` int UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `lgas`
--

INSERT INTO `lgas` (`id`, `state_id`, `name`, `created_at`, `updated_at`) VALUES
(1, 1, 'Aba North', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(2, 1, 'Aba South', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(3, 1, 'Arochukwu', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(4, 1, 'Bende', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(5, 1, 'Ikwuano', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(6, 1, 'Isiala Ngwa North', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(7, 1, 'Isiala Ngwa South', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(8, 1, 'Isuikwuato', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(9, 1, 'Obi Ngwa', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(10, 1, 'Ohafia', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(11, 1, 'Osisioma', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(12, 1, 'Ugwunagbo', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(13, 1, 'Ukwa East', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(14, 1, 'Ukwa West', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(15, 1, 'Umuahia North', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(16, 1, 'Umuahia South', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(17, 1, 'Umu Nneochi', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(18, 2, 'Demsa', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(19, 2, 'Fufure', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(20, 2, 'Ganye', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(21, 2, 'Gayuk', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(22, 2, 'Gombi', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(23, 2, 'Grie', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(24, 2, 'Hong', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(25, 2, 'Jada', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(26, 2, 'Larmurde', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(27, 2, 'Madagali', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(28, 2, 'Maiha', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(29, 2, 'Mayo Belwa', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(30, 2, 'Michika', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(31, 2, 'Mubi North', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(32, 2, 'Mubi South', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(33, 2, 'Numan', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(34, 2, 'Shelleng', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(35, 2, 'Song', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(36, 2, 'Toungo', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(37, 2, 'Yola North', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(38, 2, 'Yola South', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(39, 3, 'Abak', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(40, 3, 'Eastern Obolo', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(41, 3, 'Eket', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(42, 3, 'Esit Eket', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(43, 3, 'Essien Udim', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(44, 3, 'Etim Ekpo', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(45, 3, 'Etinan', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(46, 3, 'Ibeno', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(47, 3, 'Ibesikpo Asutan', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(48, 3, 'Ibiono-Ibom', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(49, 3, 'Ika', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(50, 3, 'Ikono', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(51, 3, 'Ikot Abasi', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(52, 3, 'Ikot Ekpene', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(53, 3, 'Ini', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(54, 3, 'Itu', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(55, 3, 'Mbo', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(56, 3, 'Mkpat-Enin', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(57, 3, 'Nsit-Atai', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(58, 3, 'Nsit-Ibom', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(59, 3, 'Nsit-Ubium', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(60, 3, 'Obot Akara', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(61, 3, 'Okobo', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(62, 3, 'Onna', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(63, 3, 'Oron', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(64, 3, 'Oruk Anam', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(65, 3, 'Udung-Uko', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(66, 3, 'Ukanafun', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(67, 3, 'Uruan', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(68, 3, 'Urue-Offong/Oruko', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(69, 3, 'Uyo', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(70, 4, 'Aguata', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(71, 4, 'Anambra East', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(72, 4, 'Anambra West', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(73, 4, 'Anaocha', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(74, 4, 'Awka North', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(75, 4, 'Awka South', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(76, 4, 'Ayamelum', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(77, 4, 'Dunukofia', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(78, 4, 'Ekwusigo', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(79, 4, 'Idemili North', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(80, 4, 'Idemili South', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(81, 4, 'Ihiala', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(82, 4, 'Njikoka', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(83, 4, 'Nnewi North', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(84, 4, 'Nnewi South', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(85, 4, 'Ogbaru', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(86, 4, 'Onitsha North', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(87, 4, 'Onitsha South', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(88, 4, 'Orumba North', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(89, 4, 'Orumba South', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(90, 4, 'Oyi', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(91, 5, 'Alkaleri', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(92, 5, 'Bauchi', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(93, 5, 'Bogoro', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(94, 5, 'Damban', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(95, 5, 'Darazo', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(96, 5, 'Dass', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(97, 5, 'Gamawa', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(98, 5, 'Ganjuwa', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(99, 5, 'Giade', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(100, 5, 'Itas/Gadau', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(101, 5, 'Jama\'are', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(102, 5, 'Katagum', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(103, 5, 'Kirfi', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(104, 5, 'Misau', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(105, 5, 'Ningi', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(106, 5, 'Shira', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(107, 5, 'Tafawa Balewa', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(108, 5, 'Toro', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(109, 5, 'Warji', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(110, 5, 'Zaki', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(111, 6, 'Brass', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(112, 6, 'Ekeremor', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(113, 6, 'Kolokuma/Opokuma', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(114, 6, 'Nembe', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(115, 6, 'Ogbia', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(116, 6, 'Sagbama', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(117, 6, 'Southern Ijaw', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(118, 6, 'Yenagoa', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(119, 7, 'Agatu', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(120, 7, 'Apa', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(121, 7, 'Ado', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(122, 7, 'Buruku', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(123, 7, 'Gboko', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(124, 7, 'Guma', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(125, 7, 'Gwer East', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(126, 7, 'Gwer West', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(127, 7, 'Katsina-Ala', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(128, 7, 'Konshisha', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(129, 7, 'Kwande', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(130, 7, 'Logo', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(131, 7, 'Makurdi', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(132, 7, 'Obi', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(133, 7, 'Ogbadibo', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(134, 7, 'Ohimini', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(135, 7, 'Oju', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(136, 7, 'Okpokwu', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(137, 7, 'Oturkpo', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(138, 7, 'Tarka', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(139, 7, 'Ukum', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(140, 7, 'Ushongo', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(141, 7, 'Vandeikya', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(142, 8, 'Abadam', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(143, 8, 'Askira/Uba', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(144, 8, 'Bama', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(145, 8, 'Bayo', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(146, 8, 'Biu', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(147, 8, 'Chibok', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(148, 8, 'Damboa', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(149, 8, 'Dikwa', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(150, 8, 'Gubio', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(151, 8, 'Guzamala', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(152, 8, 'Gwoza', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(153, 8, 'Hawul', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(154, 8, 'Jere', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(155, 8, 'Kaga', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(156, 8, 'Kala/Balge', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(157, 8, 'Konduga', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(158, 8, 'Kukawa', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(159, 8, 'Kwaya Kusar', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(160, 8, 'Mafa', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(161, 8, 'Magumeri', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(162, 8, 'Maiduguri', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(163, 8, 'Marte', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(164, 8, 'Mobbar', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(165, 8, 'Monguno', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(166, 8, 'Ngala', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(167, 8, 'Nganzai', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(168, 8, 'Shani', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(169, 9, 'Abi', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(170, 9, 'Akamkpa', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(171, 9, 'Akpabuyo', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(172, 9, 'Bakassi', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(173, 9, 'Bekwarra', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(174, 9, 'Biase', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(175, 9, 'Boki', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(176, 9, 'Calabar Municipal', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(177, 9, 'Calabar South', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(178, 9, 'Etung', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(179, 9, 'Ikom', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(180, 9, 'Obanliku', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(181, 9, 'Obubra', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(182, 9, 'Obudu', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(183, 9, 'Odukpani', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(184, 9, 'Ogoja', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(185, 9, 'Yakuur', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(186, 9, 'Yala', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(187, 10, 'Aniocha North', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(188, 10, 'Aniocha South', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(189, 10, 'Bomadi', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(190, 10, 'Burutu', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(191, 10, 'Ethiope East', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(192, 10, 'Ethiope West', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(193, 10, 'Ika North East', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(194, 10, 'Ika South', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(195, 10, 'Isoko North', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(196, 10, 'Isoko South', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(197, 10, 'Ndokwa East', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(198, 10, 'Ndokwa West', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(199, 10, 'Okpe', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(200, 10, 'Oshimili North', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(201, 10, 'Oshimili South', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(202, 10, 'Patani', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(203, 10, 'Sapele, Delta', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(204, 10, 'Udu', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(205, 10, 'Ughelli North', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(206, 10, 'Ughelli South', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(207, 10, 'Ukwuani', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(208, 10, 'Uvwie', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(209, 10, 'Warri North', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(210, 10, 'Warri South', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(211, 10, 'Warri South West', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(212, 11, 'Abakaliki', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(213, 11, 'Afikpo North', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(214, 11, 'Afikpo South', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(215, 11, 'Ebonyi', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(216, 11, 'Ezza North', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(217, 11, 'Ezza South', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(218, 11, 'Ikwo', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(219, 11, 'Ishielu', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(220, 11, 'Ivo', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(221, 11, 'Izzi', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(222, 11, 'Ohaozara', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(223, 11, 'Ohaukwu', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(224, 11, 'Onicha', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(225, 12, 'Akoko-Edo', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(226, 12, 'Egor', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(227, 12, 'Esan Central', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(228, 12, 'Esan North-East', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(229, 12, 'Esan South-East', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(230, 12, 'Esan West', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(231, 12, 'Etsako Central', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(232, 12, 'Etsako East', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(233, 12, 'Etsako West', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(234, 12, 'Igueben', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(235, 12, 'Ikpoba Okha', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(236, 12, 'Orhionmwon', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(237, 12, 'Oredo', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(238, 12, 'Ovia North-East', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(239, 12, 'Ovia South-West', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(240, 12, 'Owan East', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(241, 12, 'Owan West', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(242, 12, 'Uhunmwonde', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(243, 13, 'Ado Ekiti', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(244, 13, 'Efon', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(245, 13, 'Ekiti East', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(246, 13, 'Ekiti South-West', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(247, 13, 'Ekiti West', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(248, 13, 'Emure', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(249, 13, 'Gbonyin', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(250, 13, 'Ido Osi', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(251, 13, 'Ijero', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(252, 13, 'Ikere', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(253, 13, 'Ikole', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(254, 13, 'Ilejemeje', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(255, 13, 'Irepodun/Ifelodun', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(256, 13, 'Ise/Orun', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(257, 13, 'Moba', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(258, 13, 'Oye', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(259, 14, 'Aninri', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(260, 14, 'Awgu', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(261, 14, 'Enugu East', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(262, 14, 'Enugu North', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(263, 14, 'Enugu South', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(264, 14, 'Ezeagu', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(265, 14, 'Igbo Etiti', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(266, 14, 'Igbo Eze North', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(267, 14, 'Igbo Eze South', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(268, 14, 'Isi Uzo', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(269, 14, 'Nkanu East', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(270, 14, 'Nkanu West', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(271, 14, 'Nsukka', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(272, 14, 'Oji River', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(273, 14, 'Udenu', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(274, 14, 'Udi', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(275, 14, 'Uzo Uwani', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(276, 15, 'Abaji', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(277, 15, 'Bwari', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(278, 15, 'Gwagwalada', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(279, 15, 'Kuje', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(280, 15, 'Kwali', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(281, 15, 'Municipal Area Council', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(282, 16, 'Akko', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(283, 16, 'Balanga', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(284, 16, 'Billiri', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(285, 16, 'Dukku', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(286, 16, 'Funakaye', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(287, 16, 'Gombe', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(288, 16, 'Kaltungo', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(289, 16, 'Kwami', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(290, 16, 'Nafada', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(291, 16, 'Shongom', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(292, 16, 'Yamaltu/Deba', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(293, 17, 'Aboh Mbaise', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(294, 17, 'Ahiazu Mbaise', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(295, 17, 'Ehime Mbano', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(296, 17, 'Ezinihitte', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(297, 17, 'Ideato North', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(298, 17, 'Ideato South', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(299, 17, 'Ihitte/Uboma', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(300, 17, 'Ikeduru', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(301, 17, 'Isiala Mbano', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(302, 17, 'Isu', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(303, 17, 'Mbaitoli', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(304, 17, 'Ngor Okpala', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(305, 17, 'Njaba', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(306, 17, 'Nkwerre', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(307, 17, 'Nwangele', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(308, 17, 'Obowo', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(309, 17, 'Oguta', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(310, 17, 'Ohaji/Egbema', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(311, 17, 'Okigwe', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(312, 17, 'Orlu', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(313, 17, 'Orsu', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(314, 17, 'Oru East', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(315, 17, 'Oru West', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(316, 17, 'Owerri Municipal', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(317, 17, 'Owerri North', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(318, 17, 'Owerri West', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(319, 17, 'Unuimo', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(320, 18, 'Auyo', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(321, 18, 'Babura', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(322, 18, 'Biriniwa', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(323, 18, 'Birnin Kudu', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(324, 18, 'Buji', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(325, 18, 'Dutse', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(326, 18, 'Gagarawa', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(327, 18, 'Garki', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(328, 18, 'Gumel', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(329, 18, 'Guri', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(330, 18, 'Gwaram', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(331, 18, 'Gwiwa', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(332, 18, 'Hadejia', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(333, 18, 'Jahun', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(334, 18, 'Kafin Hausa', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(335, 18, 'Kazaure', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(336, 18, 'Kiri Kasama', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(337, 18, 'Kiyawa', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(338, 18, 'Kaugama', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(339, 18, 'Maigatari', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(340, 18, 'Malam Madori', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(341, 18, 'Miga', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(342, 18, 'Ringim', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(343, 18, 'Roni', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(344, 18, 'Sule Tankarkar', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(345, 18, 'Taura', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(346, 18, 'Yankwashi', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(347, 19, 'Birnin Gwari', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(348, 19, 'Chikun', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(349, 19, 'Giwa', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(350, 19, 'Igabi', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(351, 19, 'Ikara', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(352, 19, 'Jaba', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(353, 19, 'Jema\'a', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(354, 19, 'Kachia', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(355, 19, 'Kaduna North', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(356, 19, 'Kaduna South', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(357, 19, 'Kagarko', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(358, 19, 'Kajuru', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(359, 19, 'Kaura', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(360, 19, 'Kauru', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(361, 19, 'Kubau', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(362, 19, 'Kudan', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(363, 19, 'Lere', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(364, 19, 'Makarfi', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(365, 19, 'Sabon Gari', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(366, 19, 'Sanga', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(367, 19, 'Soba', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(368, 19, 'Zangon Kataf', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(369, 19, 'Zaria', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(370, 20, 'Ajingi', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(371, 20, 'Albasu', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(372, 20, 'Bagwai', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(373, 20, 'Bebeji', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(374, 20, 'Bichi', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(375, 20, 'Bunkure', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(376, 20, 'Dala', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(377, 20, 'Dambatta', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(378, 20, 'Dawakin Kudu', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(379, 20, 'Dawakin Tofa', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(380, 20, 'Doguwa', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(381, 20, 'Fagge', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(382, 20, 'Gabasawa', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(383, 20, 'Garko', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(384, 20, 'Garun Mallam', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(385, 20, 'Gaya', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(386, 20, 'Gezawa', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(387, 20, 'Gwale', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(388, 20, 'Gwarzo', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(389, 20, 'Kabo', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(390, 20, 'Kano Municipal', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(391, 20, 'Karaye', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(392, 20, 'Kibiya', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(393, 20, 'Kiru', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(394, 20, 'Kumbotso', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(395, 20, 'Kunchi', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(396, 20, 'Kura', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(397, 20, 'Madobi', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(398, 20, 'Makoda', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(399, 20, 'Minjibir', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(400, 20, 'Nasarawa', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(401, 20, 'Rano', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(402, 20, 'Rimin Gado', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(403, 20, 'Rogo', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(404, 20, 'Shanono', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(405, 20, 'Sumaila', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(406, 20, 'Takai', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(407, 20, 'Tarauni', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(408, 20, 'Tofa', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(409, 20, 'Tsanyawa', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(410, 20, 'Tudun Wada', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(411, 20, 'Ungogo', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(412, 20, 'Warawa', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(413, 20, 'Wudil', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(414, 21, 'Bakori', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(415, 21, 'Batagarawa', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(416, 21, 'Batsari', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(417, 21, 'Baure', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(418, 21, 'Bindawa', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(419, 21, 'Charanchi', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(420, 21, 'Dandume', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(421, 21, 'Danja', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(422, 21, 'Dan Musa', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(423, 21, 'Daura', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(424, 21, 'Dutsi', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(425, 21, 'Dutsin Ma', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(426, 21, 'Faskari', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(427, 21, 'Funtua', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(428, 21, 'Ingawa', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(429, 21, 'Jibia', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(430, 21, 'Kafur', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(431, 21, 'Kaita', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(432, 21, 'Kankara', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(433, 21, 'Kankia', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(434, 21, 'Katsina', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(435, 21, 'Kurfi', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(436, 21, 'Kusada', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(437, 21, 'Mai\'Adua', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(438, 21, 'Malumfashi', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(439, 21, 'Mani', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(440, 21, 'Mashi', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(441, 21, 'Matazu', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(442, 21, 'Musawa', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(443, 21, 'Rimi', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(444, 21, 'Sabuwa', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(445, 21, 'Safana', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(446, 21, 'Sandamu', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(447, 21, 'Zango', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(448, 22, 'Aleiro', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(449, 22, 'Arewa Dandi', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(450, 22, 'Argungu', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(451, 22, 'Augie', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(452, 22, 'Bagudo', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(453, 22, 'Birnin Kebbi', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(454, 22, 'Bunza', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(455, 22, 'Dandi', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(456, 22, 'Fakai', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(457, 22, 'Gwandu', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(458, 22, 'Jega', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(459, 22, 'Kalgo', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(460, 22, 'Koko/Besse', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(461, 22, 'Maiyama', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(462, 22, 'Ngaski', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(463, 22, 'Sakaba', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(464, 22, 'Shanga', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(465, 22, 'Suru', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(466, 22, 'Wasagu/Danko', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(467, 22, 'Yauri', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(468, 22, 'Zuru', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(469, 23, 'Adavi', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(470, 23, 'Ajaokuta', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(471, 23, 'Ankpa', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(472, 23, 'Bassa', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(473, 23, 'Dekina', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(474, 23, 'Ibaji', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(475, 23, 'Idah', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(476, 23, 'Igalamela Odolu', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(477, 23, 'Ijumu', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(478, 23, 'Kabba/Bunu', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(479, 23, 'Kogi', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(480, 23, 'Lokoja', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(481, 23, 'Mopa Muro', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(482, 23, 'Ofu', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(483, 23, 'Ogori/Magongo', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(484, 23, 'Okehi', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(485, 23, 'Okene', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(486, 23, 'Olamaboro', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(487, 23, 'Omala', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(488, 23, 'Yagba East', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(489, 23, 'Yagba West', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(490, 24, 'Asa', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(491, 24, 'Baruten', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(492, 24, 'Edu', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(493, 24, 'Ekiti, Kwara State', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(494, 24, 'Ifelodun', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(495, 24, 'Ilorin East', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(496, 24, 'Ilorin South', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(497, 24, 'Ilorin West', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(498, 24, 'Irepodun', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(499, 24, 'Isin', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(500, 24, 'Kaiama', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(501, 24, 'Moro', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(502, 24, 'Offa', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(503, 24, 'Oke Ero', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(504, 24, 'Oyun', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(505, 24, 'Pategi', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(506, 25, 'Agege', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(507, 25, 'Ajeromi-Ifelodun', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(508, 25, 'Alimosho', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(509, 25, 'Amuwo-Odofin', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(510, 25, 'Apapa', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(511, 25, 'Badagry', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(512, 25, 'Epe', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(513, 25, 'Eti Osa', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(514, 25, 'Ibeju-Lekki', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(515, 25, 'Ifako-Ijaiye', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(516, 25, 'Ikeja', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(517, 25, 'Ikorodu', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(518, 25, 'Kosofe', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(519, 25, 'Lagos Island', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(520, 25, 'Lagos Mainland', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(521, 25, 'Mushin', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(522, 25, 'Ojo', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(523, 25, 'Oshodi-Isolo', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(524, 25, 'Shomolu', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(525, 25, 'Surulere, Lagos State', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(526, 26, 'Akwanga', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(527, 26, 'Awe', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(528, 26, 'Doma', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(529, 26, 'Karu', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(530, 26, 'Keana', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(531, 26, 'Keffi', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(532, 26, 'Kokona', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(533, 26, 'Lafia', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(534, 26, 'Nasarawa', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(535, 26, 'Nasarawa Egon', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(536, 26, 'Obi', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(537, 26, 'Toto', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(538, 26, 'Wamba', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(539, 27, 'Agaie', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(540, 27, 'Agwara', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(541, 27, 'Bida', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(542, 27, 'Borgu', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(543, 27, 'Bosso', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(544, 27, 'Chanchaga', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(545, 27, 'Edati', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(546, 27, 'Gbako', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(547, 27, 'Gurara', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(548, 27, 'Katcha', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(549, 27, 'Kontagora', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(550, 27, 'Lapai', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(551, 27, 'Lavun', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(552, 27, 'Magama', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(553, 27, 'Mariga', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(554, 27, 'Mashegu', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(555, 27, 'Mokwa', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(556, 27, 'Moya', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(557, 27, 'Paikoro', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(558, 27, 'Rafi', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(559, 27, 'Rijau', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(560, 27, 'Shiroro', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(561, 27, 'Suleja', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(562, 27, 'Tafa', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(563, 27, 'Wushishi', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(564, 28, 'Abeokuta North', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(565, 28, 'Abeokuta South', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(566, 28, 'Ado-Odo/Ota', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(567, 28, 'Egbado North', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(568, 28, 'Egbado South', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(569, 28, 'Ewekoro', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(570, 28, 'Ifo', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(571, 28, 'Ijebu East', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(572, 28, 'Ijebu North', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(573, 28, 'Ijebu North East', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(574, 28, 'Ijebu Ode', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(575, 28, 'Ikenne', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(576, 28, 'Imeko Afon', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(577, 28, 'Ipokia', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(578, 28, 'Obafemi Owode', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(579, 28, 'Odeda', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(580, 28, 'Odogbolu', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(581, 28, 'Ogun Waterside', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(582, 28, 'Remo North', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(583, 28, 'Shagamu', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(584, 29, 'Akoko North-East', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(585, 29, 'Akoko North-West', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(586, 29, 'Akoko South-West', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(587, 29, 'Akoko South-East', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(588, 29, 'Akure North', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(589, 29, 'Akure South', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(590, 29, 'Ese Odo', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(591, 29, 'Idanre', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(592, 29, 'Ifedore', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(593, 29, 'Ilaje', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(594, 29, 'Ile Oluji/Okeigbo', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(595, 29, 'Irele', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(596, 29, 'Odigbo', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(597, 29, 'Okitipupa', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(598, 29, 'Ondo East', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(599, 29, 'Ondo West', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(600, 29, 'Ose', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(601, 29, 'Owo', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(602, 30, 'Atakunmosa East', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(603, 30, 'Atakunmosa West', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(604, 30, 'Aiyedaade', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(605, 30, 'Aiyedire', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(606, 30, 'Boluwaduro', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(607, 30, 'Boripe', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(608, 30, 'Ede North', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(609, 30, 'Ede South', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(610, 30, 'Ife Central', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(611, 30, 'Ife East', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(612, 30, 'Ife North', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(613, 30, 'Ife South', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(614, 30, 'Egbedore', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(615, 30, 'Ejigbo', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(616, 30, 'Ifedayo', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(617, 30, 'Ifelodun', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(618, 30, 'Ila', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(619, 30, 'Ilesa East', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(620, 30, 'Ilesa West', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(621, 30, 'Irepodun', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(622, 30, 'Irewole', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(623, 30, 'Isokan', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(624, 30, 'Iwo', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(625, 30, 'Obokun', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(626, 30, 'Odo Otin', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(627, 30, 'Ola Oluwa', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(628, 30, 'Olorunda', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(629, 30, 'Oriade', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(630, 30, 'Orolu', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(631, 30, 'Osogbo', '2025-10-28 15:30:14', '2025-10-28 15:30:14'),
(632, 31, 'Afijio', '2025-10-28 15:30:15', '2025-10-28 15:30:15'),
(633, 31, 'Akinyele', '2025-10-28 15:30:15', '2025-10-28 15:30:15'),
(634, 31, 'Atiba', '2025-10-28 15:30:15', '2025-10-28 15:30:15'),
(635, 31, 'Atisbo', '2025-10-28 15:30:15', '2025-10-28 15:30:15'),
(636, 31, 'Egbeda', '2025-10-28 15:30:15', '2025-10-28 15:30:15'),
(637, 31, 'Ibadan North', '2025-10-28 15:30:15', '2025-10-28 15:30:15'),
(638, 31, 'Ibadan North-East', '2025-10-28 15:30:15', '2025-10-28 15:30:15'),
(639, 31, 'Ibadan North-West', '2025-10-28 15:30:15', '2025-10-28 15:30:15'),
(640, 31, 'Ibadan South-East', '2025-10-28 15:30:15', '2025-10-28 15:30:15'),
(641, 31, 'Ibadan South-West', '2025-10-28 15:30:15', '2025-10-28 15:30:15'),
(642, 31, 'Ibarapa Central', '2025-10-28 15:30:15', '2025-10-28 15:30:15'),
(643, 31, 'Ibarapa East', '2025-10-28 15:30:15', '2025-10-28 15:30:15'),
(644, 31, 'Ibarapa North', '2025-10-28 15:30:15', '2025-10-28 15:30:15'),
(645, 31, 'Ido', '2025-10-28 15:30:15', '2025-10-28 15:30:15'),
(646, 31, 'Irepo', '2025-10-28 15:30:15', '2025-10-28 15:30:15'),
(647, 31, 'Iseyin', '2025-10-28 15:30:15', '2025-10-28 15:30:15'),
(648, 31, 'Itesiwaju', '2025-10-28 15:30:15', '2025-10-28 15:30:15'),
(649, 31, 'Iwajowa', '2025-10-28 15:30:15', '2025-10-28 15:30:15'),
(650, 31, 'Kajola', '2025-10-28 15:30:15', '2025-10-28 15:30:15'),
(651, 31, 'Lagelu', '2025-10-28 15:30:15', '2025-10-28 15:30:15'),
(652, 31, 'Ogbomosho North', '2025-10-28 15:30:15', '2025-10-28 15:30:15'),
(653, 31, 'Ogbomosho South', '2025-10-28 15:30:15', '2025-10-28 15:30:15'),
(654, 31, 'Ogo Oluwa', '2025-10-28 15:30:15', '2025-10-28 15:30:15'),
(655, 31, 'Olorunsogo', '2025-10-28 15:30:15', '2025-10-28 15:30:15'),
(656, 31, 'Oluyole', '2025-10-28 15:30:15', '2025-10-28 15:30:15'),
(657, 31, 'Ona Ara', '2025-10-28 15:30:15', '2025-10-28 15:30:15'),
(658, 31, 'Orelope', '2025-10-28 15:30:15', '2025-10-28 15:30:15'),
(659, 31, 'Ori Ire', '2025-10-28 15:30:15', '2025-10-28 15:30:15'),
(660, 31, 'Oyo', '2025-10-28 15:30:15', '2025-10-28 15:30:15'),
(661, 31, 'Oyo East', '2025-10-28 15:30:15', '2025-10-28 15:30:15'),
(662, 31, 'Saki East', '2025-10-28 15:30:15', '2025-10-28 15:30:15'),
(663, 31, 'Saki West', '2025-10-28 15:30:15', '2025-10-28 15:30:15'),
(664, 31, 'Surulere, Oyo State', '2025-10-28 15:30:15', '2025-10-28 15:30:15'),
(665, 32, 'Bokkos', '2025-10-28 15:30:15', '2025-10-28 15:30:15'),
(666, 32, 'Barkin Ladi', '2025-10-28 15:30:15', '2025-10-28 15:30:15'),
(667, 32, 'Bassa', '2025-10-28 15:30:15', '2025-10-28 15:30:15'),
(668, 32, 'Jos East', '2025-10-28 15:30:15', '2025-10-28 15:30:15'),
(669, 32, 'Jos North', '2025-10-28 15:30:15', '2025-10-28 15:30:15'),
(670, 32, 'Jos South', '2025-10-28 15:30:15', '2025-10-28 15:30:15'),
(671, 32, 'Kanam', '2025-10-28 15:30:15', '2025-10-28 15:30:15'),
(672, 32, 'Kanke', '2025-10-28 15:30:15', '2025-10-28 15:30:15'),
(673, 32, 'Langtang South', '2025-10-28 15:30:15', '2025-10-28 15:30:15'),
(674, 32, 'Langtang North', '2025-10-28 15:30:15', '2025-10-28 15:30:15'),
(675, 32, 'Mangu', '2025-10-28 15:30:15', '2025-10-28 15:30:15'),
(676, 32, 'Mikang', '2025-10-28 15:30:15', '2025-10-28 15:30:15'),
(677, 32, 'Pankshin', '2025-10-28 15:30:15', '2025-10-28 15:30:15'),
(678, 32, 'Qua\'an Pan', '2025-10-28 15:30:15', '2025-10-28 15:30:15'),
(679, 32, 'Riyom', '2025-10-28 15:30:15', '2025-10-28 15:30:15'),
(680, 32, 'Shendam', '2025-10-28 15:30:15', '2025-10-28 15:30:15'),
(681, 32, 'Wase', '2025-10-28 15:30:15', '2025-10-28 15:30:15'),
(682, 33, 'Abua/Odual', '2025-10-28 15:30:15', '2025-10-28 15:30:15'),
(683, 33, 'Ahoada East', '2025-10-28 15:30:15', '2025-10-28 15:30:15'),
(684, 33, 'Ahoada West', '2025-10-28 15:30:15', '2025-10-28 15:30:15'),
(685, 33, 'Akuku-Toru', '2025-10-28 15:30:15', '2025-10-28 15:30:15'),
(686, 33, 'Andoni', '2025-10-28 15:30:15', '2025-10-28 15:30:15'),
(687, 33, 'Asari-Toru', '2025-10-28 15:30:15', '2025-10-28 15:30:15'),
(688, 33, 'Bonny', '2025-10-28 15:30:15', '2025-10-28 15:30:15'),
(689, 33, 'Degema', '2025-10-28 15:30:15', '2025-10-28 15:30:15'),
(690, 33, 'Eleme', '2025-10-28 15:30:15', '2025-10-28 15:30:15'),
(691, 33, 'Emuoha', '2025-10-28 15:30:15', '2025-10-28 15:30:15'),
(692, 33, 'Etche', '2025-10-28 15:30:15', '2025-10-28 15:30:15'),
(693, 33, 'Gokana', '2025-10-28 15:30:15', '2025-10-28 15:30:15'),
(694, 33, 'Ikwerre', '2025-10-28 15:30:15', '2025-10-28 15:30:15'),
(695, 33, 'Khana', '2025-10-28 15:30:15', '2025-10-28 15:30:15'),
(696, 33, 'Obio/Akpor', '2025-10-28 15:30:15', '2025-10-28 15:30:15'),
(697, 33, 'Ogba/Egbema/Ndoni', '2025-10-28 15:30:15', '2025-10-28 15:30:15'),
(698, 33, 'Ogu/Bolo', '2025-10-28 15:30:15', '2025-10-28 15:30:15'),
(699, 33, 'Okrika', '2025-10-28 15:30:15', '2025-10-28 15:30:15'),
(700, 33, 'Omuma', '2025-10-28 15:30:15', '2025-10-28 15:30:15'),
(701, 33, 'Opobo/Nkoro', '2025-10-28 15:30:15', '2025-10-28 15:30:15'),
(702, 33, 'Oyigbo', '2025-10-28 15:30:15', '2025-10-28 15:30:15'),
(703, 33, 'Port Harcourt', '2025-10-28 15:30:15', '2025-10-28 15:30:15'),
(704, 33, 'Tai', '2025-10-28 15:30:15', '2025-10-28 15:30:15'),
(705, 34, 'Binji', '2025-10-28 15:30:15', '2025-10-28 15:30:15'),
(706, 34, 'Bodinga', '2025-10-28 15:30:15', '2025-10-28 15:30:15'),
(707, 34, 'Dange Shuni', '2025-10-28 15:30:15', '2025-10-28 15:30:15'),
(708, 34, 'Gada', '2025-10-28 15:30:15', '2025-10-28 15:30:15'),
(709, 34, 'Goronyo', '2025-10-28 15:30:15', '2025-10-28 15:30:15'),
(710, 34, 'Gudu', '2025-10-28 15:30:15', '2025-10-28 15:30:15'),
(711, 34, 'Gwadabawa', '2025-10-28 15:30:15', '2025-10-28 15:30:15'),
(712, 34, 'Illela', '2025-10-28 15:30:15', '2025-10-28 15:30:15'),
(713, 34, 'Isa', '2025-10-28 15:30:15', '2025-10-28 15:30:15'),
(714, 34, 'Kebbe', '2025-10-28 15:30:15', '2025-10-28 15:30:15'),
(715, 34, 'Kware', '2025-10-28 15:30:15', '2025-10-28 15:30:15'),
(716, 34, 'Rabah', '2025-10-28 15:30:15', '2025-10-28 15:30:15'),
(717, 34, 'Sabon Birni', '2025-10-28 15:30:15', '2025-10-28 15:30:15'),
(718, 34, 'Shagari', '2025-10-28 15:30:15', '2025-10-28 15:30:15'),
(719, 34, 'Silame', '2025-10-28 15:30:15', '2025-10-28 15:30:15'),
(720, 34, 'Sokoto North', '2025-10-28 15:30:15', '2025-10-28 15:30:15'),
(721, 34, 'Sokoto South', '2025-10-28 15:30:15', '2025-10-28 15:30:15'),
(722, 34, 'Tambuwal', '2025-10-28 15:30:15', '2025-10-28 15:30:15'),
(723, 34, 'Tangaza', '2025-10-28 15:30:15', '2025-10-28 15:30:15'),
(724, 34, 'Tureta', '2025-10-28 15:30:15', '2025-10-28 15:30:15'),
(725, 34, 'Wamako', '2025-10-28 15:30:15', '2025-10-28 15:30:15'),
(726, 34, 'Wurno', '2025-10-28 15:30:15', '2025-10-28 15:30:15'),
(727, 34, 'Yabo', '2025-10-28 15:30:15', '2025-10-28 15:30:15'),
(728, 35, 'Ardo Kola', '2025-10-28 15:30:15', '2025-10-28 15:30:15'),
(729, 35, 'Bali', '2025-10-28 15:30:15', '2025-10-28 15:30:15'),
(730, 35, 'Donga', '2025-10-28 15:30:15', '2025-10-28 15:30:15'),
(731, 35, 'Gashaka', '2025-10-28 15:30:15', '2025-10-28 15:30:15'),
(732, 35, 'Gassol', '2025-10-28 15:30:15', '2025-10-28 15:30:15'),
(733, 35, 'Ibi', '2025-10-28 15:30:15', '2025-10-28 15:30:15'),
(734, 35, 'Jalingo', '2025-10-28 15:30:15', '2025-10-28 15:30:15'),
(735, 35, 'Karim Lamido', '2025-10-28 15:30:15', '2025-10-28 15:30:15'),
(736, 35, 'Kumi', '2025-10-28 15:30:15', '2025-10-28 15:30:15'),
(737, 35, 'Lau', '2025-10-28 15:30:15', '2025-10-28 15:30:15'),
(738, 35, 'Sardauna', '2025-10-28 15:30:15', '2025-10-28 15:30:15'),
(739, 35, 'Takum', '2025-10-28 15:30:15', '2025-10-28 15:30:15'),
(740, 35, 'Ussa', '2025-10-28 15:30:15', '2025-10-28 15:30:15'),
(741, 35, 'Wukari', '2025-10-28 15:30:15', '2025-10-28 15:30:15'),
(742, 35, 'Yorro', '2025-10-28 15:30:15', '2025-10-28 15:30:15'),
(743, 35, 'Zing', '2025-10-28 15:30:15', '2025-10-28 15:30:15'),
(744, 36, 'Bade', '2025-10-28 15:30:15', '2025-10-28 15:30:15'),
(745, 36, 'Bursari', '2025-10-28 15:30:15', '2025-10-28 15:30:15'),
(746, 36, 'Damaturu', '2025-10-28 15:30:15', '2025-10-28 15:30:15'),
(747, 36, 'Fika', '2025-10-28 15:30:15', '2025-10-28 15:30:15'),
(748, 36, 'Fune', '2025-10-28 15:30:15', '2025-10-28 15:30:15'),
(749, 36, 'Geidam', '2025-10-28 15:30:15', '2025-10-28 15:30:15'),
(750, 36, 'Gujba', '2025-10-28 15:30:15', '2025-10-28 15:30:15'),
(751, 36, 'Gulani', '2025-10-28 15:30:15', '2025-10-28 15:30:15'),
(752, 36, 'Jakusko', '2025-10-28 15:30:15', '2025-10-28 15:30:15'),
(753, 36, 'Karasuwa', '2025-10-28 15:30:15', '2025-10-28 15:30:15');
INSERT INTO `lgas` (`id`, `state_id`, `name`, `created_at`, `updated_at`) VALUES
(754, 36, 'Machina', '2025-10-28 15:30:15', '2025-10-28 15:30:15'),
(755, 36, 'Nangere', '2025-10-28 15:30:15', '2025-10-28 15:30:15'),
(756, 36, 'Nguru', '2025-10-28 15:30:15', '2025-10-28 15:30:15'),
(757, 36, 'Potiskum', '2025-10-28 15:30:15', '2025-10-28 15:30:15'),
(758, 36, 'Tarmuwa', '2025-10-28 15:30:15', '2025-10-28 15:30:15'),
(759, 36, 'Yunusari', '2025-10-28 15:30:15', '2025-10-28 15:30:15'),
(760, 36, 'Yusufari', '2025-10-28 15:30:15', '2025-10-28 15:30:15'),
(761, 37, 'Anka', '2025-10-28 15:30:15', '2025-10-28 15:30:15'),
(762, 37, 'Bakura', '2025-10-28 15:30:15', '2025-10-28 15:30:15'),
(763, 37, 'Birnin Magaji/Kiyaw', '2025-10-28 15:30:15', '2025-10-28 15:30:15'),
(764, 37, 'Bukkuyum', '2025-10-28 15:30:15', '2025-10-28 15:30:15'),
(765, 37, 'Bungudu', '2025-10-28 15:30:15', '2025-10-28 15:30:15'),
(766, 37, 'Gummi', '2025-10-28 15:30:15', '2025-10-28 15:30:15'),
(767, 37, 'Gusau', '2025-10-28 15:30:15', '2025-10-28 15:30:15'),
(768, 37, 'Kaura Namoda', '2025-10-28 15:30:15', '2025-10-28 15:30:15'),
(769, 37, 'Maradun', '2025-10-28 15:30:15', '2025-10-28 15:30:15'),
(770, 37, 'Maru', '2025-10-28 15:30:15', '2025-10-28 15:30:15'),
(771, 37, 'Shinkafi', '2025-10-28 15:30:15', '2025-10-28 15:30:15'),
(772, 37, 'Talata Mafara', '2025-10-28 15:30:15', '2025-10-28 15:30:15'),
(773, 37, 'Chafe', '2025-10-28 15:30:15', '2025-10-28 15:30:15'),
(774, 37, 'Zurmi', '2025-10-28 15:30:15', '2025-10-28 15:30:15');

-- --------------------------------------------------------

--
-- Table structure for table `marks`
--

CREATE TABLE `marks` (
  `id` int UNSIGNED NOT NULL,
  `student_id` int UNSIGNED NOT NULL,
  `subject_id` int UNSIGNED NOT NULL,
  `my_class_id` int UNSIGNED NOT NULL,
  `section_id` int UNSIGNED NOT NULL,
  `exam_id` int UNSIGNED NOT NULL,
  `t1` int DEFAULT NULL,
  `t2` int DEFAULT NULL,
  `t3` int DEFAULT NULL,
  `t4` int DEFAULT NULL,
  `tca` int DEFAULT NULL,
  `exm` int DEFAULT NULL,
  `tex1` int DEFAULT NULL,
  `tex2` int DEFAULT NULL,
  `tex3` int DEFAULT NULL,
  `sub_pos` tinyint DEFAULT NULL,
  `cum` int DEFAULT NULL,
  `cum_ave` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `grade_id` int UNSIGNED DEFAULT NULL,
  `year` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` bigint UNSIGNED NOT NULL,
  `sender_id` int UNSIGNED NOT NULL,
  `receiver_id` int UNSIGNED NOT NULL,
  `subject` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT '0',
  `read_at` timestamp NULL DEFAULT NULL,
  `file_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `priority` enum('low','normal','high') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'normal',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `message_recipients`
--

CREATE TABLE `message_recipients` (
  `id` bigint UNSIGNED NOT NULL,
  `message_id` bigint UNSIGNED NOT NULL,
  `recipient_id` int UNSIGNED NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2013_09_20_121733_create_blood_groups_table', 1),
(2, '2013_09_22_124750_create_states_table', 1),
(3, '2013_09_22_124806_create_lgas_table', 1),
(4, '2013_09_26_121148_create_nationalities_table', 1),
(5, '2014_10_12_000000_create_users_table', 1),
(6, '2014_10_12_100000_create_password_resets_table', 1),
(7, '2018_09_19_000000_create_students_table', 1),
(8, '2018_09_20_100249_create_user_types_table', 1),
(9, '2018_09_20_150906_create_class_types_table', 1),
(10, '2018_09_22_073005_create_my_classes_table', 1),
(11, '2018_09_22_073526_create_sections_table', 1),
(12, '2018_09_22_080555_create_settings_table', 1),
(13, '2018_09_22_081302_create_subjects_table', 1),
(14, '2018_09_22_151514_create_student_records_table', 1),
(15, '2018_09_26_124241_create_dorms_table', 1),
(16, '2018_10_04_224910_create_exams_table', 1),
(17, '2018_10_06_224846_create_marks_table', 1),
(18, '2018_10_06_224944_create_grades_table', 1),
(19, '2018_10_06_225007_create_pins_table', 1),
(20, '2018_10_18_205550_create_skills_table', 1),
(21, '2018_10_18_205842_create_exam_records_table', 1),
(22, '2018_10_31_191358_create_books_table', 1),
(23, '2018_10_31_192540_create_book_requests_table', 1),
(24, '2018_11_01_132115_create_staff_records_table', 1),
(25, '2018_11_03_210758_create_payments_table', 1),
(26, '2018_11_03_210817_create_payment_records_table', 1),
(27, '2018_11_06_083707_create_receipts_table', 1),
(28, '2018_11_27_180401_create_time_tables_table', 1),
(29, '2019_09_22_142514_create_fks', 1),
(30, '2019_09_26_132227_create_promotions_table', 1),
(31, '2019_10_01_000000_add_foreign_keys_to_students_table', 1),
(32, '2024_01_01_000002_create_assignments_table', 1),
(33, '2024_01_01_000003_create_assignment_submissions_table', 1),
(34, '2024_01_01_000004_create_messages_table', 1),
(35, '2024_01_01_000005_update_book_requests_table', 1),
(36, '2024_10_24_000001_create_notices_table', 1),
(37, '2024_10_24_000002_create_school_events_table', 1),
(38, '2024_10_24_000003_create_study_materials_table', 1),
(39, '2025_10_25_030727_update_attendances_table', 1),
(40, '2025_10_25_032056_add_available_to_books_table', 1),
(41, '2025_10_25_102255_check_missing_columns', 1),
(42, '2025_10_25_103824_create_student_attendances_table', 1),
(43, '2025_10_25_120100_create_message_recipients_table', 1),
(44, '2025_10_28_000001_create_learning_materials_table', 1),
(45, '2025_10_28_000002_create_attendances_table', 1),
(46, '2025_10_28_000003_update_assignments_table', 1),
(47, '2025_10_28_153200_fresh_start', 1),
(48, '2025_10_28_163000_fix_all_issues', 1),
(49, '2025_10_29_124011_create_notifications_table', 2);

-- --------------------------------------------------------

--
-- Table structure for table `my_classes`
--

CREATE TABLE `my_classes` (
  `id` int UNSIGNED NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `class_type_id` int UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `my_classes`
--

INSERT INTO `my_classes` (`id`, `name`, `class_type_id`, `created_at`, `updated_at`) VALUES
(1, 'Nursery 1', 3, NULL, NULL),
(2, 'Nursery 2', 3, NULL, NULL),
(3, 'Nursery 3', 3, NULL, NULL),
(4, 'Primary 1', 4, NULL, NULL),
(5, 'Primary 2', 4, NULL, NULL),
(6, 'JSS 2', 5, NULL, NULL),
(7, 'JSS 3', 5, NULL, NULL),
(8, 'SSS 1', 6, NULL, NULL),
(9, 'SSS 2', 6, NULL, NULL),
(10, 'SSS 3', 6, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `nationalities`
--

CREATE TABLE `nationalities` (
  `id` int UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `nationalities`
--

INSERT INTO `nationalities` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Afghan', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(2, 'Albanian', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(3, 'Algerian', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(4, 'American', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(5, 'Andorran', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(6, 'Angolan', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(7, 'Antiguans', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(8, 'Argentinean', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(9, 'Armenian', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(10, 'Australian', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(11, 'Austrian', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(12, 'Azerbaijani', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(13, 'Bahamian', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(14, 'Bahraini', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(15, 'Bangladeshi', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(16, 'Barbadian', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(17, 'Barbudans', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(18, 'Batswana', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(19, 'Belarusian', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(20, 'Belgian', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(21, 'Belizean', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(22, 'Beninese', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(23, 'Bhutanese', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(24, 'Bolivian', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(25, 'Bosnian', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(26, 'Brazilian', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(27, 'British', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(28, 'Bruneian', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(29, 'Bulgarian', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(30, 'Burkinabe', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(31, 'Burmese', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(32, 'Burundian', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(33, 'Cambodian', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(34, 'Cameroonian', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(35, 'Canadian', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(36, 'Cape Verdean', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(37, 'Central African', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(38, 'Chadian', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(39, 'Chilean', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(40, 'Chinese', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(41, 'Colombian', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(42, 'Comoran', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(43, 'Congolese', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(44, 'Costa Rican', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(45, 'Croatian', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(46, 'Cuban', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(47, 'Cypriot', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(48, 'Czech', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(49, 'Danish', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(50, 'Djibouti', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(51, 'Dominican', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(52, 'Dutch', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(53, 'East Timorese', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(54, 'Ecuadorean', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(55, 'Egyptian', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(56, 'Emirian', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(57, 'Equatorial Guinean', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(58, 'Eritrean', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(59, 'Estonian', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(60, 'Ethiopian', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(61, 'Fijian', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(62, 'Filipino', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(63, 'Finnish', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(64, 'French', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(65, 'Gabonese', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(66, 'Gambian', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(67, 'Georgian', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(68, 'German', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(69, 'Ghanaian', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(70, 'Greek', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(71, 'Grenadian', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(72, 'Guatemalan', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(73, 'Guinea-Bissauan', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(74, 'Guinean', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(75, 'Guyanese', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(76, 'Haitian', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(77, 'Herzegovinian', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(78, 'Honduran', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(79, 'Hungarian', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(80, 'I-Kiribati', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(81, 'Icelander', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(82, 'Indian', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(83, 'Indonesian', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(84, 'Iranian', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(85, 'Iraqi', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(86, 'Irish', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(87, 'Israeli', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(88, 'Italian', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(89, 'Ivorian', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(90, 'Jamaican', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(91, 'Japanese', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(92, 'Jordanian', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(93, 'Kazakhstani', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(94, 'Kenyan', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(95, 'Kittian and Nevisian', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(96, 'Kuwaiti', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(97, 'Kyrgyz', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(98, 'Laotian', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(99, 'Latvian', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(100, 'Lebanese', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(101, 'Liberian', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(102, 'Libyan', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(103, 'Liechtensteiner', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(104, 'Lithuanian', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(105, 'Luxembourger', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(106, 'Macedonian', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(107, 'Malagasy', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(108, 'Malawian', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(109, 'Malaysian', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(110, 'Maldivan', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(111, 'Malian', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(112, 'Maltese', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(113, 'Marshallese', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(114, 'Mauritanian', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(115, 'Mauritian', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(116, 'Mexican', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(117, 'Micronesian', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(118, 'Moldovan', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(119, 'Monacan', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(120, 'Mongolian', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(121, 'Moroccan', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(122, 'Mosotho', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(123, 'Motswana', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(124, 'Mozambican', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(125, 'Namibian', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(126, 'Nauruan', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(127, 'Nepalese', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(128, 'New Zealander', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(129, 'Nicaraguan', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(130, 'Nigerian', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(131, 'Nigerien', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(132, 'North Korean', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(133, 'Northern Irish', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(134, 'Norwegian', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(135, 'Omani', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(136, 'Pakistani', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(137, 'Palauan', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(138, 'Panamanian', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(139, 'Papua New Guinean', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(140, 'Paraguayan', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(141, 'Peruvian', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(142, 'Polish', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(143, 'Portuguese', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(144, 'Qatari', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(145, 'Romanian', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(146, 'Russian', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(147, 'Rwandan', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(148, 'Saint Lucian', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(149, 'Salvadoran', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(150, 'Samoan', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(151, 'San Marinese', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(152, 'Sao Tomean', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(153, 'Saudi', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(154, 'Scottish', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(155, 'Senegalese', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(156, 'Serbian', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(157, 'Seychellois', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(158, 'Sierra Leonean', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(159, 'Singaporean', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(160, 'Slovakian', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(161, 'Slovenian', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(162, 'Solomon Islander', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(163, 'Somali', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(164, 'South African', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(165, 'South Korean', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(166, 'Spanish', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(167, 'Sri Lankan', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(168, 'Sudanese', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(169, 'Surinamer', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(170, 'Swazi', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(171, 'Swedish', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(172, 'Swiss', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(173, 'Syrian', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(174, 'Taiwanese', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(175, 'Tajik', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(176, 'Tanzanian', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(177, 'Thai', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(178, 'Togolese', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(179, 'Tongan', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(180, 'Trinidadian/Tobagonian', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(181, 'Tunisian', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(182, 'Turkish', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(183, 'Tuvaluan', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(184, 'Ugandan', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(185, 'Ukrainian', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(186, 'Uruguayan', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(187, 'Uzbekistani', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(188, 'Venezuelan', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(189, 'Vietnamese', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(190, 'Welsh', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(191, 'Yemenite', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(192, 'Zambian', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(193, 'Zimbabwean', '2025-10-28 15:30:12', '2025-10-28 15:30:12');

-- --------------------------------------------------------

--
-- Table structure for table `notices`
--

CREATE TABLE `notices` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('announcement','event','urgent','general') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'general',
  `start_date` datetime NOT NULL,
  `end_date` datetime DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `target_audience` enum('all','students','teachers','parents','staff') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'all',
  `created_by` int UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notices`
--

INSERT INTO `notices` (`id`, `title`, `content`, `type`, `start_date`, `end_date`, `is_active`, `target_audience`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'BONGO', 'fgg', 'event', '2025-10-28 19:00:00', NULL, 1, 'all', 1, '2025-10-28 15:55:51', '2025-10-28 15:55:51');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_id` bigint UNSIGNED NOT NULL,
  `data` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int UNSIGNED NOT NULL,
  `title` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` int NOT NULL,
  `ref_no` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `method` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'cash',
  `my_class_id` int UNSIGNED DEFAULT NULL,
  `class_id` int UNSIGNED DEFAULT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `year` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `title`, `amount`, `ref_no`, `method`, `my_class_id`, `class_id`, `description`, `year`, `created_at`, `updated_at`) VALUES
(1, 'Frais de scolarité', 150000, 'PAY202510286900EFA001115', 'cash', NULL, NULL, 'Frais de scolarité annuels', '2025', '2025-10-28 15:30:24', '2025-10-28 15:30:24'),
(2, 'Frais d\'inscription', 50000, 'PAY202510286900EFA0024C0', 'cash', NULL, NULL, 'Frais d\'inscription annuels', '2025', '2025-10-28 15:30:24', '2025-10-28 15:30:24'),
(3, 'Frais de bibliothèque', 25000, 'PAY202510286900EFA003AF0', 'cash', NULL, NULL, 'Accès à la bibliothèque', '2025', '2025-10-28 15:30:24', '2025-10-28 15:30:24'),
(4, 'Frais de laboratoire', 35000, 'PAY202510286900EFA004B1D', 'cash', NULL, NULL, 'Accès aux laboratoires', '2025', '2025-10-28 15:30:24', '2025-10-28 15:30:24'),
(5, 'Frais de sport', 15000, 'PAY202510286900EFA005CB5', 'cash', NULL, NULL, 'Activités sportives', '2025', '2025-10-28 15:30:24', '2025-10-28 15:30:24'),
(6, 'Frais de cantine', 100000, 'PAY202510286900EFA006C55', 'cash', NULL, NULL, 'Repas de midi pour l\'année', '2025', '2025-10-28 15:30:24', '2025-10-28 15:30:24'),
(7, 'Frais d\'uniforme', 45000, 'PAY202510286900EFA007BC1', 'cash', NULL, NULL, 'Uniforme scolaire', '2025', '2025-10-28 15:30:24', '2025-10-28 15:30:24'),
(8, 'Frais d\'examen', 20000, 'PAY202510286900EFA008CA8', 'cash', NULL, NULL, 'Frais d\'examen de fin d\'année', '2025', '2025-10-28 15:30:24', '2025-10-28 15:30:24');

-- --------------------------------------------------------

--
-- Table structure for table `payment_records`
--

CREATE TABLE `payment_records` (
  `id` int UNSIGNED NOT NULL,
  `payment_id` int UNSIGNED NOT NULL,
  `student_id` int UNSIGNED NOT NULL,
  `ref_no` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `amt_paid` int DEFAULT NULL,
  `balance` int DEFAULT NULL,
  `paid` tinyint NOT NULL DEFAULT '0',
  `year` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payment_records`
--

INSERT INTO `payment_records` (`id`, `payment_id`, `student_id`, `ref_no`, `amt_paid`, `balance`, `paid`, `year`, `created_at`, `updated_at`) VALUES
(1, 2, 1, 'PAY176166902810', 49216, 784, 0, '2024', '2025-07-22 20:38:17', '2025-10-28 15:30:28'),
(2, 8, 1, 'PAY176166902811', 298, 19702, 0, '2025', '2025-09-17 17:30:55', '2025-10-28 15:30:28'),
(3, 5, 1, 'PAY176166902812', 9157, 5843, 0, '2022', '2024-12-25 08:34:22', '2025-10-28 15:30:28'),
(4, 5, 1, 'PAY176166902813', 14945, 55, 0, '2024', '2025-07-14 22:23:44', '2025-10-28 15:30:28'),
(5, 5, 1, 'PAY176166902814', 355, 14645, 0, '2023', '2025-06-13 22:06:39', '2025-10-28 15:30:28'),
(6, 1, 2, 'PAY176166902820', 96188, 53812, 0, '2025', '2025-04-10 00:30:41', '2025-10-28 15:30:28'),
(7, 8, 3, 'PAY176166902830', 17794, 2206, 0, '2024', '2024-12-12 23:44:09', '2025-10-28 15:30:28'),
(8, 5, 3, 'PAY176166902831', 5970, 9030, 0, '2023', '2025-02-05 20:25:52', '2025-10-28 15:30:28'),
(9, 1, 3, 'PAY176166902832', 33445, 116555, 0, '2022', '2025-06-20 20:12:52', '2025-10-28 15:30:28'),
(10, 8, 4, 'PAY176166902840', 15137, 4863, 0, '2024', '2025-07-31 10:10:03', '2025-10-28 15:30:28'),
(11, 2, 4, 'PAY176166902841', 44348, 5652, 0, '2022', '2025-04-16 11:06:28', '2025-10-28 15:30:28'),
(12, 1, 4, 'PAY176166902842', 18368, 131632, 0, '2023', '2025-10-02 00:57:26', '2025-10-28 15:30:28'),
(13, 8, 4, 'PAY176166902843', 10840, 9160, 0, '2025', '2024-12-13 11:12:48', '2025-10-28 15:30:28'),
(14, 1, 5, 'PAY176166902850', 16378, 133622, 0, '2024', '2025-02-24 01:09:08', '2025-10-28 15:30:28'),
(15, 1, 5, 'PAY176166902851', 12580, 137420, 0, '2023', '2025-10-12 08:34:41', '2025-10-28 15:30:28'),
(16, 1, 5, 'PAY176166902852', 116319, 33681, 0, '2022', '2025-03-14 22:42:38', '2025-10-28 15:30:28'),
(17, 1, 6, 'PAY176166902860', 26343, 123657, 0, '2023', '2024-12-31 22:25:06', '2025-10-28 15:30:28'),
(18, 1, 6, 'PAY176166902861', 4010, 145990, 0, '2025', '2024-12-23 22:28:24', '2025-10-28 15:30:28'),
(19, 1, 6, 'PAY176166902862', 91589, 58411, 0, '2024', '2025-08-05 12:03:49', '2025-10-28 15:30:28'),
(20, 4, 6, 'PAY176166902863', 19527, 15473, 0, '2024', '2025-02-17 09:05:35', '2025-10-28 15:30:28'),
(21, 1, 6, 'PAY176166902864', 44816, 105184, 0, '2024', '2025-09-11 10:38:51', '2025-10-28 15:30:28'),
(22, 6, 7, 'PAY176166902870', 63344, 36656, 0, '2025', '2025-10-04 17:37:07', '2025-10-28 15:30:28'),
(23, 7, 7, 'PAY176166902871', 4976, 40024, 0, '2025', '2025-03-16 13:25:35', '2025-10-28 15:30:28'),
(24, 8, 7, 'PAY176166902872', 7863, 12137, 0, '2025', '2025-10-22 06:13:14', '2025-10-28 15:30:28'),
(25, 3, 7, 'PAY176166902873', 4321, 20679, 0, '2025', '2024-12-30 01:56:14', '2025-10-28 15:30:28'),
(26, 8, 8, 'PAY176166902880', 7996, 12004, 0, '2024', '2024-12-11 17:49:25', '2025-10-28 15:30:28'),
(27, 4, 9, 'PAY176166902890', 25541, 9459, 0, '2024', '2025-03-23 23:53:47', '2025-10-28 15:30:28'),
(28, 4, 10, 'PAY1761669028100', 33457, 1543, 0, '2023', '2024-11-05 20:55:11', '2025-10-28 15:30:28'),
(29, 8, 10, 'PAY1761669028101', 13614, 6386, 0, '2022', '2025-09-14 15:11:22', '2025-10-28 15:30:28'),
(30, 8, 10, 'PAY1761669028102', 8401, 11599, 0, '2022', '2025-04-10 09:55:09', '2025-10-28 15:30:28'),
(31, 3, 10, 'PAY1761669028103', 14558, 10442, 0, '2025', '2025-01-06 16:36:05', '2025-10-28 15:30:28'),
(32, 1, 11, 'PAY1761669028110', 105635, 44365, 0, '2022', '2025-05-09 07:05:09', '2025-10-28 15:30:28'),
(33, 1, 11, 'PAY1761669028111', 3569, 146431, 0, '2025', '2025-03-22 09:01:29', '2025-10-28 15:30:28'),
(34, 6, 11, 'PAY1761669028112', 1324, 98676, 0, '2022', '2025-02-14 09:06:45', '2025-10-28 15:30:28'),
(35, 6, 11, 'PAY1761669028113', 53734, 46266, 0, '2024', '2025-07-11 10:34:18', '2025-10-28 15:30:28'),
(36, 3, 12, 'PAY1761669028120', 21394, 3606, 0, '2022', '2025-04-24 09:18:12', '2025-10-28 15:30:28'),
(37, 1, 12, 'PAY1761669028121', 32722, 117278, 0, '2025', '2025-07-04 15:44:55', '2025-10-28 15:30:28'),
(38, 7, 12, 'PAY1761669028122', 40321, 4679, 0, '2023', '2025-01-14 01:41:18', '2025-10-28 15:30:28'),
(39, 7, 12, 'PAY1761669028123', 10277, 34723, 0, '2024', '2024-12-12 21:02:25', '2025-10-28 15:30:28'),
(40, 2, 13, 'PAY1761669028130', 9578, 40422, 0, '2025', '2024-10-31 08:00:43', '2025-10-28 15:30:28'),
(41, 4, 13, 'PAY1761669028131', 25566, 9434, 0, '2023', '2025-06-12 11:51:21', '2025-10-28 15:30:28'),
(42, 1, 13, 'PAY1761669028132', 31140, 118860, 0, '2025', '2024-12-27 00:19:44', '2025-10-28 15:30:28'),
(43, 6, 14, 'PAY1761669028140', 33398, 66602, 0, '2024', '2025-06-02 21:37:09', '2025-10-28 15:30:28'),
(44, 2, 14, 'PAY1761669028141', 40023, 9977, 0, '2025', '2025-02-20 04:49:11', '2025-10-28 15:30:28'),
(45, 2, 15, 'PAY1761669028150', 7971, 42029, 0, '2025', '2025-09-08 14:30:17', '2025-10-28 15:30:28'),
(46, 4, 15, 'PAY1761669028151', 2836, 32164, 0, '2022', '2025-05-30 07:23:35', '2025-10-28 15:30:28'),
(47, 5, 15, 'PAY1761669028152', 5170, 9830, 0, '2024', '2025-08-14 21:06:57', '2025-10-28 15:30:28'),
(48, 8, 16, 'PAY1761669028160', 12781, 7219, 0, '2025', '2025-07-26 01:23:50', '2025-10-28 15:30:28'),
(49, 6, 16, 'PAY1761669028161', 74124, 25876, 0, '2024', '2025-01-27 03:03:56', '2025-10-28 15:30:28'),
(50, 1, 17, 'PAY1761669028170', 82477, 67523, 0, '2022', '2024-12-30 16:30:20', '2025-10-28 15:30:28'),
(51, 1, 17, 'PAY1761669028171', 40985, 109015, 0, '2024', '2025-06-20 22:52:24', '2025-10-28 15:30:28'),
(52, 3, 17, 'PAY1761669028172', 17214, 7786, 0, '2024', '2025-02-07 00:48:15', '2025-10-28 15:30:28'),
(53, 2, 17, 'PAY1761669028173', 17262, 32738, 0, '2024', '2025-03-13 23:51:16', '2025-10-28 15:30:28'),
(54, 8, 18, 'PAY1761669028180', 14920, 5080, 0, '2025', '2025-06-07 06:51:59', '2025-10-28 15:30:28'),
(55, 8, 18, 'PAY1761669028181', 1104, 18896, 0, '2024', '2025-01-08 02:58:51', '2025-10-28 15:30:28'),
(56, 8, 18, 'PAY1761669028182', 18491, 1509, 0, '2025', '2025-05-18 00:58:09', '2025-10-28 15:30:28'),
(57, 2, 18, 'PAY1761669028183', 36125, 13875, 0, '2023', '2025-02-17 05:20:28', '2025-10-28 15:30:28'),
(58, 6, 19, 'PAY1761669028190', 12016, 87984, 0, '2023', '2025-05-08 00:59:49', '2025-10-28 15:30:28'),
(59, 2, 19, 'PAY1761669028191', 49132, 868, 0, '2024', '2025-07-09 05:16:46', '2025-10-28 15:30:28'),
(60, 3, 19, 'PAY1761669028192', 5881, 19119, 0, '2024', '2025-08-07 17:35:22', '2025-10-28 15:30:28'),
(61, 2, 19, 'PAY1761669028193', 22583, 27417, 0, '2023', '2025-10-12 23:17:21', '2025-10-28 15:30:28'),
(62, 8, 20, 'PAY1761669028200', 3104, 16896, 0, '2023', '2025-06-28 06:27:46', '2025-10-28 15:30:28'),
(63, 1, 20, 'PAY1761669028201', 112089, 37911, 0, '2022', '2025-01-09 05:47:56', '2025-10-28 15:30:28'),
(64, 7, 20, 'PAY1761669028202', 26951, 18049, 0, '2023', '2025-07-17 23:15:48', '2025-10-28 15:30:28'),
(65, 7, 20, 'PAY1761669028203', 41712, 3288, 0, '2024', '2025-08-30 06:41:58', '2025-10-28 15:30:28');

-- --------------------------------------------------------

--
-- Table structure for table `pins`
--

CREATE TABLE `pins` (
  `id` int UNSIGNED NOT NULL,
  `code` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  `used` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `times_used` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `user_id` int UNSIGNED DEFAULT NULL,
  `student_id` int UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `promotions`
--

CREATE TABLE `promotions` (
  `id` int UNSIGNED NOT NULL,
  `student_id` int UNSIGNED NOT NULL,
  `from_class` int UNSIGNED NOT NULL,
  `from_section` int UNSIGNED NOT NULL,
  `to_class` int UNSIGNED NOT NULL,
  `to_section` int UNSIGNED NOT NULL,
  `grad` tinyint NOT NULL,
  `from_session` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `to_session` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `receipts`
--

CREATE TABLE `receipts` (
  `id` int UNSIGNED NOT NULL,
  `pr_id` int UNSIGNED NOT NULL,
  `amt_paid` int NOT NULL,
  `balance` int NOT NULL,
  `year` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `receipts`
--

INSERT INTO `receipts` (`id`, `pr_id`, `amt_paid`, `balance`, `year`, `created_at`, `updated_at`) VALUES
(1, 1, 49216, 784, '2024', '2025-07-22 20:38:17', '2025-10-28 15:30:28'),
(2, 2, 298, 19702, '2025', '2025-09-17 17:30:55', '2025-10-28 15:30:28'),
(3, 3, 9157, 5843, '2022', '2024-12-25 08:34:22', '2025-10-28 15:30:28'),
(4, 4, 14945, 55, '2024', '2025-07-14 22:23:44', '2025-10-28 15:30:28'),
(5, 5, 355, 14645, '2023', '2025-06-13 22:06:39', '2025-10-28 15:30:28'),
(6, 6, 96188, 53812, '2025', '2025-04-10 00:30:41', '2025-10-28 15:30:28'),
(7, 7, 17794, 2206, '2024', '2024-12-12 23:44:09', '2025-10-28 15:30:28'),
(8, 8, 5970, 9030, '2023', '2025-02-05 20:25:52', '2025-10-28 15:30:28'),
(9, 9, 33445, 116555, '2022', '2025-06-20 20:12:52', '2025-10-28 15:30:28'),
(10, 10, 15137, 4863, '2024', '2025-07-31 10:10:03', '2025-10-28 15:30:28'),
(11, 11, 44348, 5652, '2022', '2025-04-16 11:06:28', '2025-10-28 15:30:28'),
(12, 12, 18368, 131632, '2023', '2025-10-02 00:57:26', '2025-10-28 15:30:28'),
(13, 13, 10840, 9160, '2025', '2024-12-13 11:12:48', '2025-10-28 15:30:28'),
(14, 14, 16378, 133622, '2024', '2025-02-24 01:09:08', '2025-10-28 15:30:28'),
(15, 15, 12580, 137420, '2023', '2025-10-12 08:34:41', '2025-10-28 15:30:28'),
(16, 16, 116319, 33681, '2022', '2025-03-14 22:42:38', '2025-10-28 15:30:28'),
(17, 17, 26343, 123657, '2023', '2024-12-31 22:25:06', '2025-10-28 15:30:28'),
(18, 18, 4010, 145990, '2025', '2024-12-23 22:28:24', '2025-10-28 15:30:28'),
(19, 19, 91589, 58411, '2024', '2025-08-05 12:03:49', '2025-10-28 15:30:28'),
(20, 20, 19527, 15473, '2024', '2025-02-17 09:05:35', '2025-10-28 15:30:28'),
(21, 21, 44816, 105184, '2024', '2025-09-11 10:38:51', '2025-10-28 15:30:28'),
(22, 22, 63344, 36656, '2025', '2025-10-04 17:37:07', '2025-10-28 15:30:28'),
(23, 23, 4976, 40024, '2025', '2025-03-16 13:25:35', '2025-10-28 15:30:28'),
(24, 24, 7863, 12137, '2025', '2025-10-22 06:13:14', '2025-10-28 15:30:28'),
(25, 25, 4321, 20679, '2025', '2024-12-30 01:56:14', '2025-10-28 15:30:28'),
(26, 26, 7996, 12004, '2024', '2024-12-11 17:49:25', '2025-10-28 15:30:28'),
(27, 27, 25541, 9459, '2024', '2025-03-23 23:53:47', '2025-10-28 15:30:28'),
(28, 28, 33457, 1543, '2023', '2024-11-05 20:55:11', '2025-10-28 15:30:28'),
(29, 29, 13614, 6386, '2022', '2025-09-14 15:11:22', '2025-10-28 15:30:28'),
(30, 30, 8401, 11599, '2022', '2025-04-10 09:55:09', '2025-10-28 15:30:28'),
(31, 31, 14558, 10442, '2025', '2025-01-06 16:36:05', '2025-10-28 15:30:28'),
(32, 32, 105635, 44365, '2022', '2025-05-09 07:05:09', '2025-10-28 15:30:28'),
(33, 33, 3569, 146431, '2025', '2025-03-22 09:01:29', '2025-10-28 15:30:28'),
(34, 34, 1324, 98676, '2022', '2025-02-14 09:06:45', '2025-10-28 15:30:28'),
(35, 35, 53734, 46266, '2024', '2025-07-11 10:34:18', '2025-10-28 15:30:28'),
(36, 36, 21394, 3606, '2022', '2025-04-24 09:18:12', '2025-10-28 15:30:28'),
(37, 37, 32722, 117278, '2025', '2025-07-04 15:44:55', '2025-10-28 15:30:28'),
(38, 38, 40321, 4679, '2023', '2025-01-14 01:41:18', '2025-10-28 15:30:28'),
(39, 39, 10277, 34723, '2024', '2024-12-12 21:02:25', '2025-10-28 15:30:28'),
(40, 40, 9578, 40422, '2025', '2024-10-31 08:00:43', '2025-10-28 15:30:28'),
(41, 41, 25566, 9434, '2023', '2025-06-12 11:51:21', '2025-10-28 15:30:28'),
(42, 42, 31140, 118860, '2025', '2024-12-27 00:19:44', '2025-10-28 15:30:28'),
(43, 43, 33398, 66602, '2024', '2025-06-02 21:37:09', '2025-10-28 15:30:28'),
(44, 44, 40023, 9977, '2025', '2025-02-20 04:49:11', '2025-10-28 15:30:28'),
(45, 45, 7971, 42029, '2025', '2025-09-08 14:30:17', '2025-10-28 15:30:28'),
(46, 46, 2836, 32164, '2022', '2025-05-30 07:23:35', '2025-10-28 15:30:28'),
(47, 47, 5170, 9830, '2024', '2025-08-14 21:06:57', '2025-10-28 15:30:28'),
(48, 48, 12781, 7219, '2025', '2025-07-26 01:23:50', '2025-10-28 15:30:28'),
(49, 49, 74124, 25876, '2024', '2025-01-27 03:03:56', '2025-10-28 15:30:28'),
(50, 50, 82477, 67523, '2022', '2024-12-30 16:30:20', '2025-10-28 15:30:28'),
(51, 51, 40985, 109015, '2024', '2025-06-20 22:52:24', '2025-10-28 15:30:28'),
(52, 52, 17214, 7786, '2024', '2025-02-07 00:48:15', '2025-10-28 15:30:28'),
(53, 53, 17262, 32738, '2024', '2025-03-13 23:51:16', '2025-10-28 15:30:28'),
(54, 54, 14920, 5080, '2025', '2025-06-07 06:51:59', '2025-10-28 15:30:28'),
(55, 55, 1104, 18896, '2024', '2025-01-08 02:58:51', '2025-10-28 15:30:28'),
(56, 56, 18491, 1509, '2025', '2025-05-18 00:58:09', '2025-10-28 15:30:28'),
(57, 57, 36125, 13875, '2023', '2025-02-17 05:20:28', '2025-10-28 15:30:28'),
(58, 58, 12016, 87984, '2023', '2025-05-08 00:59:49', '2025-10-28 15:30:28'),
(59, 59, 49132, 868, '2024', '2025-07-09 05:16:46', '2025-10-28 15:30:28'),
(60, 60, 5881, 19119, '2024', '2025-08-07 17:35:22', '2025-10-28 15:30:28'),
(61, 61, 22583, 27417, '2023', '2025-10-12 23:17:21', '2025-10-28 15:30:28'),
(62, 62, 3104, 16896, '2023', '2025-06-28 06:27:46', '2025-10-28 15:30:28'),
(63, 63, 112089, 37911, '2022', '2025-01-09 05:47:56', '2025-10-28 15:30:28'),
(64, 64, 26951, 18049, '2023', '2025-07-17 23:15:48', '2025-10-28 15:30:28'),
(65, 65, 41712, 3288, '2024', '2025-08-30 06:41:58', '2025-10-28 15:30:28');

-- --------------------------------------------------------

--
-- Table structure for table `school_events`
--

CREATE TABLE `school_events` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `event_date` date NOT NULL,
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `location` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `event_type` enum('academic','sports','cultural','meeting','exam','holiday','other') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'other',
  `is_recurring` tinyint(1) NOT NULL DEFAULT '0',
  `recurrence_pattern` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `target_audience` enum('all','students','teachers','parents','staff') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'all',
  `color` varchar(7) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '#3788d8',
  `created_by` int UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `school_events`
--

INSERT INTO `school_events` (`id`, `title`, `description`, `event_date`, `start_time`, `end_time`, `location`, `event_type`, `is_recurring`, `recurrence_pattern`, `target_audience`, `color`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'Réunion parents-professeurs', 'Nihil molestiae rerum excepturi vitae eum esse non nihil. Libero impedit quaerat ut aspernatur eum. Aut numquam sed labore inventore qui.', '2026-01-25', NULL, NULL, 'Jacquet', 'meeting', 0, 'monthly', 'staff', '#9a168c', 1, '2025-10-28 15:30:29', '2025-10-28 15:30:29'),
(2, 'English Language - Vacances de November', 'Beatae non saepe et ad et quia. Pariatur amet nihil alias qui non mollitia. Dolor consequatur modi laudantium expedita ut. Aut consequatur est placeat quo impedit laborum.', '2025-12-09', '15:45:00', '18:45:00', 'Martineau-sur-Diaz', 'holiday', 0, 'monthly', 'parents', '#288ed5', 1, '2025-10-28 15:30:29', '2025-10-28 15:30:29'),
(3, 'English Language - Date limite: ut modi voluptatem', NULL, '2026-01-03', '16:15:00', '17:15:00', 'Perret-sur-Goncalves', 'academic', 0, NULL, 'parents', '#923d88', 1, '2025-10-28 15:30:29', '2025-10-28 15:30:29'),
(4, 'English Language - Vacances de October', 'Assumenda nostrum velit nihil quia. Est voluptatem totam voluptatem aut et delectus eum. Praesentium rerum vel eveniet laudantium ratione minus itaque.', '2025-11-25', '15:45:00', '16:45:00', NULL, 'holiday', 0, 'monthly', 'staff', '#4fdcab', 1, '2025-10-28 15:30:29', '2025-10-28 15:30:29'),
(5, 'Date limite: cum explicabo eum', 'Accusamus eius voluptas magnam. Quidem nihil eaque totam quas inventore. Sit quia quibusdam quidem est quos. Occaecati ipsam libero culpa sequi qui.', '2025-12-31', '12:30:00', '15:30:00', 'Marchal', 'academic', 0, NULL, 'all', '#0467d8', 1, '2025-10-28 15:30:29', '2025-10-28 15:30:29'),
(6, 'Partiel de rattrapage', 'Consequatur eum consequatur dolores voluptates voluptas. Voluptas debitis ut qui hic et autem sit. Tempora dolorem eius minima aperiam dolor. Dolor cum cum odio qui temporibus maiores.', '2025-11-08', NULL, NULL, 'ChauvetVille', 'exam', 0, NULL, 'staff', '#0d15e6', 1, '2025-10-28 15:30:29', '2025-10-28 15:30:29'),
(7, 'Mathematics - Assemblée générale', 'Nihil odit praesentium illo ducimus voluptate ut nihil. Et iure quia autem quod in.', '2025-10-02', '08:15:00', '11:15:00', NULL, 'meeting', 0, NULL, 'staff', '#6b4da0', 1, '2025-10-28 15:30:29', '2025-10-28 15:30:29'),
(8, 'Mathematics - Vacances de October', NULL, '2025-11-09', '16:00:00', '18:00:00', NULL, 'holiday', 0, NULL, 'parents', '#141e68', 1, '2025-10-28 15:30:29', '2025-10-28 15:30:29'),
(9, 'English Language - Examen de fin de session', 'Qui doloremque sit rerum. Labore dolor enim sit eos velit voluptatem qui animi. Quae voluptas sint nisi iusto culpa quia esse. Possimus similique adipisci et blanditiis nihil et omnis.', '2025-10-23', NULL, NULL, NULL, 'exam', 1, NULL, 'students', '#9a9206', 1, '2025-10-28 15:30:29', '2025-10-28 15:30:29'),
(10, 'Mathematics - Autem autem incidunt.', NULL, '2025-10-14', NULL, NULL, NULL, 'cultural', 0, 'weekly', 'parents', '#54cac2', 1, '2025-10-28 15:30:29', '2025-10-28 15:30:29'),
(11, 'English Language - Examen de mi-session', NULL, '2025-11-19', '14:00:00', '17:00:00', NULL, 'exam', 0, NULL, 'students', '#5c5f02', 1, '2025-10-28 15:30:29', '2025-10-28 15:30:29'),
(12, 'Contrôle de mi-session', 'Officiis eum quae quis vel aperiam nobis iure. Et et quis asperiores ab corporis ea. Accusamus voluptate alias aut debitis aut molestiae reprehenderit hic. Quidem culpa aliquam vero illum.', '2025-11-08', '12:30:00', '13:30:00', NULL, 'exam', 0, NULL, 'all', '#391b78', 1, '2025-10-28 15:30:29', '2025-10-28 15:30:29'),
(13, 'Voluptates iure quos unde.', 'Tempore consequatur harum a quod. Magni unde temporibus eum dolor corrupti voluptas aut. Qui quia libero delectus accusantium esse rerum.', '2025-10-30', '09:00:00', '11:00:00', 'Blanchet', 'exam', 0, 'daily', 'all', '#31ffa5', 1, '2025-10-28 15:30:29', '2025-10-28 15:30:29'),
(14, 'English Language - Assemblée générale', 'Laudantium dolorem enim corrupti modi. Laborum non dolorem omnis ratione. In corrupti nesciunt similique enim qui natus dolorem. Velit quo error ut.', '2026-01-15', '13:15:00', '16:15:00', 'Marion', 'meeting', 0, NULL, 'students', '#ef2715', 1, '2025-10-28 15:30:29', '2025-10-28 15:30:29'),
(15, 'Mathematics - Date limite: voluptas voluptatem ducimus', 'Earum quae magnam magni dolor. Pariatur quaerat est perferendis perspiciatis. Earum perferendis et inventore.', '2025-11-26', '12:30:00', '13:30:00', 'Mendes-sur-Laroche', 'academic', 0, 'monthly', 'teachers', '#56c672', 1, '2025-10-28 15:30:29', '2025-10-28 15:30:29'),
(16, 'Vero blanditiis similique quasi.', 'Quia modi sunt sunt maxime. Commodi corporis adipisci et pariatur placeat accusamus. Sunt nulla laboriosam perspiciatis dignissimos quod.', '2025-10-17', NULL, NULL, 'Fischer', 'cultural', 0, 'yearly', 'students', '#9e35d3', 1, '2025-10-28 15:30:29', '2025-10-28 15:30:29'),
(17, 'Assemblée générale', 'Facere dignissimos in vel nulla similique ut voluptatem. Non nemo corrupti ratione culpa rerum. Dolor et dolore perspiciatis sapiente ut aliquam modi.', '2025-12-21', NULL, NULL, 'Charpentier-sur-Mer', 'meeting', 1, 'monthly', 'teachers', '#3ae956', 1, '2025-10-28 15:30:29', '2025-10-28 15:30:29'),
(18, 'Mathematics - Assemblée générale', 'Delectus itaque iure quod. Corrupti ut neque amet rerum magni modi aperiam odit. Saepe sapiente debitis repellat perferendis ut voluptatibus rem. Reiciendis voluptatem deserunt qui consequuntur mollitia.', '2026-01-06', NULL, NULL, NULL, 'meeting', 0, NULL, 'teachers', '#500aed', 1, '2025-10-28 15:30:29', '2025-10-28 15:30:29'),
(19, 'Réunion des professeurs', 'Nostrum corrupti dolore reprehenderit dolorem minus dolorem qui. Rem eos modi sit tenetur eligendi. Quia adipisci ab soluta doloribus autem.', '2025-11-25', '10:15:00', '12:15:00', 'Paris', 'meeting', 1, NULL, 'parents', '#e3f247', 1, '2025-10-28 15:30:29', '2025-10-28 15:30:29'),
(20, 'English Language - Quia reprehenderit veniam est.', 'Voluptas ad sed enim necessitatibus. Cum ut nobis accusantium et consequatur molestiae. Eos omnis ut nihil ex et. Magni sed amet quasi enim voluptatum velit.', '2025-12-19', '16:15:00', '17:15:00', 'Foucher-les-Bains', 'cultural', 0, NULL, 'staff', '#8af8b5', 1, '2025-10-28 15:30:29', '2025-10-28 15:30:29'),
(21, 'English Language - Eos voluptas.', 'Laborum consequatur itaque porro vel soluta et. Ut id non totam nesciunt. Dignissimos non aperiam nisi voluptas quis libero.', '2026-01-08', '12:30:00', '14:30:00', 'Carlierdan', 'exam', 0, NULL, 'all', '#218540', 1, '2025-10-28 15:30:29', '2025-10-28 15:30:29'),
(22, 'Minus ex fuga.', 'Reprehenderit neque ut cum quia consequatur laboriosam. Commodi necessitatibus dicta amet qui. Asperiores nesciunt dolor a error eveniet.', '2025-12-02', '13:15:00', '16:15:00', NULL, 'cultural', 1, NULL, 'all', '#681a8e', 1, '2025-10-28 15:30:29', '2025-10-28 15:30:29'),
(23, 'English Language - Date limite: sunt iusto et', 'Enim sequi labore nulla sed occaecati doloremque. Voluptatem beatae ipsam et id id quos. Et esse itaque ab deleniti. Nam minus maiores est nesciunt tempore quidem voluptas.', '2025-11-12', '11:00:00', '12:00:00', NULL, 'academic', 1, 'daily', 'all', '#a1565a', 1, '2025-10-28 15:30:29', '2025-10-28 15:30:29'),
(24, 'Conseil de classe', 'Sit magni enim et deserunt. Velit cupiditate facilis provident nisi expedita. Nobis recusandae impedit saepe ea soluta doloribus.', '2025-10-23', NULL, NULL, 'Antoine', 'meeting', 0, 'weekly', 'students', '#74a824', 1, '2025-10-28 15:30:29', '2025-10-28 15:30:29'),
(25, 'English Language - Réunion des professeurs', 'Id qui eius maiores numquam quia ut a. Vel ipsam tempore dignissimos assumenda.', '2025-11-12', '08:15:00', '09:15:00', 'Klein-sur-Guillou', 'meeting', 1, NULL, 'students', '#ed7123', 1, '2025-10-28 15:30:29', '2025-10-28 15:30:29'),
(26, 'English Language - Qui nihil est delectus.', 'Iste ipsa quos nam rem et ab. Natus quia voluptatem ratione non velit quisquam perferendis iste. Eveniet quia nesciunt ut necessitatibus totam. Iure similique a sapiente repellendus explicabo.', '2025-12-18', NULL, NULL, 'LopezVille', 'cultural', 0, NULL, 'parents', '#85f156', 1, '2025-10-28 15:30:29', '2025-10-28 15:30:29'),
(27, 'English Language - Partiel de fin de session', 'Reiciendis doloremque nesciunt consequatur qui. Sapiente quo ea ducimus quaerat aperiam. Et delectus nesciunt unde voluptatem esse. Et illum soluta sed commodi praesentium deleniti. Et a alias itaque facilis.', '2026-01-22', NULL, NULL, NULL, 'exam', 0, NULL, 'all', '#dc4d62', 1, '2025-10-28 15:30:29', '2025-10-28 15:30:29'),
(28, 'Congé de March', 'Perferendis totam velit laudantium ducimus qui molestias. Et ullam expedita debitis sequi alias. Nihil ad exercitationem accusantium iste facilis. Tempore possimus consectetur voluptatum deserunt nemo facilis voluptate.', '2025-12-07', '08:45:00', '10:45:00', NULL, 'holiday', 0, NULL, 'parents', '#49f692', 1, '2025-10-28 15:30:29', '2025-10-28 15:30:29'),
(29, 'English Language - Examen de fin de session', NULL, '2025-09-30', NULL, NULL, NULL, 'exam', 0, NULL, 'staff', '#d446c7', 1, '2025-10-28 15:30:29', '2025-10-28 15:30:29'),
(30, 'Mathematics - Ad illo consequatur.', 'Ad earum accusantium magni nobis et quia veniam. Beatae ea dignissimos placeat iure possimus. Et amet voluptatibus et est. Assumenda rerum expedita sit et ab.', '2025-12-10', '11:00:00', '13:00:00', 'Besnard', 'cultural', 1, NULL, 'staff', '#f02b54', 1, '2025-10-28 15:30:29', '2025-10-28 15:30:29'),
(31, 'English Language - Réunion des professeurs', 'Qui et corporis eveniet et et. Cum quas non dolorem aut alias repudiandae omnis.', '2026-01-23', '08:30:00', '10:30:00', 'Gilles', 'meeting', 0, 'yearly', 'staff', '#1d7986', 1, '2025-10-28 15:30:29', '2025-10-28 15:30:29'),
(32, 'English Language - Laboriosam numquam autem.', 'Placeat et non dolore harum harum provident autem. Sunt eaque eum suscipit praesentium nemo a magnam. Ea perferendis tempore possimus iusto voluptatem et. Impedit et magnam magni deserunt facere.', '2026-01-12', '13:30:00', '15:30:00', 'Da Silvadan', 'exam', 0, NULL, 'teachers', '#c9f898', 1, '2025-10-28 15:30:29', '2025-10-28 15:30:29'),
(33, 'Mathematics - Vacances de January', 'Earum repudiandae nihil ipsum aut. Dolorem vitae alias a enim. Velit magni quam est nihil temporibus repudiandae. Perferendis quaerat voluptates id voluptas et voluptate sed. Voluptatum quisquam odio aut deleniti.', '2025-11-03', '12:00:00', '13:00:00', 'Grenier-sur-Legendre', 'holiday', 0, NULL, 'students', '#f4fd4b', 1, '2025-10-28 15:30:29', '2025-10-28 15:30:29'),
(34, 'English Language - Partiel de fin de session', 'Et voluptatibus ipsam aut corporis. Non consequatur voluptatem qui esse dolore fuga eum necessitatibus. Commodi et accusamus quia vel incidunt vel animi.', '2025-11-12', NULL, NULL, 'Bonneau-sur-Gros', 'exam', 0, NULL, 'teachers', '#cba689', 1, '2025-10-28 15:30:29', '2025-10-28 15:30:29'),
(35, 'Date limite: modi voluptas qui', 'Aut tempora maxime praesentium est sint veritatis aspernatur. Quia maxime sit vel est corporis. Quisquam ipsam a esse voluptates. Pariatur vero sint impedit vitae.', '2025-10-18', NULL, NULL, 'Chauvet', 'academic', 0, NULL, 'staff', '#9a564b', 1, '2025-10-28 15:30:29', '2025-10-28 15:30:29'),
(36, 'Fête scolaire', 'Quia mollitia eum inventore autem. Soluta non eum sapiente nam vel ut. Recusandae ut quisquam a non. Omnis est enim aut itaque sit itaque omnis voluptatem.', '2025-10-28', NULL, NULL, 'Millet-sur-Mer', 'holiday', 1, NULL, 'all', '#0dd24a', 1, '2025-10-28 15:30:29', '2025-10-28 15:30:29'),
(37, 'Sequi culpa ipsam quaerat.', 'Mollitia tempora qui quia architecto laborum enim laudantium. Voluptatum error modi ipsum labore laboriosam sint. Fuga quos quaerat fuga ut nobis nulla.', '2025-10-25', '08:15:00', '11:15:00', NULL, 'exam', 0, 'monthly', 'teachers', '#2966b0', 1, '2025-10-28 15:30:29', '2025-10-28 15:30:29'),
(38, 'Congé de March', 'Aspernatur voluptas vel cum deleniti nostrum sit quis repellendus. Mollitia eos voluptas repudiandae. Autem tempore quia qui ut earum. Consequuntur est dicta sed deserunt voluptatem et rerum.', '2025-12-26', '10:45:00', '12:45:00', 'Benoit', 'holiday', 0, NULL, 'staff', '#50fb78', 1, '2025-10-28 15:30:29', '2025-10-28 15:30:29'),
(39, 'English Language - Est fugit.', 'Eligendi in laboriosam ullam molestiae. Reprehenderit voluptatem et labore doloremque qui est consequatur. Est consequatur recusandae numquam dolore facilis exercitationem.', '2025-10-05', NULL, NULL, NULL, 'exam', 0, NULL, 'staff', '#20bd80', 1, '2025-10-28 15:30:29', '2025-10-28 15:30:29'),
(40, 'Omnis a voluptas aliquam repellendus.', 'Velit quae minus perspiciatis vel iure recusandae sint. Consequatur eveniet voluptatem voluptate adipisci voluptas soluta libero blanditiis.', '2025-12-12', NULL, NULL, NULL, 'exam', 1, NULL, 'staff', '#569df3', 1, '2025-10-28 15:30:29', '2025-10-28 15:30:29'),
(41, 'Mathematics - Date limite: possimus odit cumque', 'Quaerat dignissimos sunt tenetur rerum qui repudiandae. Totam in corrupti debitis quos quis nulla. Sequi unde quae ut animi enim eaque. Facere rem ratione nihil non ut aut.', '2025-12-15', NULL, NULL, NULL, 'academic', 0, NULL, 'staff', '#5c7b82', 1, '2025-10-28 15:30:29', '2025-10-28 15:30:29'),
(42, 'Mathematics - Voluptatem voluptates ut sit.', 'Commodi quia voluptatem cumque et laudantium. Nesciunt ea est tempore veniam iste sint impedit. Totam esse et fuga dolorem maxime.', '2025-11-28', '09:30:00', '11:30:00', NULL, 'exam', 0, NULL, 'teachers', '#6275bb', 1, '2025-10-28 15:30:29', '2025-10-28 15:30:29'),
(43, 'Date limite: omnis et enim', 'Exercitationem maiores occaecati corrupti eius enim quidem nam incidunt. Reprehenderit aut dolorum rerum nobis. Eius sed vitae officia nihil impedit. Enim asperiores adipisci ut reiciendis exercitationem excepturi.', '2026-01-18', NULL, NULL, 'Breton', 'academic', 0, NULL, 'staff', '#7c713f', 1, '2025-10-28 15:30:29', '2025-10-28 15:30:29'),
(44, 'Quo officia facere.', 'Eveniet aut sunt sapiente ea dolorem atque. Sint aut quidem aperiam dolorem ab repellendus ullam dicta. Ut est impedit incidunt aut. Placeat qui illo aut voluptate ab enim.', '2025-11-04', '13:30:00', '14:30:00', NULL, 'exam', 0, NULL, 'students', '#3112cd', 1, '2025-10-28 15:30:29', '2025-10-28 15:30:29'),
(45, 'Mathematics - Voluptatum iste sapiente.', NULL, '2025-10-15', NULL, NULL, NULL, 'cultural', 0, 'daily', 'staff', '#176e08', 1, '2025-10-28 15:30:29', '2025-10-28 15:30:29'),
(46, 'Mathematics - Ea repellendus qui.', 'Aut enim adipisci consectetur odio. Atque neque enim provident provident magni beatae. Maiores sint et provident ea et. Saepe voluptas et nisi atque placeat fugit.', '2025-11-21', '10:30:00', '13:30:00', 'Caron', 'cultural', 1, NULL, 'teachers', '#8b1e18', 1, '2025-10-28 15:30:29', '2025-10-28 15:30:29'),
(47, 'Examen de mi-session', 'Possimus quia culpa consequatur. Earum veniam ipsam necessitatibus autem necessitatibus sit necessitatibus.', '2025-10-22', '15:00:00', '16:00:00', NULL, 'exam', 0, NULL, 'students', '#7448f6', 1, '2025-10-28 15:30:29', '2025-10-28 15:30:29'),
(48, 'Date limite: nihil deleniti velit', NULL, '2025-10-23', '09:45:00', '11:45:00', 'Fleurydan', 'academic', 0, NULL, 'staff', '#bdb924', 1, '2025-10-28 15:30:29', '2025-10-28 15:30:29'),
(49, 'Mathematics - Quisquam molestiae.', 'Voluptas sint voluptas recusandae est iste. Consectetur fugit dolore sequi praesentium consequatur rerum est. Modi cum qui neque et. Nostrum ullam voluptate ea officia repellat ut. Ipsum aut error animi ratione eveniet tenetur est.', '2026-01-23', '15:45:00', '16:45:00', 'Turpin-les-Bains', 'cultural', 0, 'monthly', 'students', '#d6efa5', 1, '2025-10-28 15:30:29', '2025-10-28 15:30:29'),
(50, 'Examen de rattrapage', 'Eum enim nemo doloremque. Autem et a sed et. Modi provident qui magni debitis amet. Inventore recusandae tenetur cumque occaecati aliquam illum.', '2025-10-20', NULL, NULL, NULL, 'exam', 0, NULL, 'all', '#390e9e', 1, '2025-10-28 15:30:29', '2025-10-28 15:30:29'),
(51, 'Mathematics - Examen de mi-session', 'Sint voluptatem nemo qui dolores sequi eos. Quos et et explicabo voluptatem sed hic eligendi. Est et fugiat ea officia impedit maiores alias officiis. Voluptatem odit dignissimos velit et dignissimos et quam et.', '2025-10-08', '11:45:00', '12:45:00', NULL, 'exam', 1, NULL, 'all', '#d238d0', 1, '2025-10-28 15:30:29', '2025-10-28 15:30:29'),
(52, 'Congé de January', 'Rerum est omnis recusandae quasi quas. Ab eius corporis sunt dignissimos quae ipsa. Consectetur deleniti dolores odio magnam non delectus id.', '2025-11-17', '08:45:00', '10:45:00', 'Munoz-les-Bains', 'holiday', 0, NULL, 'all', '#4329c2', 1, '2025-10-28 15:30:29', '2025-10-28 15:30:29'),
(53, 'Mathematics - Contrôle de fin de session', 'Unde quis aspernatur doloribus hic ipsa cumque. Qui sequi dolore aperiam nulla enim. Ratione aliquam nihil sunt enim natus.', '2025-12-13', '15:30:00', '18:30:00', 'Weiss', 'exam', 0, NULL, 'teachers', '#7c880d', 1, '2025-10-28 15:30:29', '2025-10-28 15:30:29'),
(54, 'Partiel de rattrapage', 'Id eos necessitatibus libero dignissimos molestias perferendis. Ad aut quos est et illum sed omnis. Reiciendis illo voluptatibus minima modi nam. Consequuntur iure dolorem qui porro ut autem.', '2025-11-19', '08:45:00', '11:45:00', NULL, 'exam', 0, 'weekly', 'teachers', '#303b10', 1, '2025-10-28 15:30:29', '2025-10-28 15:30:29'),
(55, 'Mathematics - Congé de May', 'Animi reiciendis velit voluptatum quia. Aut eos sint aut nulla sapiente. Et ad placeat non corrupti animi. Officiis explicabo nesciunt est eligendi veniam et aliquid.', '2026-01-14', NULL, NULL, NULL, 'holiday', 0, NULL, 'teachers', '#2997ff', 1, '2025-10-28 15:30:29', '2025-10-28 15:30:29'),
(56, 'English Language - Iste aut beatae.', NULL, '2025-12-31', NULL, NULL, NULL, 'cultural', 0, NULL, 'parents', '#d097e9', 1, '2025-10-28 15:30:29', '2025-10-28 15:30:29'),
(57, 'Mathematics - Partiel de fin de session', 'Optio atque in voluptas. Adipisci vel eos aut velit quis. Voluptas aut et doloremque sapiente quia eos eligendi dolorum.', '2026-01-24', '12:00:00', '13:00:00', 'Guilbert', 'exam', 0, NULL, 'staff', '#5d7b91', 1, '2025-10-28 15:30:29', '2025-10-28 15:30:29'),
(58, 'Deserunt omnis in.', 'Id nesciunt sit non qui quod rerum. Voluptatem omnis minus earum incidunt sunt nam voluptates officiis.', '2025-09-30', '14:00:00', '15:00:00', 'Martinez-les-Bains', 'cultural', 0, NULL, 'all', '#630a0f', 1, '2025-10-28 15:30:29', '2025-10-28 15:30:29'),
(59, 'Mathematics - Soluta accusamus consectetur.', 'Voluptatem aliquid qui est qui. Aut consequuntur explicabo illo sint sunt ut. Quia dolorem ut eligendi incidunt.', '2025-10-04', '11:15:00', '13:15:00', 'Legrand', 'exam', 1, 'daily', 'parents', '#f07271', 1, '2025-10-28 15:30:29', '2025-10-28 15:30:29'),
(60, 'Mathematics - Numquam dolores rerum ex.', 'Nostrum possimus quia animi dignissimos mollitia qui. Enim omnis qui qui vitae consequatur. Velit tenetur est autem sit voluptatem et repellat. Omnis id sequi magni sed ut.', '2025-12-27', '12:15:00', '15:15:00', 'Bergerdan', 'exam', 0, NULL, 'all', '#725421', 1, '2025-10-28 15:30:29', '2025-10-28 15:30:29');

-- --------------------------------------------------------

--
-- Table structure for table `sections`
--

CREATE TABLE `sections` (
  `id` int UNSIGNED NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `my_class_id` int UNSIGNED NOT NULL,
  `teacher_id` int UNSIGNED DEFAULT NULL,
  `active` tinyint NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sections`
--

INSERT INTO `sections` (`id`, `name`, `my_class_id`, `teacher_id`, `active`, `created_at`, `updated_at`) VALUES
(1, 'Gold', 1, NULL, 1, NULL, NULL),
(2, 'Diamond', 1, NULL, 0, NULL, NULL),
(3, 'Silver', 2, NULL, 1, NULL, NULL),
(4, 'Lemon', 2, NULL, 0, NULL, NULL),
(5, 'Bronze', 3, NULL, 1, NULL, NULL),
(6, 'Silver', 4, NULL, 1, NULL, NULL),
(7, 'Diamond', 5, NULL, 1, NULL, NULL),
(8, 'Blue', 6, NULL, 1, NULL, NULL),
(9, 'A', 7, NULL, 1, NULL, NULL),
(10, 'A', 8, NULL, 1, NULL, NULL),
(11, 'A', 9, NULL, 1, NULL, NULL),
(12, 'A', 10, NULL, 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int UNSIGNED NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `type`, `description`, `created_at`, `updated_at`) VALUES
(1, 'current_session', '2018-2019', NULL, NULL),
(2, 'system_title', 'CJIA', NULL, NULL),
(3, 'system_name', 'MrGentil ACADEMY', NULL, NULL),
(4, 'term_ends', '7/10/2018', NULL, NULL),
(5, 'term_begins', '7/10/2018', NULL, NULL),
(6, 'phone', '0123456789', NULL, NULL),
(7, 'address', '18B North Central Park, Behind Central Square Tourist Center', NULL, NULL),
(8, 'system_email', 'cjacademy@cj.com', NULL, NULL),
(9, 'alt_email', '', NULL, NULL),
(10, 'email_host', '', NULL, NULL),
(11, 'email_pass', '', NULL, NULL),
(12, 'lock_exam', '0', NULL, NULL),
(13, 'logo', '', NULL, NULL),
(14, 'next_term_fees_j', '20000', NULL, NULL),
(15, 'next_term_fees_pn', '25000', NULL, NULL),
(16, 'next_term_fees_p', '25000', NULL, NULL),
(17, 'next_term_fees_n', '25600', NULL, NULL),
(18, 'next_term_fees_s', '15600', NULL, NULL),
(19, 'next_term_fees_c', '1600', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `skills`
--

CREATE TABLE `skills` (
  `id` int UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `skill_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `class_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `skills`
--

INSERT INTO `skills` (`id`, `name`, `skill_type`, `class_type`, `created_at`, `updated_at`) VALUES
(1, 'PUNCTUALITY', 'AF', NULL, NULL, NULL),
(2, 'NEATNESS', 'AF', NULL, NULL, NULL),
(3, 'HONESTY', 'AF', NULL, NULL, NULL),
(4, 'RELIABILITY', 'AF', NULL, NULL, NULL),
(5, 'RELATIONSHIP WITH OTHERS', 'AF', NULL, NULL, NULL),
(6, 'POLITENESS', 'AF', NULL, NULL, NULL),
(7, 'ALERTNESS', 'AF', NULL, NULL, NULL),
(8, 'HANDWRITING', 'PS', NULL, NULL, NULL),
(9, 'GAMES & SPORTS', 'PS', NULL, NULL, NULL),
(10, 'DRAWING & ARTS', 'PS', NULL, NULL, NULL),
(11, 'PAINTING', 'PS', NULL, NULL, NULL),
(12, 'CONSTRUCTION', 'PS', NULL, NULL, NULL),
(13, 'MUSICAL SKILLS', 'PS', NULL, NULL, NULL),
(14, 'FLEXIBILITY', 'PS', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `staff_records`
--

CREATE TABLE `staff_records` (
  `id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `code` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `emp_date` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `staff_records`
--

INSERT INTO `staff_records` (`id`, `user_id`, `code`, `emp_date`, `created_at`, `updated_at`) VALUES
(1, 77, 'CJIA/STAFF/2000/04/5690', '04/09/2000', '2025-10-29 11:33:34', '2025-10-29 11:33:34');

-- --------------------------------------------------------

--
-- Table structure for table `states`
--

CREATE TABLE `states` (
  `id` int UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `states`
--

INSERT INTO `states` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Abia', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(2, 'Adamawa', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(3, 'Akwa Ibom', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(4, 'Anambra', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(5, 'Bauchi', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(6, 'Bayelsa', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(7, 'Benue', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(8, 'Borno', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(9, 'Cross River', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(10, 'Delta', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(11, 'Ebonyi', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(12, 'Edo', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(13, 'Ekiti', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(14, 'Enugu', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(15, 'FCT', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(16, 'Gombe', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(17, 'Imo', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(18, 'Jigawa', '2025-10-28 15:30:12', '2025-10-28 15:30:12'),
(19, 'Kaduna', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(20, 'Kano', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(21, 'Katsina', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(22, 'Kebbi', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(23, 'Kogi', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(24, 'Kwara', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(25, 'Lagos', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(26, 'Nasarawa', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(27, 'Niger', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(28, 'Ogun', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(29, 'Ondo', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(30, 'Osun', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(31, 'Oyo', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(32, 'Plateau', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(33, 'Rivers', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(34, 'Sokoto', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(35, 'Taraba', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(36, 'Yobe', '2025-10-28 15:30:13', '2025-10-28 15:30:13'),
(37, 'Zamfara', '2025-10-28 15:30:13', '2025-10-28 15:30:13');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `class_id` int UNSIGNED DEFAULT NULL,
  `admission_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guardian_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `guardian_phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `gender` enum('male','female','other') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `user_id`, `class_id`, `admission_number`, `guardian_name`, `guardian_phone`, `address`, `date_of_birth`, `gender`, `created_at`, `updated_at`) VALUES
(1, 56, 6, 'STD0056', 'Dorothée Garcia', '0636622450', '666, impasse Levy\n66718 Guillaume', '2010-03-25', 'female', '2025-10-28 15:30:24', '2025-10-28 15:30:24'),
(2, 57, 6, 'STD0057', 'Margaux Normand-Antoine', '+33 1 62 08 87 07', 'rue Riviere\n51706 Bernier-sur-Normand', '2007-01-21', 'female', '2025-10-28 15:30:24', '2025-10-28 15:30:24'),
(3, 58, 3, 'STD0058', 'Henriette Guyon', '+33 (0)9 81 53 84 27', '267, rue de Bourgeois\n06260 Mendes', '2007-06-14', 'female', '2025-10-28 15:30:24', '2025-10-28 15:30:24'),
(4, 59, 1, 'STD0059', 'Honoré-Joseph Dijoux', '+33 2 89 24 77 35', '5, place Marthe David\n32982 Riviere', '2008-09-30', 'male', '2025-10-28 15:30:24', '2025-10-28 15:30:24'),
(5, 60, 1, 'STD0060', 'Noémi-Manon Gregoire', '0782598961', '48, rue Emmanuel Moreau\n29386 Descamps-sur-Marty', '2006-07-25', 'male', '2025-10-28 15:30:24', '2025-10-28 15:30:24'),
(6, 61, 3, 'STD0061', 'Virginie Cohen', '04 63 29 03 31', 'place Bouchet\n53779 Delaunay-sur-Valentin', '2006-06-20', 'male', '2025-10-28 15:30:24', '2025-10-28 15:30:24'),
(7, 62, 10, 'STD0062', 'Martine Rousseau-Ramos', '0401236351', '66, impasse de Louis\n89975 Camusdan', '2007-01-22', 'female', '2025-10-28 15:30:24', '2025-10-28 15:30:24'),
(8, 63, 9, 'STD0063', 'Alice de Guibert', '0477131053', '84, boulevard de Lopez\n38299 Vincent-sur-Baron', '2007-05-31', 'female', '2025-10-28 15:30:24', '2025-10-28 15:30:24'),
(9, 64, 7, 'STD0064', 'Lucas Bonnin', '+33 (0)1 62 94 86 45', '317, boulevard Zacharie Bourdon\n61180 Martins', '2009-05-09', 'female', '2025-10-28 15:30:25', '2025-10-28 15:30:25'),
(10, 65, 4, 'STD0065', 'Adélaïde-Océane Robert', '+33 3 83 02 61 60', '69, rue de Huet\n00902 Lopes', '2009-01-17', 'male', '2025-10-28 15:30:25', '2025-10-28 15:30:25'),
(11, 66, 9, 'STD0066', 'Susan Guillaume-Buisson', '09 35 90 64 46', '36, avenue Patricia Briand\n50556 Humbert', '2006-08-12', 'male', '2025-10-28 15:30:25', '2025-10-28 15:30:25'),
(12, 67, 7, 'STD0067', 'Nathalie-Aurore Bertin', '+33 (0)4 16 85 19 84', '32, rue Bertrand\n90683 Lemairedan', '2009-09-23', 'male', '2025-10-28 15:30:25', '2025-10-28 15:30:25'),
(13, 68, 3, 'STD0068', 'Gilbert Pichon', '0695693352', '51, place Olivier\n71947 Thierry-sur-Schneider', '2010-01-04', 'female', '2025-10-28 15:30:25', '2025-10-28 15:30:25'),
(14, 69, 4, 'STD0069', 'Dominique Benard', '+33 (0)1 06 71 01 86', '40, rue Antoine Lecoq\n75961 Guillot', '2009-09-22', 'female', '2025-10-28 15:30:25', '2025-10-28 15:30:25'),
(15, 70, 9, 'STD0070', 'Yves Weiss', '+33 (0)8 97 74 28 21', '90, impasse Aimé Perez\n87100 Bernard-sur-Mer', '2005-11-06', 'male', '2025-10-28 15:30:25', '2025-10-28 15:30:25'),
(16, 71, 1, 'STD0071', 'Andrée-Marine Joly', '01 36 86 13 29', '71, avenue Teixeira\n31010 Albert', '2009-03-14', 'male', '2025-10-28 15:30:26', '2025-10-28 15:30:26'),
(17, 72, 3, 'STD0072', 'Pierre Le Guichard', '+33 8 28 95 31 14', '36, impasse Laporte\n10840 Vaillant', '2007-03-06', 'female', '2025-10-28 15:30:26', '2025-10-28 15:30:26'),
(18, 73, 6, 'STD0073', 'Jacques Salmon', '0100115290', 'avenue Arnaud\n20436 Loiseau', '2010-06-25', 'female', '2025-10-28 15:30:26', '2025-10-28 15:30:26'),
(19, 74, 10, 'STD0074', 'Robert Grondin', '0318185537', 'rue Francois\n74170 Marty', '2008-12-03', 'male', '2025-10-28 15:30:26', '2025-10-28 15:30:26'),
(20, 75, 6, 'STD0075', 'Lorraine Weber', '0250519819', '3, boulevard Alex Meyer\n45277 De Sousa', '2007-05-14', 'male', '2025-10-28 15:30:26', '2025-10-28 15:30:26'),
(21, 8, 5, 'STD2888', 'Louis Richard', '0197143738', '758, rue Juliette Laroche\n23838 Roussel', '2009-09-10', 'female', '2025-10-28 15:30:28', '2025-10-28 15:30:28'),
(22, 16, 3, 'STD5970', 'Inès Toussaint', '+33 (0)1 46 78 97 17', '85, rue de Bourgeois\n03724 Remyboeuf', '2006-04-06', 'female', '2025-10-28 15:30:28', '2025-10-28 15:30:28'),
(23, 12, 1, 'STD8544', 'Suzanne Legrand', '+33 (0)7 63 00 33 53', '75, avenue Aurélie Levy\n74347 LemoineBourg', '2009-05-20', 'male', '2025-10-28 15:30:29', '2025-10-28 15:30:29'),
(24, 3, 9, 'STD7038', 'Christelle Gauthier', '0346027137', '65, chemin Valentin\n04936 Sauvage', '2008-07-03', 'female', '2025-10-28 15:30:29', '2025-10-28 15:30:29'),
(25, 76, NULL, 'STD761761737053', NULL, NULL, NULL, NULL, NULL, '2025-10-29 10:24:13', '2025-10-29 10:24:13');

-- --------------------------------------------------------

--
-- Table structure for table `student_attendances`
--

CREATE TABLE `student_attendances` (
  `id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `student_records`
--

CREATE TABLE `student_records` (
  `id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `my_class_id` int UNSIGNED NOT NULL,
  `section_id` int UNSIGNED NOT NULL,
  `adm_no` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `my_parent_id` int UNSIGNED DEFAULT NULL,
  `dorm_id` int UNSIGNED DEFAULT NULL,
  `dorm_room_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `session` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `house` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `age` tinyint DEFAULT NULL,
  `year_admitted` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `grad` tinyint NOT NULL DEFAULT '0',
  `grad_date` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `student_records`
--

INSERT INTO `student_records` (`id`, `user_id`, `my_class_id`, `section_id`, `adm_no`, `my_parent_id`, `dorm_id`, `dorm_room_no`, `session`, `house`, `age`, `year_admitted`, `grad`, `grad_date`, `created_at`, `updated_at`) VALUES
(1, 19, 1, 1, NULL, NULL, NULL, NULL, '2018-2019', NULL, NULL, NULL, 0, NULL, '2025-10-28 15:30:17', '2025-10-28 15:30:17'),
(2, 20, 1, 1, NULL, NULL, NULL, NULL, '2018-2019', NULL, NULL, NULL, 0, NULL, '2025-10-28 15:30:18', '2025-10-28 15:30:18'),
(3, 21, 1, 1, NULL, NULL, NULL, NULL, '2018-2019', NULL, NULL, NULL, 0, NULL, '2025-10-28 15:30:18', '2025-10-28 15:30:18'),
(4, 22, 1, 1, NULL, NULL, NULL, NULL, '2018-2019', NULL, NULL, NULL, 0, NULL, '2025-10-28 15:30:18', '2025-10-28 15:30:18'),
(5, 23, 1, 2, NULL, NULL, NULL, NULL, '2018-2019', NULL, NULL, NULL, 0, NULL, '2025-10-28 15:30:18', '2025-10-28 15:30:18'),
(6, 24, 1, 2, NULL, NULL, NULL, NULL, '2018-2019', NULL, NULL, NULL, 0, NULL, '2025-10-28 15:30:18', '2025-10-28 15:30:18'),
(7, 25, 1, 2, NULL, NULL, NULL, NULL, '2018-2019', NULL, NULL, NULL, 0, NULL, '2025-10-28 15:30:18', '2025-10-28 15:30:18'),
(8, 26, 2, 3, NULL, NULL, NULL, NULL, '2018-2019', NULL, NULL, NULL, 0, NULL, '2025-10-28 15:30:19', '2025-10-28 15:30:19'),
(9, 27, 2, 3, NULL, NULL, NULL, NULL, '2018-2019', NULL, NULL, NULL, 0, NULL, '2025-10-28 15:30:19', '2025-10-28 15:30:19'),
(10, 28, 2, 3, NULL, NULL, NULL, NULL, '2018-2019', NULL, NULL, NULL, 0, NULL, '2025-10-28 15:30:19', '2025-10-28 15:30:19'),
(11, 29, 2, 4, NULL, NULL, NULL, NULL, '2018-2019', NULL, NULL, NULL, 0, NULL, '2025-10-28 15:30:19', '2025-10-28 15:30:19'),
(12, 30, 2, 4, NULL, NULL, NULL, NULL, '2018-2019', NULL, NULL, NULL, 0, NULL, '2025-10-28 15:30:19', '2025-10-28 15:30:19'),
(13, 31, 2, 4, NULL, NULL, NULL, NULL, '2018-2019', NULL, NULL, NULL, 0, NULL, '2025-10-28 15:30:19', '2025-10-28 15:30:19'),
(14, 32, 3, 5, NULL, NULL, NULL, NULL, '2018-2019', NULL, NULL, NULL, 0, NULL, '2025-10-28 15:30:20', '2025-10-28 15:30:20'),
(15, 33, 3, 5, NULL, NULL, NULL, NULL, '2018-2019', NULL, NULL, NULL, 0, NULL, '2025-10-28 15:30:20', '2025-10-28 15:30:20'),
(16, 34, 3, 5, NULL, NULL, NULL, NULL, '2018-2019', NULL, NULL, NULL, 0, NULL, '2025-10-28 15:30:20', '2025-10-28 15:30:20'),
(17, 35, 4, 6, NULL, NULL, NULL, NULL, '2018-2019', NULL, NULL, NULL, 0, NULL, '2025-10-28 15:30:20', '2025-10-28 15:30:20'),
(18, 36, 4, 6, NULL, NULL, NULL, NULL, '2018-2019', NULL, NULL, NULL, 0, NULL, '2025-10-28 15:30:20', '2025-10-28 15:30:20'),
(19, 37, 4, 6, NULL, NULL, NULL, NULL, '2018-2019', NULL, NULL, NULL, 0, NULL, '2025-10-28 15:30:20', '2025-10-28 15:30:20'),
(20, 38, 5, 7, NULL, NULL, NULL, NULL, '2018-2019', NULL, NULL, NULL, 0, NULL, '2025-10-28 15:30:21', '2025-10-28 15:30:21'),
(21, 39, 5, 7, NULL, NULL, NULL, NULL, '2018-2019', NULL, NULL, NULL, 0, NULL, '2025-10-28 15:30:21', '2025-10-28 15:30:21'),
(22, 40, 5, 7, NULL, NULL, NULL, NULL, '2018-2019', NULL, NULL, NULL, 0, NULL, '2025-10-28 15:30:21', '2025-10-28 15:30:21'),
(23, 41, 6, 8, NULL, NULL, NULL, NULL, '2018-2019', NULL, NULL, NULL, 0, NULL, '2025-10-28 15:30:21', '2025-10-28 15:30:21'),
(24, 42, 6, 8, NULL, NULL, NULL, NULL, '2018-2019', NULL, NULL, NULL, 0, NULL, '2025-10-28 15:30:21', '2025-10-28 15:30:21'),
(25, 43, 6, 8, NULL, NULL, NULL, NULL, '2018-2019', NULL, NULL, NULL, 0, NULL, '2025-10-28 15:30:21', '2025-10-28 15:30:21'),
(26, 44, 7, 9, NULL, NULL, NULL, NULL, '2018-2019', NULL, NULL, NULL, 0, NULL, '2025-10-28 15:30:22', '2025-10-28 15:30:22'),
(27, 45, 7, 9, NULL, NULL, NULL, NULL, '2018-2019', NULL, NULL, NULL, 0, NULL, '2025-10-28 15:30:22', '2025-10-28 15:30:22'),
(28, 46, 7, 9, NULL, NULL, NULL, NULL, '2018-2019', NULL, NULL, NULL, 0, NULL, '2025-10-28 15:30:22', '2025-10-28 15:30:22'),
(29, 47, 8, 10, NULL, NULL, NULL, NULL, '2018-2019', NULL, NULL, NULL, 0, NULL, '2025-10-28 15:30:22', '2025-10-28 15:30:22'),
(30, 48, 8, 10, NULL, NULL, NULL, NULL, '2018-2019', NULL, NULL, NULL, 0, NULL, '2025-10-28 15:30:22', '2025-10-28 15:30:22'),
(31, 49, 8, 10, NULL, NULL, NULL, NULL, '2018-2019', NULL, NULL, NULL, 0, NULL, '2025-10-28 15:30:22', '2025-10-28 15:30:22'),
(32, 50, 9, 11, NULL, NULL, NULL, NULL, '2018-2019', NULL, NULL, NULL, 0, NULL, '2025-10-28 15:30:23', '2025-10-28 15:30:23'),
(33, 51, 9, 11, NULL, NULL, NULL, NULL, '2018-2019', NULL, NULL, NULL, 0, NULL, '2025-10-28 15:30:23', '2025-10-28 15:30:23'),
(34, 52, 9, 11, NULL, NULL, NULL, NULL, '2018-2019', NULL, NULL, NULL, 0, NULL, '2025-10-28 15:30:23', '2025-10-28 15:30:23'),
(35, 53, 10, 12, NULL, NULL, NULL, NULL, '2018-2019', NULL, NULL, NULL, 0, NULL, '2025-10-28 15:30:23', '2025-10-28 15:30:23'),
(36, 54, 10, 12, NULL, NULL, NULL, NULL, '2018-2019', NULL, NULL, NULL, 0, NULL, '2025-10-28 15:30:23', '2025-10-28 15:30:23'),
(37, 55, 10, 12, NULL, NULL, NULL, NULL, '2018-2019', NULL, NULL, NULL, 0, NULL, '2025-10-28 15:30:23', '2025-10-28 15:30:23');

-- --------------------------------------------------------

--
-- Table structure for table `study_materials`
--

CREATE TABLE `study_materials` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `file_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_size` bigint NOT NULL,
  `file_type` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject_id` int UNSIGNED DEFAULT NULL,
  `my_class_id` int UNSIGNED DEFAULT NULL,
  `uploaded_by` int UNSIGNED NOT NULL,
  `is_public` tinyint(1) NOT NULL DEFAULT '1',
  `download_count` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subjects`
--

CREATE TABLE `subjects` (
  `id` int UNSIGNED NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `my_class_id` int UNSIGNED NOT NULL,
  `teacher_id` int UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `subjects`
--

INSERT INTO `subjects` (`id`, `name`, `slug`, `my_class_id`, `teacher_id`, `created_at`, `updated_at`) VALUES
(1, 'English Language', 'Eng', 1, 3, NULL, NULL),
(2, 'Mathematics', 'Math', 1, 3, NULL, NULL),
(3, 'English Language', 'Eng', 2, 3, NULL, NULL),
(4, 'Mathematics', 'Math', 2, 3, NULL, NULL),
(5, 'English Language', 'Eng', 3, 3, NULL, NULL),
(6, 'Mathematics', 'Math', 3, 3, NULL, NULL),
(7, 'English Language', 'Eng', 4, 3, NULL, NULL),
(8, 'Mathematics', 'Math', 4, 3, NULL, NULL),
(9, 'English Language', 'Eng', 5, 3, NULL, NULL),
(10, 'Mathematics', 'Math', 5, 3, NULL, NULL),
(11, 'English Language', 'Eng', 6, 3, NULL, NULL),
(12, 'Mathematics', 'Math', 6, 3, NULL, NULL),
(13, 'English Language', 'Eng', 7, 3, NULL, NULL),
(14, 'Mathematics', 'Math', 7, 3, NULL, NULL),
(15, 'English Language', 'Eng', 8, 3, NULL, NULL),
(16, 'Mathematics', 'Math', 8, 3, NULL, NULL),
(17, 'English Language', 'Eng', 9, 3, NULL, NULL),
(18, 'Mathematics', 'Math', 9, 3, NULL, NULL),
(19, 'English Language', 'Eng', 10, 3, NULL, NULL),
(20, 'Mathematics', 'Math', 10, 3, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `time_slots`
--

CREATE TABLE `time_slots` (
  `id` int UNSIGNED NOT NULL,
  `ttr_id` int UNSIGNED NOT NULL,
  `hour_from` tinyint NOT NULL,
  `min_from` varchar(2) COLLATE utf8mb4_unicode_ci NOT NULL,
  `meridian_from` varchar(2) COLLATE utf8mb4_unicode_ci NOT NULL,
  `hour_to` tinyint NOT NULL,
  `min_to` varchar(2) COLLATE utf8mb4_unicode_ci NOT NULL,
  `meridian_to` varchar(2) COLLATE utf8mb4_unicode_ci NOT NULL,
  `time_from` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `time_to` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `timestamp_from` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `timestamp_to` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `full` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `time_tables`
--

CREATE TABLE `time_tables` (
  `id` int UNSIGNED NOT NULL,
  `ttr_id` int UNSIGNED NOT NULL,
  `ts_id` int UNSIGNED NOT NULL,
  `subject_id` int UNSIGNED DEFAULT NULL,
  `exam_date` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `timestamp_from` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `timestamp_to` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `day` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `day_num` tinyint DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `time_table_records`
--

CREATE TABLE `time_table_records` (
  `id` int UNSIGNED NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `my_class_id` int UNSIGNED NOT NULL,
  `exam_id` int UNSIGNED DEFAULT NULL,
  `year` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `dob` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gender` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `photo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'http://localhost:8000/global_assets/images/user.png',
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone2` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bg_id` int UNSIGNED DEFAULT NULL,
  `state_id` int UNSIGNED DEFAULT NULL,
  `lga_id` int UNSIGNED DEFAULT NULL,
  `nal_id` int UNSIGNED DEFAULT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `code`, `username`, `user_type`, `dob`, `gender`, `photo`, `phone`, `phone2`, `bg_id`, `state_id`, `lga_id`, `nal_id`, `address`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'CJ Inspired', 'cj@cj.com', 'RDME2XORNP', 'cj', 'super_admin', NULL, NULL, 'http://localhost:8000/global_assets/images/user.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '$2y$10$f21ZNLtMgK.DhtkHTbR32uO98Cl.y3KrpSY4GWUIo4eEXWLBcMsQG', 'xJe5TigVe17aQm1io18ruWchFoPaYIhY901oPcUPQAEgKvVXfRv7AQELmuDI', NULL, NULL),
(2, 'Admin KORA', 'admin@admin.com', '18USG5TJSM', 'admin', 'admin', NULL, NULL, 'http://localhost:8000/global_assets/images/user.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '$2y$10$f21ZNLtMgK.DhtkHTbR32uO98Cl.y3KrpSY4GWUIo4eEXWLBcMsQG', 'vwSJcMgaxc', NULL, NULL),
(3, 'Teacher Chike', 'teacher@teacher.com', '72TPOITUIH', 'teacher', 'teacher', NULL, NULL, 'http://localhost:8000/global_assets/images/user.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '$2y$10$f21ZNLtMgK.DhtkHTbR32uO98Cl.y3KrpSY4GWUIo4eEXWLBcMsQG', 'JFf9x5aFuk', NULL, NULL),
(4, 'Parent Kaba', 'parent@parent.com', 'OAZNPJT1FN', 'parent', 'parent', NULL, NULL, 'http://localhost:8000/global_assets/images/user.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '$2y$10$f21ZNLtMgK.DhtkHTbR32uO98Cl.y3KrpSY4GWUIo4eEXWLBcMsQG', 'X1e995gTPQ', NULL, NULL),
(5, 'Accountant Jeff', 'accountant@accountant.com', 'NSZXPASAVM', 'accountant', 'accountant', NULL, NULL, 'http://localhost:8000/global_assets/images/user.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '$2y$10$f21ZNLtMgK.DhtkHTbR32uO98Cl.y3KrpSY4GWUIo4eEXWLBcMsQG', 'ySkF5EpWoO', NULL, NULL),
(6, 'Librarian Marie', 'librarian@librarian.com', 'AYPWZZKZFQ', 'librarian', 'librarian', NULL, NULL, 'http://localhost:8000/global_assets/images/user.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '$2y$10$f21ZNLtMgK.DhtkHTbR32uO98Cl.y3KrpSY4GWUIo4eEXWLBcMsQG', '5c66wLjRfc', NULL, NULL),
(7, 'Admin 1', 'admin1@admin.com', 'PBSKN47ZXC', 'admin1', 'admin', NULL, NULL, 'http://localhost:8000/global_assets/images/user.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '$2y$10$YucO16bU8IDeWbqD3gwXAuxPnjXLqMfCabRjfTtsLn2PagmkOGDBm', '5COFFKgZXJ', NULL, NULL),
(8, 'Teacher 1', 'teacher1@teacher.com', 'FHFSHCDNM4', 'teacher1', 'teacher', NULL, NULL, 'http://localhost:8000/global_assets/images/user.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '$2y$10$j0We9EOWHEIJjQxFbYUgyOHerMyFdwv2g65UIoHd3vFmg7QaqNlOu', 'JboUtPnbGP', NULL, NULL),
(9, 'Accountant 1', 'accountant1@accountant.com', 'KBAZHDFRQX', 'accountant1', 'accountant', NULL, NULL, 'http://localhost:8000/global_assets/images/user.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '$2y$10$3XXfMIIGINhn81EF.rU44edqoHaKqCYg0K0zDAqavXKBmuW51hufq', 'fakBdN3dOe', NULL, NULL),
(10, 'Parent 1', 'parent1@parent.com', 'ZM14XVUAND', 'parent1', 'parent', NULL, NULL, 'http://localhost:8000/global_assets/images/user.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '$2y$10$vdHdKRLWuZs1HvbydXHyLOSN39BN97Ul5eKiCEnA48vQBCwcpNV0i', 'dd5awWhX7c', NULL, NULL),
(11, 'Admin 2', 'admin2@admin.com', 'A8P3YDGQTB', 'admin2', 'admin', NULL, NULL, 'http://localhost:8000/global_assets/images/user.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '$2y$10$LTS/hXUHZj3OWnutaquz6enWCw4Kt3pUNBbxIflpuQxxHW/nM.qCy', 'eUZUqTllFC', NULL, NULL),
(12, 'Teacher 2', 'teacher2@teacher.com', 'BXU2TD40B3', 'teacher2', 'teacher', NULL, NULL, 'http://localhost:8000/global_assets/images/user.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '$2y$10$.F6xTNSELLs8tlmDqnEESOQhS.xOHn2yf4AqnkX8zw1hb7wGoFaUm', 'SeGhEPk9dH', NULL, NULL),
(13, 'Accountant 2', 'accountant2@accountant.com', 'UKG7GKOKLM', 'accountant2', 'accountant', NULL, NULL, 'http://localhost:8000/global_assets/images/user.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '$2y$10$w4/8BMAwKK1rd4/SQYEFVeSiRjw/w9hKsj0LRi3Z/HMkHkMDsNNs6', 'CVyxo0mAsD', NULL, NULL),
(14, 'Parent 2', 'parent2@parent.com', 'X2W7QOPYY5', 'parent2', 'parent', NULL, NULL, 'http://localhost:8000/global_assets/images/user.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '$2y$10$JoGr3odcq8NfAsxM6tlibekTEGxSbOSYDEDzIBPLllq4RZIQRfOX2', 'JYjm7jC0sr', NULL, NULL),
(15, 'Admin 3', 'admin3@admin.com', 'OSSUA8LUS2', 'admin3', 'admin', NULL, NULL, 'http://localhost:8000/global_assets/images/user.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '$2y$10$fk/1xJImtpsrUT3HkCgPk.4w8AzfgCmqlwPa/kDQvYhfbA62cQGqi', 'sq9hrJ2gWh', NULL, NULL),
(16, 'Teacher 3', 'teacher3@teacher.com', 'JKSMLGGUU6', 'teacher3', 'teacher', NULL, NULL, 'http://localhost:8000/global_assets/images/user.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '$2y$10$Zh1gSKvvMUxLCN1UyH8q3en6R8w9NgnmgWYtrq/k2MxsSQEphvZcC', 'UHECjhK9ng', NULL, NULL),
(17, 'Accountant 3', 'accountant3@accountant.com', 'LYU1OBZ7BI', 'accountant3', 'accountant', NULL, NULL, 'http://localhost:8000/global_assets/images/user.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '$2y$10$oQFbxTnH7W5H95n631ZcY.n1HpB26KELnV8A2S4dmL39Bz8gU38Je', '8YXj1X5e9X', NULL, NULL),
(18, 'Parent 3', 'parent3@parent.com', 'QQAHFMKWF5', 'parent3', 'parent', NULL, NULL, 'http://localhost:8000/global_assets/images/user.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '$2y$10$WzH6mHsn4x3Zm6qUXWN67.PvJyFhi5aHSIaiIBgBfvmqun9PyyBG2', '0vnydxGLbw', NULL, NULL),
(19, 'Student CJ', 'student@student.com', 'P1MFFOXGFB', 'student', 'student', NULL, NULL, 'http://localhost:8000/global_assets/images/user.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '$2y$10$vZOo5ksWfW8uSyirxXJXQ.8JHGO.7jqZpHAIHdsQkP6ZiwYVxgBa.', 'jmZjnMwnFo', '2025-10-28 15:30:17', '2025-10-28 15:30:17'),
(20, 'Kyle Lehner', 'bkozey@example.com', 'WUVGJVME1O', 'jakob56', 'student', NULL, NULL, 'http://localhost:8000/global_assets/images/user.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '$2y$10$LjnsSWm.kiS9gaulzLX8xe1veZPKgqx3mq2dKS4U3xM7.9dm0ruHS', 'AihzEptyoe', '2025-10-28 15:30:17', '2025-10-28 15:30:17'),
(21, 'Dr. Amina Boehm', 'runte.julia@example.com', '5KX2XCWFDB', 'kohara', 'student', NULL, NULL, 'http://localhost:8000/global_assets/images/user.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '$2y$10$LjnsSWm.kiS9gaulzLX8xe1veZPKgqx3mq2dKS4U3xM7.9dm0ruHS', 'kwQy8Yb11u', '2025-10-28 15:30:18', '2025-10-28 15:30:18'),
(22, 'Prof. Unique Witting', 'baumbach.troy@example.com', 'CIWNAUE2QG', 'paltenwerth', 'student', NULL, NULL, 'http://localhost:8000/global_assets/images/user.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '$2y$10$LjnsSWm.kiS9gaulzLX8xe1veZPKgqx3mq2dKS4U3xM7.9dm0ruHS', 'avci9LQKEn', '2025-10-28 15:30:18', '2025-10-28 15:30:18'),
(23, 'Demond Kirlin', 'xjast@example.org', '49CQJZ2T1J', 'evalyn36', 'student', NULL, NULL, 'http://localhost:8000/global_assets/images/user.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '$2y$10$UefMHfqJuHJQHoQAOMC/a.kuZABQw47.LKjEWiD4lUS2shQeNMSu.', 'TbScoo1XP2', '2025-10-28 15:30:18', '2025-10-28 15:30:18'),
(24, 'Eric Franecki', 'mosinski@example.org', 'CSSZPU6JZ9', 'jkertzmann', 'student', NULL, NULL, 'http://localhost:8000/global_assets/images/user.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '$2y$10$UefMHfqJuHJQHoQAOMC/a.kuZABQw47.LKjEWiD4lUS2shQeNMSu.', 'OcvMcKY7tj', '2025-10-28 15:30:18', '2025-10-28 15:30:18'),
(25, 'Ara Conn', 'reilly.roman@example.org', 'IBHDDWGEH3', 'turcotte.alene', 'student', NULL, NULL, 'http://localhost:8000/global_assets/images/user.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '$2y$10$UefMHfqJuHJQHoQAOMC/a.kuZABQw47.LKjEWiD4lUS2shQeNMSu.', 'Zw6iM9wu6W', '2025-10-28 15:30:18', '2025-10-28 15:30:18'),
(26, 'Orval Goldner', 'tremblay.nathaniel@example.net', 'ZAVTZYR6O1', 'langworth.fletcher', 'student', NULL, NULL, 'http://localhost:8000/global_assets/images/user.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '$2y$10$e7XQy8Vl6vJRj2DBZ9jKaObVYFDGtj9tzRKzo0uwa99jrIrvPWej6', 'ckIcRFOt5P', '2025-10-28 15:30:19', '2025-10-28 15:30:19'),
(27, 'Prof. Lizzie Satterfield', 'timmy.denesik@example.net', 'JJDNFSBVDF', 'jaron.waters', 'student', NULL, NULL, 'http://localhost:8000/global_assets/images/user.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '$2y$10$e7XQy8Vl6vJRj2DBZ9jKaObVYFDGtj9tzRKzo0uwa99jrIrvPWej6', '2NX8Rd6NKx', '2025-10-28 15:30:19', '2025-10-28 15:30:19'),
(28, 'Prof. Austin Lang IV', 'wisoky.efrain@example.com', 'FYOK4OGKO3', 'bulah.lebsack', 'student', NULL, NULL, 'http://localhost:8000/global_assets/images/user.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '$2y$10$e7XQy8Vl6vJRj2DBZ9jKaObVYFDGtj9tzRKzo0uwa99jrIrvPWej6', 'OuSUszUFGf', '2025-10-28 15:30:19', '2025-10-28 15:30:19'),
(29, 'Lennie Daniel', 'isac96@example.net', 'AWFDCQEHHY', 'urban.simonis', 'student', NULL, NULL, 'http://localhost:8000/global_assets/images/user.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '$2y$10$4brsDZMjvbucnpe/Twj/Nelfge2o7RKoOTE6qnxTVrbXcWatw5tJ.', 'On1ghJYOiv', '2025-10-28 15:30:19', '2025-10-28 15:30:19'),
(30, 'Mr. Royal Carter', 'alayna.reilly@example.net', 'HQSD83CDZZ', 'hanna20', 'student', NULL, NULL, 'http://localhost:8000/global_assets/images/user.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '$2y$10$4brsDZMjvbucnpe/Twj/Nelfge2o7RKoOTE6qnxTVrbXcWatw5tJ.', 'aIC5mP9iXm', '2025-10-28 15:30:19', '2025-10-28 15:30:19'),
(31, 'Prof. Makayla Welch', 'itzel46@example.net', 'O6UDXJRGRV', 'bwaelchi', 'student', NULL, NULL, 'http://localhost:8000/global_assets/images/user.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '$2y$10$4brsDZMjvbucnpe/Twj/Nelfge2o7RKoOTE6qnxTVrbXcWatw5tJ.', 'Cay6lPz1vB', '2025-10-28 15:30:19', '2025-10-28 15:30:19'),
(32, 'Lila Bartoletti', 'mable.will@example.org', 'NWTSUEB9RN', 'jamison19', 'student', NULL, NULL, 'http://localhost:8000/global_assets/images/user.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '$2y$10$atZolMOsijXgXUJzmAwWrOa3BBDa.X.7aMQQV9w3vasdIul6LM1/K', 'S6fYLzoZei', '2025-10-28 15:30:20', '2025-10-28 15:30:20'),
(33, 'Prof. Zechariah Kub', 'lincoln.trantow@example.org', '7BX7YP2FST', 'reynold29', 'student', NULL, NULL, 'http://localhost:8000/global_assets/images/user.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '$2y$10$atZolMOsijXgXUJzmAwWrOa3BBDa.X.7aMQQV9w3vasdIul6LM1/K', 'cVSTaj5fYY', '2025-10-28 15:30:20', '2025-10-28 15:30:20'),
(34, 'Tanya Dare', 'price.conn@example.net', '9AB2EMHQ4D', 'xschimmel', 'student', NULL, NULL, 'http://localhost:8000/global_assets/images/user.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '$2y$10$atZolMOsijXgXUJzmAwWrOa3BBDa.X.7aMQQV9w3vasdIul6LM1/K', 'qorVkBpRc1', '2025-10-28 15:30:20', '2025-10-28 15:30:20'),
(35, 'Earnest Leannon II', 'aisha.senger@example.org', 'MPBXYEMOML', 'miller.kaylee', 'student', NULL, NULL, 'http://localhost:8000/global_assets/images/user.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '$2y$10$2tiwETpo17tWVaetChldh.M3iWy89zQ/Va4JaWVR68zNbt/w5KZju', 'O5jf7snfTt', '2025-10-28 15:30:20', '2025-10-28 15:30:20'),
(36, 'Raegan Lind', 'srosenbaum@example.net', 'K3L0LW4UH1', 'alowe', 'student', NULL, NULL, 'http://localhost:8000/global_assets/images/user.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '$2y$10$2tiwETpo17tWVaetChldh.M3iWy89zQ/Va4JaWVR68zNbt/w5KZju', 'kNSSxw72Vc', '2025-10-28 15:30:20', '2025-10-28 15:30:20'),
(37, 'Benedict Stark III', 'alanna66@example.org', 'SFMSNXT7LW', 'paolo.gorczany', 'student', NULL, NULL, 'http://localhost:8000/global_assets/images/user.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '$2y$10$2tiwETpo17tWVaetChldh.M3iWy89zQ/Va4JaWVR68zNbt/w5KZju', 'Yl9sFM0020', '2025-10-28 15:30:20', '2025-10-28 15:30:20'),
(38, 'Mr. Elijah Jerde V', 'devin.heller@example.org', 'XL2XW7MPGL', 'angelo20', 'student', NULL, NULL, 'http://localhost:8000/global_assets/images/user.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '$2y$10$FOjZhnWllQoT2iGjnd/BhOrrDHly6aMLfaxc1mCAj3xBA3u/pPHZS', 'KO5WJuMP6H', '2025-10-28 15:30:21', '2025-10-28 15:30:21'),
(39, 'Lexus Rath', 'opfannerstill@example.org', 'EXKFIKIXRZ', 'vernie17', 'student', NULL, NULL, 'http://localhost:8000/global_assets/images/user.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '$2y$10$FOjZhnWllQoT2iGjnd/BhOrrDHly6aMLfaxc1mCAj3xBA3u/pPHZS', 'XlHtDxSqun', '2025-10-28 15:30:21', '2025-10-28 15:30:21'),
(40, 'Valentina Stiedemann Sr.', 'clueilwitz@example.com', 'VQE1LAULWF', 'koepp.elissa', 'student', NULL, NULL, 'http://localhost:8000/global_assets/images/user.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '$2y$10$FOjZhnWllQoT2iGjnd/BhOrrDHly6aMLfaxc1mCAj3xBA3u/pPHZS', 'yLOikk1Nef', '2025-10-28 15:30:21', '2025-10-28 15:30:21'),
(41, 'Destin Corwin', 'blanche.connelly@example.org', 'HAEOPD71CQ', 'nelle.christiansen', 'student', NULL, NULL, 'http://localhost:8000/global_assets/images/user.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '$2y$10$qjQA.vP2Wib0aF2snBefnu9PBVtAjUtFhkeLBsxDxt2CJnD8/BNQq', 'XOxJjJVgHI', '2025-10-28 15:30:21', '2025-10-28 15:30:21'),
(42, 'Heber Leannon DVM', 'ova.ortiz@example.com', 'AN0JOABWKC', 'titus68', 'student', NULL, NULL, 'http://localhost:8000/global_assets/images/user.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '$2y$10$qjQA.vP2Wib0aF2snBefnu9PBVtAjUtFhkeLBsxDxt2CJnD8/BNQq', 'tT9vwnHYI4', '2025-10-28 15:30:21', '2025-10-28 15:30:21'),
(43, 'Mr. Wilfred DuBuque DVM', 'emmie.rosenbaum@example.com', 'C1FUGLCZJH', 'lhilpert', 'student', NULL, NULL, 'http://localhost:8000/global_assets/images/user.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '$2y$10$qjQA.vP2Wib0aF2snBefnu9PBVtAjUtFhkeLBsxDxt2CJnD8/BNQq', 'PGejeEzPzn', '2025-10-28 15:30:21', '2025-10-28 15:30:21'),
(44, 'Mallory Daugherty Jr.', 'kboyle@example.org', 'XIGLGP9KFY', 'sarai.spinka', 'student', NULL, NULL, 'http://localhost:8000/global_assets/images/user.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '$2y$10$E1duUkb9Um734uQbMKtxoOaORdLdEssKAXeeqT.Fe9MXm6aL8d3gK', 'YkWLTPrITE', '2025-10-28 15:30:22', '2025-10-28 15:30:22'),
(45, 'Saige Hamill', 'murazik.brennan@example.net', 'KBEQFMD2YM', 'yoconner', 'student', NULL, NULL, 'http://localhost:8000/global_assets/images/user.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '$2y$10$E1duUkb9Um734uQbMKtxoOaORdLdEssKAXeeqT.Fe9MXm6aL8d3gK', 'jF2tRBvshp', '2025-10-28 15:30:22', '2025-10-28 15:30:22'),
(46, 'Mrs. Pauline Collier', 'qframi@example.com', 'CGQ9PWB1QZ', 'murray34', 'student', NULL, NULL, 'http://localhost:8000/global_assets/images/user.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '$2y$10$E1duUkb9Um734uQbMKtxoOaORdLdEssKAXeeqT.Fe9MXm6aL8d3gK', 'yrXLxiuxhd', '2025-10-28 15:30:22', '2025-10-28 15:30:22'),
(47, 'Mr. Sanford Pagac', 'damon.lubowitz@example.org', 'BOLHHCKXMA', 'imelda.beer', 'student', NULL, NULL, 'http://localhost:8000/global_assets/images/user.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '$2y$10$nL2K3i9zV/CmfWNbbFyJ5umFJFztEKDoIw/TAv7G7FfEpXgCSV.re', 'eU8PXOaCwc', '2025-10-28 15:30:22', '2025-10-28 15:30:22'),
(48, 'Chanel Nolan IV', 'wwalsh@example.org', 'DUCNGUNBMZ', 'green.stuart', 'student', NULL, NULL, 'http://localhost:8000/global_assets/images/user.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '$2y$10$nL2K3i9zV/CmfWNbbFyJ5umFJFztEKDoIw/TAv7G7FfEpXgCSV.re', 'FDoh7bqmUO', '2025-10-28 15:30:22', '2025-10-28 15:30:22'),
(49, 'Willow Lindgren', 'troy66@example.net', 'Q9HE19KJIV', 'qhuel', 'student', NULL, NULL, 'http://localhost:8000/global_assets/images/user.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '$2y$10$nL2K3i9zV/CmfWNbbFyJ5umFJFztEKDoIw/TAv7G7FfEpXgCSV.re', 'bqOb714ieF', '2025-10-28 15:30:22', '2025-10-28 15:30:22'),
(50, 'Johathan Bayer DVM', 'herzog.kara@example.com', 'T9SWY03BWF', 'brittany.durgan', 'student', NULL, NULL, 'http://localhost:8000/global_assets/images/user.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '$2y$10$RoiDHTw/BMLeuMJaxOs3gum6IvkMMm4pgJ6KSnJ61zJYdtdV1h/Xu', 'LPq9dsZ6I7', '2025-10-28 15:30:23', '2025-10-28 15:30:23'),
(51, 'Clyde Cruickshank', 'ctowne@example.org', 'K21NFTQFCE', 'hettinger.braulio', 'student', NULL, NULL, 'http://localhost:8000/global_assets/images/user.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '$2y$10$RoiDHTw/BMLeuMJaxOs3gum6IvkMMm4pgJ6KSnJ61zJYdtdV1h/Xu', 'KUCJu7XBrO', '2025-10-28 15:30:23', '2025-10-28 15:30:23'),
(52, 'Mr. Orville Goldner MD', 'dayana93@example.net', 'QJ3OKCBXUE', 'vcassin', 'student', NULL, NULL, 'http://localhost:8000/global_assets/images/user.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '$2y$10$RoiDHTw/BMLeuMJaxOs3gum6IvkMMm4pgJ6KSnJ61zJYdtdV1h/Xu', 'wWEoyGnBYx', '2025-10-28 15:30:23', '2025-10-28 15:30:23'),
(53, 'Pauline Lueilwitz', 'tomas.hansen@example.org', 'Q1HBM9H0ES', 'conroy.maria', 'student', NULL, NULL, 'http://localhost:8000/global_assets/images/user.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '$2y$10$yNWLzNo.kfxqncLxmVkjbOFHlAfPr3kNp7BvO6EbWyEtOLc6AD/R6', '47mvZjDcSV', '2025-10-28 15:30:23', '2025-10-28 15:30:23'),
(54, 'Dr. Julie Littel IV', 'christy.gerlach@example.net', 'OPQ8RKOCJZ', 'caden.collins', 'student', NULL, NULL, 'http://localhost:8000/global_assets/images/user.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '$2y$10$yNWLzNo.kfxqncLxmVkjbOFHlAfPr3kNp7BvO6EbWyEtOLc6AD/R6', 'Jp6tTMVEpO', '2025-10-28 15:30:23', '2025-10-28 15:30:23'),
(55, 'Prof. Betsy Crooks V', 'florencio.medhurst@example.net', 'PY2FSXYOCF', 'hjacobs', 'student', NULL, NULL, 'http://localhost:8000/global_assets/images/user.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '$2y$10$yNWLzNo.kfxqncLxmVkjbOFHlAfPr3kNp7BvO6EbWyEtOLc6AD/R6', 'D1rRZzbQ7U', '2025-10-28 15:30:23', '2025-10-28 15:30:23'),
(56, 'Aurélie Fournier', 'etudiant1@example.com', 'STD0001', NULL, 'student', NULL, 'male', 'http://localhost:8000/global_assets/images/user.png', '+33 9 24 18 17 56', NULL, NULL, NULL, NULL, NULL, '57, place de Lebon\n31915 Thomas', '2025-10-28 15:30:24', '$2y$10$iRVOttnmPoAcbQQ4tDW7p.u1/8X.QNQEr0UB4yFKMkhyW.aEJ7yki', 'VTnI1dzlmU', '2025-10-28 15:30:24', '2025-10-28 15:30:24'),
(57, 'Océane Potier', 'etudiant2@example.com', 'STD0002', NULL, 'student', NULL, 'male', 'http://localhost:8000/global_assets/images/user.png', '07 30 23 00 46', NULL, NULL, NULL, NULL, NULL, '84, chemin Michel Leveque\n78899 Camus-sur-Louis', '2025-10-28 15:30:24', '$2y$10$r0kJV7a81TOoVNVOUxbL1OEv7hN6qVNavFIhZHrnHNmLZE0vzLUd6', 'eOmQ7Z5A71', '2025-10-28 15:30:24', '2025-10-28 15:30:24'),
(58, 'Maryse Guichard', 'etudiant3@example.com', 'STD0003', NULL, 'student', NULL, 'female', 'http://localhost:8000/global_assets/images/user.png', '+33 1 49 45 75 63', NULL, NULL, NULL, NULL, NULL, '979, rue Leduc\n20321 Traore-sur-Poulain', '2025-10-28 15:30:24', '$2y$10$a50LZMCu/bsilPzwEkg.Oe.3nozniRXgkgvknFjbcM9OkZlNUWLLi', 'svdfaVPA3J', '2025-10-28 15:30:24', '2025-10-28 15:30:24'),
(59, 'Sophie Perrier', 'etudiant4@example.com', 'STD0004', NULL, 'student', NULL, 'male', 'http://localhost:8000/global_assets/images/user.png', '04 46 19 91 17', NULL, NULL, NULL, NULL, NULL, '4, place Nicolas\n10857 Renard', '2025-10-28 15:30:24', '$2y$10$4grh/.R0dtZBkBOYoWIRDurt/vdymHwkzevoLEMM/4Q0UDV7OSMTG', '4jGfSzX9Wm', '2025-10-28 15:30:24', '2025-10-28 15:30:24'),
(60, 'Michel Carlier', 'etudiant5@example.com', 'STD0005', NULL, 'student', NULL, 'female', 'http://localhost:8000/global_assets/images/user.png', '0895998145', NULL, NULL, NULL, NULL, NULL, 'boulevard Agathe Pasquier\n95868 Fouquet-sur-Martin', '2025-10-28 15:30:24', '$2y$10$/eitFBVc3jzMaGKZQIjIE.srTiO9T4qWtNBuvJtng9JaCvMQ2JfsG', 'dSHA14YgRQ', '2025-10-28 15:30:24', '2025-10-28 15:30:24'),
(61, 'Jean Albert', 'etudiant6@example.com', 'STD0006', NULL, 'student', NULL, 'female', 'http://localhost:8000/global_assets/images/user.png', '01 94 70 31 28', NULL, NULL, NULL, NULL, NULL, '11, impasse de Barthelemy\n20793 Leleu-sur-Mer', '2025-10-28 15:30:24', '$2y$10$6cFuH5O3RVDu.rPQsiPFIuQCQJ8vtmA4ihQ81vKv7DmzgJsvitpAG', '09B8h1Bj9C', '2025-10-28 15:30:24', '2025-10-28 15:30:24'),
(62, 'Capucine Tessier', 'etudiant7@example.com', 'STD0007', NULL, 'student', NULL, 'male', 'http://localhost:8000/global_assets/images/user.png', '+33 (0)3 57 27 00 34', NULL, NULL, NULL, NULL, NULL, '94, chemin Morel\n77301 Brunet-sur-Briand', '2025-10-28 15:30:24', '$2y$10$v7mFFwoMv4GTuHXbUAEZ8eM38x9QfWPgRBhf0sIbaqhnNJUl2pnne', '9GGvDlz1cc', '2025-10-28 15:30:24', '2025-10-28 15:30:24'),
(63, 'Jacques Antoine', 'etudiant8@example.com', 'STD0008', NULL, 'student', NULL, 'male', 'http://localhost:8000/global_assets/images/user.png', '01 37 13 87 44', NULL, NULL, NULL, NULL, NULL, '31, place Charles Jacquet\n79167 Ribeiro', '2025-10-28 15:30:24', '$2y$10$ban313vwmZ8eUyg1MuvaO.hAFxn3BEmR2Bvdmlo53ZsPPG0CybUs.', 'ohYZsBPWZi', '2025-10-28 15:30:24', '2025-10-28 15:30:24'),
(64, 'Victoire Renault', 'etudiant9@example.com', 'STD0009', NULL, 'student', NULL, 'female', 'http://localhost:8000/global_assets/images/user.png', '0294951531', NULL, NULL, NULL, NULL, NULL, '59, impasse Begue\n79787 Grenierboeuf', '2025-10-28 15:30:25', '$2y$10$A5P3Yj7nYwbyV5iaagje1u0q156jOlggHneXaCXz7HAnJQN85zt.u', 'fziUrSiHpR', '2025-10-28 15:30:25', '2025-10-28 15:30:25'),
(65, 'Benoît Riou', 'etudiant10@example.com', 'STD0010', NULL, 'student', NULL, 'female', 'http://localhost:8000/global_assets/images/user.png', '+33 7 70 96 04 88', NULL, NULL, NULL, NULL, NULL, '840, place Mercier\n77404 Couturier', '2025-10-28 15:30:25', '$2y$10$D8iyPxt12HvpNpXpsRuOTeKB4n4HOAtDye14/i1USvl2YwoQWkMs.', 'kayTo52J0r', '2025-10-28 15:30:25', '2025-10-28 15:30:25'),
(66, 'Stéphane Moulin', 'etudiant11@example.com', 'STD0011', NULL, 'student', NULL, 'male', 'http://localhost:8000/global_assets/images/user.png', '05 53 93 89 13', NULL, NULL, NULL, NULL, NULL, '2, avenue Stéphanie Fernandes\n06292 Lopesboeuf', '2025-10-28 15:30:25', '$2y$10$Nyhwa8oEWAfWfD6zFfquY.Jd4ymLqCsuiFgqP5EQmJbkxC1pVfhdi', 'j0pJaZ1wyz', '2025-10-28 15:30:25', '2025-10-28 15:30:25'),
(67, 'Margot Leleu', 'etudiant12@example.com', 'STD0012', NULL, 'student', NULL, 'male', 'http://localhost:8000/global_assets/images/user.png', '+33 (0)9 02 21 70 53', NULL, NULL, NULL, NULL, NULL, '147, place Zacharie Herve\n94262 Didierdan', '2025-10-28 15:30:25', '$2y$10$Mf09L1Fn5E0u3N73M8W5FOrNXP18wZ0CdzdZp3dfg5dJUMvqYRrZi', 'Qr2Qzo6FRc', '2025-10-28 15:30:25', '2025-10-28 15:30:25'),
(68, 'Théophile Klein', 'etudiant13@example.com', 'STD0013', NULL, 'student', NULL, 'male', 'http://localhost:8000/global_assets/images/user.png', '+33 9 78 38 58 05', NULL, NULL, NULL, NULL, NULL, '65, place Tanguy\n40278 Baillydan', '2025-10-28 15:30:25', '$2y$10$Y9t/rlCPrQquDLxhY6H0je5hBqdS6AgtFNrYyBOW5KVGtdOyDIT/a', '2oj6PVMvDK', '2025-10-28 15:30:25', '2025-10-28 15:30:25'),
(69, 'Guy Leroux', 'etudiant14@example.com', 'STD0014', NULL, 'student', NULL, 'male', 'http://localhost:8000/global_assets/images/user.png', '0895926784', NULL, NULL, NULL, NULL, NULL, 'place Véronique Colin\n97960 Blondel-sur-Mer', '2025-10-28 15:30:25', '$2y$10$KfVrdbmXNMzazTidfGkJJ.HaaNg67QggzDM19msOzD.j5Dmr7n1FO', '8RLxnZlITG', '2025-10-28 15:30:25', '2025-10-28 15:30:25'),
(70, 'Philippine Rossi', 'etudiant15@example.com', 'STD0015', NULL, 'student', NULL, 'female', 'http://localhost:8000/global_assets/images/user.png', '0699666013', NULL, NULL, NULL, NULL, NULL, '4, avenue de Benoit\n89140 Lemaire', '2025-10-28 15:30:25', '$2y$10$D/Psmq72T2zvSbigBTFuh.aQoJhqGhLnD39GIjuEWQ2mmkHQW3fUm', 'OoIMtZMiv8', '2025-10-28 15:30:25', '2025-10-28 15:30:25'),
(71, 'Jacqueline Jourdan', 'etudiant16@example.com', 'STD0016', NULL, 'student', NULL, 'male', 'http://localhost:8000/global_assets/images/user.png', '+33 3 71 83 53 90', NULL, NULL, NULL, NULL, NULL, '66, rue Eugène Georges\n47912 Perret', '2025-10-28 15:30:26', '$2y$10$Wf5AgGQ2XYokjKaZBosT8.Jxyxjy.k4com/l5gB2gPcoBoQsp/Shm', 'wbY2b3w5tY', '2025-10-28 15:30:26', '2025-10-28 15:30:26'),
(72, 'Suzanne Boyer', 'etudiant17@example.com', 'STD0017', NULL, 'student', NULL, 'female', 'http://localhost:8000/global_assets/images/user.png', '+33 (0)2 37 87 93 01', NULL, NULL, NULL, NULL, NULL, 'place Amélie Menard\n99818 Lagarde-sur-Mer', '2025-10-28 15:30:26', '$2y$10$KrNspqyXXrRC/ZYQCqw8A.rLobMuPCX99tKm74c72Qb3/Li4ZG84e', 'h3sKtCWwEm', '2025-10-28 15:30:26', '2025-10-28 15:30:26'),
(73, 'Alexandria Maurice', 'etudiant18@example.com', 'STD0018', NULL, 'student', NULL, 'male', 'http://localhost:8000/global_assets/images/user.png', '0568562541', NULL, NULL, NULL, NULL, NULL, '68, rue Martinez\n69313 Garciaboeuf', '2025-10-28 15:30:26', '$2y$10$v7vYx57MSUSk2u1ohB/SRu.QOKV8B4N.wqoalvNZ/kf5kNFsKhfuG', 'BH7AJkD44W', '2025-10-28 15:30:26', '2025-10-28 15:30:26'),
(74, 'Camille Meunier', 'etudiant19@example.com', 'STD0019', NULL, 'student', NULL, 'female', 'http://localhost:8000/global_assets/images/user.png', '0637504652', NULL, NULL, NULL, NULL, NULL, '75, impasse de Hubert\n12004 Teixeira', '2025-10-28 15:30:26', '$2y$10$ifaA3ZxWc.eNEwPvuxDXQuz758Ulh9airYHu1gErw852OPCb4IhVK', 'cb3UkS8nnD', '2025-10-28 15:30:26', '2025-10-28 15:30:26'),
(75, 'Danielle Chretien', 'etudiant20@example.com', 'STD0020', NULL, 'student', NULL, 'female', 'http://localhost:8000/global_assets/images/user.png', '+33 (0)5 05 47 17 16', NULL, NULL, NULL, NULL, NULL, '18, boulevard Laurence Meunier\n21424 Carre', '2025-10-28 15:30:26', '$2y$10$C9A/pjAhZNm7Ap0iJEjPru5InAG/uWOUzBPPMed0Y/6hRdLoJuy.G', 'gGXvByTXmN', '2025-10-28 15:30:26', '2025-10-28 15:30:26'),
(76, 'Bedi Tshitsho', 'tshitshob@gmail.com', 'YQV7EDZKKC', 'CJIA/P/2025/69108', 'student', '04/09/1995', 'Male', 'http://localhost:8000/storage/uploads/student/YQV7EDZKKC/photo.png', '0812380589', '0812380589', 2, 5, 91, 43, 'Mon adresse', NULL, '$2y$10$uN4.7ccfRDepsj4AJx.AduAZBRvdU.wJED0QTMJ6/jn0ybOeNzdIy', 'MqvoVFerhevpgpKjfRKALDlrrFcO7hMhMLEx4nXaOHWNxJIFMDkzySskaxUz', '2025-10-29 10:21:06', '2025-10-29 10:21:06'),
(77, 'MrGentil', 'bedi@totem-experience.com', 'RF4NLM4VXL', 'CJIA/STAFF/2000/04/5690', 'librarian', NULL, 'Male', 'http://localhost:8000/storage/uploads/librarian/RF4NLM4VXL/photo.jpg', '0820450035', '0820450035', 1, 3, 39, 43, 'Son adresse', NULL, '$2y$10$I27Erbo2W/mACo4y6FxIDeImP5.vPa6a2Dnl5Dr6ttA3bMhRfzN86', NULL, '2025-10-29 11:33:34', '2025-10-29 11:33:34');

-- --------------------------------------------------------

--
-- Table structure for table `user_types`
--

CREATE TABLE `user_types` (
  `id` int UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `level` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_types`
--

INSERT INTO `user_types` (`id`, `title`, `name`, `level`, `created_at`, `updated_at`) VALUES
(1, 'accountant', 'Accountant', '5', NULL, NULL),
(2, 'parent', 'Parent', '4', NULL, NULL),
(3, 'teacher', 'Teacher', '3', NULL, NULL),
(4, 'admin', 'Admin', '2', NULL, NULL),
(5, 'super_admin', 'Super Admin', '1', NULL, NULL),
(6, 'librarian', 'Librarian', '6', NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `assignments`
--
ALTER TABLE `assignments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `assignments_subject_id_foreign` (`subject_id`),
  ADD KEY `assignments_my_class_id_foreign` (`my_class_id`),
  ADD KEY `assignments_section_id_foreign` (`section_id`),
  ADD KEY `assignments_teacher_id_foreign` (`teacher_id`);

--
-- Indexes for table `assignment_submissions`
--
ALTER TABLE `assignment_submissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `assignment_submissions_assignment_id_student_id_unique` (`assignment_id`,`student_id`),
  ADD KEY `assignment_submissions_student_id_foreign` (`student_id`);

--
-- Indexes for table `attendances`
--
ALTER TABLE `attendances`
  ADD PRIMARY KEY (`id`),
  ADD KEY `attendances_student_id_attendance_date_index` (`student_id`,`attendance_date`);

--
-- Indexes for table `blood_groups`
--
ALTER TABLE `blood_groups`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`id`),
  ADD KEY `books_my_class_id_foreign` (`my_class_id`);

--
-- Indexes for table `book_categories`
--
ALTER TABLE `book_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `book_requests`
--
ALTER TABLE `book_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `book_requests_book_id_foreign` (`book_id`),
  ADD KEY `book_requests_student_id_foreign` (`student_id`),
  ADD KEY `book_requests_approved_by_foreign` (`approved_by`);

--
-- Indexes for table `class_types`
--
ALTER TABLE `class_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dorms`
--
ALTER TABLE `dorms`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `dorms_name_unique` (`name`);

--
-- Indexes for table `exams`
--
ALTER TABLE `exams`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `exams_term_year_unique` (`term`,`year`);

--
-- Indexes for table `exam_records`
--
ALTER TABLE `exam_records`
  ADD PRIMARY KEY (`id`),
  ADD KEY `exam_records_exam_id_foreign` (`exam_id`),
  ADD KEY `exam_records_my_class_id_foreign` (`my_class_id`),
  ADD KEY `exam_records_student_id_foreign` (`student_id`),
  ADD KEY `exam_records_section_id_foreign` (`section_id`);

--
-- Indexes for table `grades`
--
ALTER TABLE `grades`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `grades_name_class_type_id_remark_unique` (`name`,`class_type_id`,`remark`),
  ADD KEY `grades_class_type_id_foreign` (`class_type_id`);

--
-- Indexes for table `learning_materials`
--
ALTER TABLE `learning_materials`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `lgas`
--
ALTER TABLE `lgas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lgas_state_id_foreign` (`state_id`);

--
-- Indexes for table `marks`
--
ALTER TABLE `marks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `marks_student_id_foreign` (`student_id`),
  ADD KEY `marks_my_class_id_foreign` (`my_class_id`),
  ADD KEY `marks_section_id_foreign` (`section_id`),
  ADD KEY `marks_subject_id_foreign` (`subject_id`),
  ADD KEY `marks_exam_id_foreign` (`exam_id`),
  ADD KEY `marks_grade_id_foreign` (`grade_id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `messages_sender_id_foreign` (`sender_id`),
  ADD KEY `messages_receiver_id_foreign` (`receiver_id`);

--
-- Indexes for table `message_recipients`
--
ALTER TABLE `message_recipients`
  ADD PRIMARY KEY (`id`),
  ADD KEY `message_recipients_message_id_foreign` (`message_id`),
  ADD KEY `message_recipients_recipient_id_foreign` (`recipient_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `my_classes`
--
ALTER TABLE `my_classes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `my_classes_class_type_id_name_unique` (`class_type_id`,`name`);

--
-- Indexes for table `nationalities`
--
ALTER TABLE `nationalities`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notices`
--
ALTER TABLE `notices`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notices_created_by_foreign` (`created_by`),
  ADD KEY `notices_is_active_start_date_end_date_index` (`is_active`,`start_date`,`end_date`),
  ADD KEY `notices_target_audience_index` (`target_audience`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `payments_ref_no_unique` (`ref_no`),
  ADD KEY `payments_my_class_id_foreign` (`my_class_id`),
  ADD KEY `payments_class_id_foreign` (`class_id`);

--
-- Indexes for table `payment_records`
--
ALTER TABLE `payment_records`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `payment_records_ref_no_unique` (`ref_no`),
  ADD KEY `payment_records_payment_id_foreign` (`payment_id`),
  ADD KEY `payment_records_student_id_foreign` (`student_id`);

--
-- Indexes for table `pins`
--
ALTER TABLE `pins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `pins_code_unique` (`code`),
  ADD KEY `pins_user_id_foreign` (`user_id`),
  ADD KEY `pins_student_id_foreign` (`student_id`);

--
-- Indexes for table `promotions`
--
ALTER TABLE `promotions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `promotions_student_id_foreign` (`student_id`),
  ADD KEY `promotions_from_class_foreign` (`from_class`),
  ADD KEY `promotions_from_section_foreign` (`from_section`),
  ADD KEY `promotions_to_section_foreign` (`to_section`),
  ADD KEY `promotions_to_class_foreign` (`to_class`);

--
-- Indexes for table `receipts`
--
ALTER TABLE `receipts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `receipts_pr_id_foreign` (`pr_id`);

--
-- Indexes for table `school_events`
--
ALTER TABLE `school_events`
  ADD PRIMARY KEY (`id`),
  ADD KEY `school_events_created_by_foreign` (`created_by`),
  ADD KEY `school_events_event_date_target_audience_index` (`event_date`,`target_audience`),
  ADD KEY `school_events_event_type_index` (`event_type`);

--
-- Indexes for table `sections`
--
ALTER TABLE `sections`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `sections_name_my_class_id_unique` (`name`,`my_class_id`),
  ADD KEY `sections_my_class_id_foreign` (`my_class_id`),
  ADD KEY `sections_teacher_id_foreign` (`teacher_id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `skills`
--
ALTER TABLE `skills`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `staff_records`
--
ALTER TABLE `staff_records`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `staff_records_code_unique` (`code`),
  ADD KEY `staff_records_user_id_foreign` (`user_id`);

--
-- Indexes for table `states`
--
ALTER TABLE `states`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `students_admission_number_unique` (`admission_number`),
  ADD KEY `students_user_id_foreign` (`user_id`),
  ADD KEY `students_class_id_foreign` (`class_id`);

--
-- Indexes for table `student_attendances`
--
ALTER TABLE `student_attendances`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `student_records`
--
ALTER TABLE `student_records`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `student_records_adm_no_unique` (`adm_no`),
  ADD KEY `student_records_user_id_foreign` (`user_id`),
  ADD KEY `student_records_my_class_id_foreign` (`my_class_id`),
  ADD KEY `student_records_section_id_foreign` (`section_id`),
  ADD KEY `student_records_my_parent_id_foreign` (`my_parent_id`),
  ADD KEY `student_records_dorm_id_foreign` (`dorm_id`);

--
-- Indexes for table `study_materials`
--
ALTER TABLE `study_materials`
  ADD PRIMARY KEY (`id`),
  ADD KEY `study_materials_subject_id_foreign` (`subject_id`),
  ADD KEY `study_materials_uploaded_by_foreign` (`uploaded_by`),
  ADD KEY `study_materials_my_class_id_subject_id_index` (`my_class_id`,`subject_id`),
  ADD KEY `study_materials_is_public_index` (`is_public`);

--
-- Indexes for table `subjects`
--
ALTER TABLE `subjects`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `subjects_my_class_id_name_unique` (`my_class_id`,`name`),
  ADD KEY `subjects_teacher_id_foreign` (`teacher_id`);

--
-- Indexes for table `time_slots`
--
ALTER TABLE `time_slots`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `time_slots_timestamp_from_timestamp_to_ttr_id_unique` (`timestamp_from`,`timestamp_to`,`ttr_id`),
  ADD KEY `time_slots_ttr_id_foreign` (`ttr_id`);

--
-- Indexes for table `time_tables`
--
ALTER TABLE `time_tables`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `time_tables_ttr_id_ts_id_day_unique` (`ttr_id`,`ts_id`,`day`),
  ADD UNIQUE KEY `time_tables_ttr_id_ts_id_exam_date_unique` (`ttr_id`,`ts_id`,`exam_date`),
  ADD KEY `time_tables_ts_id_foreign` (`ts_id`),
  ADD KEY `time_tables_subject_id_foreign` (`subject_id`);

--
-- Indexes for table `time_table_records`
--
ALTER TABLE `time_table_records`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `time_table_records_name_unique` (`name`),
  ADD UNIQUE KEY `time_table_records_my_class_id_exam_id_year_unique` (`my_class_id`,`exam_id`,`year`),
  ADD KEY `time_table_records_exam_id_foreign` (`exam_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_code_unique` (`code`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_username_unique` (`username`),
  ADD KEY `users_state_id_foreign` (`state_id`),
  ADD KEY `users_lga_id_foreign` (`lga_id`),
  ADD KEY `users_bg_id_foreign` (`bg_id`),
  ADD KEY `users_nal_id_foreign` (`nal_id`);

--
-- Indexes for table `user_types`
--
ALTER TABLE `user_types`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `assignments`
--
ALTER TABLE `assignments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `assignment_submissions`
--
ALTER TABLE `assignment_submissions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=263;

--
-- AUTO_INCREMENT for table `attendances`
--
ALTER TABLE `attendances`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `blood_groups`
--
ALTER TABLE `blood_groups`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `books`
--
ALTER TABLE `books`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `book_categories`
--
ALTER TABLE `book_categories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `book_requests`
--
ALTER TABLE `book_requests`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;

--
-- AUTO_INCREMENT for table `class_types`
--
ALTER TABLE `class_types`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `dorms`
--
ALTER TABLE `dorms`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `exams`
--
ALTER TABLE `exams`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `exam_records`
--
ALTER TABLE `exam_records`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `grades`
--
ALTER TABLE `grades`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `learning_materials`
--
ALTER TABLE `learning_materials`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lgas`
--
ALTER TABLE `lgas`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=775;

--
-- AUTO_INCREMENT for table `marks`
--
ALTER TABLE `marks`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `message_recipients`
--
ALTER TABLE `message_recipients`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT for table `my_classes`
--
ALTER TABLE `my_classes`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `nationalities`
--
ALTER TABLE `nationalities`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=194;

--
-- AUTO_INCREMENT for table `notices`
--
ALTER TABLE `notices`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `payment_records`
--
ALTER TABLE `payment_records`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;

--
-- AUTO_INCREMENT for table `pins`
--
ALTER TABLE `pins`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `promotions`
--
ALTER TABLE `promotions`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `receipts`
--
ALTER TABLE `receipts`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;

--
-- AUTO_INCREMENT for table `school_events`
--
ALTER TABLE `school_events`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT for table `sections`
--
ALTER TABLE `sections`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `skills`
--
ALTER TABLE `skills`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `staff_records`
--
ALTER TABLE `staff_records`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `states`
--
ALTER TABLE `states`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `student_attendances`
--
ALTER TABLE `student_attendances`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `student_records`
--
ALTER TABLE `student_records`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `study_materials`
--
ALTER TABLE `study_materials`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `subjects`
--
ALTER TABLE `subjects`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `time_slots`
--
ALTER TABLE `time_slots`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `time_tables`
--
ALTER TABLE `time_tables`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `time_table_records`
--
ALTER TABLE `time_table_records`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=78;

--
-- AUTO_INCREMENT for table `user_types`
--
ALTER TABLE `user_types`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `assignments`
--
ALTER TABLE `assignments`
  ADD CONSTRAINT `assignments_my_class_id_foreign` FOREIGN KEY (`my_class_id`) REFERENCES `my_classes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `assignments_section_id_foreign` FOREIGN KEY (`section_id`) REFERENCES `sections` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `assignments_subject_id_foreign` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `assignments_teacher_id_foreign` FOREIGN KEY (`teacher_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `assignment_submissions`
--
ALTER TABLE `assignment_submissions`
  ADD CONSTRAINT `assignment_submissions_assignment_id_foreign` FOREIGN KEY (`assignment_id`) REFERENCES `assignments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `assignment_submissions_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `books`
--
ALTER TABLE `books`
  ADD CONSTRAINT `books_my_class_id_foreign` FOREIGN KEY (`my_class_id`) REFERENCES `my_classes` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `book_requests`
--
ALTER TABLE `book_requests`
  ADD CONSTRAINT `book_requests_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `book_requests_book_id_foreign` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `book_requests_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `exam_records`
--
ALTER TABLE `exam_records`
  ADD CONSTRAINT `exam_records_exam_id_foreign` FOREIGN KEY (`exam_id`) REFERENCES `exams` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `exam_records_my_class_id_foreign` FOREIGN KEY (`my_class_id`) REFERENCES `my_classes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `exam_records_section_id_foreign` FOREIGN KEY (`section_id`) REFERENCES `sections` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `exam_records_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `grades`
--
ALTER TABLE `grades`
  ADD CONSTRAINT `grades_class_type_id_foreign` FOREIGN KEY (`class_type_id`) REFERENCES `class_types` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `lgas`
--
ALTER TABLE `lgas`
  ADD CONSTRAINT `lgas_state_id_foreign` FOREIGN KEY (`state_id`) REFERENCES `states` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `marks`
--
ALTER TABLE `marks`
  ADD CONSTRAINT `marks_exam_id_foreign` FOREIGN KEY (`exam_id`) REFERENCES `exams` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `marks_grade_id_foreign` FOREIGN KEY (`grade_id`) REFERENCES `grades` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `marks_my_class_id_foreign` FOREIGN KEY (`my_class_id`) REFERENCES `my_classes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `marks_section_id_foreign` FOREIGN KEY (`section_id`) REFERENCES `sections` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `marks_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `marks_subject_id_foreign` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_receiver_id_foreign` FOREIGN KEY (`receiver_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `messages_sender_id_foreign` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `message_recipients`
--
ALTER TABLE `message_recipients`
  ADD CONSTRAINT `message_recipients_message_id_foreign` FOREIGN KEY (`message_id`) REFERENCES `messages` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `message_recipients_recipient_id_foreign` FOREIGN KEY (`recipient_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `my_classes`
--
ALTER TABLE `my_classes`
  ADD CONSTRAINT `my_classes_class_type_id_foreign` FOREIGN KEY (`class_type_id`) REFERENCES `class_types` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `notices`
--
ALTER TABLE `notices`
  ADD CONSTRAINT `notices_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `fk_payments_my_class_id` FOREIGN KEY (`my_class_id`) REFERENCES `my_classes` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `payments_class_id_foreign` FOREIGN KEY (`class_id`) REFERENCES `my_classes` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `payments_my_class_id_foreign` FOREIGN KEY (`my_class_id`) REFERENCES `my_classes` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `payment_records`
--
ALTER TABLE `payment_records`
  ADD CONSTRAINT `payment_records_payment_id_foreign` FOREIGN KEY (`payment_id`) REFERENCES `payments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `payment_records_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `pins`
--
ALTER TABLE `pins`
  ADD CONSTRAINT `pins_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pins_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `promotions`
--
ALTER TABLE `promotions`
  ADD CONSTRAINT `promotions_from_class_foreign` FOREIGN KEY (`from_class`) REFERENCES `my_classes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `promotions_from_section_foreign` FOREIGN KEY (`from_section`) REFERENCES `sections` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `promotions_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `promotions_to_class_foreign` FOREIGN KEY (`to_class`) REFERENCES `my_classes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `promotions_to_section_foreign` FOREIGN KEY (`to_section`) REFERENCES `sections` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `receipts`
--
ALTER TABLE `receipts`
  ADD CONSTRAINT `receipts_pr_id_foreign` FOREIGN KEY (`pr_id`) REFERENCES `payment_records` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `school_events`
--
ALTER TABLE `school_events`
  ADD CONSTRAINT `school_events_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `sections`
--
ALTER TABLE `sections`
  ADD CONSTRAINT `sections_my_class_id_foreign` FOREIGN KEY (`my_class_id`) REFERENCES `my_classes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sections_teacher_id_foreign` FOREIGN KEY (`teacher_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `staff_records`
--
ALTER TABLE `staff_records`
  ADD CONSTRAINT `staff_records_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `students`
--
ALTER TABLE `students`
  ADD CONSTRAINT `students_class_id_foreign` FOREIGN KEY (`class_id`) REFERENCES `my_classes` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `students_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `student_records`
--
ALTER TABLE `student_records`
  ADD CONSTRAINT `student_records_dorm_id_foreign` FOREIGN KEY (`dorm_id`) REFERENCES `dorms` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `student_records_my_class_id_foreign` FOREIGN KEY (`my_class_id`) REFERENCES `my_classes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `student_records_my_parent_id_foreign` FOREIGN KEY (`my_parent_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `student_records_section_id_foreign` FOREIGN KEY (`section_id`) REFERENCES `sections` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `student_records_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `study_materials`
--
ALTER TABLE `study_materials`
  ADD CONSTRAINT `study_materials_my_class_id_foreign` FOREIGN KEY (`my_class_id`) REFERENCES `my_classes` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `study_materials_subject_id_foreign` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `study_materials_uploaded_by_foreign` FOREIGN KEY (`uploaded_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `subjects`
--
ALTER TABLE `subjects`
  ADD CONSTRAINT `subjects_my_class_id_foreign` FOREIGN KEY (`my_class_id`) REFERENCES `my_classes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `subjects_teacher_id_foreign` FOREIGN KEY (`teacher_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `time_slots`
--
ALTER TABLE `time_slots`
  ADD CONSTRAINT `time_slots_ttr_id_foreign` FOREIGN KEY (`ttr_id`) REFERENCES `time_table_records` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `time_tables`
--
ALTER TABLE `time_tables`
  ADD CONSTRAINT `time_tables_subject_id_foreign` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `time_tables_ts_id_foreign` FOREIGN KEY (`ts_id`) REFERENCES `time_slots` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `time_tables_ttr_id_foreign` FOREIGN KEY (`ttr_id`) REFERENCES `time_table_records` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `time_table_records`
--
ALTER TABLE `time_table_records`
  ADD CONSTRAINT `time_table_records_exam_id_foreign` FOREIGN KEY (`exam_id`) REFERENCES `exams` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `time_table_records_my_class_id_foreign` FOREIGN KEY (`my_class_id`) REFERENCES `my_classes` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_bg_id_foreign` FOREIGN KEY (`bg_id`) REFERENCES `blood_groups` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `users_lga_id_foreign` FOREIGN KEY (`lga_id`) REFERENCES `lgas` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `users_nal_id_foreign` FOREIGN KEY (`nal_id`) REFERENCES `nationalities` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `users_state_id_foreign` FOREIGN KEY (`state_id`) REFERENCES `states` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
