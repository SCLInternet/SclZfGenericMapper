<?php
/**
 * SclZfGenericMapper (https://github.com/SCLInternet/SclZfGenericMapper)
 *
 * @link https://github.com/SCLInternet/SclZfGenericMapper for the canonical source repository
 * @license http://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace SclZfGenericMapperTests;

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use SclZfGenericMapper\DoctrineMapper;
use SclZfGenericMapperTests\TestAssets\Entity\TestEntity;
use SclZfGenericMapper\Doctrine\FlushLock;

/**
 * Unit tests for {@see DoctrineMapper}.
 *
 * @author Tom Oram <tom@scl.co.uk>
 */
class DoctrineMapperTest extends AbstractMapperImplementationTest
{
    /**
     * Create a doctrine ORM entity manager.
     *
     * @return \Doctrine\ORM\EntityManager
     */
    private function createEntityManager()
    {
        $paths = [__DIR__ . '/TestAssets/Entity'];
        $isDevMode = false;

        return EntityManager::create(
            [
                'driver'   => 'pdo_sqlite',
                'path'     => $this->dbName,
            ],
            Setup::createAnnotationMetadataConfiguration($paths, $isDevMode)
        );
    }

    /**
     * Set up the instance of the mapper.
     *
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();

        $entityManager = $this->createEntityManager();

        $this->mapper = new DoctrineMapper(
            new TestEntity(),
            $entityManager,
            new FlushLock($entityManager)
        );
    }
}
