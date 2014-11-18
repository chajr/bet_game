<?php
/**
 * Wallet model
 *
 * @package     models
 * @subpackage  Bonus
 * @author      Michał Adamiak <michal.adamiak@spj.com.pl>
 */


class Wallet extends Eloquent
{
    /**
     * max value for bonus wallets
     */
    const BONUS_WALLET_LIMIT = 100;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'wallets';

    /**
     * bonus id column name
     *
     * @var string
     */
    protected $primaryKey   = 'wallet_id';

    /**
     * save winn by user money
     * 
     * @param int $win
     * @param int $userId
     */
    public function saveWin($win, $userId)
    {
        $winWallet = $this->where('user_id', '=', $userId)
            ->where('origin', '=', 1)
            ->where('bonus_id', '=', null)
            ->get();

        if ($winWallet->isEmpty()) {
            $winWallet = $this->createWallet([
                'user_id'       => $userId,
                'currency'      => 0,
                'status'        => 1,
                'init_val'      => 0,
                'origin'        => 0,
                'bonus_id'      => null,
                'value'         => 0,
            ]);
        }

        $winWalletValue = $winWallet->value;
        $this->calculateRealOrBonusMoney($win, $winWalletValue);
    }

    /**
     * calculate how much money should be saved on bonus or real wallet
     * 
     * @param $win
     * @param $winWallet
     */
    protected function calculateRealOrBonusMoney($win, $winWallet)
    {
        $sum    = $win + $winWallet;
        $return = [
            'real'  => 0,
            'bonus' => 0
        ];

        if ($sum > self::BONUS_WALLET_LIMIT) {
            
        }
    }

    /**
     * create new wallet from given data
     * 
     * @param array $data
     * @return $this
     */
    public function createWallet(array $data)
    {
        $this->wallet_id  = null;
        $this->user_id    = $data['user_id'];
        $this->currency   = $data['currency'];
        $this->status     = $data['status'];
        $this->origin     = $data['origin'];
        $this->bonus_id   = $data['bonus_id'];
        $this->value      = $data['value'];

        $this->save();

        return $this;
    }
}
