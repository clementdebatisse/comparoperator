<?php

declare(strict_types=1);

namespace Entities;

/**
 * 
 */
class Offering extends Entity
{
    /**
     * @var array <string, mixed>[] [$field_name => $filter_definition]
     */
    const definitions =
    [
        'destination_id' => [
            'filter' => FILTER_VALIDATE_INT,
            'options' => [
                'min_range' => 1,
                'max_range' => 16777215
            ]
        ],
        'operator_id' => [
            'filter' => FILTER_VALIDATE_INT,
            'options' => [
                'min_range' => 1,
                'max_range' => 16777215
            ]
        ],
        'created_at' => [
            'filter' => FILTER_VALIDATE_REGEXP,
            'options' => [
                /**
                 * @note
                 *   This validates the format but does NOT check for
                 *   impossible dates.
                 */
                'regexp' => '/^[0-9]{4}-[0-1][0-9]-[0-3][0-9] [0-2][0-9]:[0-5][0-9]:[0-5][0-9]$/'
            ]
        ],
        'location' => [
            'filter' => FILTER_VALIDATE_REGEXP,
            'options' => ['regexp' => '/^([A-Za-z0-9_\-\s]+)$/']
        ],
        'price' => [
            'filter' => FILTER_VALIDATE_FLOAT
        ],
        'thumbnail' => [
            'filter' => FILTER_VALIDATE_REGEXP,
            'options' => [
                'regexp' => '/^(?!.*--|.*\/\/|.*\\\\\\\\|.*\.\.)[\\\\\/A-Za-z0-9_\-\.]+\.(?:jpg|jpeg|gif|svg|webp|png)$/'
            ],
        ],
        'operator' => [
            'filter' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
        ],
        'website' => [
            'filter' => FILTER_VALIDATE_URL
        ],
        'logo' => [
            'filter' => FILTER_VALIDATE_REGEXP,
            'options' => [
                'regexp' => '/^(?!.*--|.*\/\/|.*\\\\\\\\|.*\.\.)[\\\\\/A-Za-z0-9_\-\.]+\.(?:jpg|jpeg|gif|svg|webp|png)$/'
            ],
        ],
        'is_premium' => [
            'filter' => FILTER_VALIDATE_INT,
            'options' => [
                'min_range' => 0,
                'max_range' => 1
            ]
        ]
    ];

    /**
     * List of field names required for insertion in database.
     * 
     * @var array string[]
     */
    const required_fields = [
        'operator_id',
        'created_at',
        'location',
        'price',
        'thumbnail'
    ];
}
