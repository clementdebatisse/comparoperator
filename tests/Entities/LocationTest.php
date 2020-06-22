<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class LocationTest extends TestCase
{
    /**
     * @test
     */
    public function valid_data()
    {
        $data = [
            'name' => 'Berlin',
            'thumbnail' => 'images/products/destinations/0012.jpg',
            'offering_count' => 3,
        ];
        $this->assertNotEmpty($data);
        return $data;
    }

    public function invalid_data()
    {
        return [
            [['name' => '']],
            [['name' => null]],
            [['name' => '/z<mlk>']],
            [['thumbnail' => '/z<mlk>']],
            [['thumbnail' => null]],
            [['thumbnail' => '//images/products/destinations/0012.jpg']],
            [['thumbnail' => '/images/products/destinations/0012 .jpg']],
            [['thumbnail' => '/images--/products/destinations/0012.jpg']],
            [['thumbnail' => '/images/products/destinations/0012.doc']],
            [['offering_count' => 3.5]],
            [['offering_count' => '3z']],
            [['offering_count' => null]],
        ];
    }

    public function entity_definition_keys()
    {
        $cases = [];
        foreach (array_keys(\Entities\Location::definitions) as $case) {
            $cases[] = [$case];
        }
        return $cases;
    }

    /**
     * @test
     * @depends clone valid_data
     */
    public function Location_instanced_with_valid_data_validates_itself($valid_data): void
    {
        /* Given */
        $entity =  new \Entities\Location($valid_data);

        /* When  */
        
        /* Then */
        $this->assertTrue($entity->isValid());
    }

    /**
     * @test
     * @depends clone valid_data
     * @dataProvider invalid_data
     */
    public function Location_instanced_with_invalid_data_invalidates_itself($invalid_data, $valid_data): void
    {
        /* Given */
        // $data = array_merge($valid_data, $invalid_data);
        $data = $invalid_data + $valid_data;
        $entity =  new \Entities\Location($data);

        /* When  */

        /* Then */
        $this->assertFalse($entity->isValid());
    }

    /**
     * @test
     * @depends clone valid_data
     */
    public function Location_instanced_with_valid_data_validates_its_required_fields($valid_data): void
    {
        /* Given */
        $entity =  new \Entities\Location($valid_data);


        /* When  */

        /* Then */
        $this->assertTrue($entity->hasValidRequiredFields());
    }

    // /**
    //  * @test
    //  * @depends clone valid_data
    //  */
    // public function Location_instanced_with_invalid_required_fields_invalidates_its_required_fields($valid_data): void
    // {
    //     /* Given */
    //     $entity =  new \Entities\Location($valid_data);

    //     /* When  */

    //     /* Then */
    //     $this->assertFalse($entity->hasValidRequiredFields());
    // }

    /**
     * @test
     * @depends clone valid_data
     * @dataProvider entity_definition_keys
     */
    public function Accessing_a_property_existing_in_backing_array_returns_value_from_backing_array($key, $valid_data): void
    {
        /* Given */
        $entity =  new \Entities\Location($valid_data);

        /* When  */

        /* Then */
        $is_equivalent = ($entity->$key === $entity->data[$key]);
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
        $entity =  new \Entities\Location($valid_data);
        $was_valid = $entity->isValid();

        /* When  */
        $entity->$key = null;

        /* Then */
        $is_no_longer_valid = !$entity->isValid();
        $this->assertTrue($was_valid && $is_no_longer_valid);
    }
}
