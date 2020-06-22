<?php

/**
 * 
 */

declare(strict_types=1);

namespace Models;

use Controllers\Controller;
/**
 * note
 *   Base class Model describes how it is initialized, should be talked to, 
 *   what it gives back, forwards messages and its ability to invalidate cached 
 *   pages
 * 
 *   Where to get, how to get, how to massage the requested data is up to 
 *   derived classes
 */
abstract class Model
{
    public $args = [];
    
    protected $data = [];

    /**
     * @var \Controllers\Controller
     */
    protected $controller;

    public function __construct(Controller $controller)
    {
        /**
         * todo
         *   - [ ] Explore ways of sending message upstream
         */
        $this->controller = $controller;

        /**
         * todo
         *   - [ ] Explore what php does here
         *     + [ ] Figure out if it is a copy (how deep ?), a reference ?
         *     + [ ] Compare modifying it directly if its a reference vs
         *           vs using reference to controller->set()
         * note
         *   Error: Cannot access protected property Controllers\Home::$args 
         *          in /shared/httpd/hello-php/htdocs/src/Views/View.php
         *   -> it is a copy of unknown depth
         *   -> error is about visibility
         */
        $this->args = &$controller->args;
    }

}
