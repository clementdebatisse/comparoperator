<?php

/**
 * 
 */

declare(strict_types=1);

namespace Layouts;

use Interfaces\Templatable;

/**
 * 
 */
class Minimal implements Templatable
{
    public $data = [];

    /**
     * note
     *   Provide innocuous default value to make the template displayable
     */
    public function __construct(array $rendered_components = [])
    {
        $defaults = [
            'page_title' => 'hello-php',
            'fonts' => '',
            'css' => '',
            'nav' => '',
            'content' => '',
            'footer' => '',
            'js' => ''
        ];
        // $this->data = array_replace($defaults, $rendered_components);
        $this->data = $rendered_components + $defaults;
    }

    /**
     * 
     */
    public function getRaw(): array
    {
        return $this->data;
    }

    /**
     * const img = event.currentTarget;
     * setInterval(function () {
     *     removeSpinner(img);
     * }, 2000);
     */
    public function render(): string
    {
        return <<<TEMPLATE
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{$this->data['page_title']}></title>
    {$this->data['fonts']}
    <style>
    .loading {filter: opacity(50%);background : transparent url('images/icons/spinner.svg')  no-repeat scroll center center; background-blend-mode: multiply;}
    </style>
    {$this->data['css']}
</head>

<body>
    {$this->data['nav']}

    {$this->data['content']}
    
    {$this->data['footer']}

    <script>
    !function(){"use strict";const images=[...document.querySelectorAll("img")];function removeSpinner(event){event.currentTarget.classList.remove("loading")}for(let i=0,length=images.length;i<length;i++)images[i].complete||(images[i].classList.add("loading"),images[i].addEventListener("load",(function(event){removeSpinner(event)}),!1))}();
    </script>
    {$this->data['js']}
</body>
</html>
TEMPLATE;
    }
}
