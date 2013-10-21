<?php
/**
 * SclZfGenericMapper (https://github.com/SCLInternet/SclZfGenericMapper)
 *
 * @link https://github.com/SCLInternet/SclZfGenericMapper for the canonical source repository
 * @license http://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace SclZfGenericMapper;

use Doctrine\Common\Persistence\ObjectManager;
use SclZfGenericMapper\Doctrine\FlushLock;
use SclZfGenericMapper\Exception\InvalidArgumentException;

/**
 * Basic mapper class for doctrine storage.
 *
 * @author Tom Oram <tom@scl.co.uk>
 */
class DoctrineMapper implements MapperInterface
{
    use CommonMapperMethodsTrait;

    /**
     * The Doctrine ObjectManager.
     *
     * @var ObjectManager
     */
    protected $entityManager;

    /**
     * The FlushLock class.
     *
     * @var FlushLock
     */
    protected $flushLock;

    /**
     * Inject required objects.
     *
     * @param  object        $prototype
     * @param  ObjectManager $entityManager
     * @param  FlushLock     $flushLock
     */
    public function __construct(
        $prototype,
        ObjectManager $entityManager,
        FlushLock $flushLock = null
    ) {
        $this->setPrototype($prototype);

        $this->entityManager = $entityManager;
        $this->flushLock     = $flushLock;
    }

    /**
     * {@inheritDoc}
     */
    public function save($entity)
    {
        $this->checkIsEntity($entity);

        if (null !== $this->flushLock) {
            $this->flushLock->lock();

            $this->entityManager->persist($entity);

            return $this->flushLock->unlock();
        }

        $this->entityManager->persist($entity);

        $this->entityManager->flush();

        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function findById($id)
    {
        return $this->entityManager->find($this->entityName, $id);
    }

    /**
     * {@inheritDoc}
     */
    public function findAll()
    {
        return $this->entityManager->getRepository($this->entityName)->findAll();
    }

    /**
     * {@inheritDoc}
     */
    public function findBy(array $criteria)
    {
        return $this->entityManager->getRepository($this->entityName)->findBy($criteria);
    }

    /**
     * {@inheritDoc}
     */
    public function remove($entity)
    {
        $this->checkIsEntity($entity);

        if (null !== $this->flushLock) {
            $this->flushLock->lock();

            $this->entityManager->remove($entity);

            return $this->flushLock->unlock();
        }

        $this->entityManager->remove($entity);

        $this->entityManager->flush();

        return true;
    }
}
