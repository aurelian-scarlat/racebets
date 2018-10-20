<?php
/**
 * Created by PhpStorm.
 * User: aurelian
 * Date: 20.10.2018
 * Time: 22:01
 */

namespace App\Repositories;


use App\Models\Transaction;

class TransactionRepository extends BaseRepository
{
    /**
     * @param Transaction $transaction
     *
     * @return int
     */
    public function insert(Transaction $transaction): int
    {
        $query = $this->execute(
            'INSERT INTO transactions (userId, type, amount, added) VALUES (:userId, :type, :amount, :added)',
            [
                ':userId' => $transaction->getUserId(),
                ':type' => $transaction->getType(),
                ':amount' => $transaction->getAmount(),
                ':added' => $transaction->getAdded()
            ]
        );
        $transaction->setId($this->connection->lastInsertId());

        return $query->rowCount();
    }

    /**
     * @param int $userId
     * @param int $type
     *
     * @return Transaction|null
     */
    public function findLastByUserIdAndType(int $userId, int $type): ?Transaction
    {
        /** @var Transaction $transaction */
        $transaction = $this->execute('SELECT * FROM transactions WHERE userId = :userId AND type = :type ORDER BY id DESC LIMIT 1',
            [
                ':userId' => $userId,
                ':type' => $type
            ])->fetchObject(Transaction::TYPE_CLASSES[$type]);

        return $transaction === false ? null : $transaction;
    }

    /**
     * @param int $userId
     * @param int $type
     * @param int $id
     *
     * @return int
     */
    public function countByUserIdAndTypeAfterId(int $userId, int $type, int $id): int
    {
        return $this->execute('SELECT COUNT(*) FROM transactions WHERE userId = :userId AND type = :type AND id > :id',
            [
                ':userId' => $userId,
                ':type' => $type,
                ':id' => $id
            ])->fetchColumn();
    }

    /**
     * @param int $userId
     * @param int $type
     *
     * @return int
     */
    public function sumByUserIdAndType(int $userId, int $type): int
    {
        return $this->execute('SELECT SUM(amount) FROM transactions WHERE userId = :userId AND type = :type',
            [
                ':userId' => $userId,
                ':type' => $type
            ])->fetchColumn();
    }

    /**
     * @param int $userId
     *
     * @return int
     */
    public function balanceWithoutBonus(int $userId): int
    {
        return $this->execute('SELECT SUM(amount) FROM transactions WHERE userId = :userId AND type != :type',
            [
                ':userId' => $userId,
                ':type' => Transaction::TYPE_BONUS
            ])->fetchColumn();
    }
}