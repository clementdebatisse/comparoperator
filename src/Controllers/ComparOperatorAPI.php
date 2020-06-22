<?php

/**
 * 
 */

declare(strict_types=1);

namespace Controllers;

use Exception;
use PDOException;
use Models\Model;

/**
 * 
 */
final class ComparOperatorAPI extends API
{
    /**
     * 
     */
    public function runDefault(array $args = []): void
    {
        /* set model, args, ...then call() */
        $this->set($args);

        /* use existing model or load one */
        $this->model ?? $this->loadModel();
        $this->call();
    }

    /**
     *
     */
    public function runProduct(array $args = []): void
    {
        /* set model, args, ...then call() */
        $args['endpoint'] = 'Product';
        $this->set($args);

        /* use existing model or load one */
        $this->model ?? $this->loadModel();
        $this->call();
    }


    /**
     *
     */
    public function runComment(array $args = []): void
    {
        /* set model, args, ...then call() */
        $args['endpoint'] = 'Comment';
        $this->set($args);

        /* use existing model or load one */
        $this->model ?? $this->loadModel();
        $this->call();
    }

    /**
     *
     */
    public function runVote(array $args = []): void
    {
        /* set model, args, ...then call() */
        $args['endpoint'] = 'Vote';
        $this->set($args);

        /* use existing model or load one */
        $this->model ?? $this->loadModel();
        $this->call();
    }

    /**
     * note
     *   Overriding Controller->cache()
     */
    public function cache(): Controller
    {
        if ($this->rendered_page !== '') {
            $cached_file = fopen($this->cache_path, 'w');
            fwrite($cached_file, $this->rendered_page);
            fclose($cached_file);
        }

        return $this;
    }

    /**
     * RESTish API : Register a vote for given user on given product.
     * 
     * @api ComparOperatorAPI
     * @endpoint Vote
     * @mode POST 
     * 
     * @query ?controller=ComparOperatorAPI&endpoint=Vote&user_id={id}&product_id={id}
     *
     * @return array to be emitted as json by ComparOperatorAPI controller
     * 
     * @Example
     * @Response STATUS 200 - application/json
     * OK
     * <pre><code>
     * [132]
     * </code></pre>
     * 
     * @Response STATUS 204 - application/json
     * No Content
     * <pre><code>
     * []
     * </code></pre>
     * 
     * @Response STATUS 400 - application/json
     * Bad Request
     * <pre><code>
     * ['ERROR : missing or invalid parameter product_id.']
     * ['ERROR : missing or invalid parameter user_id.']
     * ['ERROR : vote has already been cast for this used_id, product_id.']
     * ['ERROR : user_id or product_id does NOT exist, cannot cast vote.']
     * </code></pre>
     * 
     * @Response STATUS 500 - application/json
     * Internal Server Error
     * <pre><code>
     * ['ERROR : ...']
     * </code></pre>
     * 
     * @todo Translate PDO/MySQL errors into meaningful response for the
     *       RESTish API.
     * @todo Add a json cache/buffer.
     * @todo Have a look at PDO statement errorInfo().
     * @todo Handle situation where vote has already been cast.
     */
    protected function restPOSTVote(): ?array
    {
        if (!isset($this->args['user_id']) || !is_numeric($this->args['user_id'])) {
            /* Bad Request */
            $this->args['status_code'] = 400;
            return ['ERROR : missing or invalid parameter user_id.'];
        }

        if (!isset($this->args['product_id']) || !is_numeric($this->args['product_id'])) {
            /* Bad Request */
            $this->args['status_code'] = 400;
            return ['ERROR : missing or invalid parameter product_id.'];
        }

        $user_id = intval($this->args['user_id'] ?? 0);
        $product_id = intval($this->args['product_id'] ?? 0);

        try {
            $results = [$this->model->vote($user_id, $product_id)];

            if (empty($results)) {
                /* Not Found */
                $this->args['status_code'] = 404;
            } else {
                /* OK */
                $this->args['status_code'] = 200;
            }

            /* useful only if you want to do more than emit json */
            // $this->args['data'] = $results;

            return $results;
        } catch (PDOException | Exception $e) {
            $error_msg = $e->getMessage();

            $duplicate_entry = 'Integrity constraint violation: 1062 Duplicate entry';
            if (strpos($error_msg, $duplicate_entry) !== false) {
                /* Bad Request */
                $this->args['status_code'] = 400;
                return ['ERROR : vote already been cast for this user_id, product_id.'];
            }

            $fk_constraint_fail = 'Integrity constraint violation: 1452';
            if (strpos($error_msg, $fk_constraint_fail) !== false) {
                /* Bad Request */
                $this->args['status_code'] = 400;
                return ['ERROR : user_id or product_id does NOT exist, cannot cast vote.'];
            }

            /* Internal Server Error */
            $this->args['status_code'] = 500;
            return [$error_msg];
        }
    }

