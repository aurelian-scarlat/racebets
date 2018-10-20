<?php
/**
 * Created by PhpStorm.
 * User: aurelian
 * Date: 20.10.2018
 * Time: 21:55
 */

namespace App\Models;


class Transaction implements \JsonSerializable
{
    public const TYPE_DEPOSIT = 1;
    public const TYPE_BONUS = 2;
    public const TYPE_WITHDRAWAL = 3;

    public const TYPE_CLASSES = [
        self::TYPE_DEPOSIT => Deposit::class,
        self::TYPE_BONUS => Bonus::class,
        self::TYPE_WITHDRAWAL => Withdrawal::class
    ];

    /** @var int */
    protected $id;

    /** @var int */
    protected $userId;

    /** @var string */
    protected $type;

    /** @var float */
    protected $amount;

    /** @var string */
    protected $added;

    public static function getTypeClass(int $type)
    {
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     *
     * @return Transaction
     */
    public function setId(int $id): Transaction
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * @param int $userId
     *
     * @return Transaction
     */
    public function setUserId(int $userId): Transaction
    {
        $this->userId = $userId;
        return $this;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return float
     */
    public function getAmount(): float
    {
        return $this->amount;
    }

    /**
     * @param float $amount
     *
     * @return Transaction
     */
    public function setAmount(float $amount): Transaction
    {
        $this->amount = $amount;
        return $this;
    }

    /**
     * @return string
     */
    public function getAdded(): string
    {
        return $this->added;
    }

    /**
     * @param string $added
     *
     * @return Transaction
     */
    public function setAdded(string $added): Transaction
    {
        $this->added = $added;
        return $this;
    }

    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'userId' => $this->userId,
            'amount' => $this->amount,
            'added' => $this->added
        ];
    }
}