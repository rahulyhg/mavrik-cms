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
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;

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

    public function notify()
    {
        $text = $this->request['text'];
        $sender = $this->request['name'];
        $respond = $this->request['email'];

        try {
            //try to send the email
            if ($this->request->hasFile('file')) {

                $file = $this->request['file'];
                $pathToFile = $file->getPathName();
                $display = $file->getClientOriginalName();
                $mime = File::mimeType($pathToFile);

                Mail::send('email.notification', ['text' => $text, 'sender' => $sender, 'respond' => $respond], function ($m) use ($pathToFile, $display, $mime) {

                    $m->to('mrricki.m.usmc@gmail.com')->subject('New Message Request!');
                    $m->attach($pathToFile, ['as' => $display, 'mime' => $mime]);

                });
            }
            Mail::send('email.notification', ['text' => $text, 'sender' => $sender, 'respond' => $respond], function ($m) {
                $m->to('mrricki.m.usmc@gmail.com')->subject('New Message Request!');
            });

        }

            //catch exception
        catch(\Exception $e) {
            return false;
        }
        return true;

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