<?php

namespace App\Utils;

use App\User;
use Illuminate\Http\Exceptions\HttpResponseException;

class UserModificationHanlder {
    /**
     * update or help with create and save new user
     */
    public static function saveUser($user, $body, $filter) {

        if ($user === null) {
            $user = new User;
        }
        // loop through body to handle updating
        foreach ($body as $key => $value) {
            if (in_array($key, $filter)) $user[$key] = $value;
        }
        //Create or save user using that infos
        $user->save();
        return $user;
    }
}