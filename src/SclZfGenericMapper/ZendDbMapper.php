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
 *
 * @todo Fix the fact that it depends on getId() method.
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
     *
     * @param  object            $prototype
     * @param  string            $tableName
     * @param  string            $idField
     * @param  HydratorInterface $hydrator
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
                       ->where([$this->idField => $id]);

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
            [$this->idField => $entity->getId()],
            $this->tableName
        );
    }

    /**
     * {@inheritDoc}
     */
    public function save($entity)
    {
        $this->checkIsEntity($entity);

        if (!$entity->getId()) {
            $result = $this->insert($entity);

            $entity->setId($result->getGeneratedValue());

            return;
        }

        $this->update($entity, [$this->idField => $entity->getId()]);
    }

    /**
     * The version allows read-write access.
     *
     * @param  string $table
     *
     * @return \Zend\Db\Sql\Select
     */
    protected function getSelect($table = null)
    {
        $this->initialize();

        $select = $this->getSlaveSql()->select();

        $select->from($table ?: $this->getTableName());

        return $select;
    }
}
