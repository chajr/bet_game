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
}
