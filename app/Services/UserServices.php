<?php

namespace App\Services;

use App\Models\Users;

class UserService
{
    public function checkNrp($nrp) {
        $checkNrp = Users::findByNrp($nrp);

        // if (condition) {
        //     # code...
        // }
    }
}
