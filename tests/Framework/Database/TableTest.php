<?php
namespace Tests\Framework\Database;

use Framework\Database\Table;
use PDO;
use PHPUnit\Framework\TestCase;

class TableTest extends TestCase {

    /**
     * @var Table
     */
    private $table;

    public function setUp()
    {
        $pdo = new PDO('sqlite::memory:', null, null, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ
        ]);
        $pdo->exec('CREATE TABLE test (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name VARCHAR(255)
        )');

        $this->table = new Table($pdo);
        $reflection = new \ReflectionClass($this->table);
        $property = $reflection->getProperty('table');
        $property->setAccessible(true);
        $property->setValue($this->table, 'test');
    }

    public function testFind()
    {
        $this->table->getPdo()->exec('INSERT INTO test (name) VALUES ("a1")');
        $this->table->getPdo()->exec('INSERT INTO test (name) VALUES ("a2")');
        $test = $this->table->find(1);
        $this->assertInstanceOf(\stdClass::class, $test);
        $this->assertEquals('a1', $test->name);
    }

    public function testFindList()
    {
        $this->table->getPdo()->exec('INSERT INTO test (name) VALUES ("a1")');
        $this->table->getPdo()->exec('INSERT INTO test (name) VALUES ("a2")');
        $this->assertEquals(['1' => 'a1', '2' => 'a2'], $this->table->findList());
    }

    public function testExists()
    {
        $this->table->getPdo()->exec('INSERT INTO test (name) VALUES ("a1")');
        $this->table->getPdo()->exec('INSERT INTO test (name) VALUES ("a2")');
        $this->assertTrue($this->table->exists(1));
        $this->assertTrue($this->table->exists(2));
        $this->assertFalse($this->table->exists(3123));
    }

}