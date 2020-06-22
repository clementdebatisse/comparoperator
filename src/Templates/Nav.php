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
class Nav implements Templatable
{
    protected $data;

    /**
     * Create a new Nav instance.
     * 
     * @note
     *   Uses bootstrap navbar component.
     *
     * @param \Template\Image $logo
     * @param <string, string>[] $links content to display => href
     * @param int $active_link
     * @param string $search_action
     * @param string $button_text
     * @param string $button_href
     * 
     * @return void
     */
    public function __construct(
        Image $logo,
        array $links = ['Project Name' => 'index.php?controller=Home'],
        int $active_link,
        string $search_action,
        string $button_text,
        string $button_href
    ) {
        $this->data = [
            'logo' => $logo,
            'links' => $links,
            'active_link' => (
                ($active_link >= 0) && ($active_link < count($links)))
                ? $active_link : 0,
            'search_action' => $search_action,
            'button_text' => $button_text,
            'button_href' => $button_href,
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
    private function renderLinks(): string
    {
        $i = 0;
        $rendered_links = '';
        foreach ($this->data['links'] as $link => $href) {
            $rendered_links .= ($i === $this->data['active_link']) ?

                <<<ACTIVE_LINK
<a class="nav-item nav-link active" href="{$href}">
{$link} <span class="sr-only">(current)</span>
</a>
ACTIVE_LINK

                : <<<LINK
<a class="nav-item nav-link" href="{$href}">{$link}</a>
LINK;

            $i++;
        }
        return $rendered_links;
    }
    /**
     * 
     */
    public function render(): string
    {
        return <<<TEMPLATE
<nav
    class="navbar fixed-top navbar-expand-sm navbar-light bg-white"
>
    <a class="navbar-brand" href="/">
        {$this->data['logo']->render()}
    </a>
    <button
        class="navbar-toggler"
        type="button"
        data-toggle="collapse"
        data-target="#navbarNavAltMarkup"
        aria-controls="navbarNavAltMarkup"
        aria-expanded="false"
        aria-label="Toggle navigation"
    >
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
        <div class="navbar-nav">
            {$this->renderLinks()}
        </div>

        <form
            class="form-inline input-group"
            id="search"
            method="GET"
            action="{$this->data['search_action']}"
        >
            <input
                class="form-control col-3"
                type="text"
                name="search"
                placeholder="..."
                aria-label="Search"
            />
            <div class="input-group-append">
                <button class="btn btn-success" type="submit">Search</button>
            </div>
        </form>

        <a 
            class="btn btn-secondary ml-auto w-25" 
            href="{$this->data['button_href']}" 
            role="button"
        >
            {$this->data['button_text']}
        </a>
    </div>
</nav>
TEMPLATE;
    }
}
