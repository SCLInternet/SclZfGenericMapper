<?php
/**
 * SclZfGenericMapper (https://github.com/SCLInternet/SclZfGenericMapper)
 *
 * @link https://github.com/SCLInternet/SclZfGenericMapper for the canonical source repository
 * @license http://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace SclZfGenericMapperTests\Doctrine;

use SclZfGenericMapper\Doctrine\FlushLock;

class FlushLockTest extends \PHPUnit_Framework_TestCase
{
    private $flushLock;

    private $entityManager;

    protected function setUp()
    {
        $this->entityManager = $this->getMock('Doctrine\Common\Persistence\ObjectManager');

        $this->flushLock = new FlushLock($this->entityManager);
    }

    public function test_module_provides_FlushLock_service()
    {
        $this->assertInstanceOf(
            'SclZfGenericMapper\Doctrine\FlushLock',
            \TestBootstrap::getApplication()
                          ->getServiceManager()
                          ->get('SclZfGenericMapper\Doctrine\FlushLock')
        );
    }

    public function test_locking_and_unlocking()
    {
        $this->entityManager
             ->expects($this->once())
             ->method('flush');

        $this->flushLock->lock();
        $this->flushLock->lock();

        $this->assertFalse($this->flushLock->unlock(), 'Returned true on first unlock.');

        $this->assertTrue($this->flushLock->unlock(), 'Returned false on final unlock.');
    }

    public function test_unlocking_too_many_times()
    {
        $this->entityManager
             ->expects($this->never())
             ->method('flush');

        $this->assertFalse($this->flushLock->unlock(), 'Returned true when unlocking beyond 0.');
    }
}
