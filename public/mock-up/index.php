<?php

/**
 * Setup
 * Retrieve configuration
 * Run the app !
 * 
 * @note
 *   This is is the main entry point
 * 
 * @todo
 *   - [x] Redirect to parameterized index.php
 *   - [x] Use Dispatcher to call Controller/Action/Param
 *   - [x] Use Controller to request, filter, hand over Model data
 *   - [x] Use View to compose model data over layout, template
 *     + [x] Inline css, js when rendering layout, templates
 *   - [x] Plug in Model
 *     + [ ] Use Validatable interface to check Entity going in and out
 *   - [x] Use a configuration file
 *     + [x] Define a named constant to force config update
 *   - [x] Implement a simple file cache
 *     + [ ] Allow for Controller, Model, View to invalidate cached files
 *     + [ ] Handle getting hammered with requests that resolve to a valid
 *           Controller but end up swamping the cache because of distinct
 *           query strings filled with junk parameters
 *     + [x] Handle requests resolving to super long file name more gracefully
 *     + [x] Check if all characters allowed in a query string are valid in
 *           a filename
 *       - [ ] Consider a rewrite rule or some validation
 *     + [x] Use the configured components as controller white list
 *     + [x] Use existing controller methods as an action white list
 *   - [x] Consider supporting Deferred components that are rendered via Js 
 *         hooks and placeholders after all regular components are fist pushed 
 *         and painted.
 *   - [x] Consider Deferred components via Js/Ajax
 *   - [ ] Test run Templates using and rendering other Templates
 *   - [ ] Add a project specific QueryString builder to simplify link creation
 *   - [ ] Write the test suite Entity->isValid(), validate() deserves
 *   - [ ] Investigate CORS issue with font preloading
 */

declare(strict_types=1);

define('ROOT', str_replace('public', '', __DIR__));
define('DEV_FORCE_CONFIG_UPDATE', true);
define('DEV_GLOBALS_DUMP', true);

require ROOT . 'src/Helpers/AutoLoader.php';

use Helpers\Dispatcher;
use Helpers\Cache;
//--------------------------------------------------------------- playground

//------------------------------------------------------------------ session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
    /* generate a CRSF guard token */
    if (empty($_SESSION['token'])) {
        $_SESSION['token'] = bin2hex(random_bytes(32));
    }

    /**
     * note
     *   Add an hidden input in forms :
     * 
     *     <input type="hidden" name="token" value="{$_SESSION['token']} />
     * 
     *   Verify token :
     * 
     *     if (!empty($_POST['token'])) {
     *         if (hash_equals($_SESSION['token'], $_POST['token'])){
     *             // good to go
     *         } else {
     *             // something might be up
     *         }
     *     }
     *   
     * 
     *   Use per form :
     *     
     *     hash_hmac('sha256', '/form.php', $_SESSION['another_token']);
     * 
     *     ( see available crypto algos with hash_hmac_algos() )
     */
}
//------------------------------------------------------------------- config
require ROOT . 'src/Helpers/Config.php';
date_default_timezone_set('Europe/Paris');
//--------------------------------------------------------------- playground
// echo is_file($file = 'index.php');
// echo $file;

// echo time() . '<br/>';
// echo get_class(time()) . '<br/>';

// $distribution_factor = 1;
// $render_time = 0.004;
// $log_odd = log(mt_rand() / mt_getrandmax());
// echo time() - $render_time * $distribution_factor * $log_odd;
// exit;

//--------------------------------------------------------------- playground
// define('TEMPLATE', ROOT . 'src/Templates/');

// $hero = [
//     'content' => include TEMPLATE . 'cta_newsletter.php'
// ];

// $layout = [
//     'title' => 'Yacht Share Prestige',
//     'nav' => include TEMPLATE . 'nav.php',
//     'article' => include TEMPLATE . 'hero.php',
//     'footer' => include TEMPLATE . 'footer.php'
// ];
// include ROOT . 'src/Layouts/Layout.php';

//--------------------------------------------------------------- playground
echo '' . date('Y-m-d H:i:s');

echo '' . date('Y-m-d H:i:s');

