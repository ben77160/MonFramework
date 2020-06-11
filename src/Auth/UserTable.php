<?php
namespace App\Auth;

use Framework\Database\Table;
use Ramsey\Uuid\Uuid;

class UserTable extends Table
{

    protected $table = "users";

    public function __construct(\PDO $pdo, string $entity = User::class)
    {
        $this->entity = $entity;
        parent::__construct($pdo);
    }

    public function resetPassword(int $id): string
    {
        $token = Uuid::uuid4()->toString();
        $this->update($id, [
            'password_reset' => $token,
            'password_reset_at' => date('Y-m-d H:i:s')
        ]);
        return $token;
    }

    public function updatePassword(int $id, string $password): void
    {
        $this->update($id, [
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'password_reset' => null,
            'password_reset_at' => null
        ]);
    }
}
