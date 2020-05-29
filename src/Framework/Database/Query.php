<?php


namespace App\Framework\Database;

class Query
{
    private $select;

    private $from;

    private $where = [];

    private $entity;

    private $group;

    private $order;

    private $limit;

    private $pdo;

    private $params;




    public function __construct(? \PDO $pdo = null)
    {
        $this->pdo = $pdo;
    }

    public function from(string $table, ?string $alias = null): self
    {
        if ($alias) {
            $this->from[$alias] = $table;
        } else {
            $this->from[] = $table;
        }
        return $this;
    }


    public function select(string ...$fields): self
    {
        $this->select = $fields;
        return $this;
    }


    public function where(string ...$condition): self
    {
        $this->where = array_merge($this->where, $condition);
        return $this;
    }

    /**
     * @return int
     */
    public function count(): int
    {
        $this->select = ["COUNT(id)"];
        return $this->execute()->fetchColumn();
    }

    /**
     * @param array $params
     * @return $this
     */
    public function params(array $params): self
    {
         $this->params = $params;
         return $this;
    }

    /**
     * @param string $entity
     * @return $this
     */
    public function into(string $entity): self
    {
        $this->entity = $entity;
        return $this;
    }

    /**
     * @return QueryResult
     */
    public function all(): QueryResult
    {
        return new QueryResult($this->execute()->fetchAll(\PDO::FETCH_ASSOC), $this->entity);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        $parts = ['SELECT'];
        if ($this->select) {
            $parts[] = join(', ', $this->select);
        } else {
            $parts[] = '*';
        }
        $parts[] = 'FROM';
        $parts[] = $this->buildFrom();
        if (!empty($this->where)) {
            $parts[] = "WHERE";
            $parts[] = "(" . join(') AND (', $this->where) . ')';
        }
        return join(' ', $parts);
    }

    private function buildFrom(): string
    {
        $from = [];
        foreach ($this->from as $key => $value) {
            if (is_string($key)) {
                $from[] = "$value as $key";
            } else {
                $from[] = $value;
            }
        }
        return join(' ', $from);
    }

    private function execute()
    {
        $query = $this->__toString();
        if ($this->params) {
            $statement = $this->pdo->prepare($query);
            $statement->execute($this->params);
            return $statement;
        }
        return $this->pdo->query($query);
    }
}
