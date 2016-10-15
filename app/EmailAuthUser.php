<?php
/**
 * Created by PhpStorm.
 * User: plastics1509moore
 * Date: 9/10/16
 * Time: 10:50 PM
 */

namespace App;

use Auth;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;

class EmailAuthUser
{
    use ValidatesRequests;

    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function invite()
    {
        $this->validateRequest()
            ->createToken()
            ->send();
    }

    public function auth(LoginToken $token)
    {
        Auth::login($token->user);
        $token->delete();
    }


    protected function validateRequest()
    {
        $this->validate($this->request, [
            'email' => 'required|email|exists:users'
        ]);

        return $this;
    }

    protected function createToken()
    {
        $user = User::byEmail($this->request->email);
        return LoginToken::generateFor($user);
    }
}