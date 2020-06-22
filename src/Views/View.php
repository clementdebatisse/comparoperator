<?php

/**
 * 
 */

declare(strict_types=1);

namespace Views;

use Controllers\Controller;
use Interfaces\Layoutable;

/**
 * 
 */
abstract class View implements Layoutable
{
    public $args = [];
    
    protected $data = [];
    protected $components = [];

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
         *     + [ ] Bench, pick one, move on
         * 
         * note
         *   Error: Cannot access protected property Controllers\Home::$args 
         *          in /shared/httpd/hello-php/htdocs/src/Views/View.php
         *   -> it is a copy of unknown depth
         *   -> error is about visibility
         */
        $this->args = &$controller->args;
    }

    /**
     * 
     */
    public function set(array $args): self
    {
        // $this->args = array_merge(
        //     $this->args,
        //     $args
        // );
        $this->args = $args + $this->args;
        return $this;
    }

    /**
     * todo
     *   - [ ] Discard $this->data if you do NOT process it before emitting it
     *         to $this->args['data']
     */
    public function getRaw(): array
    {
        return [
            'data' => $this->data,
            'components' => $this->components
        ];
    }
    /**
     * Render components
     *   -> [string name => string rendered component]
     * 
     * todo
     *   - [ ] Consider some form of memoization to avoid rendering the same 
     *         thing used at different places twice
     *     + [ ] Hash components __construct arguments
     *     + [ ] Build a job list with the hash of all components to be rendered
     *     + [ ] Build a renders hashtable [hash => rendered_component]
     *     + [ ] Render a component only if its hash does NOT exist in the table
     *     + [ ] Emit renders walking down the job list reading from the table
     *     + [ ] Note that this only possible if render is pure
     *     + [ ] Note that hash collision may occur and needs to be checked
     *   - [ ] See https://www.php.net/manual/en/book.memcached.php
     */
    public function render(): array
    {
        $batch_names = array_keys($this->components);
        $batch_count = count($batch_names);
        $i = 0;

        while ($i < $batch_count) {
            $batch_name = $batch_names[$i];

            $rendered_batch = '';

            $component_count = count($this->components[$batch_name]);
            $j = 0;
            while ($j < $component_count) {
                $rendered_batch .= $this->components[$batch_name][$j]->render();
                $j++;
            }

            $this->components[$batch_name] = $rendered_batch;
            $i++;
        }

        // foreach ($this->components as $key => $batch) {
        //     $rendered_batch = '';

        //     foreach ($batch as $component) {
        //         $rendered_batch .= $component->render();
        //     }

        //     $this->components[$key] = $rendered_batch;
        // }
        return $this->components;
    }
    /**
     * 
     */
    abstract public function compose();
}
