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

    /**
     * check that user should get login bonus and apply it
     * 
     * @return string
     */
    public function checkForLoginBonus()
    {
        /** @var Bonus $bonus */
        $bonus = Bonus::where('trigger', '=', 'login')
            ->where('multiplier', '=', $this->log)
            ->get();

        if ($bonus->isEmpty()) {
            return '';
        }

        $wallet = Wallet::where('user_id', '=', $this->user_id)
            ->where('bonus_id', '=', 1)
            ->where('origin', '=', 0)
            ->where('status', '=', 1)
            ->get();

        if ($wallet->isEmpty()) {
            return '';
        }

        $message = 'Congratulations! This is you '
            . $this->log
            . ' login, so you been reward by '
            . $bonus->first()->value
            . Bonus::getBonusType($bonus->first()->value_type)
            . ' bonus';
        $wallet->first()->value = $wallet->first()->value + $bonus->first()->value;
        $wallet->first()->save();

        return $message;
    }
}
