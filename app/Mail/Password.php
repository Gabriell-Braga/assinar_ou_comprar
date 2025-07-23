<?php

namespace App\Mail;

use \App\Models\User;

class Password extends \App\Mail\Model
{
    // use Queueable, SerializesModels;

    public $subject = 'Recuperação de senha';

    public $body = 'Seu código de verificação:
<pre>{{ $code }}</pre>
Para prosseguir com a recuperação de senha, <a href="{{ url("/auth/password?code={$code}") }}">clique aqui</a>.';
    
    public $user;
    public $code;

    public function __construct(\App\Models\User $user, $code) {
        $this->user = $user;
        $this->code = $code;
    }
}