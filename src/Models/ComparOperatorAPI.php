<?php


declare(strict_types=1);

namespace Models;

use Controllers\Controller;
use Models\Endpoints\UserEndpoint;
use Models\Endpoints\DestinationEndpoint;
use Models\Endpoints\OperatorEndpoint;

/**
 * ComparOperatorAPI
 * 
 * @todo Return entities instead of assoc arrays in next more oop-ish 
 *       iteration.
 * @todo Consider returning a generic 'EntityInterface' instead of an entity
 *       to be able to move things around in a specific entity
 *       implementation and NOT break to many things.
 * 
 * @todo Break-up API into multiple files by endpoints.
 * @todo Consider using traits for endpoints and composing API by using endpoint
 *       traits.
 */
class ComparOperatorAPI extends DBPDO
{
    use UserEndpoint;
    use OperatorEndpoint;
    use DestinationEndpoint;
    /**
     * Create a new ComparOperatorAPI instance from a decoded config json.
     * 
     * @param  array $config
     * @return ComparOperatorAPI
     */
    public static function fromConfig(array $db_configs): ComparOperatorAPI
    {
        $controller = new \Controllers\ComparOperatorAPI();

        $controller->set(['db_configs' => $db_configs]);
        return new self($controller);
    }


    /**
     * Create a new ComparOperatorAPI instance.
     *
     * @param  Controller|NULL $controller
     * @return void
     */
    public function __construct(?Controller $controller = NULL)
    {
        if (is_null($controller)) {
            $controller = new \Controllers\ComparOperatorAPI();
        }
        parent::__construct($controller);
    }


    // /**
    //  * Get most popular products.
    //  * 
    //  * @api ComparOperatorAPI
    //  * 
    //  * @param  int $count  How many products to return (default = 10).
    //  * @param  int $offset How many products to skip   (default = 0)
    //  *                     Use for pagination.
    //  * 
    //  * @return array <pre><code>[
    //  *     [
    //  *         'product_id'     => int,
    //  *         'name'           => string,
    //  *         'created_at'     => string date('Y-m-d H:i:s'),
    //  *         'website'        => string,
    //  *         'summary'        => string,
    //  *         'thumbnail'      => string,
    //  *         'votes_count'    => int,
    //  *         'comments_count' => int
    //  *     ], 
    //  *     ...
    //  * ] </code></pre>
    //  */
    // public function getPopularProducts(int $count = 10, int $offset = 0): array
    // {
    //     if ($count < 0) {
    //         $count = 10;
    //     }
    //     if ($offset < 0) {
    //         $offset = 0;
    //     }

    //     return $this->execute(
    //         'comparoperator',
    //         'SELECT
    //              `products`.`product_id`,
    //              `products`.`created_at`,
    //              `products`.`name`,
    //              `products`.`summary`,
    //              `products`.`website`,
    //              `products`.`thumbnail`,
    //              COUNT(DISTINCT `comments`.`comment_id`) AS `comments_count`,
    //              COUNT(DISTINCT `votes`.`user_id`) AS `votes_count`
    //          FROM 
    //              `products`
    //          LEFT JOIN
    //              `comments`
    //          ON
    //              `products`.`product_id` = `comments`.`product_id`
    //          LEFT JOIN
    //              `votes`
    //          ON
    //              `products`.`product_id` = `votes`.`product_id`
    //          GROUP BY
    //              `products`.`product_id`
    //          ORDER BY
    //             `votes_count` DESC, `products`.`created_at` DESC
    //          LIMIT ? OFFSET ?;',
    //         [$count, $offset]
    //     );
    // }

    // /**
    //  * Get category info for a given category id.
    //  * 
    //  * @api ComparOperatorAPI
    //  * 
    //  * @param  int $category_id
    //  * 
    //  * @return array <pre><code>[
    //  *     'category_id'    => int,
    //  *     'name'           => string,
    //  *     'summary'        => string,
    //  *     'thumbnail'      => string
    //  * ] </code></pre>
    //  */
    // public function getCategory(int $category_id): array
    // {
    //     if ($category_id <= 0) {
    //         // $category_id = 0;
    //         return [];
    //     }

