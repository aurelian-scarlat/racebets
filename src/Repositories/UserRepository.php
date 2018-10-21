<?php
/**
 * Created by PhpStorm.
 * User: aurelian
 * Date: 20.10.2018
 * Time: 15:06
 */

namespace App\Repositories;


use App\Exceptions\ConcurrencyException;
use App\Models\Transaction;
use App\Models\User;

class UserRepository extends BaseRepository
{
    /**
     * @param User $user
     *
     * @return int
     * @throws ConcurrencyException
     */
    public function save(User $user): int
    {
        if (!$user->getId()) {
            return $this->insert($user);
        } else {
            return $this->update($user);
        }
    }

    /**
     * @param User $user
     *
     * @return int
     */
    protected function insert(User $user): int
    {
        $query = $this->execute(
            'INSERT INTO users (email, firstName, lastName, country, gender, bonusIncrements, bonus) VALUES (:email, :firstName, :lastName, :country, :gender, :bonusIncrements, :bonus)',
            [
                ':email' => $user->getEmail(),
                ':firstName' => $user->getFirstName(),
                ':lastName' => $user->getLastName(),
                ':country' => $user->getCountry(),
                ':gender' => $user->getGender(),
                ':bonusIncrements' => $user->getBonusIncrements(),
                ':bonus' => $user->getBonus()
            ]);
        $user->setId($this->connection->lastInsertId());

        return $query->rowCount();
    }

    /**
     * @param User $user
     *
     * @return int
     * @throws ConcurrencyException
     */
    protected function update(User $user): int
    {
        $query = $this->execute(
            'UPDATE users SET email = :email, firstName = :firstName, lastName = :lastName, country = :country, gender = 
:gender, bonusIncrements = :bonusIncrements, bonus = :bonus, version = version + 1 WHERE id = :id AND version = :version',
            [
                ':id' => $user->getId(),
                ':email' => $user->getEmail(),
                ':firstName' => $user->getFirstName(),
                ':lastName' => $user->getLastName(),
                ':country' => $user->getCountry(),
                ':gender' => $user->getGender(),
                ':bonusIncrements' => $user->getBonusIncrements(),
                ':bonus' => $user->getBonus(),
                ':version' => $user->getVersion()
            ]);

        if($query->rowCount() == 1) {
            $user->incrementVersion();
        } else {
            throw new ConcurrencyException('User is stale, current version is ' . $user->getVersion(), 101);
        }

        return $query->rowCount();
    }

    /**
     * @param int $id
     *
     * @return User|null
     */
    public function findById(int $id): ?User
    {
        $user = $this->execute(
            'SELECT * FROM users WHERE id = ? LIMIT 1',
            [$id]
        )->fetchObject(User::class);

        return $user === false ? null : $user;
    }

    /**
     * @param string $email
     *
     * @return User|null
     */
    public function findByEmail(string $email): ?User
    {
        $user = $this->execute(
            'SELECT * FROM users WHERE email = ? LIMIT 1',
            [$email]
        )->fetchObject(User::class);

        return $user === false ? null : $user;
    }

    /**
     * @param int      $startDate
     * @param int|null $endDate
     *
     * @return array
     */
    public function transactionReportByDayAndCountry(int $startDate, ?int $endDate = null): array
    {
        $rows = $this->execute(
            'SELECT 
                        date(t.added, "unixepoch") AS day, t.type, u.country, 
                        count(DISTINCT t.userId) AS customers, count(t.id) as transactions, 
                        sum(t.amount) as amount
                    FROM transactions AS t
                    INNER JOIN users AS u ON t.userId = u.id
                    WHERE t.type != :notType AND t.added >= :startDate AND t.added <= :endDate
                    GROUP BY t.type, day, u.country
                    ORDER BY day DESC',
            [
                ':notType' => Transaction::TYPE_BONUS,
                ':startDate' => $startDate,
                ':endDate' => $endDate ?? time()
            ]
        )->fetchAll();

        $result = [];
        foreach ($rows as $row){
            $pre = $row['type'] == Transaction::TYPE_DEPOSIT ? 'deposit_' : 'withdrawal_';
            $result[$row['day']][$row['country']][$pre . 'customers'] = $row['customers'];
            $result[$row['day']][$row['country']][$pre . 'amount'] = $row['amount'];
            $result[$row['day']][$row['country']][$pre . 'count'] = $row['transactions'];
        }

        return $result;
    }
}