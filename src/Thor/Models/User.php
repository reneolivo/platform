<?php

namespace Thor\Models;

use Zizaco\Confide\ConfideUser;

class User extends ConfideUser
{

    use \Zizaco\Entrust\HasRole;

    protected $fillable = array(
        'usename', 'email', 'password', 'confirmed', 'confirmation_code'
    );

    /**
     * Ardent validation rules
     *
     * @var array
     */
    public static $rules = array(
        'username' => 'required|alpha_dash|unique:users,username,{id}',
        'email' => 'required|email|unique:users,email,{id}',
        'password' => 'required|min:4|confirmed',
        'password_confirmation' => 'min:4',
    );

    /**
     * Get the e-mail address where password reminders are sent.
     *
     * @return string
     */
    public function getReminderEmail()
    {
        return $this->email;
    }

  public function save( array $rules = array(), array $customMessages = array(), array $options = array(), \Closure $beforeSave = null, \Closure $afterSave = null )
    {
        $id = ($this->getKey() > 0) ? $this->getKey() : '';
        $rules = array_merge(static::$rules, $rules);

        foreach ($rules as $key => $rule) {
            $rules[$key] = str_replace('{id}', $id, $rule);
        }
        return parent::save($rules, $customMessages, $options, $beforeSave, $afterSave);
    }

}
