<?php

/**
 * 
 */

declare(strict_types=1);

namespace Templates;

use Interfaces\Templatable;

/**
 * todo
 *   - [x] Add js spinner script once and only if an Image or RemoteImage
 *         component is used
 */
class Image implements Templatable
{
    protected $data;

    /**
     * note
     *   Use with local image only ( or get spinners ! )
     * 
     *   Image tries to provide relevant values for width, height if none are
     *   given with the caveat that it requires the script to waste time to load
     *   the complete images to determine its size
     * 
     * todo
     *   - [ ] Implement a RemoteImage component
     *   - [ ] Implement a InlinedSvg component
     */
    public function __construct(
        string $path = 'images/icons/spinner.svg',
        string $alt = '',
        ?int $width = NULL,
        ?int $height = NULL,
        string $class = '',
        bool $lazy = true
    ) {
        // $img_path = ROOT . $path;

        // if (is_file($img_path)) {
        //     $this->data['path'] = $path;
        // } else {
        //     $this->data['path'] = 'images/icons/spinner.svg';
        //     $width = 64;
        //     $height = 64;
        // }
        $this->data['path'] = $path;
        $this->data['alt'] = $alt;

        if (is_null($width) || is_null($height)) {
            [$this->data['width'], $this->data['height']] = self::getSize($path);
        }else{
            $this->data['width'] = $width;
            $this->data['height'] = $height;
        }

        $this->data['class'] = $class;
        $this->data['lazy'] = $lazy;
    }

    /**
     * note
     *   Only handles format supported by php getimagesize() and .svg
     */
    public static function getSize(string $img_path): array
    {
        $img_path_extension = pathinfo($img_path, PATHINFO_EXTENSION);

        if (strcasecmp($img_path_extension, 'svg') === 0) {
            $svg = simplexml_load_file($img_path);

            if (is_null($svg['width']) || is_null($svg['height'])) {
                /**
                 * note
                 *   This assumes viewBox value uses either space or comma as 
                 *   value separator !
                 * 
                 * todo
                 *   - [ ] Check svg specs for all possible formats
                 */
                $viewbox = (string) $svg['viewBox'];
                strpos(',', $viewbox) ? $separator = ',' : $separator = ' ';
                $viewbox = explode($separator, (string) $svg['viewBox']);

                $width = intval($viewbox[2]);
                $height = intval($viewbox[3]);
            } else {
                $width = intval((string) $svg['width']);
                $height = intval((string) $svg['height']);
            }
        } else {
            [$width, $height] = getimagesize($img_path);
        }

        return [$width, $height];
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
        $this->data['lazy'] ? $lazy = 'loading="lazy"' : $lazy = '';
        
        return <<<TEMPLATE
<img
    src="{$this->data['path']}"
    alt="{$this->data['alt']}"
    width="{$this->data['width']}"
    height="{$this->data['height']}"
    class="{$this->data['class']}"
    {$lazy}
/>
TEMPLATE;
    }
}
