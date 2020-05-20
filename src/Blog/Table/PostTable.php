<?php
namespace App\Blog\Table;

use App\Blog\Entity\PostEntity;
use Framework\Database\PaginatedQuery;
use Framework\Database\Table;
use Pagerfanta\Pagerfanta;

class PostTable extends Table
{
    protected $entity = PostEntity::class;
    protected $table = 'posts';

    protected function paginationQuery()
    {
        return parent::paginationQuery() ."ORDER BY created_at DESC";
    }
}
