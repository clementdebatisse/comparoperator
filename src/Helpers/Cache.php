<?php

declare(strict_types=1);

namespace Helpers;

use Controllers\Controller;

/**
 * note
 *   If a cache file exists and is referenced in the trove and is not past its 
 *   expiration date, it is considered valid and Dispatcher will serve it.
 * 
 * @todo - [x] Forbid instancing multiple Cache with the same name
 */
class Cache
{
    /**
     * Path to root cache folder.
     *
     * @var string
     */
    const CACHE_PATH = ROOT . 'cache/';

    /**
     * 'Set' of currently instanced caches.
     * 
     * Used to forbid instancing multiple Cache with the same name.
     * 
     * @var array [string $name => 1]
     */
    private static $list = [];

    /**
     * Cache name.
     *
     * @var string
     */
    public $name;

    /**
     * Time To Live in seconds before an item in cache is considered stale.
     *
     * A specific ttl is configurable per response status code.
     *
     * @var array <pre><code>[
     *         200       => int
     *         400       => int,
     *         404       => int,
     *         ... 
     *         'default' => int
     *     ...
     * ] </code></pre>
     * 
     * @todo Consider storing ttl in each CacheItem for fine-grained tuning.
     * @todo Consider storing expires_at in each CacheItem wether using coarse
     *       or fine grained ttl values.
     */
    protected $ttl = 30;

    /**
     * Path to this cache .ttl file.
     *
     * .ttl files are used to store serialized version of the Cache trove and as
     * persistent tickers to determine when pruning is due.
     * 
     * @var string
     */
    protected $ttl_path;

    /**
     * Odds that pruning will be actually triggered when calling pruneCache().
     * 
     * Used to throttle possibly expensive cache operations. Does not affect
     * cache invalidation.
     * 
     * @var array 2-tuple [int $chance, $int out_of]
     */
    protected $prune_odds;

    /**
     * Cache metadata collection.
     * 
     * @todo Decide if keeping a flipped [$filename => $key] as a ghetto set 
     *       is worth it to speed up unique filename collision detection
     * 
     * @var array [string $key, CacheItem $item]
     */
    protected $trove;

    /**
     * Create a new Cache instance.
     * 
     * Caller must check and get the actual Cache name used and must NOT assume 
     * the one it requested was free.
     * 
     * @param integer $ttl
     * @param string $name

     * @return void
     */
    public function __construct(string $name)
    {
        /* make sure a unique cache name is used */
        $this->name = $name;
        while (isset(Cache::$list[$this->name])) {
            $this->name = $name . uniqid();
        }
        Cache::$list[$this->name] = 1;
    }

        
    /**
     * List currently instanced Cache by name.
     *
     * @return array
     */
    public static function listCaches() : array {
        return array_keys(Cache::$list);
    }
    
    /**
     * Determine if a Cache instance of given name exists.
     *
     * @param  string $name
     * @return bool
     */
    public static function exists(string $name) : bool {
        return (isset(Cache::$list[$name]));
    }
    
    /**
     * Load metadata persisted to disk, if any, into the Cache trove.
     *
     * @return $this
     */
    public function load() : self
    {
        return $this;
    }

    /**
     * Persist Cache trove metadata to disk.
     *
     * @return $this
     */
    protected function save() : self
    {
        return $this;
    }
    /**
     * Retrieve cached content for given key if it exists and is not stale.
     *
     * @param  string $key
     * @return string|NULL
     */
    public function getCached(string $key): ?string
    {
        if (
            isset($this->trove[$key])
            && (time() < $this->trove[$key]['expiry_date'])
            && is_file(
                $cached_file =
                    self::CACHE_PATH . $this->trove[$key]['filename'] . '.' . $this->name
            )
        ) {

            return file_get_contents($cached_file);
        } else {
            return NULL;
        }
    }

    /**
     * Determine if a valid cached version exists for given key.
     *
     * @return boolean
     */
    public function isCached($key): bool
    {
        return (isset($this->trove[$key])
            && (time() < $this->trove[$key]['expiry_date'])
            && is_file(
                self::CACHE_PATH . $this->trove[$key]['filename'] . '.' . $this->name
            ));
    }

