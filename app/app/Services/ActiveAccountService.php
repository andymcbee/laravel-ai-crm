<?php

namespace App\Services;

use App\Models\Account;
use Illuminate\Support\Facades\Session;

class ActiveAccountService
{
    /**
     * @return Account
     */
    public function getActiveAccount(): Account
    {
        $account = Session::get('active_account');

        if(!$account instanceof Account) {
            abort(403, 'Active account not found');
        }

        return $account;
    }

}
