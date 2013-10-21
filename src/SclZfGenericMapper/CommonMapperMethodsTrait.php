<?php
/**
 * SclZfGenericMapper (https://github.com/SCLInternet/SclZfGenericMapper)
 *
 * @link https://github.com/SCLInternet/SclZfGenericMapper for the canonical source repository
 * @license http://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace SclZfGenericMapper;

use SclZfGenericMapper\Exception\InvalidArgumentException;

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
     * @todo   Make sure set prototype has been called.
     */
    public function create()
    {
        $entityClass = $this->entityName;

        return new $entityClass();
    }

    /**
     * Return the name of the entity this mapper works with.
     *
     * @return string
     * @todo   Make sure set prototype has been called.
     */
    public function getEntityName()
    {
        return $this->entityName;
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
     * @todo   Make sure cannot be called twice.
     */
    protected function setPrototype($prototype)
    {
        if (!is_object($prototype)) {
            throw InvalidArgumentException::objectExpected($prototype);
        }

        $this->entityName = get_class($prototype);
    }
}
