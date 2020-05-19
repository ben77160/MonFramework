<?php

namespace Tests\App\Blog\Table;

use App\Blog\Entity\PostEntity;
use App\Blog\Table\PostTable;
use Tests\DatabaseTestCase;

class PostTableTest extends DatabaseTestCase
{

    /**
     * @var PostTable
     */
    private $postTable;

    public function setUp()
    {
        parent::setUp();
        $this->postTable = new PostTable($this->pdo);
    }

    public function testFind()
    {
        $this->seedDatabase();
        $post = $this->postTable->find(1);
        $this->assertInstanceOf(PostEntity::class, $post);
    }

    public function testFindNotFoundRecord()
    {
        $post = $this->postTable->find(1);
        $this->assertNull($post);
    }

    public function testUpdate()
    {
        $this->seedDatabase();
        $this->postTable->update(1, ['name' => 'Salut', 'slug' => 'demon']);
        $post = $this->postTable->find(1);
        $this->assertEquals('Salut', $post->name);
        $this->assertEquals('demo', $post->slug);
    }

    public function tesstInsert()
    {
        $this->postTable->insert(['name' => 'Salut', 'slug' => 'demo']);
        $post = $this->postTable->find(1);
        $this->assertEquals('Salut', $post->name);
        $this->assertEquals('demo', $post->slug);
    }

    public function testDelete()
    {
        $this->postTable->insert(['name' => 'Salut', 'slug' => 'demo']);
        $this->postTable->insert(['name' => 'Salut', 'slug' => 'demo']);
        $count = $this->pdo->query('SELECT COUNT(id) FROM posts')->fetchColumn();
        $this->assertEquals(2, (int) $count);
        $this->postTable->delete($this->pdo->lastInsertId());
        $count = $this->pdo->query('SELECT COUNT(id) FROM posts')->fetchColumn();
        $this->assertEquals(1, (int)$count);
    }
}
