-- Host: mysql
-- PHP Version: 7.3.16
SET
    SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";

SET
    AUTOCOMMIT = 0;

START TRANSACTION;

SET
    time_zone = "+00:00";

--
-- Database: `tp_comparoperator`
--
USE `tp_comparoperator`;
-- --------------------------------------------------------
--
-- Placeholder data for table `operators`
--
INSERT INTO
    `operators` (
        `operator_id`,
        `name`,
        `website`,
        `logo`,
        `is_premium`
    )
VALUES
    (
        1,
        'Club Med',
        'https://www.clubmed.fr/',
        'images/operators/clubmed.svg',
        0
    ),
    (
        2,
        'FRAM',
        'https://www.fram.fr/',
        'images/operators/fram.svg',
        0
    ),
    (
        3,
        'Marmara',
        'https://www.tui.fr/gamme-club-marmara/',
        'images/operators/marmara.svg',
        1
    ),
    (
        4,
        'Lookea',
        'https://www.tui.fr/',
        'images/operators/lookea.svg',
        0
    ),
    (
        5,
        'Promovacances',
        'https://www.promovacances.com/',
        'images/operators/promovacances.svg',
        1
    ),
    (
        6,
        'Expedia',
        'https://www.expedia.fr/',
        'images/operators/expedia.svg',
        0
    );

-- --------------------------------------------------------
--
-- Placeholder data for table `destinations`
--
INSERT INTO
    `destinations` (
        `destination_id`,
        `operator_id`,
        `created_at`,
        `location`,
        `price`,
        `thumbnail`
    )
VALUES
    (
        1,
        1,
        '2020-05-10 07:01:01',
        'Corse',
        500,
        'images/destinations/0001.jpg'
    ),
    (
        2,
        1,
        '2020-05-10 07:01:02',
        'Corse',
        500,
        'images/destinations/0002.jpg'
    ),
    (
        3,
        2,
        '2020-05-10 07:01:03',
        'Corse',
        499,
        'images/destinations/0003.jpg'
    ),
    (
        4,
        3,
        '2020-05-10 07:01:04',
        'Corse',
        750,
        'images/destinations/0004.jpg'
    ),
    (
        5,
        1,
        '2020-05-10 07:01:05',
        'Budapest',
        800,
        'images/destinations/0005.jpg'
    ),
    (
        6,
        2,
        '2020-05-10 07:01:06',
        'Budapest',
        725,
        'images/destinations/0006.jpg'
    ),
    (
        7,
        3,
        '2020-05-10 07:01:07',
        'Budapest',
        910,
        'images/destinations/0007.jpg'
    ),
    (
        8,
        4,
        '2020-05-10 07:01:08',
        'Budapest',
        899,
        'images/destinations/0008.jpg'
    ),
    (
        9,
        4,
        '2020-05-10 07:01:09',
        'Osaka',
        1500,
        'images/destinations/0009.jpg'
    ),
    (
        10,
        5,
        '2020-05-10 07:01:10',
        'Osaka',
        2500,
        'images/destinations/0010.jpg'
    ),
    (
        11,
        6,
        '2020-05-10 07:01:11',
        'Osaka',
        2499,
        'images/destinations/0011.jpg'
    ),
    (
        12,
        2,
        '2020-05-10 07:01:12',
        'Berlin',
        400,
        'images/destinations/0012.jpg'
    ),
    (
        13,
        3,
        '2020-05-10 07:01:13',
        'Berlin',
        350,
        'images/destinations/0013.jpg'
    ),
    (
        14,
        4,
        '2020-05-10 07:01:14',
        'Berlin',
        500,
        'images/destinations/0014.jpg'
    ),
    (
        15,
        1,
        '2020-05-10 07:01:15',
        'Lyon',
        200,
        'images/destinations/0015.jpg'
    ),
    (
        16,
        2,
        '2020-05-10 07:01:16',
        'Lyon',
        100,
        'images/destinations/0016.jpg'
    ),
    (
        17,
        3,
        '2020-05-10 07:01:17',
        'Lyon',
        150,
        'images/destinations/0017.jpg'
    ),
    (
        18,
        4,
        '2020-05-10 07:01:18',
        'Lyon',
        199,
        'images/destinations/0018.jpg'
    ),
    (
        19,
        5,
        '2020-05-10 07:01:19',
        'Lyon',
        119,
        'images/destinations/0019.jpg'
    ),
    (
        20,
        6,
        '2020-05-10 07:01:20',
        'Lyon',
        149,
        'images/destinations/0020.jpg'
    );

-- --------------------------------------------------------
--
-- Placeholder data for table `users`
--
INSERT INTO
    `users` (`user_id`, `name`, `created_at`, `ip`)
