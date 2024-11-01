<?php

namespace App\Services;

use App\Models\Users;

class UserService
{
    public function checkNik($nik) {
        return Users::where('nik', $nik)->exists();
    }

    public function checkUserIfExist($userId)
    {
        return Users::find($userId);
    }

}
