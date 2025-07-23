<?php

namespace App\Mail;


class Register extends \App\Mail\Model
{
    public $subject = 'Olá {{ $user->name }}';

    public $body = 'Seja bem vindo.
Por favor, verifique seu e-mail <a href="{{ $verify_link }}">clicando aqui</a>.';

    public $user = false;
    
    public $verify_link = false;

    public function __construct(\App\Models\User $user) {
        $this->user = $user;
        $this->verify_link = url('/user/verify/'. base64_encode(serialize($user)));
    }
}