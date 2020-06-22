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
class Home implements Templatable
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
            'ads' => '',
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
          <div class="container-fluid">
            {$this->data['nav']}
        
            <main class="row mt-5">
              <div class="col-md-10">
                <div class="row">
                    {$this->data['content']}
                </div>
              </div>
              
              <div class="col-md-2 mt-4">
                    <img 
                        class="card-img-top img-fluid rounded mb-3" 
                        src="images/destinations/0005.jpg" 
                        alt="Prenium TO" />
                    <h5 class="card-title font-weight-bold text-center">
                        <u>Dubaï ♔</u>
                    </h5>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item text-center">
                            Description brève
                        </li>
                        <li class="list-group-item text-center">
                            Pourquoi prenium ?
                        </li>
                        <li class="list-group-item text-center">
                            <a href="#" class="card-link text-primary">
                                Redirection
                            </a>
                        </li>
                    </ul>
              </div>
            </main>
        
            {$this->data['footer']}
          </div>
          <script>
            !function(){"use strict";const images=[...document.querySelectorAll("img")];function removeSpinner(event){event.currentTarget.classList.remove("loading")}for(let i=0,length=images.length;i<length;i++)images[i].complete||(images[i].classList.add("loading"),images[i].addEventListener("load",(function(event){removeSpinner(event)}),!1))}();
          </script>
          {$this->data['js']}
        
          <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
          <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
          <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
        </body>
        </html>
TEMPLATE;
    }
}
