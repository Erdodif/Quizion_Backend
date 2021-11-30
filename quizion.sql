-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Gép: 127.0.0.1
-- Létrehozás ideje: 2021. Nov 30. 12:40
-- Kiszolgáló verziója: 10.4.21-MariaDB
-- PHP verzió: 8.0.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Adatbázis: `quizion`
--
CREATE DATABASE IF NOT EXISTS `quizion` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_hungarian_ci;
USE `quizion`;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `answer`
--

CREATE TABLE IF NOT EXISTS `answer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `question_id` int(11) NOT NULL,
  `content` varchar(255) COLLATE utf8mb4_hungarian_ci NOT NULL,
  `is_right` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `question_id` (`question_id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_hungarian_ci;

--
-- A tábla adatainak kiíratása `answer`
--

INSERT INTO `answer` (`id`, `question_id`, `content`, `is_right`) VALUES
(1, 1, 'Első kvíz első kérdés első válaszlehetőség', 1),
(2, 1, 'Első kvíz első kérdés második válaszlehetőség', 0),
(3, 1, 'Első kvíz első kérdés harmadik válaszlehetőség', 0),
(4, 1, 'Első kvíz első kérdés negyedik válaszlehetőség', 0),
(5, 3, 'Második kvíz első kérdés első válaszlehetőség', 1),
(6, 3, 'Második kvíz első kérdés második válaszlehetőség', 0),
(7, 3, 'Második kvíz első kérdés harmadik válaszlehetőség', 0),
(8, 2, 'Első kvíz második kérdés első válaszlehetőség', 1),
(9, 2, 'Első kvíz második kérdés második válaszlehetőség', 0),
(10, 2, 'Első kvíz második kérdés harmadik válaszlehetőség', 0),
(11, 4, 'Második kvíz második kérdés első válaszlehetőség', 1),
(12, 4, 'Második kvíz második kérdés második válaszlehetőség', 0),
(13, 4, 'Második kvíz második kérdés harmadik válaszlehetőség', 0),
(14, 4, 'Második kvíz második kérdés negyedik válaszlehetőség', 0),
(15, 5, 'Második kvíz harmadik kérdés első válaszlehetőség', 1),
(16, 5, 'Második kvíz harmadik kérdés második válaszlehetőség', 0),
(17, 6, 'Harmadik kvíz első kérdés első válaszlehetőség', 1),
(18, 6, 'Harmadik kvíz első kérdés második válaszlehetőség', 0);

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `question`
--

CREATE TABLE IF NOT EXISTS `question` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `quiz_id` int(11) NOT NULL,
  `content` varchar(255) COLLATE utf8mb4_hungarian_ci NOT NULL,
  `no_right_answers` int(11) NOT NULL,
  `point` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `quiz_id` (`quiz_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_hungarian_ci;

--
-- A tábla adatainak kiíratása `question`
--

INSERT INTO `question` (`id`, `quiz_id`, `content`, `no_right_answers`, `point`) VALUES
(1, 1, 'Első kvíz első kérdés', 1, 100),
(2, 1, 'Első kvíz második kérdés', 1, 200),
(3, 2, 'Második kvíz első kérdés', 1, 100),
(4, 2, 'Második kvíz második kérdés', 1, 200),
(5, 2, 'Második kvíz harmadik kérdés', 1, 100),
(6, 3, 'Harmadik kvíz első kérdés', 1, 100);

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `quiz`
--

CREATE TABLE IF NOT EXISTS `quiz` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `header` varchar(255) COLLATE utf8mb4_hungarian_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_hungarian_ci NOT NULL,
  `active` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_hungarian_ci;

--
-- A tábla adatainak kiíratása `quiz`
--

INSERT INTO `quiz` (`id`, `header`, `description`, `active`) VALUES
(1, 'Első kvíz', 'Első kvíz leírás', 1),
(2, 'Második kvíz', 'Második kvíz leírás', 1),
(3, 'Harmadik kvíz', 'Harmadik kvíz leírás', 1);

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `results`
--

CREATE TABLE IF NOT EXISTS `results` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `quiz_id` int(11) NOT NULL,
  `start` datetime NOT NULL DEFAULT curdate(),
  `end` datetime DEFAULT NULL,
  `right` double(10,5) DEFAULT 0.00000,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `quiz_id` (`quiz_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_hungarian_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(25) COLLATE utf8mb4_hungarian_ci NOT NULL,
  `email` varchar(64) COLLATE utf8mb4_hungarian_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 NOT NULL,
  `xp` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_hungarian_ci;

--
-- Megkötések a kiírt táblákhoz
--

--
-- Megkötések a táblához `answer`
--
ALTER TABLE `answer`
  ADD CONSTRAINT `answer_ibfk_1` FOREIGN KEY (`question_id`) REFERENCES `question` (`id`);

--
-- Megkötések a táblához `question`
--
ALTER TABLE `question`
  ADD CONSTRAINT `question_ibfk_1` FOREIGN KEY (`quiz_id`) REFERENCES `quiz` (`id`);

--
-- Megkötések a táblához `results`
--
ALTER TABLE `results`
  ADD CONSTRAINT `results_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `results_ibfk_2` FOREIGN KEY (`quiz_id`) REFERENCES `quiz` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
