<?php

declare(strict_types=1);

namespace Entities;

/**
 * 
 */
class Operator extends Entity
{
    /**
     * @var array <string, mixed>[] [$field_name => $filter_definition]
     */
    const definitions =
    [
        'operator_id' => [
            'filter' => FILTER_VALIDATE_INT,
            'options' => [
                'min_range' => 1,
                'max_range' => 16777215
            ]
        ],
        'name' => [
            'filter' => FILTER_VALIDATE_REGEXP,
            'options' => ['regexp' => '/^([A-Za-z0-9_\-\s]+)$/']
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
        'name',
        'website',
        'logo'
    ];
}
