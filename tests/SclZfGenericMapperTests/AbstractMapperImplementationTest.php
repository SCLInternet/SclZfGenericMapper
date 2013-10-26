<?php
/**
 * SclZfGenericMapper (https://github.com/SCLInternet/SclZfGenericMapper)
 *
 * @link https://github.com/SCLInternet/SclZfGenericMapper for the canonical source repository
 * @license http://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace SclZfGenericMapperTests;

use SclZfGenericMapperTests\TestAssets\Entity\TestEntity;

/**
 * Unit tests for {@see ZendDbMapper}.
 *
 * @author Tom Oram <tom@scl.co.uk>
 */
abstract class AbstractMapperImplementationTest extends \PHPUnit_Extensions_Database_TestCase
{
    /**
     * The mapper to be tested.
     *
     * @var \SclZfGenericMapper\MapperInterface
     */
    protected $mapper;

    /**
     * The name of the database file.
     *
     * @var string
     */
    protected $dbName;

    /**
     * __construct
     */
    public function __construct()
    {
        // Set the name of the SQLite database file.
        $this->dbName = __DIR__ . '/../test.db';
    }

    /*
     * Setup
     */

    public function getConnection()
    {
        $pdo = new \PDO('sqlite:' . $this->dbName);

        $pdo->exec('DROP TABLE IF EXISTS test_entity');

        $pdo->exec(
            'CREATE TABLE test_entity ('
            . ' id INTEGER PRIMARY KEY,'
            . ' name TEXT)'
        );

        return $this->createDefaultDBConnection($pdo);
    }

    /**
     * Load the test data.
     *
     * @return void
     */
    public function getDataSet()
    {
        return new \PHPUnit_Extensions_Database_DataSet_YamlDataSet(
            __DIR__ .  '/TestAssets/test_entity.yml'
        );
    }

    /*
     * Tests
     */

    public function test_implements_mapper_interface()
    {
        $this->assertInstanceOf(
            'SclZfGenericMapper\MapperInterface',
            $this->mapper
        );
    }

    /*
     * getEntityName()
     */
    public function test_getEntityName_returns_the_fqcn_of_the_entity_class()
    {
        $this->assertEquals(
            'SclZfGenericMapperTests\TestAssets\Entity\TestEntity',
            $this->mapper->getEntityName()
        );
    }

    /*
     * create()
     */

    public function test_create_returns_an_instance_of_entity()
    {
        $this->assertInstanceOf(
            'SclZfGenericMapperTests\TestAssets\Entity\TestEntity',
            $this->mapper->create()
        );
    }

    /*
     * findById()
     */

    public function test_findById_returns_Entity()
    {
        $this->assertInstanceOf(
            'SclZfGenericMapperTests\TestAssets\Entity\TestEntity',
            $this->mapper->findById(1)
        );
    }

    public function test_findById_returns_correct_Entity()
    {
        $entity = $this->mapper->findById(1);

        $this->assertEquals(1, $entity->getId());
    }

    public function test_findById_returns_false_if_not_found()
    {
        $this->assertNull($this->mapper->findById(200));
    }

    /*
     * findBy()
     */

    public function test_findBy_returns_Entity()
    {
        $this->assertInternalType(
            'array',
            $this->mapper->findBy(['id' => 1])
        );

        $entities = $this->mapper->findBy(['id' => 1]);

        $this->assertInstanceOf(
            'SclZfGenericMapperTests\TestAssets\Entity\TestEntity',
            reset($entities)
        );
    }

    public function test_findBy_returns_correct_Entity()
    {
        $entities = $this->mapper->findBy(['name' => 'fantastic']);

        $this->assertEquals(1, reset($entities)->getId());
    }

    /*
     * findAll()
     */

    public function test_findAll_returns_array()
    {
        $this->assertInternalType(
            'array',
            $this->mapper->findAll()
        );
    }

    public function test_findAll_returns_correct_number_of_entities()
    {
        $this->assertCount(2, $this->mapper->findAll());
    }

    public function test_findAll_returns_Tag_entities()
    {
        $entities = $this->mapper->findAll();

        $this->assertInstanceOf(
            'SclZfGenericMapperTests\TestAssets\Entity\TestEntity',
            reset($entities)
        );
    }

    public function test_findAll_loads_correct_entities()
    {
        $this->assertEntityListIs(
            [1 => 'fantastic', 2 => 'delete-me'],
            $this->mapper->findAll()
        );
    }

    /*
     * remove()
     */

    /**
     * @depends test_findAll_loads_correct_entities
     */
    public function test_remove_removes_Entity()
    {
        $entity = $this->mapper->findById(2);

        $this->mapper->remove($entity);

        $this->assertEntityListIs(
            [1 => 'fantastic'],
            $this->mapper->findAll()
        );
    }

    public function test_remove_throws_for_bad_entity()

    {
        $this->setExpectedException(
            'SclZfGenericMapper\Exception\InvalidArgumentException',
            'Entity must be an instance of "SclZfGenericMapperTests\TestAssets\Entity\TestEntity"; got "stdClass".'
        );

        $this->mapper->remove(new \stdClass());
    }

    /*
     * save()
     */

    public function test_save_throws_for_bad_entity()
    {
        $this->setExpectedException(
            'SclZfGenericMapper\Exception\InvalidArgumentException',
            'Entity must be an instance of "SclZfGenericMapperTests\TestAssets\Entity\TestEntity"; got "stdClass".'
        );

        $this->mapper->save(new \stdClass());
    }

    public function test_save_adds_Entity_to_the_database()
    {
        $entity = new TestEntity();

        $entity->setName('new-entity');

        $this->mapper->save($entity);

        $this->assertinstanceof(
            'SclZfGenericMapperTests\TestAssets\Entity\TestEntity',
            $this->mapper->findById(3) // @todo use findBy instead
        );
    }

    public function test_save_updates_the_object_id()
    {
        $entity = new TestEntity();

        $entity->setName('new-entity');

        $this->mapper->save($entity);

        $this->assertInternalType('integer', $entity->getId());
        $this->assertEquals(3, $entity->getId());
    }

    /**
     * @depends test_save_adds_Entity_to_the_database
     * @depends test_save_updates_the_object_id
     */
    public function test_save_updates_existing_object()
    {
        $entity = new TestEntity();

        $entity->setName('initial');

        $this->mapper->save($entity);

        $id = $entity->getId();

        $entity->setName('newval');

        $this->mapper->save($entity);

        $reloadedEntity = $this->mapper->findById($id);

        $this->assertEquals('newval', $reloadedEntity->getName());
    }

    /**
     * Checks that a list of Entitys matches the expected array of values.
     *
     * Expected values are id => entity_name
     *
     * @param  array $list
     *
     * @return void
     */
    private function assertEntityListIs(array $expected, array $entities)
    {
        $this->assertCount(count($expected), $entities);

        foreach ($entities as $entity) {
            $this->assertEquals(
                $expected[$entity->getId()],
                $entity->getName(),
                'Entity ' . $entity->getId() . ' has wrong name.'
            );
        }
    }
}
