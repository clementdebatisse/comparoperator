<?php

declare(strict_types=1);

namespace Views;

use Controllers\Controller;
use Templates\Nav;
use Templates\Footer;
use Templates\Image;
use Templates\InlinedCss;
use Templates\LocationCard;

/**
 * 
 */
class Home extends View
{
    /**
     * @var \Entities\Locations|]
     */
    private $locations = [];
    /**
     * Define defaults, take arguments
     */
    public function __construct(Controller $controller)
    {
        parent::__construct($controller);

        $this->locations = &$this->args['data']['locations'];
    }
    /**
     * todo
     *   - [ ] Build from data
     */
    public function compose(): self
    {

        $thumbnail_size = Image::getSize(
            ROOT . 'public/' . $this->locations[0]->data['thumbnail']
        );

        $cards = [];
        foreach ($this->locations as $location) {
            $cards[] = new LocationCard(
                new Image(
                    $location->data['thumbnail'],
                    $location->data['name'],
                    $thumbnail_size[0],
                    $thumbnail_size[1],
                    'card-img-top img-fluid'
                ),
                $location->data['name'],
                'Description de ' . $location->data['name'],
                $location->data['offering_count']
            );
        }

        $this->components['css'] = [
            // new InlinedCss([ROOT.'resources/css/style.min.css']),
            new InlinedCss(['css/style.css'])
        ];

        $this->components['nav'] = [
            new Nav(
                new Image('images/icons/logo.png', 'ComparOperator', 30, 30),
                [
                    'Home' => 'index.php?controller=Home',
                    'Admin' => '?controller=Dashboard&action=Admin',
                    'Operator' => '?controller=Dashboard&action=Admin',
                ],
                0,
                /**
                 * @todo Check how stacking a query like that with a GET form
                 *       works.
                 */
                'index.php',
                'Sign up',
                'index.php?controller=Home&action=Signup',
            )
        ];


        $this->components['content'] = $cards;

        $this->components['ads'] = [$cards[0]];


        $this->components['footer'] = [
            new Footer([
                (new Image('images/icons/logo.png', 'ComparOperator', 30, 30))
                    ->render() => 'index.php?controller=Home',
                'Home' => 'index.php?controller=Home',
                'Admin' => '?controller=Dashboard&action=Admin',
                'Operator' => '?controller=Dashboard&action=Admin',
            ])
        ];
        return $this;
    }
}
