<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class GenericTest extends TestCase
{
    /**
     * @test
     */
    public function Generic_instanced_with_valid_data_validates_itself(): void
    {
        /* Given */
        $entity = \Entities\Generic::fromData(
            [
                'user_id' => 1,
                'name' => 'El Guapo',
                'created_at' => '2020',
                'ip' => '127.0.0.1',
            ]
        );
        /* When  */

        /* Then */
        $this->assertTrue($entity->isValid());
    }

    /**
     * @test
     */
    public function Generic_instanced_with_invalid_data_invalidates_itself(): void
    {
        /* Given */
        $entity = \Entities\Generic::fromData(
            [
                'user_id' => 1,
                'name' => 'El Guapo',
                'invalid_entry' => '20:20',
                'ip' => '127.0.0.1',
            ]
        );
        /* When  */

        /* Then */
        $this->assertFalse($entity->isValid());
    }

    /**
     * @test
     * @dataProvider required_boolean_null_on_failure_field_data
     */
    public function Generic_with_a_required_boolean_null_on_failure_field_validates_its_required_fields($data, $expected): void
    {
        /* Given */
        $entity = new \Entities\Generic(
            ['field' => $data],
            ['field' => [
                'filter' => FILTER_VALIDATE_BOOLEAN,
                'flags' => FILTER_NULL_ON_FAILURE,
                // 'options' => [
                //     'default' => null,
                // ]
            ]],
            ['field']

        );
        /* When  */
        // echo PHP_EOL . var_export($data, true)
        //     . "\t:\t" . var_export($entity->getFiltered()['field'], true)
        //     . "\t|\t" . var_export($expected, true)
        //     . "\t:\t" . var_export($entity->hasValidRequiredFields(), true) . PHP_EOL;

        /* Then */
        $this->assertSame($entity->hasValidRequiredFields(), $expected);
    }

    public function required_boolean_null_on_failure_field_data()
    {
        return [
            [true, true],
            [false, true],
            [1, true],
            [0, true],
            "gotcha : '1' is valid as true" => ['1', true],
            "gotcha : '0' is valid as false" => ['0', true],
            "gotcha : 'on' is valid as false" => ['on', true],
            "gotcha : 'off' is valid as false" => ['off', true],
            "gotcha : 'true' is valid as true" => ['true', true],
            "gotcha : 'false' is valid as false" => ['false', true],
            [5, false],
            [-1, false],
            [-1.5, false],
            [1.5, false],
            ['junk', false],
            ['null', false],
            ['php', false],
            ['\n', false],
            ['\0', false],
            ["\0", false],
            'gotcha : null is valid as false' => [null, true],
            'gotcha : empty string is valid as false' => ["", true],
            'gotcha : undefined is valid as false' => [(new stdClass())->p, true],
            [[], false],
            [new stdClass(), false],
            [function () {
                return true;
            }, false],
        ];
    }

    /**
     * @test
     * @dataProvider required_boolean_field_data
     */
    public function Generic_with_a_required_boolean_field_validates_its_required_fields($data, $expected): void
    {
        /* Given */
        $entity = new \Entities\Generic(
            ['field' => $data],
            ['field' => [
                'filter' => FILTER_VALIDATE_BOOLEAN,
            ]],
            ['field']

        );
        /* When  */
        // echo PHP_EOL . var_export($data, true)
        //     . "\t:\t" . var_export($entity->getFiltered()['field'], true)
        //     . "\t|\t" . var_export($expected, true)
        //     . "\t:\t" . var_export($entity->hasValidRequiredFields(), true) . PHP_EOL;

        /* Then */
        $this->assertSame($entity->hasValidRequiredFields(), $expected);
    }

    public function required_boolean_field_data()
    {
        return [
            [true, true],
            [false, true],
            [1, true],
            [0, true],
            "gotcha : '1' is valid as true" => ['1', true],
            "gotcha : '0' is valid as false" => ['0', true],
            "gotcha : 'on' is valid as true" => ['on', true],
            "gotcha : 'off' is valid as false" => ['off', true],
            "gotcha : 'true' is valid as true" => ['true', true],
            "gotcha : 'false' is valid as false" => ['false', true],
            "gotcha : junk int is valid as false" => [5, true],
            "gotcha : junk string is valid as false" => ['junk', true],
            "gotcha : null is valid as false" => [null, true],
            "gotcha : empty string is valid as false" => ["", true],
            "gotcha : undefined is valid as false" => [(new stdClass())->p, true],
            "gotcha : empty array is valid as false" => [[], true],
            "gotcha : object is valid as false" => [new stdClass(), true],
            "gotcha : closure is valid as false" => [function () {
                return;
            }, true],
        ];
    }

    /**
     * @test
     * @dataProvider required_boolean_as_int_field_data
     */
    public function Generic_with_a_required_boolean_as_int_field_validates_its_required_fields($data, $expected): void
    {
        /* Given */
        $entity = new \Entities\Generic(
            ['field' => $data],
            ['field' => [
                'filter' => FILTER_VALIDATE_INT,
                'options' => [
                    'min_range' => 0,
                    'max_range' => 1,
                ]
            ]],
            ['field']

        );
        /* When  */
        // echo PHP_EOL . var_export($data, true)
        //     . "\t:\t" . var_export($entity->getFiltered()['field'], true)
        //     . "\t|\t" . var_export($expected, true)
        //     . "\t:\t" . var_export($entity->hasValidRequiredFields(), true) . PHP_EOL;

        /* Then */
        $this->assertSame($entity->hasValidRequiredFields(), $expected);
    }

    /**
     * @test
     * @dataProvider required_boolean_as_int_field_data
     */
    public function Generic_with_a_required_boolean_as_int_field_defaulting_to_null_validates_its_required_fields($data, $expected): void
    {
        /* Given */
        $entity = new \Entities\Generic(
            ['field' => $data],
            ['field' => [
                'filter' => FILTER_VALIDATE_INT,
                'options' => [
                    'min_range' => 0,
                    'max_range' => 1,
                    'default' => null
                ]
            ]],
            ['field']

        );
        /* When  */
        // echo PHP_EOL . var_export($data, true)
        //     . "\t:\t" . var_export($entity->getFiltered()['field'], true)
        //     . "\t|\t" . var_export($expected, true)
        //     . "\t:\t" . var_export($entity->hasValidRequiredFields(), true) . PHP_EOL;

        /* Then */
        $this->assertSame($entity->hasValidRequiredFields(), $expected);
    }


    public function required_boolean_as_int_field_data()
    {
        return [
            [true, true],
            [1, true],
            [0, true],
            ['1', true],
            ['0', true],
            [false, false],
            ['on', false],
            ['off', false],
            ['true', false],
            ['false', false],
            [5, false],
            [-1, false],
            [-1.5, false],
            [1.5, false],
            ['junk', false],
            ['null', false],
            ['php', false],
            ['\n', false],
            ['\0', false],
            ["\0", false],
            [null, false],
            ["", false],
            [(new stdClass())->p, false],
            [[], false],
            [new stdClass(), false],
            [function () {
                return true;
            }, false],
        ];
    }

    /**
     * @test
     * @dataProvider required_boolean_as_callback
     */
    public function Generic_with_a_required_boolean_as_callback_validates_its_required_fields($data, $expected): void
    {
        /* Given */
        $entity = new \Entities\Generic(
            ['field' => $data],
            ['field' => [
                'filter' => FILTER_CALLBACK,
                'options' => function ($value) {
                    switch ($value) {
                        case '0':
                        case 'false':
                            return 0;
                        case '1':
                        case 'true':
                            return 1;
                        default:
                            return null;
                    }
                    // if (($value === '0') || ($value === '1')) {
                    //     return intval($value);
                    // }
                    // return null;
                }
            ]],
            ['field']

        );
        /* When  */
        // echo PHP_EOL . var_export($data, true)
        //     . "\t:\t" . var_export($entity->getFiltered()['field'], true)
        //     . "\t|\t" . var_export($expected, true)
        //     . "\t:\t" . var_export($entity->hasValidRequiredFields(), true) . PHP_EOL;

        /* Then */
        $this->assertSame($entity->hasValidRequiredFields(), $expected);
    }
    public function required_boolean_as_callback()
    {
        return [
            [true, true],
            [[], true],
            [1, true],
            [0, true],
            ['true', true],
            ['false', true],
            [false, false],
            ['1', true],
            ['0', true],
            ['on', false],
            ['off', false],
            [5, false],
            [-1, false],
            [-1.5, false],
            [1.5, false],
            ['junk', false],
            ['null', false],
            ['php', false],
            ['\n', false],
            ['\0', false],
            ["\0", false],
            [null, false],
            ["", false],
            [(new stdClass())->p, false],
            [new stdClass(), false],
            [function () {
                return true;
            }, false],
        ];
    }

    /**
     * @test
     * @dataProvider required_boolean_as_stricter_callback
     */
    public function Generic_with_a_required_boolean__as_stricter_callback_validates_its_required_fields($data, $expected): void
    {
        /* Given */
        $entity = new \Entities\Generic(
            ['field' => $data],
            ['field' => [
                'filter' => FILTER_CALLBACK,
                'options' => function ($value) {
                    if (($value === '0') || ($value === '1')) {
                        return intval($value);
                    }
                    return null;
                }
            ]],
            ['field']

        );
        /* When  */
        // echo PHP_EOL . var_export($data, true)
        //     . "\t:\t" . var_export($entity->getFiltered()['field'], true)
        //     . "\t|\t" . var_export($expected, true)
        //     . "\t:\t" . var_export($entity->hasValidRequiredFields(), true) . PHP_EOL;

        /* Then */
        $this->assertSame($entity->hasValidRequiredFields(), $expected);
    }
    public function required_boolean_as_stricter_callback()
    {
        return [
            [true, true],
            [[], true],
            [1, true],
            [0, true],
            ['1', true],
            ['0', true],
            ['true', false],
            ['false', false],
            [false, false],
            ['on', false],
            ['off', false],
            [5, false],
            [-1, false],
            [-1.5, false],
            [1.5, false],
            ['junk', false],
            ['null', false],
            ['php', false],
            ['\n', false],
            ['\0', false],
            ["\0", false],
            [null, false],
            ["", false],
            [(new stdClass())->p, false],
            [new stdClass(), false],
            [function () {
                return true;
            }, false],
        ];
    }
}
