<?php
/**
 * SclZfGenericMapper (https://github.com/SCLInternet/SclZfGenericMapper)
 *
 * @link https://github.com/SCLInternet/SclZfGenericMapper for the canonical source repository
 * @license http://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace SclZfGenericMapper;

use SclZfGenericMapper\Exception\InvalidArgumentException;
use SclZfGenericMapper\Exception\RuntimeException;

/**
 * Common methods for mappers.
 *
 * @author Tom Oram <tom@scl.co.uk>
 */
trait CommonMapperMethodsTrait
{
    /**
     * The FQCN of the entity this mapper manages.
     *
     * @var string
     */
    private $entityName;

    /**
     * Creates an instance of the entity.
     *
     * @return object
     *
     * @throws RuntimeException If prototype has not yet been set.
     */
    public function create()
    {
        $entityClass = $this->getEntityName();

        $reflection = new \ReflectionClass($entityClass);

        if ($reflection->isAbstract()) {
            throw RuntimeException::createAbstract($entityClass);
        }

        return new $entityClass();
    }

    /**
     * Return the name of the entity this mapper works with.
     *
     * @return string
     *
     * @throws RuntimeException If prototype has not yet been set.
     */
    public function getEntityName()
    {
        if (!$this->entityName) {
            throw RuntimeException::prototypeNotSet();
        }

        return $this->entityName;
    }

    /**
     * Makes sure a result contains a single result.
     *
     * @param  array|null|object entity
     *
     * @return object|null
     *
     * @throws RuntimeException         If mulitple entities are found in the array.
     * @throws InvalidArgumentException If the object is not an entity.
     */
    protected function singleEntity($entity)
    {
        if (empty($entity)) {
            return null;
        }

        $singleEntity = $entity;

        if (is_array($entity)) {
            if (count($entity) > 1) {
                throw RuntimeException::multipleResultsFound();
            }

            $singleEntity = reset($entity);
        }

        $entityClass = $this->getEntityName();

        if (!$singleEntity instanceof $entityClass) {
            throw InvalidArgumentException::invalidEntityType(
                $this->getEntityName(),
                $singleEntity
            );
        }

        return $singleEntity;
    }

    /**
     * Checks the given entity is an instance of the entity this mapper works with.
     *
     * @param  mixed $object
     *
     * @return void
     *
     * @throws InvalidArgumentException If $object is not an entity.
     */
    public function checkIsEntity($object)
    {
        if (!$object instanceof $this->entityName) {
            throw InvalidArgumentException::invalidEntityType(
                $this->getEntityName(),
                $object
            );
        }
    }

    /**
     * Setup the prototype of the entity this mapper works with.
     *
     * @param  object $prototype
     */
    protected function setPrototype($prototype)
    {
        if ($this->entityName) {
            throw RuntimeException::setPrototypeCalledAgain();
        }

        $this->entityName = is_object($prototype)
            ? get_class($prototype)
            : $prototype;
    }
}
