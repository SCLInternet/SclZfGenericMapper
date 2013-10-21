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
        $this->mapper = new TestMapper(new TestEntity());
    }

    /*
     * setPrototype()
     */

    public function test_constructor_throws_if_prototype_not_object()
    {
        $this->setExpectedException(
            'SclZfGenericMapper\Exception\InvalidArgumentException',
            'Expected an object; got "string".'
        );

        new TestMapper('bad_protoype');
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
            'Entity must be an instance of "SclZfGenericMapperTests\TestAssets\Entity\TestEntity"; got "stdClass".'
        );

        $this->mapper->checkIsEntity(new \stdClass());
    }
}
