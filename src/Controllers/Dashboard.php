<?php

/**
 * 
 */

declare(strict_types=1);

namespace Controllers;

use Models\Model;
use Views\View;


/**
 * 
 */
class Dashboard extends Controller
{

    // public function __construct()
    // {
    //     parent::__construct();
    //     echo '<pre>Home()</pre>';
    // }

    /**
     * 
     */
    public function runDefault(array $args = []): void
    {
        $this->set($args);
        $this->serve();
    }

    /**
     * Fallback action called when the Dispatcher resolves the url/request to
     * a route that does NOT exist.
     * 
     * @example Serve a 404 page.
     * 
     * @param  string $name
     * @param  array $arguments
     * @return void
     * 
     * @todo Research if that use case for __call is ill-advised.
     */    
    public function __call(string $name, array $arguments): void
    {
        /* Dispatcher passed array $args is accessible in $arguments */
        // $this->args['model'] = $associated_class;
        $this->args['view'] = 'Error404';
        // $this->layout = 'OptionalSpecificLayout';
        $this->serve();
    }

    /**
     * note
     *   from simplon-tp-product-hunt
     */
    // public function runLogout(array $args = []): void
    // {
    //     if (isset($_COOKIE['user_name'])) {
    //         setcookie('user_name', '', strtotime('-1 year'), '/');
    //     }

    //     header('Refresh: 0; url=/', TRUE, 302);
    //     exit();
    // }
}