    //     $category = $this->execute(
    //         'comparoperator',
    //         'SELECT
    //              `category_id`,
    //              `name`,
    //              `summary`,
    //              `thumbnail`
    //          FROM
    //              `categories`
    //          WHERE
    //         `category_id` = ?;',
    //         [$category_id]
    //     );

    //     if (!empty($category)) {
    //         $category = $category[0];
    //     }

    //     return $category;
    // }
    // /**
    //  * Get product content associated with a given product id.
    //  * 
    //  * @api ComparOperatorAPI
    //  * 
    //  * @param  int $product_id
    //  * 
    //  * @return array <pre><code>[
    //  *     'article_id'     => int,
    //  *     'product_id'     => int,
    //  *     'content'        => string,
    //  *     'media'          => array[string, ...]
    //  *     'comments'       => [
    //  *                             [
    //  *                                 'comment_id'     => int,
    //  *                                 'product_id'     => int,
    //  *                                 'user_id'        => int,
    //  *                                 'name'           => string,
    //  *                                 'created_at'     => string date('Y-m-d H:i:s'),
    //  *                                 'content'        => string
    //  *                             ], 
    //  *                             ...
    //  *                         ]
    //  * ] </code></pre>
    //  * 
    //  * @todo Return categories
    //  */
    // public function getProduct(int $product_id): array
    // {
    //     if ($product_id <= 0) {
    //         return [];
    //     }

    //     $product = $this->execute(
    //         'comparoperator',
    //         'SELECT
    //              `product_id`,
    //              `article_id`,
    //              `content`,
    //              `media`
    //          FROM 
    //              `articles`
    //          WHERE
    //             `product_id` = ?;',
    //         [$product_id]
    //     );

    //     if (!empty($product)) {
    //         $product = $product[0];
    //     }

    //     if (isset($product['media'])) {
    //         $product['media'] = json_decode($product['media']);
    //     }

    //     return $product;
    // }

    // /**
    //  * Get products associated with a given category.
    //  * 
    //  * @api ComparOperatorAPI
    //  * 
    //  * @param  int $category_id
    //  * @param  int $count  How many products to return (default = 10).
    //  * @param  int $offset How many products to skip   (default = 0)
    //  *                     Use for pagination.
    //  * 
    //  * @return array <pre><code>[
    //  *     [
    //  *         'product_id'     => int,
    //  *         'name'           => string,
    //  *         'created_at'     => string date('Y-m-d H:i:s'),
    //  *         'website'        => string,
    //  *         'summary'        => string,
    //  *         'thumbnail'      => string,
    //  *         'votes_count'    => int,
    //  *         'comments_count' => int
    //  *     ], 
    //  *     ...
    //  * ] </code></pre>
    //  * 
    //  * @todo Implement query
    //  */
    // public function getProductsCollection(
    //     int $category_id,
    //     int $count = 10,
    //     int $offset = 0
    // ): array {

    //     if ($category_id <= 0) {
    //         return [];
    //     }
    //     if ($count < 0) {
    //         $count = 10;
    //     }
    //     if ($offset < 0) {
    //         $offset = 0;
    //     }

    //     return $this->execute(
    //         'comparoperator',
    //         'SELECT
    //              `products`.`product_id`,
    //              `products`.`created_at`,
    //              `products`.`name`,
    //              `products`.`summary`,
    //              `products`.`website`,
    //              `products`.`thumbnail`,
    //              COUNT(DISTINCT `comments`.`comment_id`) AS `comments_count`,
    //              COUNT(DISTINCT `votes`.`user_id`) AS `votes_count`
    //          FROM 
    //              `products`
    //          INNER JOIN
    //              `collections`
    //          ON
    //              `products`.`product_id` = `collections`.`product_id`
    //          LEFT JOIN
    //              `comments`
    //          ON
    //              `products`.`product_id` = `comments`.`product_id`
    //          LEFT JOIN
    //              `votes`
    //          ON
    //              `products`.`product_id` = `votes`.`product_id`
    //          WHERE
    //              `collections`.`category_id` = ?    
    //          GROUP BY
    //              `products`.`product_id`
    //          ORDER BY
    //              `votes_count` DESC, `products`.`created_at` DESC
    //          LIMIT ? OFFSET ?;',
    //         [$category_id, $count, $offset]
    //     );
    // }

