<?php
namespace Framework\Database;

use Pagerfanta\Adapter\AdapterInterface;
use PDO;

/**
 * @method PDO getConnection()
 */
class PaginatedQuery implements AdapterInterface
{
    /**
     * @var PDO
     */
    private $pdo;
    /**
     * @var string
     */
    private $query;
    /**
     * @var string
     */
    private $countQuery;

    /**
     * @var string|null
     */
    private $entity;


    /**
     * PaginatedQuery constructor.
     * @param PDO $pdo
     * @param string $query Requête permettant de récupérer X résultats
     * @param string $countQuery  $countQuery Requête permettant de compter le nombre de résultats total
     * @param string|null $entity
     */
    public function __construct(PDO $pdo, string $query, string $countQuery, ?string  $entity)
    {
        $this->pdo = $pdo;
        $this->query = $query;
        $this->countQuery = $countQuery;
        $this->entity = $entity;
    }

    /**
     * @return integer
     */
    public function getNbResults(): int
    {
        return $this->pdo->query($this->countQuery)->fetchColumn();
    }

    /**
     * @param int $offset
     * @param int $length
     * @return array
     */
    public function getSlice($offset, $length): array
    {
        $statement = $this->pdo->prepare($this->query . ' LIMIT :offset , :length');
        $statement->bindParam('offset', $offset, PDO::PARAM_INT);
        $statement->bindParam('length', $length, PDO::PARAM_INT);
        if ($this->entity) {
            $statement->setFetchMode(PDO::FETCH_CLASS, $this->entity);
        }
        $statement->execute();
        return $statement->fetchAll();
    }
}
