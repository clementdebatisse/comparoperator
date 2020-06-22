-- Host: mysql
-- PHP Version: 7.3.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
-- SET AUTOCOMMIT = 0;
-- START TRANSACTION;
-- SET time_zone = "+00:00";

--
-- Database: `tp_comparoperator`
--

-- --------------------------------------------------------
--
-- public function getUserbyName(string $name): array
--
SELECT
    `user_id`,
    `name`,
    `created_at`,
    `ip`
FROM
    `users`
WHERE
    `name` = "Simplony";

-- --------------------------------------------------------
--
-- public function getUserById(int $user_id): array
--
SELECT
    `user_id`,
    `name`,
    `created_at`,
    `ip`
FROM
    `users`
WHERE
    `user_id` = 1;

-- --------------------------------------------------------
--
-- public function addUser(string $name, string $ip): array
--
INSERT INTO `users`(`name`, `created_at`, `ip`)
VALUES(
    'Simplony-Skeleton',
    '2020-05-10 07:01:00',
    INET_ATON('127.0.0.1')
);

SELECT
    `user_id`,
    `name`,
    `created_at`,
    `ip`
FROM
    `users`
WHERE
    `user_id` = LAST_INSERT_ID();

-- --------------------------------------------------------
--
-- public function getLocations(int $count = 10, int $offset = 0): array
--
SELECT
    `location` AS `name`,
    `thumbnail`,
    COUNT(DISTINCT `destination_id`) AS `offering_count`
FROM
    `destinations`
GROUP BY
    `location`
LIMIT 10 OFFSET 0;

-- --------------------------------------------------------
--
-- public function getOfferings(string $location, int $count = 10, int $offset = 0): array
--
SELECT
    `destinations`.`destination_id`,
    `destinations`.`operator_id`,
    `destinations`.`created_at`,
    `destinations`.`location`,
    `destinations`.`price`,
    `destinations`.`thumbnail`,
    `operators`.`name` AS `operator`,
    `operators`.`website`,
    `operators`.`logo`,
    `operators`.`is_premium`,
    COUNT(DISTINCT `reviews`.`review_id`) AS `review_count`,
    IFNULL(AVG(`reviews`.`rating`), 0.0) AS `operator_rating`
FROM
    `destinations`
LEFT JOIN
    `operators`
ON
    `destinations`.`operator_id` = `operators`.`operator_id`
LEFT JOIN
    `reviews`
ON
    `operators`.`operator_id` = `reviews`.`operator_id`
WHERE
    `destinations`.`location` = 'Osaka'
GROUP BY
    `operators`.`operator_id`
ORDER BY
    `destinations`.`created_at` DESC
LIMIT 10 OFFSET 0;
--     `destinations`.`destination_id`,
--     `destinations`.`price`,
--     `destinations`.`thumbnail`,
--     `destinations`.`operator_id`,
--     `operators`.`name` AS `operator`,
--     `operators`.`website`,
--     `operators`.`logo`,
--     `operators`.`is_premium`,
--     COUNT(DISTINCT `reviews`.`review_id`) AS `review_count`,
--     AVG(`ratings`.`rating`) AS `operator_rating`
-- FROM
--     `destinations`
-- LEFT JOIN
--     `operators`
-- ON
--     `destinations`.`operator_id` = `operators`.`operator_id`
-- LEFT JOIN
--     `reviews`
-- ON
--     `operators`.`operator_id` = `reviews`.`operator_id`
-- LEFT JOIN
--     `ratings`
-- ON
--     `operators`.`operator_id` = `ratings`.`operator_id`
-- WHERE
--     `destinations`.`location` = 'Osaka'
-- GROUP BY
--     `operators`.`operator_id`    
-- LIMIT 10 OFFSET 0;

-- --------------------------------------------------------
--
-- public function getOperators(int $count = 10, int $offset = 0): array
--
SELECT
    `operator_id`,
    `name`,
    `website`,
    `logo`,
    `is_premium`
FROM 
    `operators`
LIMIT 10 OFFSET 0;

-- --------------------------------------------------------
--
-- public function getOperatorByName(string $name): array
--
SELECT
    `operator_id`,
    `name`,
    `website`,
    `logo`,
    `is_premium`
FROM 
    `operators`
WHERE
    `name` = "FRAM";

-- --------------------------------------------------------
--
-- public function getOperatorById(int $operator_id): array
--
SELECT
    `operator_id`,
    `name`,
    `website`,
    `logo`,
    `is_premium`
FROM 
    `operators`
WHERE
    `operator_id` = 4;

-- --------------------------------------------------------
--
-- public function setOperatorPremium(int $operator_id, bool $premium): array
--
-- -> Put operation in RESTish API
--

