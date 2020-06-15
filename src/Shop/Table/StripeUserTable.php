<?php
namespace App\Shop\Table;

use App\Auth\User;
use Framework\Database\Table;

class StripeUserTable extends Table
{

    protected $table = "users_stripe";

    public function findCustomerForUser(User $user): ?string
    {
        $record = $this->makeQuery()
            ->select('customer_id')
            ->where('user_id = :user')
            ->params(['user' => $user->getId()])
            ->fetch();
        if ($record === null) {
            return null;
        }
        return $record->customerId;
    }
}
