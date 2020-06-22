<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class UserTest extends TestCase
{
    /**
     * @test
     */
    public function valid_data()
    {
        $data = [
            'user_id' => 1,
            'name' => 'El Guapo',
            'created_at' => date('Y-m-d H:i:s'),
            'ip' => inet_pton('127.0.0.1'),
        ];
        $this->assertNotEmpty($data);
        return $data;
    }

    public function invalid_data()
    {
        return [
            [['user_id' => 0]],
            [['user_id' => -1]],
            [['user_id' => (16777215 + 1)]],
            [['user_id' => '']],
            [['user_id' => null]],
            [['name' => '']],
            [['name' => null]],
            [['name' => '/z<mlk>']],
            [['created_at' => '']],
            [['created_at' => null]],
            [['created_at' => date('Y-m-d i:s')]],
            [['ip' => '']],
            [['ip' => null]],
            [['ip' => '127.0.01']],
        ];
    }

    public function invalid_required()
    {
        return [
            [['name' => '']],
            [['name' => null]],
            [['name' => '/z<mlk>']],
            [['created_at' => '']],
            [['created_at' => null]],
            [['created_at' => date('Y-m-d i:s')]],
            [['ip' => '']],
            [['ip' => null]],
            [['ip' => '127.0.01']],
        ];
    }

    public function entity_definition_keys()
    {
        $cases = [];
        foreach (array_keys(\Entities\User::definitions) as $case) {
            $cases[] = [$case];
        }
        return $cases;
    }

    /**
     * @test
     * @depends clone valid_data
     */
    public function User_instanced_with_valid_data_validates_itself($valid_data): void
    {
        /* Given */
        $entity =  new \Entities\User($valid_data);

        /* When  */

        /* Then */
        $this->assertTrue($entity->isValid());
    }

    /**
     * @test
     * @depends clone valid_data
     * @dataProvider invalid_data
     */
    public function User_instanced_with_invalid_data_invalidates_itself($invalid_data, $valid_data): void
    {
        /* Given */
        $data = $invalid_data + $valid_data;
        $entity =  new \Entities\User($data);

        /* When  */

        /* Then */
        $this->assertFalse($entity->isValid());
    }

    /**
     * @test
     * @depends clone valid_data
     */
    public function User_instanced_with_valid_data_validates_its_required_fields($valid_data): void
    {
        /* Given */
        $entity =  new \Entities\User($valid_data);

        /* When  */

        /* Then */
        $this->assertTrue($entity->hasValidRequiredFields());
    }

    /**
     * @test
     * @depends clone valid_data
     */
    public function User_instanced_with_valid_required_fields_and_other_invalid_fields_validates_its_required_fields($valid_data): void
    {
        /* Given */
        $data =  ['user_id' => 0] + $valid_data;
        $entity =  new \Entities\User($data);

        /* When  */

        /* Then */
        $this->assertTrue($entity->hasValidRequiredFields());
    }

    /**
     * @test
     * @depends clone valid_data
     * @dataProvider invalid_required
     */
    public function User_instanced_with_invalid_required_fields_invalidates_its_required_fields($invalid_data, $valid_data): void
    {
        /* Given */
        $data = $invalid_data + $valid_data;
        $entity =  new \Entities\User($data);

        /* When  */

        /* Then */
        $this->assertFalse($entity->hasValidRequiredFields());
    }

    /**
     * @test
     * @depends clone valid_data
     * @dataProvider entity_definition_keys
     */
    public function Accessing_a_property_existing_in_backing_array_returns_value_from_backing_array($key, $valid_data): void
    {
        /* Given */
        $entity =  new \Entities\User($valid_data);

        /* When  */
        $is_equivalent = ($entity->$key === $entity->data[$key]);

        /* Then */
        $this->assertTrue($is_equivalent);
    }

    /**
     * @test
     * @depends clone valid_data
     * @dataProvider entity_definition_keys
     */
    public function Setting_a_property_invalidates_previous_data_validation_if_any($key, $valid_data): void
    {
        /* Given */
        $entity =  new \Entities\User($valid_data);
        $was_valid = $entity->isValid();
        // echo PHP_EOL . var_export($key, true);
        
        /* When  */
        $entity->$key = null;
        $is_no_longer_valid = !$entity->isValid();

        /* Then */
        $this->assertTrue($was_valid && $is_no_longer_valid);
    }
}
