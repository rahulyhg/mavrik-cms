<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;

class LoginToken extends Model
{
    protected $fillable = ['user_id', 'token'];

    public function getRouteKeyName()
    {
        return 'token';
    }

    public static function generateFor(User $user)
    {
        return static::create([
            'user_id' => $user->id,
            'token' => str_random(50)
        ]);
    }

    public function send()
    {
        $url = url('/auth/token', $this->token);
        Mail::raw(
        "{$url}",
        function ($message){
            $message->to($this->user->email)
                ->subject('LOGIN AUTHORIZATION');
        }
        );
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
