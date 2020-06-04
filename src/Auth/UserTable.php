<?php
namespace App\Auth;

use Framework\Database\Table;

class UserTable extends Table
{

    protected $table = "users";

    protected $entity = User::class;
}