UPDATE 
    `operators`
SET
    `is_premium` = 0
WHERE 
    `operator_id` = 1;

SELECT
    `operator_id`,
    `name`,
    `website`,
    `logo`,
    `is_premium`
FROM 
    `operators`
WHERE
    `operator_id` = 1;

-- --------------------------------------------------------
--
-- public function addOperator(string $name, string $url, string $logo_path, bool $premium = false): array
--
INSERT INTO
    `operators` (
        `name`,
        `website`,
        `logo`,
        `is_premium`
    )
VALUES
    (
        'Club Red',
        'https://www.clubred.fr/',
        'images/products/operators/clubmed.svg',
        0
    );
SELECT
    `operator_id`,
    `name`,
    `website`,
    `logo`,
    `is_premium`
FROM 
    `operators`
WHERE
    `operator_id` = LAST_INSERT_ID();
-- --------------------------------------------------------
--
-- public function addDestination(string $location, float $prince, string $thumb_path): array
--
INSERT INTO
    `destinations` (
        `operator_id`,
        `created_at`,
        `location`,
        `price`,
        `thumbnail`
    )
VALUES
    (
        1,
        '2020-05-10 07:01:01',
        'Vienna',
        600,
        'images/products/destinations/0001.jpg'
    );
    
SELECT
    `destination_id`,
    `operator_id`,
    `created_at`,
    `location`,
    `price`,
    `thumbnail`
FROM 
    `destinations`
WHERE
    `destination_id` = LAST_INSERT_ID();

-- --------------------------------------------------------
--
-- public function getUserRatings(int $user_id): array
--
-- SELECT
--     `operator_id`,
--     `rating`
-- FROM
--     `ratings`
-- WHERE
--     `user_id` = 1;


-- --------------------------------------------------------
--
-- public function getReviewsByDestination(int $destination_id): array
--
SELECT
    `review_id`,
    `destination_id`,
    `operator_id`,
    `user_id`,
    `created_at`,
    `rating`,
    `message`
FROM
    `reviews`
WHERE
    `destination_id` = 4
LIMIT 10 OFFSET 0;
-- --------------------------------------------------------
--
-- public function getReviewsByOperator(int $operator_id, int $count = 10, int $offset = 0): array
--
SELECT
    `review_id`,
    `destination_id`,
    `operator_id`,
    `user_id`,
    `created_at`,
    `rating`,
    `message`
FROM
    `reviews`
WHERE
    `operator_id` = 4
LIMIT 10 OFFSET 0;
-- --------------------------------------------------------
--
-- public function review(int $user_id, int $operator_id, int $rating): array
--
-- @todo Consider alternative to avg subquery
-- @todo Explore cost of this kind of subquery
--
INSERT INTO 
    `reviews` (
        `destination_id`,
        `operator_id`,
        `user_id`,
        `created_at`,
        `rating`,
        `message`
    )
VALUES
    (
        1,
        1,
        3,
        '2020-05-10 07:01:01',
        5,
        'One more review'
    );

SELECT
    `review_id`,
    `destination_id`,
    `operator_id`,
    `user_id`,
    `created_at`,
    `rating`,
    `message`,
    (
        SELECT
            IFNULL(AVG(`reviews`.`rating`), 0.0)
        FROM
            `reviews`
        WHERE
            `operator_id`= 1
    ) AS `operator_rating`
FROM
    `reviews`
WHERE
    `review_id` = LAST_INSERT_ID();



-- INSERT INTO 
--     `reviews` (
--         `destination_id`,
--         `operator_id`,
--         `user_id`,
--         `created_at`,
--         `rating`,
--         `message`
--     )
-- VALUES
--     (
--         1,
--         1,
--         3,
--         '2020-05-10 07:01:01',
--         5,
--         'One more review'
--     );
-- SELECT
--     `reviews`.`review_id`,
--     `reviews`.`destination_id`,
--     `reviews`.`operator_id`,
--     `reviews`.`user_id`,
--     `reviews`.`created_at`,
--     `reviews`.`rating`,
--     `reviews`.`message`,
--      IFNULL(AVG(`ratings`.`rating`), 0.0) AS `operator_rating`
-- FROM
--     `reviews`
-- RIGHT OUTER JOIN
--     `reviews` as `ratings`
-- ON
--     `reviews`.`operator_id` = `ratings`.`operator_id`
-- WHERE
--     `reviews`.`review_id` = LAST_INSERT_ID();
-- --------------------------------------------------------
-- --------------------------------------------------------
-- --------------------------------------------------------
-- --------------------------------------------------------
-- --------------------------------------------------------