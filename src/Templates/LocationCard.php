<?php

/**
 * 
 */

declare(strict_types=1);

namespace Templates;

use Interfaces\Templatable;

/**
 * 
 */
class LocationCard implements Templatable
{
    protected $data;

    /**
     * Create a new LocationCard instance.
     * 
     * @note
     *   Uses bootstrap Card component.
     *
     * @param \Template\Image $thumbnail
     * @param string $name
     * @param string $description
     * @param int $offering_count
     * 
     * @return void
     */
    public function __construct(
        Image $thumbnail,
        string $name,
        string $description,
        int $offering_count
    ) {
        $this->data = [
            'thumbnail' => $thumbnail,
            'name' => $name,
            'description' => $description,
            'offering_count' => $offering_count,
        ];
    }

    /**
     * 
     */
    public function getRaw(): array
    {
        return $this->data;
    }

    /**
     * 
     */
    public function render(): string
    {
        return <<<TEMPLATE
<div class="col-md-6 col-xl-4 my-4">
    <div class="card overflow-hidden">
        {$this->data['thumbnail']->render()}
        <div class="card-body">
            <h5 class="card-title text-dark text-center">
                <u>{$this->data['name']}</u>
            </h5>
            <p class="card-text text-secondary text-center">
                {$this->data['description']}
            </p>
            <a href="#" class="btn btn-primary text-white">
                {$this->data['offering_count']} offres
            </a>
        </div>
    </div>
</div>
TEMPLATE;
    }
}