    // /**
    //  * Find products whose name match given search string.
    //  * 
    //  * @api ComparOperatorAPI
    //  * 
    //  * @param  string $search_string
    //  * @param  int $count  How many products to return (default = 10).
    //  * @param  int $offset How many products to skip   (default = 0)
    //  *                     Use for pagination.
    //  * 
    //  * @return array <pre><code>[
    //  *     [
    //  *         'product_id'     => int,
    //  *         'name'           => string,
    //  *         'created_at'     => string date('Y-m-d H:i:s'),
    //  *         'website'        => string,
    //  *         'summary'        => string,
    //  *         'thumbnail'      => string,
    //  *         'votes_count'    => int,
    //  *         'comments_count' => int
    //  *     ], 
    //  *     ...
    //  * ] </code></pre>
    //  * 
    //  * @todo Implement query
    //  */
    // public function findProductsByName(
    //     string $search_string,
    //     int $count = 10,
    //     int $offset = 0
    // ): array {

    //     $search_string = '%' . $search_string . '%';

    //     if ($count < 0) {
    //         $count = 10;
    //     }
    //     if ($offset < 0) {
    //         $offset = 0;
    //     }

    //     return $this->execute(
    //         'comparoperator',
    //         'SELECT
    //              `products`.`product_id`,
    //              `products`.`created_at`,
    //              `products`.`name`,
    //              `products`.`summary`,
    //              `products`.`website`,
    //              `products`.`thumbnail`,
    //              COUNT(DISTINCT `comments`.`comment_id`) AS `comments_count`,
    //              COUNT(DISTINCT `votes`.`user_id`) AS `votes_count`
    //          FROM 
    //              `products`
    //          LEFT JOIN
    //              `comments`
    //          ON
    //              `products`.`product_id` = `comments`.`product_id`
    //          LEFT JOIN
    //              `votes`
    //          ON
    //              `products`.`product_id` = `votes`.`product_id`
    //          WHERE
    //              `products`.`name` LIKE ?
    //          GROUP BY
    //              `products`.`product_id`
    //          ORDER BY
    //              `votes_count` DESC, `products`.`created_at` DESC
    //          LIMIT ? OFFSET ?;',
    //         [$search_string, $count, $offset]
    //     );
    // }

    // /**
    //  * Find products whose content match given search string.
    //  * 
    //  * @api ComparOperatorAPI
    //  * 
    //  * @param  string $search_string
    //  * @param  int $count  How many products to return (default = 10).
    //  * @param  int $offset How many products to skip   (default = 0)
    //  *                     Use for pagination.
    //  * 
    //  * @return array <pre><code>[
    //  *     [
    //  *         'product_id'     => int,
    //  *         'name'           => string,
    //  *         'created_at'     => string date('Y-m-d H:i:s'),
    //  *         'website'        => string,
    //  *         'summary'        => string,
    //  *         'thumbnail'      => string,
    //  *         'votes_count'    => int,
    //  *         'comments_count' => int
    //  *     ], 
    //  *     ...
    //  * ] </code></pre>
    //  * 
    //  * @todo Implement query
    //  */
    // public function findProductsByContent(
    //     string $search_string,
    //     int $count = 10,
    //     int $offset = 0
    // ): array {
    //     return [
    //         [
    //             'product_id'     => 3,
    //             'name'           => 'Remoty',
    //             'created_at'     => '2020-05-10 07:01:00',
    //             'website'        => 'https://rewind.netlify.app/?ref=producthunt',
    //             'summary'        => 'Your bookmarks, by date, with thumbnails and instant search',
    //             'thumbnail'      => 'images/products/thumbnails/1_Rewind.webp',
    //             'votes_count'    => 0,
    //             'comments_count' => 0
    //         ], [
    //             'product_id'     => 7,
    //             'name'           => 'Meeter',
    //             'created_at'     => '2020-05-10 07:01:00',
    //             'website'        => 'https://rewind.netlify.app/?ref=producthunt',
    //             'summary'        => 'Your bookmarks, by date, with thumbnails and instant search',
    //             'thumbnail'      => 'images/products/thumbnails/1_Rewind.webp',
    //             'votes_count'    => 0,
    //             'comments_count' => 0
    //         ]
    //     ];
    // }