    /**
     * RESTish API : Get product content associated to a given product id.
     * 
     * @api ComparOperatorAPI
     * @endpoint Product
     * @mode GET 
     * @query ?controller=ComparOperatorAPI&endpoint=Product&product_id={id}
     *
     * @return array to be emitted as json by ComparOperatorAPI controller
     * 
     * @Example
     * @Response STATUS 200 - application/json
     * OK
     * <pre><code>
     * {
     *   "article_id": 1,
     *   "product_id": 5,
     *   "content": "Rewind displays your bookmarks filtered by date, with thumbnails and instant search. It takes one click to see the links you saved yesterday, last week, last month. It's totally free and it relies on your local bookmarks, you don't have to create an account.",
     *   "media": [
     *     "images/products/1_Rewind_0.webp",
     *     "images/products/1_Rewind_1.webp",
     *     "images/products/1_Rewind_2.webp",
     *     "images/products/1_Rewind_3.webp",
     *     "images/products/1_Rewind_4.webp"
     *   ],
     *   "comments": [
     *     {
     *       "comment_id": 1,
     *       "product_id": 5,
     *       "user_id": 1,
     *       "name": "JeanPlaceHaut-le-Der",
     *       "created_at": "2020-05-10 07:01:00",
     *       "content": "This is a placeholder comment."
     *     },
     *     {
     *       "comment_id": 2,
     *       "product_id": 5,
     *       "user_id": 1,
     *       "name": "JeanPlaceHaut-le-Der",
     *       "created_at": "2020-05-10 07:01:01",
     *       "content": "This is another placeholder comment."
     *     }
     *   ]
     * }
     * </code></pre>
     * 
     * @Response STATUS 204 - application/json
     * No Content
     * <pre><code>
     * []
     * </code></pre>
     * 
     * @Response STATUS 400 - application/json
     * Bad Request
     * <pre><code>
     * ['ERROR : missing or invalid parameter product_id']
     * </code></pre>
     * 
     * @Response STATUS 500 - application/json
     * Internal Server Error
     * <pre><code>
     * ['ERROR : ...']
     * </code></pre>
     * 
     * @todo Translate PDO/MySQL errors into meaningful response for the
     *       RESTish API.
     * @todo Add a json cache/buffer.
     * @todo Have a look at PDO statement errorInfo().
     */
    protected function restGETProduct(): ?array
    {

        if (!isset($this->args['product_id']) || !is_numeric($this->args['product_id'])) {
            /* Bad Request */
            $this->args['status_code'] = 400;
            return ['ERROR : missing or invalid parameter product_id'];
        }

        $product_id = intval($this->args['product_id'] ?? 0);

        try {
            $product = $this->model->getProduct($product_id);
            $comments = ['comments' => $this->model->getProductComments($product_id)];
            // $results = array_merge($product, $comments);
            $results = $comments + $product;

            if (empty($results)) {
                /* Not Found */
                $this->args['status_code'] = 404;
            } else {
                /* OK */
                $this->args['status_code'] = 200;
            }

            /* useful only if you want to do more than emit json */
            // $this->args['data'] = $results;

            return $results;
        } catch (Exception $e) {
            /* Internal Server Error */
            $this->args['status_code'] = 500;
            return [$e->getMessage()];
        }
    }