use Models\ComparOperatorAPI;
use Entities\User;
use Entities\Location;
use Entities\Offering;
use Entities\Operator;
use Entities\Review;
// $controller = new Home(['db_configs' => $config['db_configs']]);
// $pdo = new DBPDO($controller);
$pdo = ComparOperatorAPI::fromConfig($config['db_configs']);
// class SuperUseless {

// }

$iterations = 10;
echo '//--------------------------------------------------------------<br />';
$t = microtime(true);
$i   = 0;

while ($i < $iterations) {
    $raw_reviews = $pdo->execute(
        'comparoperator',
        "SELECT
             `review_id`,
             `destination_id`,
             `operator_id`,
             `user_id`,
             `created_at`,
             `rating`,
             `message`
         FROM
             `reviews`",
    );
    $reviews = [];
    // foreach($raw_users as $raw_user) {
    for ($u = 0, $count = count($raw_reviews); $u < $count; $u++) {
        $reviews[] = new Review($raw_reviews[$u]);
    }
    $index =  $i % count($reviews);
    $filtered = $reviews[$index]->getFiltered();
    $data_array = $reviews[$index]->getData();
    ++$i;
}
echo '<pre>' . var_export(count($reviews), true) . '</pre><hr />';
echo '<pre>' . var_export($reviews[0], true) . '</pre><hr />';
echo '<pre>' . var_export($data_array, true) . '</pre><hr />';
echo '<pre>' . var_export($filtered, true) . '</pre><hr />';
echo '//--------------------------------------------------------------<br />';
$t = microtime(true);
$i   = 0;

while ($i < $iterations) {
    $raw_operators = $pdo->execute(
        'comparoperator',
        "SELECT
             `operator_id`,
             `name`,
             `website`,
             `logo`,
             `is_premium`
         FROM 
             `operators`",
    );
    $operators = [];
    // foreach($raw_users as $raw_user) {
    for ($u = 0, $count = count($raw_operators); $u < $count; $u++) {
        $operators[] = new Operator($raw_operators[$u]);
    }
    $index =  $i % count($operators);
    $filtered = $operators[$index]->getFiltered();
    $data_array = $operators[$index]->getData();
    ++$i;
}
echo '<pre>' . var_export(count($operators), true) . '</pre><hr />';
echo '<pre>' . var_export($operators[0], true) . '</pre><hr />';
echo '<pre>' . var_export($data_array, true) . '</pre><hr />';
echo '<pre>' . var_export($filtered, true) . '</pre><hr />';
echo '//--------------------------------------------------------------<br />';
$t = microtime(true);
$i   = 0;

while ($i < $iterations) {
    $raw_offerings = $pdo->execute(
        'comparoperator',
        "SELECT
             `destinations`.`destination_id`,
             `destinations`.`price`,
             `destinations`.`thumbnail`,
             `destinations`.`operator_id`,
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
             `operators`.`operator_id`",
    );
    $offerings = [];
    // foreach($raw_users as $raw_user) {
    for ($u = 0, $count = count($raw_offerings); $u < $count; $u++) {
        $offerings[] = new Offering($raw_offerings[$u]);
    }
    $index =  $i % count($offerings);
    $filtered = $offerings[$index]->getFiltered();
    $data_array = $offerings[$index]->getData();
    ++$i;
}
echo '<pre>' . var_export(count($offerings), true) . '</pre><hr />';
echo '<pre>' . var_export($offerings[0], true) . '</pre><hr />';
echo '<pre>' . var_export($data_array, true) . '</pre><hr />';
echo '<pre>' . var_export($filtered, true) . '</pre><hr />';
echo '//--------------------------------------------------------------<br />';
$t = microtime(true);
$i   = 0;

while ($i < $iterations) {
    $raw_locations = $pdo->execute(
        'comparoperator',
        "SELECT
            `location` AS `name`,
            `thumbnail`,
            COUNT(DISTINCT `destination_id`) AS `offering_count`
         FROM
            `destinations`
         GROUP BY
            `location`",
    );
    $locations = [];
    // foreach($raw_users as $raw_user) {
    for ($u = 0, $count = count($raw_locations); $u < $count; $u++) {
        $locations[] = new Location($raw_locations[$u]);
    }
    $index =  $i % count($locations);
    $filtered = $locations[$index]->getFiltered();
    $data_array = $locations[$index]->getData();
    ++$i;
}
echo '<pre>' . var_export(count($locations), true) . '</pre><hr />';
echo '<pre>' . var_export($locations[0], true) . '</pre><hr />';
echo '<pre>' . var_export($data_array, true) . '</pre><hr />';
echo '<pre>' . var_export($filtered, true) . '</pre><hr />';