    /**
     *  note
     *    Will erase content of cached file if used before route()
     *    Subsequent route() may serve that cached empty files
     */
    // public function cache(): self
    // {
    //     ($this->controller ?? $this->load())->cache();

    //     $cache_ttl_path = self::CACHE_PATH . 'cache.ttl';
    //     if (!is_file($cache_ttl_path)) {
    //         touch($cache_ttl_path);
    //     }
    //     $this->pruneCache();

    //     return $this;
    // }

    /**
     * Create cache.ttl ticker if it does NOT exist
     * If at least a ttl period has elapsed since last pruning
     *   Delete each cached files past ttl
     *   Reset ticker
     * 
     * todo
     *   - [ ] Implement probabilistic early expiration.
     *   - [ ] Implement subsequent pre-emptive re-rendering.
     *     + [ ] Throttle/delay pre-emptive re-rendering according to server 
     *           load.
     *     + [ ] See https://www.php.net/manual/en/function.proc-nice.php
     *   - [ ] Consider locking a Cache item so that only process at a time can
     *         populate it. 
     *     + [ ] Serve stale or keep concurrent process/requests on hold until
     *           lock is released.
     *   - [ ] Limit Cache to a size or number of CacheItem.
     *     + [ ] Toss CacheItem based on freshness/popularity.
     * 
     * note
     *   The goal is to pre-emptively re-render often requested pages that have
     *   been rendered and cached once they expire or are invalidated.
     *  
     *   ~~Structure the cached file names as follow :~~
     *      ~~sanitized_query_string.popularity_counter.extension~~
     *      
     *   Where popularity_counter is an integer incremented each time that page
     *   is requested and decremented each time it is pre-emptively re-rendered.
     * 
     *   Use popularity_counter to tune the probability distribution of the 
     *   cache pruning lottery.
     * 
     *   Check if a request page exists in cache by populating an array of
     *   exploded filenames present in the cache folder and checking for the
     *   requested page existence in the [0] column.
     * 
     *   Alternatively, populate an array and write to cache.ttl on exit. This
     *   means that scanning directory is done when there's no rush.
     * 
     *   Solve the filename length problem by storing the query in json,
     *   assigning fixed length unique id to use a file name for each query.
     * 
     *   See file_put_contents($filename, $contents, LOCK_EX);
     * 
     *  e.g.
     * ,
     *    "controller%3DHome@Cookie:Value" : {
     *      "filename" : "5eb62a757a56e0.61116934.html",
     *      "tags" : ["tagA", "tagB"],
     *      "popularity" : 5,
     *      "render_time" : 0.0010299682617188,
     *      "expires_at" : 1590134476,
     *    },
     * 
     *  
     * 
     *   *__IMPORTANT__*
     *   All of this operations happens after the page as been served.
     * 
     * 
     * time() - $this->ttl) > filemtime($cache_ttl_path)
     * 
     *  function x-fetch(key, ttl, beta=1) {
     * value, delta, expiry ← cache_read(key)
     * if (!value || time() − delta * beta * log(rand(0,1)) ≥ expiry) {
     *   start ← time()
     *   value ← recompute_value()
     *   delta ← time() – start
     *   cache_write(key, (value, delta), ttl)
     * }
     * return value
     *      
     */
    public function pruneCache(): self
    {
        $cache_ttl_path = self::CACHE_PATH . 'cache.ttl';

        if (!is_file($cache_ttl_path)) {
            touch($cache_ttl_path);
        } else {
            if ((time() - $this->ttl) > filemtime($cache_ttl_path)) {
                // echo 'Pruning cache !';
                $cached_pages = glob(self::CACHE_PATH . '*.html');

                foreach ($cached_pages as $cached_page) {
                    if ((time() - $this->ttl) > filemtime($cached_page)) {
                        unlink($cached_page);
                    }
                }

                touch($cache_ttl_path);
            }
        }

        return $this;
    }

    /**
     * Clear all files associated with this Cache, no questions asked.
     *
     * @return $this
     */
    public function clearCache(): self
    {
        $cached_pages = glob(self::CACHE_PATH . '*.' . $this->name);

        foreach ($cached_pages as $cached_page) {
            unlink($cached_page);
        }

        unlink($this->ttl_path);

        return $this;
    }
}
