<?php

namespace Thor\Models;

use Zizaco\Entrust\EntrustRole;

class Role extends EntrustRole
{
    
    protected $fillable = array(
        'name'
    );
}