    // /**
    //  * Get products id a given user voted for.
    //  * 
    //  * @api ComparOperatorAPI
    //  * 
    //  * @param  int $user_id
    //  * 
    //  * @return array <pre><code>[
    //  *     int,
    //  *     ...
    //  * ] </code></pre>
    //  * 
    //  * @todo Implement query
    //  */
    // public function getUserVotes(int $user_id): array
    // {
    //     if ($user_id <= 0) {
    //         return [];
    //     }

    //     $products = $this->execute(
    //         'comparoperator',
    //         'SELECT
    //              `product_id`
    //          FROM
    //              `votes`
    //          WHERE
    //              `user_id` = ?;',
    //         [$user_id]
    //     );

    //     return array_column($products, 'product_id');
    // }

    // /**
    //  * Get comments for a given product.
    //  * 
    //  * @api ComparOperatorAPI
    //  * 
    //  * @param  int $product_id
    //  * 
    //  * @return array <pre><code>[
    //  *     [
    //  *         'comment_id'     => int,
    //  *         'product_id'     => int,
    //  *         'user_id'        => int,
    //  *         'name'           => string,
    //  *         'created_at'     => string date('Y-m-d H:i:s'),
    //  *         'content'        => string
    //  *     ], 
    //  *     ...
    //  * ] </code></pre>
    //  * 
    //  * @todo Implement query
    //  */
    // public function getProductComments(int $product_id): array
    // {
    //     return [
    //         [
    //             'comment_id'     => 1,
    //             'product_id'     => $product_id,
    //             'user_id'        => 1,
    //             'name'           => 'JeanPlaceHaut-le-Der',
    //             'created_at'     => '2020-05-10 07:01:00',
    //             'content'        => 'This is a placeholder comment.'
    //         ],
    //         [
    //             'comment_id'     => 2,
    //             'product_id'     => $product_id,
    //             'user_id'        => 1,
    //             'name'           => 'JeanPlaceHaut-le-Der',
    //             'created_at'     => '2020-05-10 07:01:01',
    //             'content'        => 'This is another placeholder comment.'
    //         ]
    //     ];
    // }

    // /**
    //  * Register a vote for given user on given product.
    //  * 
    //  * @api ComparOperatorAPI
    //  * 
    //  * @param  int $user_id
    //  * @param  int $product_id
    //  * 
    //  * @return int Given product updated votes count.
    //  */

    // public function vote(int $user_id, int $product_id): int
    // {
    //     if (($user_id <= 0) || ($product_id <= 0)) {
    //         return [];
    //     }

    //     $insert_result = $this->execute(
    //         'comparoperator',
    //         'INSERT INTO `votes`(
    //             `product_id`,
    //             `user_id`,
    //             `created_at`
    //         )
    //         VALUES(?, ?, ?);',
    //         [$product_id, $user_id, date('Y-m-d H:i:s')]
    //     );


    //     $result = $this->execute(
    //         'comparoperator',
    //         'SELECT
    //              COUNT(`product_id`) AS votes_count
    //          FROM
    //              `votes`
    //          WHERE
    //              `product_id` = ?;',
    //         [$product_id]
    //     );

    //     return $result[0]['votes_count'];
    // }

    // /**
    //  * Register a comment for given user on given product.
    //  * 
    //  * @api ComparOperatorAPI
    //  * 
    //  * @param  int $user_id
    //  * @param  int $product_id
    //  * @param  string $comment
    //  * 
    //  * @return array <pre><code>[
    //  *     'comment_id'     => int,
    //  *     'product_id'     => int,
    //  *     'user_id'        => int,
    //  *     'name'           => string,
    //  *     'created_at'     => string date('Y-m-d H:i:s'),
    //  *     'content'        => string
    //  * ] </code></pre>
    //  * 
    //  * @todo Implement query
    //  */
    // public function comment(
    //     int $user_id,
    //     int $product_id,
    //     string $comment
    // ): array {
    //     return [
    //         'comment_id'     => 1,
    //         'product_id'     => $product_id,
    //         'user_id'        => $user_id,
    //         'name'           => 'JeanPlaceHaut-le-Der',
    //         'created_at'     => date('Y-m-d H:i:s'),
    //         'content'        => $comment
    //     ];
    // }
}
