<?php
/**
 * SclZfGenericMapper (https://github.com/SCLInternet/SclZfGenericMapper)
 *
 * @link https://github.com/SCLInternet/SclZfGenericMapper for the canonical source repository
 * @license http://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace SclZfGenericMapper;

/**
 * Basic interface for basic mapper class for doctrine storage.
 *
 * @author Tom Oram <tom@scl.co.uk>
 * @author Fee
 */
interface MapperInterface
{
    /**
     * Returns the class name of they entity types that this mapper works with.
     *
     * @return string
     */
    public function getEntityName();

    /**
     * Creates a new instance of the entity.
     *
     * @return object
     */
    public function create();

    /**
     * Persists to the Order to storage.
     *
     * @param  object $order
     * @return boolean
     */
    public function save($order);

    /**
     * Loads a given order from the database.
     *
     * @param  mixed $id
     * @return object|null
     */
    public function findById($id);

    /**
     * Returns all orders from the database.
     *
     * @return object[]
     */
    public function findAll();

    /**
     * Does a search by criteria.
     *
     * @param  array $criteria
     * @return object[]
     */
    public function findBy(array $criteria);

    /**
     * Deletes the order from the storage.
     *
     * @param  object $entity
     * @return boolean
     */
    public function remove($entity);
}
