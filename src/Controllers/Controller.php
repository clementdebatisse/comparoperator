<?php

/**
 * 
 */

declare(strict_types=1);

namespace Controllers;

// use Interfaces\Loadable;
use Models\Model;
use Views\View;

/**
 * 
 */
abstract class Controller //implements Loadable
{
    public $args = [];

    protected $model;
    protected $view;
    protected $layout = 'Minimal';

    protected $rendered_page = '';

    public function __construct(array $args = [])
    {
        $this->args = $args;

        /* Get default associated view, model name */
        /**
         * @note 
         *   Use get_class() if you need evalution at runtime.
         *   Use ::class if you need evalution at compile time.
         */
        // $associated_class = get_class($this);
        $associated_class = static::class;
        $namespace_end = strrpos($associated_class, '\\');
        $associated_class = substr($associated_class, $namespace_end + 1);

        /**
         * note 
         *   Store class names but defer loading
         *   Allow something downstream to change the associated model/view
         *   Lazy load -> when and if needed at all
         */
        $this->args['model'] = $associated_class;
        $this->args['view'] = $associated_class;
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
     * 
     */
    public function loadModel(): Model
    {
        $model_name = '\Models\\' . $this->args['model'];
        // unset($this->args['model']);
        $this->model = new $model_name($this);

        return $this->model;
    }
    /**
     * 
     */
    public function loadView(): View
    {
        $view_name = '\Views\\' . $this->args['view'];
        // unset($this->args['view']);
        $this->view = new $view_name($this);

        return $this->view;
    }

    /**
     * note
     *   Output buffering is no longer required as everything is composed and
     *   rendered before emitting the page once
     */
    public function serve(): self
    {
        /* import collected 'variables' in current context */
        // extract($this->args);

        /* output buffering ON */
        // ob_start();
        $computed_content = ($this->view ?? $this->loadView())
            ->compose()
            ->render();

        /* view may set the layout */
        $layout_name = '\Layouts\\' . $this->layout;
        $layout = new $layout_name($computed_content);

        $rendered_page = $layout->render();
        echo $rendered_page;

        /* keep it around for optional caching */
        $this->rendered_page = $rendered_page;

        return $this;
    }

    /**
     * note
     *   May be called on a Controller who never (or not yet) received args,
     *   in which case it does nothing
     */
    public function cache(): self
    {

        if (isset($this->args['cached_file'])) {
            $cached_file = fopen($this->args['cached_file'], 'w');
            fwrite($cached_file, $this->rendered_page);
            fclose($cached_file);
            // file_put_contents($this->args['cached_file'], $this->rendered_page);
        }
        return $this;
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
    abstract public function __call(string $name, array $arguments): void;


    /**
     * note
     *   Prepend all actions meant to be callable by a request with 'run'
     */
    abstract public function runDefault(array $args);
}
