<?php
namespace App\Blog\Table;

use App\Blog\Entity\PostEntity;
use Framework\Database\PaginatedQuery;
use Pagerfanta\Pagerfanta;

class PostTable
{
    /**
     * @var \PDO
     */
    private $pdo;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Pagine les articles
     * @param int $perPage
     * @return Pagerfanta
     */
    public function findPaginated(int $perPage, int $currentPage): Pagerfanta
    {
        //On recupère nos paginations
        $query =  new PaginatedQuery(
            $this->pdo,
            'SELECT * FROM posts ORDER BY created_at DESC ',
            'SELECT COUNT(id) FROM posts',
            PostEntity::class
        );
        return (new Pagerfanta($query))
            ->setMaxPerPage($perPage)
            ->setCurrentPage($currentPage);
    }

    /**
     * Récupère un article à partir de son ID
     * @param int $id
     * @return PostEntity|null
     */
    public function find(int $id): ?PostEntity
    {
        $query = $this->pdo
            ->prepare('SELECT * FROM posts WHERE id = ?');
        $query->execute([$id]);
        $query->setFetchMode(\PDO::FETCH_CLASS, PostEntity::class);
        return $query->fetch() ?: null;
    }

    /**
     * Met à jour un enregistrement au niveau de la base de donnée
     *
     * @param int $id
     * @param array $params
     * @return bool
     */
    public function update(int $id, array $params): bool
    {
            $fieldQuery = $this->buildFieldQuery($params);
            $params["id"] = $id;
            $statement = $this->pdo->prepare("UPDATE posts SET $fieldQuery WHERE id = :id");
            return $statement->execute($params);
    }

    /**
     * Crée un nouvel enregistrement
     * @param array $params
     * @return bool
     */
    public function insert(array $params):bool
    {
        $fieldQuery = $this->buildFieldQuery($params);
        $statement = $this->pdo->prepare("INSERT INTO posts SET $fieldQuery");
        return $statement->execute($params);
    }

    /**
     * Supprime un enregistrement
     * @param int $id
     * @return bool
     */
    public function delete(int $id):bool
    {
        $statement = $this->pdo->prepare('DELETE FROM posts WHERE id = ?');
        return $statement->execute([$id]);
    }

    private function buildFieldQuery(array $params)
    {
        return  join(' ,', array_map(function ($field) {
            return "$field = :$field";
        }, array_keys($params)));
    }
}
