<?php

/**
 * 
 */

declare(strict_types=1);

namespace Entities;

use Interfaces\Validatable;

class Entity implements Validatable
{
    /**
     * @var array [ string $field_name => mixed $value ]
     */
    public $data;

    /**
     * @var array [ string $field_name => mixed $filter_definition ]
     */
    protected $definitions;

    /**
     * @var bool
     */
    protected $is_valid;

    /**
     * Create a new generic Entity instance from a given associative array.
     * 
     * @note
     *   Use with class inheriting from Entity where the definitions are already
     *   specified.
     * 
     * @param  array $data [ string $field_name => mixed $value ]
     * @return Entity
     */
    public static function fromData(array $data): self
    {
        $name =  static::class;
        return new $name($data);
    }

    /**
     * Create a new generic Entity instance from a given associative array.
     * 
     * @note
     *   Use with class inheriting from Entity where the definitions are already
     *   specified.
     * 
     * @param  array $data [ string $field_name => mixed $value ]
     * @return Entity
     */
    public static function fromDataAsProperties(array $data): self
    {
        $name =  static::class;
        $entity = new $name($data);
        foreach ($data as $key => $value) {
            $entity->$key = $value;
        }
        return $entity;
    }

    /**
     * Create a new generic Entity instance.
     * 
     * @note
     *   Default filter only allows a string containing 
     *   A-Z, a-z, 0-9, space, underscore, dash, period.
     * 
     * @see https://www.php.net/manual/en/function.filter-var-array.php
     * 
     * @param  array $data [ string $field_name => mixed $value ]
     * @param  array $definitions [ string $field_name => mixed $filter_definition ]
     */
    public function __construct(array $data, array $definitions = [])
    {
        $this->data = $data;
        $this->definitions = $definitions;

        foreach (array_keys($data) as $field) {
            $this->definitions[$field] = isset($definitions[$field])
                ? $definitions[$field]
                : [
                    'filter' => FILTER_VALIDATE_REGEXP,
                    'options' => ['regexp' => '/^([A-Za-z0-9_\-\s\.]+)$/']
                ];
        }
    }

    /**
     * Return this Entity's data.
     * 
     * @return array [ string $field_name => mixed $value ]
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * Return this Entity's definition.
     * 
     * @return array [ string $field_name => mixed $value ]
     */
    public function getDefinitions(): array
    {
        return $this->definitions;
    }

    /**
     * Determine if this Entity's data is valid according to this Entity's
     * definition.
     * 
     * @note
     *   Casting $field to bool is not satisfactory given php behaviour
     * 
     *   Use strict comparison against false or NULL as filter_var_array yields 
     *   a filtered value, false or NULL for missing keys 
     * 
     * @See https://www.php.net/manual/en/types.comparisons.php
     * 
     * @return bool
     */
    public function isValid(): bool
    {
        return $this->is_valid ?? !in_array(
            false,
            filter_var_array($this->getData(), $this->definitions),
            true // strict
        );
    }

    /**
     * Return this Entity's filtered data, do not change internal state.
     * 
     * @return array [ string $field_name => mixed $value ]
     */
    public function getFiltered(): array
    {
        return filter_var_array($this->getData(), $this->definitions);
    }

    /**
     * Apply filters on this Entity's filtered data, change internal state.
     * 
     * @note
     *   Raw data of a field is lost if its filter fails !
     * 
     * @return $this
     */
    public function validate(): self
    {
        $this->data = filter_var_array($this->getData(), $this->definitions);
        $this->is_valid = !in_array(
            false,
            $this->data,
            true // strict
        );
        return $this;
    }
}
