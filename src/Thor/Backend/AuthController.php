<?php

namespace Thor\Backend;

use View,
    Input,
    Lang,
    Redirect,
    Thor\Platform\SentinelFacade,
    Password;

/**
 * Backend authentication controller
 */
class AuthController extends Controller
{

    /**
     * Displays the login form
     *
     */
    public function login()
    {
        if (SentinelFacade::hasBackendAccess()) {
            // If user is logged, redirect to an internal page
            return Redirect::route('backend.home');
        } else {
            return View::make('thor::backend.users.login', array('page' => 'login', 'unwrap' => true));
        }
    }

    /**
     * Log the user out of the application.
     *
     */
    public function logout()
    {
        $hadBackendAccess = SentinelFacade::hasBackendAccess();

        \Auth::logout();

        if ($hadBackendAccess) {
            return Redirect::route('backend.login');
        } else {
            return Redirect::to('/');
        }
    }

    /**
     * Attempt to do login
     *
     */
    public function do_login()
    {
        $input = Input::only(['email', 'password', 'remember']);
        $input['username'] = $input['email'];
    }

    /**
     * Displays the forgot password form
     *
     */
    public function forgot_password()
    {
        return View::make('thor::backend.users.forgot_password', array('page' => 'forgot_password', 'unwrap' => true));
    }

    /**
     * Attempt to send change password link to the given email
     *
     */
    public function do_forgot_password()
    {
        $reminder_sent = Password::remind(['email' => Input::get('email')]);

        if ($reminder_sent == Password::REMINDER_SENT) {
            $notice_msg = Lang::get('confide::confide.alerts.password_forgot');
            return Redirect::route('backend.login')
                            ->with('notice', $notice_msg);
        } else {
            $error_msg = Lang::get('confide::confide.alerts.wrong_password_forgot');
            return Redirect::route('backend.forgot_password')
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
        return View::make('thor::backend.users.reset_password', array('page' => 'reset_password', 'unwrap' => true, 'token' => $token));
    }

    /**
     * Attempt change password of the user
     *
     */
    public function do_reset_password()
    {
        $input = Input::only(['token', 'password', 'password_confirmation']);

        // By passing an array with the token, password and confirmation
        if (Confide::resetPassword($input)) {
            $notice_msg = Lang::get('confide::confide.alerts.password_reset');
            return Redirect::action('backend.login')
                            ->with('notice', $notice_msg);
        } else {
            $error_msg = Lang::get('confide::confide.alerts.wrong_password_reset');
            return Redirect::action('backend.reset_password', array('token' => $input['token']))
                            ->withInput()
                            ->with('error', $error_msg);
        }
    }

}
