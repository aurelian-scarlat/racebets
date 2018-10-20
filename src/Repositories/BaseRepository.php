<?php
/**
 * Created by PhpStorm.
 * User: aurelian
 * Date: 20.10.2018
 * Time: 16:56
 */

namespace App\Repositories;


use App\Services\BaseService;
use Psr\Container\ContainerInterface;

class BaseRepository extends BaseService
{
    /** @var \PDO */
    protected $connection;

    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);

        $this->connection = $container->get('db');

    }

    /**
     * @param string     $query
     * @param array|null $parameters
     *
     * @return \PDOStatement
     */
    protected function execute(string $query, ?array $parameters): \PDOStatement
    {
        $statement = $this->connection->prepare($query);
        $statement->execute($parameters);
        return $statement;
    }
}