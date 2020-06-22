<?php

declare(strict_types=1);

namespace Entities;

/**
 * 
 */
class User extends Entity
{
    /**
     * @var array <string, mixed>[] [$field_name => $filter_definition]
     */
    const definitions =
    [
        'user_id' => [
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
        'ip' => [
            'filter' => FILTER_CALLBACK,
            'options' => 'inet_ntop'
        ]
    ];

    /**
     * List of field names required for insertion in database.
     * 
     * @var array string[]
     */
    const required_fields = [
        'name',
        'created_at',
        'ip'
    ];

    // public function isValid2(): bool
    // {
    //     if ($this->is_valid === null) {
    //         // $user_id = $this->data['user_id'];
    //         $this->is_valid = true
    //             && isset(
    //                 $this->data['user_id'],
    //                 $this->data['name'],
    //                 $this->data['created_at'],
    //                 $this->data['ip'],
    //                 )
    //             && ((int) $this->data['user_id'] === $this->data['user_id'])
    //             && ($this->data['user_id'] >= 1) 
    //             && ($this->data['user_id'] <= 16777215)
    //             && preg_match('/^([A-Za-z0-9_\-\s]+)$/', $this->data['name'])
    //             && preg_match('/^[0-9]{4}-[0-1][0-9]-[0-3][0-9] [0-2][0-9]:[0-5][0-9]:[0-5][0-9]$/', $this->data['created_at'])
    //             && inet_ntop($this->data['ip']);
    //     }
    //     return $this->is_valid;
    // }
    // /**
    //  * Create a new User instance.
    //  * 
    //  * @param  array $data [ string $field_name => mixed $value ]
    //  */
    // public function __construct(array $data)
    // {
    //     $this->data = $data;

    //     /**
    //      * @note This is cute but pollutes 'hydration' with extra steps at
    //      *       each instantiation ( and possibly other unforeseen side-effect
    //      *       like messing with reference counting ).
    //      */
    //     // $this->user_id = &$this->data['user_id'];
    //     // $this->name = &$this->data['name'];
    //     // $this->created_at = &$this->data['created_at'];
    //     // $this->ip = &$this->data['ip'];
    // }

    /**
     * @todo Consider how much boilerplate/ceremony is needed to define access,
     *      mutation on each property of each entity.
     * @todo Consider how much boilerplate/ceremony is needed to juggle between
     *       filterable assoc array form and oop form.
     * @todo Consider rolling with a backing data array and providing convenient
     *       oop form via magic __set/__get in abstract parent class.
     */
    // /**
    //  * Return this User's user_id.
    //  * 
    //  * @return int
    //  */
    // public function getUserId(): int
    // {
    //     return $this->data['user_id'];
    // }
    // /**
    //  * Sets this User's user_id and invalidates existing data validation if any.
    //  * 
    //  * 
    //  * @param int $user_id
    //  * @return void
    //  */
    // public function setUserId(int $user_id): void
    // {
    //     $this->is_valid = null;
    //     $this->data['user_id'] = $user_id;
    // }
}
