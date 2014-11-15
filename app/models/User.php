<?php
/**
 * User model
 *
 * @package     models
 * @subpackage  User
 * @author      Michał Adamiak <michal.adamiak@spj.com.pl>
 */

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class User extends Eloquent implements UserInterface, RemindableInterface
{
    use UserTrait, RemindableTrait;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = array('password', 'remember_token');

    /**
     * user id column name
     * 
     * @var string
     */
    protected $primaryKey   = 'user_id';
}
