<?php

namespace Adaojunior\Passport\Tests\Stubs;

use Illuminate\Auth;
use Illuminate\Contracts\Auth\Authenticatable;

class UserEntity implements Authenticatable
{
    use Auth\Authenticatable;

    protected $id = 1;

    protected $password = 'password';

    private function getKeyName()
    {
        return 'id';
    }
}