    /**
     * RESTish API : Get most recent products.
     * 
     * @api ComparOperatorAPI
     * @endpoint Product
     * @sub-resource Fresh
     * @mode GET
     * 
     * Pagination option is available on this resource.
     * 
     * @query ?controller=ComparOperatorAPI&endpoint=Product&sub=Fresh
     * 
     * @optional &maxResults= 
     *           How many products to return (default = 10).
     * 
     * @optional &startAt= 
     *           How many products to skip   (default = 0).
     * 
     * @return array to be emitted as json by ComparOperatorAPI controller
     * 
     * @Example
     * @Response STATUS 200 - application/json
     * OK
     * <pre><code>
     * [
     *   {
     *     "product_id": 1,
     *     "name": "Rewind",
     *     "created_at": "2020-05-10 07:01:00",
     *     "website": "https://rewind.netlify.app/?ref=producthunt",
     *     "summary": "Your bookmarks, by date, with thumbnails and instant search",
     *     "thumbnail": "images/products/thumbnails/1_Rewind.webp",
     *     "votes_count": 0,
     *     "comments_count": 0
     *   },
     *   {
     *     "product_id": 2,
     *     "name": "Buy For Life",
     *     "created_at": "2020-05-10 07:01:00",
     *     "website": "https://rewind.netlify.app/?ref=producthunt",
     *     "summary": "Your bookmarks, by date, with thumbnails and instant search",
     *     "thumbnail": "images/products/thumbnails/2_Buy-For-Life.webp",
     *     "votes_count": 0,
     *     "comments_count": 0
     *   }
     * ] </code></pre>
     * 
     * @Response STATUS 204 - application/json
     * No Content
     * <pre><code>
     * []
     * </code></pre>
     * 
     * @Response STATUS 500 - application/json
     * Internal Server Error
     * <pre><code>
     * ['ERROR : ...']
     * </code></pre>
     * 
     * @todo Translate PDO/MySQL errors into meaningful response for the
     *       RESTish API.
     * @todo Add a json cache/buffer.
     * @todo Have a look at PDO statement errorInfo().
     */
    protected function restGETProductFresh(): ?array
    {
        $count = (isset($this->args['maxResults'])) ?
            intval($this->args['maxResults'])
            : 10;

        $offset = (isset($this->args['startAt'])) ?
            intval($this->args['startAt'])
            : 0;
        // $order_by = $this->args['orderBy'] ?? 'created_at';

        try {
            $results = $this->model->getFreshProducts($count, $offset);

            if (empty($results)) {
                /* No Content */
                $this->args['status_code'] = 204;
            } else {
                /* OK */
                $this->args['status_code'] = 200;
            }

            /* useful only if you want to do more than emit json */
            // $this->args['data'] = $results;

            return $results;
        } catch (Exception $e) {
            /* Internal Server Error */
            $this->args['status_code'] = 500;
            return [$e->getMessage()];
        }
    }

    /**
     * RESTish API : Get most popular products.
     * 
     * @api ComparOperatorAPI
     * @endpoint Product
     * @sub-resource Popular
     * @mode GET
     *
     * Pagination option is available on this resource.
     * 
     * @query ?controller=ComparOperatorAPI&endpoint=Product&sub=Popular
     * 
     * @optional &maxResults= 
     *           How many products to return (default = 10).
     * 
     * @optional &startAt= 
     *           How many products to skip   (default = 0).
     * 
     * @return array to be emitted as json by ComparOperatorAPI controller
     * 
     * @Example
     * @Response STATUS 200 - application/json
     * OK
     * <pre><code>
     * [
     *   {
     *     "product_id": 1,
     *     "name": "Rewind",
     *     "created_at": "2020-05-10 07:01:00",
     *     "website": "https://rewind.netlify.app/?ref=producthunt",
     *     "summary": "Your bookmarks, by date, with thumbnails and instant search",
     *     "thumbnail": "images/products/thumbnails/1_Rewind.webp",
     *     "votes_count": 0,
     *     "comments_count": 0
     *   },
     *   {
     *     "product_id": 2,
     *     "name": "Buy For Life",
     *     "created_at": "2020-05-10 07:01:00",
     *     "website": "https://rewind.netlify.app/?ref=producthunt",
     *     "summary": "Your bookmarks, by date, with thumbnails and instant search",
     *     "thumbnail": "images/products/thumbnails/2_Buy-For-Life.webp",
     *     "votes_count": 0,
     *     "comments_count": 0
     *   }
     * ] </code></pre>
     * 
     * @Response STATUS 204 - application/json
     * No Content
     * <pre><code>
     * []
     * </code></pre>
     * 
     * @Response STATUS 500 - application/json
     * Internal Server Error
     * <pre><code>
     * ['ERROR : ...']
     * </code></pre>
     * 
     * @todo Translate PDO/MySQL errors into meaningful response for the
     *       RESTish API.
     * @todo Add a json cache/buffer.
     * @todo Have a look at PDO statement errorInfo().
     */
    protected function restGETProductPopular(): ?array
    {
        $count = (isset($this->args['maxResults'])) ?
            intval($this->args['maxResults'])
            : 10;

        $offset = (isset($this->args['startAt'])) ?
            intval($this->args['startAt'])
            : 0;

        // $order_by = $this->args['orderBy'] ?? 'created_at';

        try {
            $results = $this->model->getPopularProducts($count, $offset);

            if (empty($results)) {
                /* No Content */
                $this->args['status_code'] = 204;
            } else {
                /* OK */
                $this->args['status_code'] = 200;
            }

            /* useful only if you want to do more than emit json */
            // $this->args['data'] = $results;

            return $results;
        } catch (Exception $e) {
            /* Internal Server Error */
            $this->args['status_code'] = 500;
            return [$e->getMessage()];
        }
    }
}
