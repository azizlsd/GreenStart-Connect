-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 13, 2025 at 12:07 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `gscdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id_event` int(11) NOT NULL,
  `titre_event` varchar(100) NOT NULL,
  `description_event` text NOT NULL,
  `localisation` varchar(200) NOT NULL,
  `date_debut` datetime NOT NULL,
  `date_fin` datetime NOT NULL,
  `max_participants` int(11) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `organizer_id` int(11) NOT NULL
) ;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id_event`, `titre_event`, `description_event`, `localisation`, `date_debut`, `date_fin`, `max_participants`, `image`, `organizer_id`) VALUES
(1, 'Tunis AI Summit 2025', 'Explore AI innovations and network with experts.', 'Avenue Habib Bourguiba, Tunis', '2025-06-10 09:00:00', '2025-06-11 17:00:00', 200, NULL, 1),
(2, 'Sousse Hackathon', '48-hour coding challenge for developers.', 'Rue de Paris, Sousse', '2025-07-05 10:00:00', '2025-07-07 18:00:00', 150, NULL, 2),
(3, 'Sfax CyberSec Conference', 'Learn about cybersecurity trends.', 'Avenue des Martyrs, Sfax', '2025-08-15 08:30:00', '2025-08-16 16:00:00', 100, NULL, 3),
(4, 'Monastir DevFest', 'Google tech event for developers.', 'Rue de la Liberté, Monastir', '2025-09-20 09:00:00', '2025-09-20 17:00:00', 300, NULL, 4),
(5, 'Tunis Startup Weekend', 'Pitch and build your startup idea.', 'Place du 7 Novembre, Tunis', '2025-10-03 18:00:00', '2025-10-05 20:00:00', 120, NULL, 5),
(6, 'Sfax Web Dev Workshop', 'Hands-on web development training.', 'Rue Hedi Chaker, Sfax', '2025-11-08 10:00:00', '2025-11-09 16:00:00', 50, NULL, 6),
(7, 'Nabeul Tech Meetup', 'Networking for tech enthusiasts.', 'Avenue Farhat Hached, Nabeul', '2025-12-12 19:00:00', '2025-12-12 22:00:00', 80, NULL, 7),
(8, 'Gabes Data Science Bootcamp', 'Intensive data science course.', 'Rue de Gabes, Gabes', '2026-01-10 09:00:00', '2026-01-12 17:00:00', 60, NULL, 8),
(9, 'Bizerte Cloud Expo', 'Discover cloud computing solutions.', 'Corniche de Bizerte, Bizerte', '2026-02-07 08:00:00', '2026-02-08 15:00:00', 150, NULL, 9),
(10, 'Kairouan Coding Camp', 'Learn coding basics for beginners.', 'Avenue de la République, Kairouan', '2026-03-14 10:00:00', '2026-03-15 16:00:00', 40, NULL, 10),
(11, 'Tunis Blockchain Forum', 'Dive into blockchain technology.', 'Avenue Mohamed V, Tunis', '2026-04-18 09:00:00', '2026-04-19 17:00:00', 180, NULL, 1),
(12, 'Sousse Mobile Dev Conf', 'Trends in mobile app development.', 'Boulevard du 7 Novembre, Sousse', '2026-05-09 08:30:00', '2026-05-10 16:00:00', 130, NULL, 2),
(13, 'Sfax IoT Workshop', 'Build IoT projects with experts.', 'Rue Taieb Mhiri, Sfax', '2026-06-06 10:00:00', '2026-06-07 15:00:00', 70, NULL, 3),
(14, 'Monastir UX Design Meetup', 'Discuss UX/UI design trends.', 'Avenue de l’Indépendance, Monastir', '2026-07-11 18:00:00', '2026-07-11 21:00:00', 90, NULL, 4),
(15, 'Tunis Game Dev Jam', 'Create games in a weekend.', 'Avenue de Carthage, Tunis', '2026-08-07 17:00:00', '2026-08-09 19:00:00', 100, NULL, 5),
(16, 'Nabeul AI Workshop', 'Intro to machine learning.', 'Rue de Tunis, Nabeul', '2026-09-12 09:00:00', '2026-09-13 16:00:00', 50, NULL, 6),
(17, 'Gabes Tech Expo', 'Showcase of Tunisian tech startups.', 'Avenue Habib Thameur, Gabes', '2026-10-10 10:00:00', '2026-10-11 18:00:00', 200, NULL, 7),
(18, 'Bizerte DevOps Summit', 'Best practices in DevOps.', 'Rue du 2 Mars, Bizerte', '2026-11-06 08:00:00', '2026-11-07 16:00:00', 110, NULL, 8),
(19, 'Kairouan Open Source Conf', 'Celebrate open-source software.', 'Rue de la Mosquée, Kairouan', '2026-12-04 09:00:00', '2026-12-05 17:00:00', 140, NULL, 9),
(20, 'Tunis Women in Tech', 'Empowering women in tech careers.', 'Avenue de la Bourse, Tunis', '2027-01-09 10:00:00', '2027-01-10 15:00:00', 250, NULL, 10);

-- --------------------------------------------------------

--
-- Table structure for table `reservations`
--

CREATE TABLE `reservations` (
  `id_res` int(11) NOT NULL,
  `id_event` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `nom_user` varchar(100) NOT NULL,
  `accom_res` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reservations`
--

INSERT INTO `reservations` (`id_res`, `id_event`, `id_user`, `nom_user`, `accom_res`) VALUES
(1, 1, 5, 'Youssef Mansour', 2),
(2, 1, 6, 'Leila Saidi', 3),
(3, 2, 7, 'Karim Jebali', 1),
(4, 2, 8, 'Sana Haddad', 4),
(5, 3, 9, 'Omar Khalifa', 0),
(6, 4, 10, 'Nadia Baccouche', 2),
(7, 5, 5, 'Youssef Mansour', 1),
(8, 6, 6, 'Leila Saidi', 3),
(9, 7, 7, 'Karim Jebali', 2),
(10, 8, 8, 'Sana Haddad', 1),
(11, 9, 9, 'Omar Khalifa', 2),
(12, 10, 10, 'Nadia Baccouche', 0),
(13, 11, 1, 'Ahmed Ben Ali', 3),
(14, 12, 2, 'Fatima Zouari', 2),
(15, 13, 3, 'Mohamed Trabelsi', 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id_user` int(11) NOT NULL,
  `nom_user` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id_user`, `nom_user`) VALUES
(1, 'Ahmed Ben Ali'),
(4, 'Aicha Gharbi'),
(2, 'Fatima Zouari'),
(7, 'Karim Jebali'),
(6, 'Leila Saidi'),
(3, 'Mohamed Trabelsi'),
(10, 'Nadia Baccouche'),
(9, 'Omar Khalifa'),
(8, 'Sana Haddad'),
(5, 'Youssef Mansour');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id_event`);

--
-- Indexes for table `reservations`
--
ALTER TABLE `reservations`
  ADD PRIMARY KEY (`id_res`),
  ADD KEY `id_event` (`id_event`),
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `nom_user` (`nom_user`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id_event` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reservations`
--
ALTER TABLE `reservations`
  MODIFY `id_res` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `reservations`
--
ALTER TABLE `reservations`
  ADD CONSTRAINT `reservations_ibfk_1` FOREIGN KEY (`id_event`) REFERENCES `events` (`id_event`) ON DELETE CASCADE,
  ADD CONSTRAINT `reservations_ibfk_2` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
