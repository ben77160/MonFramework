<?php

namespace Framework\Database;

use Pagerfanta\Pagerfanta;
use Traversable;

class Query implements \IteratorAggregate
{

    private $select;

    private $from;

    private $where = [];

    private $entity;

    private $order = [];

    private $limit;

    private $joins;

    private $pdo;

    private $params = [];

    public function __construct(?\PDO $pdo = null)
    {
        $this->pdo = $pdo;
    }

    /**
     * Definit le FROM
     * @param string $table
     * @param null|string $alias
     * @return Query
     */
    public function from(string $table, ?string $alias = null): self
    {
        if ($alias) {
            $this->from[$table] = $alias;
        } else {
            $this->from[] = $table;
        }
        return $this;
    }

    /**
     * Spécifie les champs à récupérer
     * @param string[] ...$fields
     * @return Query
     */
    public function select(string ...$fields): self
    {
        $this->select = $fields;
        return $this;
    }

    /**
     * Spécifie la limite
     * @param int $length
     * @param int $offset
     * @return Query
     */
    public function limit(int $length, int $offset = 0): self
    {
        $this->limit = "$offset, $length";
        return $this;
    }

    /**
     * Spécifie l'ordre de récupération
     * @param string $order
     * @return Query
     */
    public function order(string $order): self
    {
        $this->order[] = $order;
        return $this;
    }

    /**
     * Ajoute une liaison
     * @param string $table
     * @param string $condition
     * @param string $type
     * @return Query
     */
    public function join(string $table, string $condition, string $type = "left"): self
    {
        $this->joins[$type][] = [$table, $condition];
        return $this;
    }

    /**
     * Définit la condition de récupération
     * @param string[] ...$condition
     * @return Query
     */
    public function where(string ...$condition): self
    {
        $this->where = array_merge($this->where, $condition);
        return $this;
    }

    /**
     * Execute un COUNT() et renvoie la colonne
     * @return int
     */
    public function count(): int
    {
        $query = clone $this;
        $table = current($this->from);
        return $query->select("COUNT($table.id)")->execute()->fetchColumn();
    }

    /**
     * Définit les paramètre pour la requête
     * @param array $params
     * @return Query
     */
    public function params(array $params): self
    {
        $this->params = array_merge($this->params, $params);
        return $this;
    }

    /**
     * Spécifie l'entité à utiliser
     * @param string $entity
     * @return Query
     */
    public function into(string $entity): self
    {
        $this->entity = $entity;
        return $this;
    }

    /**
     * Récupère un résultat
     */
    public function fetch()
    {
        $record = $this->execute()->fetch(\PDO::FETCH_ASSOC);
        if ($record === false) {
            return false;
        }
        if ($this->entity) {
            return Hydrator::hydrate($record, $this->entity);
        }
        return $record;
    }

    /**
     * Retournera un résultat ou renvoie une exception
     * @return bool|mixed
     * @throws NoRecordException
     */
    public function fetchOrFail()
    {
        $record = $this->fetch();
        if ($record === false) {
            throw new NoRecordException();
        }
        return $record;
    }

    /**
     * Lance la requête
     * @return QueryResult
     */
    public function fetchAll(): QueryResult
    {
        return new QueryResult(
            $this->execute()->fetchAll(\PDO::FETCH_ASSOC),
            $this->entity
        );
    }

    /**
     * Pagine les résultats
     * @param int $perPage
     * @param int $currentPage
     * @return Pagerfanta
     */
    public function paginate(int $perPage, int $currentPage = 1): Pagerfanta
    {
        $paginator = new PaginatedQuery($this);
        return (new Pagerfanta($paginator))->setMaxPerPage($perPage)->setCurrentPage($currentPage);
    }

    /**
     * Génère la requête SQL
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
        if (!empty($this->joins)) {
            foreach ($this->joins as $type => $joins) {
                foreach ($joins as [$table, $condition]) {
                    $parts[] = strtoupper($type) . " JOIN $table ON $condition";
                }
            }
        }
        if (!empty($this->where)) {
            $parts[] = "WHERE";
            $parts[] = "(" . join(') AND (', $this->where) . ')';
        }
        if (!empty($this->order)) {
            $parts[] = 'ORDER BY';
            $parts[] = join(', ', $this->order);
        }
        if ($this->limit) {
            $parts[] = 'LIMIT ' . $this->limit;
        }
        return join(' ', $parts);
    }

    /**
     * Construit le FROM a as b ....
     * @return string
     */
    private function buildFrom(): string
    {
        $from = [];
        foreach ($this->from as $key => $value) {
            if (is_string($key)) {
                $from[] = "$key as $value";
            } else {
                $from[] = $value;
            }
        }
        return join(', ', $from);
    }

    /**
     * Exécute la requête
     * @return \PDOStatement
     */
    private function execute()
    {
        $query = $this->__toString();
        if (!empty($this->params)) {
            $statement = $this->pdo->prepare($query);
            $statement->execute($this->params);
            return $statement;
        }
        return $this->pdo->query($query);
    }

    public function getIterator()
    {
        return $this->fetchAll();
    }
}
