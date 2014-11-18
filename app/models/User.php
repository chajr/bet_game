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

        $message = 'Congratulations! This is you '
            . $this->log
            . ' login, so you been reward by '
            . $bonus->first()->value
            . Bonus::getBonusType($bonus->first()->value_type)
            . ' bonus';

        /** @var Wallet $wallet */
        $wallet  = new Wallet();
        $wallet->createWallet([
            'user_id'       => $this->user_id,
            'currency'      => 0,
            'status'        => 1,
            'origin'        => 0,
            'bonus_id'      => $bonus->first()->bonus_id,
            'value'         => $bonus->first()->value,
        ]);

        return $message;
    }

    /**
     * check that user should get login bonus and apply it
     * 
     * @param int $deposit
     * @return string
     */
    public function checkForDepositBonus($deposit)
    {
        /** @var Bonus $bonus */
        $bonus = Bonus::where('trigger', '=', 'deposite')
            ->where('multiplier', '=', $deposit)
            ->get();

        if ($bonus->isEmpty()) {
            return '';
        }

        $depositValue   = ($bonus->first()->value / 100) * $deposit;
        $message        = 'Congratulations! You deposit '
            . $deposit
            . '$, so you been reward by '
            . $bonus->first()->value
            . Bonus::getBonusType($bonus->first()->value_type)
            . ' bonus';

        /** @var Wallet $wallet */
        $wallet = new Wallet();
        $wallet->createWallet([
            'user_id'       => $this->user_id,
            'currency'      => 0,
            'status'        => 1,
            'origin'        => 0,
            'bonus_id'      => $bonus->first()->bonus_id,
            'value'         => $depositValue,
        ]);

        return $message;
    }

    /**
     * get user wallets information
     * 
     * @return array
     */
    public function getUserWallets()
    {
        $walletData = [
            'total'     => 0,
            'real'      => 0,
        ];

        /** @var Wallet $wallets */
        $wallets = Wallet::where('user_id', '=', $this->user_id)
            ->where('status', '=', 1)
            ->get();

        foreach ($wallets as $wallet) {
            if ($wallet->origin === '1') {
                $walletData['total']    += $wallet->value;
                $walletData['real']     += $wallet->value;
            }

            if ($wallet->origin === '0') {
                $walletData['total']    += $wallet->value;
                $walletData['bonus'][]  = [
                    'value'     => $wallet->value,
                    'name'      => $this->_getBonusName($wallet->bonus_id)
                ];
            }
        }

        Debugbar::info($wallets->toArray());
        Debugbar::info($walletData);
        return $walletData;
    }

    /**
     * get name for given bonus id
     * 
     * @param int $bonusId
     * @return mixed
     */
    protected function _getBonusName($bonusId)
    {
        /** @var Bonus $bonus */
        $bonus = Bonus::find($bonusId);
        return $bonus->name;
    }
}
