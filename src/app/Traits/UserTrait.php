<?php

namespace App\Traits;

use \Illuminate\Support\Facades\Auth;

trait UserTrait
{
    public function getUser($field = '*')
    {
        $user = Auth::user();
        if ($field != '*') {
            return data_get($user, $field);
        }

        return $user ? $user->toArray() : [];
    }
}