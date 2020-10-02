<?php

namespace App\Http\Controllers\Auth;

/**
 * 用于覆盖内建的用户登录验证逻辑。
 * @package App\Http\Controllers\Auth
 */
trait AuthenticatesUsers
{
    use \Illuminate\Foundation\Auth\AuthenticatesUsers;

    /**
     * 使用username作为登录的身份凭证。
     * @inheritDoc
     */
    public function username()
    {
        return 'name';
    }
}
