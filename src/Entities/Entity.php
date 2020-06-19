<?php

declare(strict_types=1);

namespace Entities;

use Interfaces\Validatable;

/**
 * 
 */
abstract class Entity implements Validatable
{
    /**
     * @var array [ string $field_name => mixed $value ]
     */
    public $data;

    /**
     * @var array [ string $field_name => mixed $filter_definition ]
     */
    const definitions = [];

    /**
     * List of field names required for insertion in database.
     * 
     * @var array string[]
     */
    const required_fields = [];

    /**
     * @var bool
     */
    protected $is_valid;


    /**
     * Create a new Entity instance.
     * 
     * @param  array $data [ string $field_name => mixed $value ]
     */
    public function __construct(array $data)
    {
        $this->data = $data;
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
        return static::definitions;
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
     * @todo Consider that $this->is_valid is obsolete and misleading if 
     *       anything fiddles with $this->data.
     * @todo Consider either not storing a misleading is_valid state or
     *       or invalidating it in a $this->data setter and restricted access.
     * 
     * @return bool
     */
    public function isValid(): bool
    {
        $constant = static::class . '::definitions';
        return $this->is_valid ?? !in_array(
            false,
            filter_var_array(
                $this->getData(),
                static::definitions
            ),
            true // strict
        );
    }

    /**
     * Determine if this Entity's required fields are valid according to this 
     * Entity's definition.
     * 
     * @note
     *   Casting $field to bool is not satisfactory given php behaviour.
     * 
     *   Use strict comparison against false or NULL as filter_var_array yields 
     *   a filtered value, false or NULL for missing keys.
     * 
     * @see https://www.php.net/manual/en/types.comparisons.php
     * @see EntityTest.php for all the gotchas with FILTER_VALIDATE_BOOLEAN.
     * 
     * @todo Document the fact that false is a reserved value in Entity
     * @todo Consider using int instead.
     * @todo Consider FILTER_NULL_ON_FAILURE.
     * 
     * @return bool
     */
    public function hasValidRequiredFields(): bool
    {
        $valid = true;
        $filtered = filter_var_array(
            $this->getData(),
            static::definitions
        );

        foreach (static::required_fields as $field) {

            $valid = $valid
                && isset($filtered[$field])
                && (($filtered[$field] !== false)
                    || (static::definitions[$field]['filter'] === FILTER_VALIDATE_BOOLEAN));
        }

        return $valid;
    }
    /**
     * Return this Entity's filtered data, do not change internal state.
     * 
     * @return array [ string $field_name => mixed $value ]
     */
    public function getFiltered(): array
    {
        return filter_var_array($this->getData(), static::definitions);
    }

    /**
     * Apply filters on this Entity's data, change internal state.
     * 
     * @note
     *   Raw data of a field is lost if its filter fails !
     * 
     * @return $this
     * 
     * @todo Figure out how to work with self return type and inheritance or
     *       drop it.
     */
    public function validate()
    {
        $this->data = filter_var_array($this->getData(), static::definitions);
        $this->is_valid = !in_array(
            false,
            $this->data,
            true // strict
        );
        return $this;
    }
}
