<?php
namespace Framework\Database;

use Pagerfanta\Adapter\AdapterInterface;

class PaginatedQuery implements AdapterInterface
{

    /**
     * @var Query
     */
    private $query;


    /**
     * PaginatedQuery constructor.
     * @param Query $query
     */
    public function __construct(Query $query)
    {
        $this->query = $query;
    }

    /**
     * Returns the number of results.
     *
     * @return integer The number of results.
     */
    public function getNbResults(): int
    {
        return $this->query->count();
    }

    /**
     * Returns an slice of the results.
     *
     * @param integer $offset The offset.
     * @param integer $length The length.
     *
     * @return \Traversable The slice.
     */
    public function getSlice($offset, $length): QueryResult
    {
        $query = clone $this->query;
        return $query->limit($length, $offset)->fetchAll();
    }
}
