<h1>{{ Lang::get('confide::confide.email.password_reset.subject') }}</h1>

<p>{{ Lang::get('confide::confide.email.password_reset.greetings', array( 'name' => $user->username)) }},</p>

<p>{{ Lang::get('confide::confide.email.password_reset.body') }}</p>
<a href='{{{ (Confide::checkAction('\\Thor\\Backend\\AuthController@reset_password', array($token))) ? : Backend::url('auth/reset_password/'.$token)  }}}'>
    {{{ (Confide::checkAction('\\Thor\\Backend\\AuthController@reset_password', array($token))) ? : Backend::url('auth/reset_password/'.$token)  }}}
</a>

<p>{{ Lang::get('confide::confide.email.password_reset.farewell') }}</p>
