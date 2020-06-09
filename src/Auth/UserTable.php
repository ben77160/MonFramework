<?php
namespace App\Auth;

use Framework\Database\Table;

class UserTable extends Table
{

    protected $table = "users";

    public function __construct(\PDO $pdo, string $entity = User::class)
    {
        $this->entity = $entity;
        parent::__construct($pdo);
    }
}