VALUES
    (1, 'Zoltan', '2020-05-16 14:48:18', 0xac10ee01),
    (2, 'NuGuy', '2020-05-16 15:34:54', 0xac10ee01),
    (3, 'Hamza', '2020-05-16 15:35:15', 0xac10ee01),
    (
        4,
        'pozorfluo',
        '2020-05-16 15:36:25',
        0xac10ee01
    ),
    (
        5,
        'AnotherUser',
        '2020-05-16 15:37:53',
        0xac10ee01
    ),
    (6, 'Simplony', '2020-05-17 08:42:13', 0xac10ee01),
    (
        7,
        'Rayvond Demos',
        '2020-05-17 08:42:58',
        0xac10ee01
    ),
    (
        8,
        'Chraster Lambof',
        '2020-05-17 08:45:13',
        0xac10ee01
    ),
    (
        9,
        'ProductHuntor',
        '2020-05-17 08:50:42',
        0xac10ee01
    ),
    (
        10,
        'JennyJones',
        '2020-05-17 08:54:04',
        0xac10ee01
    ),
    (
        11,
        'HamzaKarfa',
        '2020-05-17 08:56:46',
        0xac10ee01
    ),
    (
        12,
        'Zoltarene',
        '2020-05-18 00:18:52',
        0xac10ee01
    ),
    (
        13,
        'SerjTankian',
        '2020-05-18 01:06:20',
        0xac10ee01
    ),
    (14, 'Serjio', '2020-05-18 01:06:54', 0xac10ee01);

-- --------------------------------------------------------
--
-- Placeholder data for table `reviews`
--
INSERT INTO `reviews` (`review_id`, `destination_id`, `operator_id`, `user_id`, `created_at`, `rating`, `message`) VALUES
(1,  1, 1,  1, '2020-05-10 07:01:01',  3, 'Review from user  1'),
(2,  2, 1,  2, '2020-05-10 07:01:02',  4, 'Review from user  2'),
(3,  2, 1,  3, '2020-05-10 07:01:03',  5, 'Review from user  3'),
(4,  2, 1,  4, '2020-05-10 07:01:04',  5, 'Review from user  4'),
(5,  3, 2,  5, '2020-05-10 07:01:02',  1, 'Review from user  5'),
(6,  4, 3,  6, '2020-05-10 07:01:03',  0, 'Review from user  6'),
(7,  5, 1,  7, '2020-05-10 07:01:04',  4, 'Review from user  7'),
(8,  7, 3,  8, '2020-05-10 07:01:02',  3, 'Review from user  8'),
(9,  7, 3,  9, '2020-05-10 07:01:03',  5, 'Review from user  9'),
(10, 7, 3, 10, '2020-05-10 07:01:04',  4, 'Review from user 10'),
(11, 8, 4, 11, '2020-05-10 07:01:02',  0, 'Review from user 11'),
(12, 8, 4, 12, '2020-05-10 07:01:03',  2, 'Review from user 12'),
(13, 8, 4, 13, '2020-05-10 07:01:04',  1, 'Review from user 13'),
(14, 8, 5, 13, '2020-05-10 07:01:02',  2, 'Review from user 13'),
(15, 9, 4, 12, '2020-05-10 07:01:03',  3, 'Review from user 12'),
(16, 9, 4, 11, '2020-05-10 07:01:04',  4, 'Review from user 11'),
(17, 9, 4, 10, '2020-05-10 07:01:02',  5, 'Review from user 10'),
(18, 9, 4,  9, '2020-05-10 07:01:03',  4, 'Review from user  9'),
(19, 9, 4,  8, '2020-05-10 07:01:04',  3, 'Review from user  8'),
(20, 9, 4,  7, '2020-05-10 07:01:02',  2, 'Review from user  7'),
(21, 9, 4,  6, '2020-05-10 07:01:03',  1, 'Review from user  6'),
(22, 9, 4,  5, '2020-05-10 07:01:04',  5, 'Review from user  5');

-- --------------------------------------------------------
--
-- Placeholder data for table `ratings`
--
-- INSERT INTO `ratings` (`operator_id`, `user_id`, `rating`, `created_at`) VALUES
-- (1, 1, 3, '2020-05-10 07:01:01'),
-- (2, 2, 4, '2020-05-10 07:01:02'),
-- (3, 2, 5, '2020-05-10 07:01:03'),
-- (6, 4, 5, '2020-05-10 07:01:04'),
-- (1, 5, 1, '2020-05-17 08:30:29'),
-- (4, 8, 0, '2020-05-17 08:30:37'),
-- (1, 7, 4, '2020-05-17 08:30:47'),
-- (5, 8, 3, '2020-05-17 08:31:02'),
-- (6, 9, 5, '2020-05-17 08:31:03'),
-- (5, 6, 4, '2020-05-17 08:31:10'),
-- (4, 6, 0, '2020-05-17 08:31:12'),
-- (2, 5, 2, '2020-05-17 08:34:59'),
-- (3, 4, 1, '2020-05-17 08:35:04'),
-- (3, 3, 2, '2020-05-17 08:35:08'),
-- (1, 2, 3, '2020-05-17 08:38:51'),
-- (4, 1, 4, '2020-05-17 08:38:56'),
-- (1, 4, 5, '2020-05-17 08:39:47'),
-- (5, 5, 4, '2020-05-17 08:40:29'),
-- (6, 6, 3, '2020-05-17 08:40:48'),
-- (3, 9, 2, '2020-05-17 08:41:10'),
-- (2, 8, 1, '2020-05-17 08:41:15');
COMMIT;