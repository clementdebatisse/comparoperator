<?php

/**
 * 
 */

declare(strict_types=1);

namespace Interfaces;

/**
 * 
 */
interface Validatable
{
    /**
     * Return this Class data.
     * 
     * @return array [ string $field_name => mixed $value ]
     */
    public function getData(): array;

    /**
     * Return this Class definition.
     * 
     * @return array [ string $field_name => mixed $value ]
     */
    public function getDefinitions(): array;

    /**
     * Determine if this Class data is valid according to this Class
     * definition.
     * 
     * @return bool
     */
    public function isValid(): bool;

    /**
     * Return this Class filtered data, do not change internal state.
     * 
     * @return array [ string $field_name => mixed $value ]
     */
    public function getFiltered(): array;

    /**
     * Apply filters on this Class data, change internal state.
     * 
     * @note
     *   Raw data of a field is lost if its filter fails !
     * 
     * @return $this
     */
    public function validate();
}
