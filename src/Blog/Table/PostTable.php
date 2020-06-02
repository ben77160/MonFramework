<?php

namespace App\Blog\Table;

use App\Blog\Entity\Post;
use Framework\Database\PaginatedQuery;
use Framework\Database\Query;
use Framework\Database\QueryResult;
use Framework\Database\Table;
use Pagerfanta\Pagerfanta;

class PostTable extends Table
{

    protected $entity = Post::class;

    protected $table = 'posts';

    public function findAll(): Query
    {
        $category = new CategoryTable($this->pdo);
        return $this->makeQuery()
            ->join($category->getTable() . ' as c', 'c.id = p.category_id')
            ->select('p.*, c.name as category_name, c.slug as category_slug')
            ->order('p.created_at DESC');
    }

    public function findPublic(): Query
    {
        return $this->findAll()
            ->where('p.published = 1')
            ->where('p.created_at < NOW()');
    }

    public function findPublicForCategory(int $id): Query
    {
        return $this->findPublic()->where("p.category_id = $id");
    }

    public function findWithCategory(int $postId): Post
    {
        return $this->findPublic()->where("p.id = $postId")->fetch();
    }
}
