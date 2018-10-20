<?php
/**
 * Created by PhpStorm.
 * User: aurelian
 * Date: 20.10.2018
 * Time: 15:06
 */

namespace App\Repositories;


use App\Models\User;

class UserRepository extends BaseRepository
{
    /**
     * @param User $user
     */
    public function save(User $user): void
    {
        if (!$user->getId()) {
            $this->insert($user);
        } else {
            $this->update($user);
        }
    }

    /**
     * @param User $user
     */
    protected function insert(User $user): void
    {
        $this->execute(
            "INSERT INTO users (email, firstName, lastName, country, gender, bonusIncrements, bonus) VALUES (:email, :firstName, :lastName, :country, :gender, :bonusIncrements, :bonus)",
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
    }

    /**
     * @param User $user
     */
    protected function update(User $user): void
    {
        $this->execute(
            "UPDATE users SET email = :email, firstName = :firstName, lastName = :lastName, country = :country, gender = 
:gender, bonusIncrements = :bonusIncrements, bonus = :bonus WHERE id = :id",
            [
                ':id' => $user->getId(),
                ':email' => $user->getEmail(),
                ':firstName' => $user->getFirstName(),
                ':lastName' => $user->getLastName(),
                ':country' => $user->getCountry(),
                ':gender' => $user->getGender(),
                ':bonusIncrements' => $user->getBonusIncrements(),
                ':bonus' => $user->getBonus()
            ]);
    }

    /**
     * @param int $id
     *
     * @return User|null
     */
    public function findById(int $id): ?User
    {
        $user = $this->execute(
            "SELECT * FROM users WHERE id = ? LIMIT 1",
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
            "SELECT * FROM users WHERE email = ? LIMIT 1",
            [$email]
        )->fetchObject(User::class);

        return $user === false ? null : $user;
    }
}