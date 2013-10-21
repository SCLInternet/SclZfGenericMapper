<?php
/**
 * SclZfGenericMapper (https://github.com/SCLInternet/SclZfGenericMapper)
 *
 * @link https://github.com/SCLInternet/SclZfGenericMapper for the canonical source repository
 * @license http://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace SclZfGenericMapperTests;

use SclZfGenericMapperTests\TestAssets\Entity\TestEntity;
use SclZfGenericMapperTests\TestAssets\TestMapper;

/**
 * Unit tests for {@see AbstractMapper}.
 *
 * @author Tom Oram <tom@scl.co.uk>
 */
class CommonMapperMethodsTraitTest extends \PHPUnit_Framework_TestCase
{
    protected $mapper;

    /**
     * Set up the instance to be tested.
     *
     * @return void
     */
    protected function setUp()
    {
        $this->mapper = new TestMapper();
        $this->mapper->publicSetPrototype(new TestEntity());
    }

    /*
     * setPrototype()
     */

    public function test_setPrototype_throws_if_prototype_not_object()
    {
        $mapper = new TestMapper();

        $this->setExpectedException(
            'SclZfGenericMapper\Exception\InvalidArgumentException',
            'Expected an object; got "string".'
        );

        $mapper->publicSetPrototype('bad_prototype');
    }

    public function test_setPrototype_throws_if_called_twice()
    {
        $this->setExpectedException(
            'SclZfGenericMapper\Exception\RuntimeException',
            'setPrototype() can only be called once.'
        );

        $this->mapper->publicSetPrototype(new \stdClass());
    }

    /*
     * create()
     */

    public function test_create_returns_instance_of_entity()
    {
        $this->assertInstanceOf(
            'SclZfGenericMapperTests\TestAssets\Entity\TestEntity',
            $this->mapper->create()
        );
    }

    public function test_create_throws_if_prototype_not_set()
    {
        $mapper = new TestMapper();

        $this->setExpectedException(
            'SclZfGenericMapper\Exception\RuntimeException',
            'Prototype entity class has not been set.'
        );

        $mapper->create();
    }

    /*
     * getEntityName()
     */

    public function test_getEntityName_return_name_of_entity()
    {
        $this->assertEquals(
            'SclZfGenericMapperTests\TestAssets\Entity\TestEntity',
            $this->mapper->getEntityName()
        );
    }

    public function test_getEntityName_throws_if_prototype_not_set()
    {
        $mapper = new TestMapper();

        $this->setExpectedException(
            'SclZfGenericMapper\Exception\RuntimeException',
            'Prototype entity class has not been set.'
        );

        $mapper->getEntityName();
    }


    /*
     * checkIsentity()
     */

    public function test_checkIsEntity_does_not_throw_if_good()
    {
        $this->mapper->checkIsEntity(new TestEntity());
    }

    public function test_checkIsEntity_throws_if_not_entity()
    {
        $this->setExpectedException(
            'SclZfGenericMapper\Exception\InvalidArgumentException',
            'Entity must be an instance of'
            . ' "SclZfGenericMapperTests\TestAssets\Entity\TestEntity";'
            . ' got "stdClass".'
        );

        $this->mapper->checkIsEntity(new \stdClass());
    }

    /*
     * singleEntity()
     */

    public function test_singleEntity_returns_null_if_empty()
    {
        $this->assertNull($this->mapper->publicSingleEntity(null));
    }

    public function test_singleEntity_returns_entity_if_is_single_entity()
    {
        $entity = new TestEntity();

        $this->assertSame($entity, $this->mapper->publicSingleEntity($entity));
    }

    public function test_singleEntity_returns_single_entity_from_an_array()
    {
        $entity = new TestEntity();

        $this->assertSame($entity, $this->mapper->publicSingleEntity([$entity]));
    }

    public function test_singleEntity_throws_if_multiple_entities_are_found()
    {
        $this->setExpectedException(
            'SclZfGenericMapper\Exception\RuntimeException',
            'Multiple results were returned when only 1 was expected.'
        );

        $this->mapper->publicSingleEntity([1, 2]);
    }

    public function test_singleEntity_throws_if_not_instance_entity_class()
    {
        $this->setExpectedException(
            'SclZfGenericMapper\Exception\InvalidArgumentException',
            'Entity must be an instance of'
            . ' "SclZfGenericMapperTests\TestAssets\Entity\TestEntity";'
            . ' got "stdClass".'
        );

        $this->mapper->publicSingleEntity(new \stdClass());
    }

    public function test_singleEntity_throws_if_not_instance_entity_class_in_array()
    {
        $this->setExpectedException(
            'SclZfGenericMapper\Exception\InvalidArgumentException',
            'Entity must be an instance of'
            . ' "SclZfGenericMapperTests\TestAssets\Entity\TestEntity";'
            . ' got "stdClass".'
        );

        $this->mapper->publicSingleEntity([new \stdClass()]);
    }
}
