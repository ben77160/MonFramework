<?php


namespace App\Framework\Database;

class QueryResult implements \ArrayAccess, \Iterator
{
    /**
     * @var array
     */
     private $records;

    private $entity;

     private $index = 0;

    private $hydratedRecords = [];


    public function __construct(array $records, ?string $entity = null)
    {
        $this->records = $records;
        $this->entity = $entity;
    }

    public function get(int $index)
    {
        if ($this->entity) {
            if (!isset($this->hydratedRecords[$index])) {
                $this->hydratedRecords[$index] = Hydrator::hydrate($this->records[$index], $this->entity);
            }
            return $this->hydratedRecords[$index];
        }
        return $this->entity;
    }


    /**
     * @param mixed $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        return isset($this->records[$offset]);
    }

    /**
     * @param mixed $offset
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return $this->get($offset);
    }

    /**
     * @param mixed $offset
     * @param mixed $value
     * @throws \Exception
     */
    public function offsetSet($offset, $value)
    {
        throw new \Exception("can't alter records");
    }

    /**
     * @param mixed $offset
     * @throws \Exception
     */
    public function offsetUnset($offset)
    {
        throw new \Exception("can't alter records");
    }

    /**
     * @inheritDoc
     */
    public function current()
    {
        return $this->get($this->index);
    }

    /**
     * @inheritDoc
     */
    public function next():void
    {
        $this->index++;
    }

    /**
     * @inheritDoc
     */
    public function key()
    {
        return $this->index;
    }

    /**
     * @inheritDoc
     */
    public function valid()
    {
        return isset($this->records[$this->index]);
    }

    /**
     * @inheritDoc
     */
    public function rewind()
    {
        $this->index = 0;
    }
}
