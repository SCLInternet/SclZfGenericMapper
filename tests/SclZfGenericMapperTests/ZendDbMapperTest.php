<?php
/**
 * SclZfGenericMapper (https://github.com/SCLInternet/SclZfGenericMapper)
 *
 * @link https://github.com/SCLInternet/SclZfGenericMapper for the canonical source repository
 * @license http://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace SclZfGenericMapperTests;

use SclZfGenericMapper\ZendDbMapper;
use SclZfGenericMapperTests\TestAssets\Entity\TestEntity;

/**
 * Unit tests for {@see ZendDbMapper}.
 *
 * @author Tom Oram <tom@scl.co.uk>
 */
class ZendDbMapperTest extends AbstractMapperImplementationTest
{
    /**
     * Set up the instance of the mapper.
     *
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();

        $this->mapper = new ZendDbMapper(new TestEntity(), 'test_entity', 'id');

        $dbAdapter = new \Zend\Db\Adapter\Adapter([
            'driver'   => 'Pdo_Sqlite',
            'database' => $this->dbName,
        ]);

        $this->mapper->setDbAdapter($dbAdapter);
    }
}
