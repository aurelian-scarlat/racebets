<?php
/**
 * Created by PhpStorm.
 * User: aurelian
 * Date: 20.10.2018
 * Time: 15:08
 */

namespace App\Models;


class User implements \JsonSerializable
{

    /** @var int */
    private $id;

    /** @var string */
    private $email;

    /** @var string */
    private $firstName;

    /** @var string */
    private $lastName;

    /** @var string */
    private $country;

    /** @var string */
    private $gender;

    /** @var int */
    private $bonusIncrements = 3;

    /** @var int */
    private $bonus;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int $id
     *
     * @return User
     */
    public function setId(int $id): User
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     *
     * @return User
     */
    public function setEmail(string $email): User
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     *
     * @return User
     */
    public function setFirstName(string $firstName): User
    {
        $this->firstName = $firstName;
        return $this;
    }

    /**
     * @return string
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     *
     * @return User
     */
    public function setLastName(string $lastName): User
    {
        $this->lastName = $lastName;
        return $this;
    }

    /**
     * @return string
     */
    public function getCountry(): string
    {
        return $this->country;
    }

    /**
     * @param string $country
     *
     * @return User
     */
    public function setCountry(string $country): User
    {
        $this->country = $country;
        return $this;
    }

    /**
     * @return int
     */
    public function getGender(): string
    {
        return $this->gender;
    }

    /**
     * @param string $gender
     *
     * @return User
     */
    public function setGender(string $gender): User
    {
        $this->gender = $gender;
        return $this;
    }

    /**
     * @return int
     */
    public function getBonusIncrements(): int
    {
        return $this->bonusIncrements;
    }

    /**
     * @param int $bonusIncrements
     *
     * @return User
     */
    public function setBonusIncrements(int $bonusIncrements): User
    {
        $this->bonusIncrements = $bonusIncrements;
        return $this;
    }

    /**
     * @return int
     */
    public function getBonus(): int
    {
        return $this->bonus;
    }

    /**
     * @param int $bonus
     *
     * @return User
     */
    public function setBonus(int $bonus): User
    {
        $this->bonus = $bonus;
        return $this;
    }

    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'email' => $this->email,
            'firstName' => $this->firstName,
            'lastName' => $this->lastName,
            'country' => $this->country,
            'gender' => $this->gender,
            'bonusIncrements' => $this->bonusIncrements,
            'bonus' => $this->bonus
        ];
    }
}