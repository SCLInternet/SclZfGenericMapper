<?php
/**
 * SclZfGenericMapper (https://github.com/SCLInternet/SclZfGenericMapper)
 *
 * @link https://github.com/SCLInternet/SclZfGenericMapper for the canonical source repository
 * @license http://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace SclZfGenericMapper;

use SclZfGenericMapper\Exception\InvalidArgumentException;
use Zend\Stdlib\Hydrator\ClassMethods;
use Zend\Stdlib\Hydrator\HydratorInterface;
use ZfcBase\Mapper\AbstractDbMapper;

/**
 * Basic mapper class for zend db storage.
 *
 * @author Tom Oram <tom@scl.co.uk>
 */
class ZendDbMapper extends AbstractDbMapper implements MapperInterface
{
    use CommonMapperMethodsTrait;

    /**
     * The name of the ID field in the database.
     *
     * @var string
     */
    private $idField;

    /**
     * Initialize the mapper.
     */
    public function __construct(
        $prototype,
        $tableName,
        $idField,
        HydratorInterface $hydrator = null
    ) {
        $this->tableName = $tableName;
        $this->idField   = $idField;

        if (null === $hydrator) {
            $hydrator = new ClassMethods();
        }

        $this->setHydrator($hydrator);

        // Awefully similar method names!!!
        $this->setPrototype($prototype);
        $this->setEntityPrototype($prototype);
    }

    /**
     * {@inheritDoc}
     */
    public function findById($id)
    {
        $select = $this->getSelect()
                       ->where(['id' => $id]);

        $entity = $this->select($select)->current();

        return $entity ?: null;
    }

    /**
     * {@inheritDoc}
     */
    public function findBy(array $criteria)
    {
        $select = $this->getSelect()
                       ->where($criteria);

        return iterator_to_array($this->select($select));
    }

    /**
     * {@inheritDoc}
     */
    public function findAll()
    {
        return iterator_to_array($this->select($this->getSelect()));
    }

    /**
     * {@inheritDoc}
     */
    public function remove($entity)
    {
        $this->checkIsEntity($entity);

        $this->delete(
            ['id' => $entity->getId()],
            $this->tableName
        );
    }

    /**
     * {@inheritDoc}
     */
    public function save($entity)
    {
        $this->checkIsEntity($entity);

        $result = $this->insert($entity);

        $entity->setId($result->getGeneratedValue());
    }
}
