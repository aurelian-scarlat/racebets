<?php
/**
 * Created by PhpStorm.
 * User: aurelian
 * Date: 20.10.2018
 * Time: 21:35
 */

namespace App\Models;


class Bonus extends Transaction
{
    protected $type = parent::TYPE_BONUS;
}