$iterations = 10;
echo '//--------------------------------------------------------------<br />';
$t = microtime(true);
$i   = 0;

while ($i < $iterations) {

    // $a = $test_entity->getData();
    // $test_entity->user_id = $i;
    $raw_users = $pdo->execute(
        'comparoperator',
        "SELECT
            `user_id`,
            `name`,
            `created_at`,
            `ip`
         FROM `users`",
    );
    $users = [];
    // foreach($raw_users as $raw_user) {
    for ($u = 0, $count = count($raw_users); $u < $count; $u++) {
        $users[] = new User($raw_users[$u]);
    }
    $user_index =  $i % count($users);
    $filtered = $users[$user_index]->getFiltered();
    $data_array = $users[$user_index]->getData();
    ++$i;
}
echo '<pre>' . var_export(count($users), true) . '</pre><hr />';
echo '<pre>' . var_export('from Query User w Assoc Array for : ' . (microtime(true) - $t), true) . '</pre>';
// echo '<pre>' . var_export($users[0], true) . '</pre><hr />';
// echo '<pre>' . var_export($data_array, true) . '</pre><hr />';
// echo '<pre>' . var_export($filtered, true) . '</pre><hr />';
echo '//--------------------------------------------------------------<br />';
$t = microtime(true);
$i   = 0;

while ($i < $iterations) {

    // $a = $test_entity->getData();
    // $test_entity->user_id = $i;
    $raw_users = $pdo->execute(
        'comparoperator',
        "SELECT
            `user_id`,
            `name`,
            `created_at`,
            `ip`
         FROM `users`",
    );
    $users = [];
    foreach ($raw_users as $raw_user) {
        $users[] = new User($raw_user);
    }
    $user_index =  $i % count($users);
    $filtered = $users[$user_index]->getFiltered();
    $data_array = $users[$user_index]->getData();
    ++$i;
}
echo '<pre>' . var_export(count($users), true) . '</pre><hr />';
echo '<pre>' . var_export('from Query User w Assoc Array fe : ' . (microtime(true) - $t), true) . '</pre>';
// echo '<pre>' . var_export($users[0], true) . '</pre><hr />';
// echo '<pre>' . var_export($data_array, true) . '</pre><hr />';
// echo '<pre>' . var_export($filtered, true) . '</pre><hr />';

$iterations = 100;
$count = count($users);
echo '//--------------------------------------------------------------<br />';
$t = microtime(true);
$i   = 0;

while ($i < $iterations) {

    // $a = $test_entity->getData();
    // $test_entity->user_id = $i;
    $users = [];
    for ($j = 0; $j < $count; $j++) {
        $users[] =  new \Entities\User(
            [
                'user_id' => $j,
                'name' => 'El Guapo',
                'created_at' =>  date('Y-m-d H:i:s'),
                'ip' => inet_pton('127.0.0.1'),
            ]
        );
    }
    $user_index =  $i % count($users);
    $filtered = $users[$user_index]->getFiltered();
    $data_array = $users[$user_index]->getData();

    ++$i;
}
echo '<pre>' . var_export(count($users), true) . '</pre><hr />';
echo '<pre>' . var_export('from Data User w Assoc Array: ' . (microtime(true) - $t), true) . '</pre>';
// echo '<pre>' . var_export($users[0], true) . '</pre><hr />';
echo '<pre>' . var_export($data_array, true) . '</pre><hr />';
echo '<pre>' . var_export($filtered, true) . '</pre><hr />';

//---------------------------------------------------------------------- run
$t = microtime(true);

$dispatcher = new Dispatcher($config);
// $dispatcher->route()->cache();
// $dispatcher->route();

$time_spent['serving_page'] = (microtime(true) - $t);

//------------------------------------------------------------------- config
require ROOT . 'src/Helpers/SerializeConfig.php';
//-------------------------------------------------------------------- debug
require ROOT . 'src/Helpers/DebugInfo.php';
