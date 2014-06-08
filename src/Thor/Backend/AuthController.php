<?php

namespace Thor\Backend;

use View,
    Input,
    Lang,
    Redirect,
    Confide,
    Config;

/**
 * Backend authentication controller
 */
class AuthController extends \Illuminate\Routing\Controller
{

    /**
     * Displays the form for account creation
     *
     */
    protected function create()
    {
        return View::make(Config::get('confide::signup_form'));
    }

    /**
     * Stores new account
     *
     */
    protected function store()
    {
        $user = new \Thor\Models\User;

        $user->username = Input::get('username');
        $user->email = Input::get('email');
        $user->password = Input::get('password');

        // The password confirmation will be removed from model
        // before saving. This field will be used in Ardent's
        // auto validation.
        $user->password_confirmation = Input::get('password_confirmation');

        // Save if valid. Password field will be hashed before save
        $user->save();

        if($user->id) {
            $notice = Lang::get('confide::confide.alerts.account_created') . ' ' . Lang::get('confide::confide.alerts.instructions_sent');

            // Redirect with success message, You may replace "Lang::get(..." for your custom message.
            return Redirect::action('\\Thor\\Backend\\AuthController@login')
                            ->with('notice', $notice);
        } else {
            // Get validation errors (see Ardent package)
            $error = $user->errors()->all(':message');

            return Redirect::action('\\Thor\\Backend\\AuthController@create')
                            ->withInput(Input::except('password'))
                            ->with('error', $error);
        }
    }

    /**
     * Displays the login form
     *
     */
    public function login()
    {
        if(Confide::user()) {
            // If user is logged, redirect to an internal page
            return Redirect::to(\Backend::url());
        } else {
            return View::make('thor::backend.login', array('page' => 'login', 'unwrap' => true));
        }
    }

    /**
     * Attempt to do login
     *
     */
    public function do_login()
    {
        $input = array(
            'email' => Input::get('email'), // May be the username too
            'username' => Input::get('email'), // so we have to pass both
            'password' => Input::get('password'),
            'remember' => Input::get('remember'),
        );

        // If you wish to only allow login from confirmed users, call logAttempt
        // with the second parameter as true.
        // logAttempt will check if the 'email' perhaps is the username.
        // Get the value from the config file instead of changing the controller
        if(Confide::logAttempt($input, Config::get('confide::signup_confirm'))) {
            // Redirect the user to the URL they were trying to access before
            // caught by the authentication filter IE Redirect::guest('user/login').
            // Otherwise fallback to '/'
            // Fix pull #145
            return Redirect::intended(\Backend::url());
        } else {
            $user = new \Thor\Models\User;

            // Check if there was too many login attempts
            if(Confide::isThrottled($input)) {
                $err_msg = Lang::get('confide::confide.alerts.too_many_attempts');
            } elseif($user->checkUserExists($input) and ! $user->isConfirmed($input)) {
                $err_msg = Lang::get('confide::confide.alerts.not_confirmed');
            } else {
                $err_msg = Lang::get('confide::confide.alerts.wrong_credentials');
            }

            return Redirect::action('\\Thor\\Backend\\AuthController@login')
                            ->withInput(Input::except('password'))
                            ->with('error', $err_msg);
        }
    }

    /**
     * Attempt to confirm account with code
     *
     * @param  string  $code
     */
    public function confirm($code)
    {
        if(Confide::confirm($code)) {
            $notice_msg = Lang::get('confide::confide.alerts.confirmation');
            return Redirect::action('\\Thor\\Backend\\AuthController@login')
                            ->with('notice', $notice_msg);
        } else {
            $error_msg = Lang::get('confide::confide.alerts.wrong_confirmation');
            return Redirect::action('\\Thor\\Backend\\AuthController@login')
                            ->with('error', $error_msg);
        }
    }

    /**
     * Displays the forgot password form
     *
     */
    public function forgot_password()
    {
        return View::make('thor::backend.forgot_password', array('page' => 'forgot_password', 'unwrap' => true));
    }

    /**
     * Attempt to send change password link to the given email
     *
     */
    public function do_forgot_password()
    {
        if(Confide::forgotPassword(Input::get('email'))) {
            $notice_msg = Lang::get('confide::confide.alerts.password_forgot');
            return Redirect::action('\\Thor\\Backend\\AuthController@login')
                            ->with('notice', $notice_msg);
        } else {
            $error_msg = Lang::get('confide::confide.alerts.wrong_password_forgot');
            return Redirect::action('\\Thor\\Backend\\AuthController@forgot_password')
                            ->withInput()
                            ->with('error', $error_msg);
        }
    }

    /**
     * Shows the change password form with the given token
     *
     */
    public function reset_password($token)
    {
        return View::make('thor::backend.reset_password', array('page' => 'reset_password', 'unwrap' => true, 'token' => $token));
    }

    /**
     * Attempt change password of the user
     *
     */
    public function do_reset_password()
    {
        $input = array(
            'token' => Input::get('token'),
            'password' => Input::get('password'),
            'password_confirmation' => Input::get('password_confirmation'),
        );

        // By passing an array with the token, password and confirmation
        if(Confide::resetPassword($input)) {
            $notice_msg = Lang::get('confide::confide.alerts.password_reset');
            return Redirect::action('\\Thor\\Backend\\AuthController@login')
                            ->with('notice', $notice_msg);
        } else {
            $error_msg = Lang::get('confide::confide.alerts.wrong_password_reset');
            return Redirect::action('\\Thor\\Backend\\AuthController@reset_password', array('token' => $input['token']))
                            ->withInput()
                            ->with('error', $error_msg);
        }
    }

    /**
     * Log the user out of the application.
     *
     */
    public function logout()
    {
        Confide::logout();

        return Redirect::to(\Backend::url('login'));
    }

}
