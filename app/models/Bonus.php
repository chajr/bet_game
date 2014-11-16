<?php
/**
 * Bonus model
 *
 * @package     models
 * @subpackage  Bonus
 * @author      Michał Adamiak <michal.adamiak@spj.com.pl>
 */


class Bonus extends Eloquent
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'bonus';

    /**
     * bonus id column name
     *
     * @var string
     */
    protected $primaryKey   = 'bonus_id';

    public static function getBonusType($type)
    {
        switch ($type) {
            case 'fixed':
                $bonusType = '$';
                break;
            case 'percent':
                $bonusType = '%';
                break;
            default:
                $bonusType = '';
                break;
        }

        return $bonusType;
    }
}
