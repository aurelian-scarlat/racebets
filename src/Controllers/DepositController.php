<?php
/**
 * Created by PhpStorm.
 * User: aurelian
 * Date: 20.10.2018
 * Time: 09:39
 */

namespace App\Controllers;

use App\Exceptions\AmountException;
use App\Models\Bonus;
use App\Models\Deposit;
use App\Models\Transaction;
use App\Models\User;
use App\Repositories\TransactionRepository;
use App\Repositories\UserRepository;
use App\Services\BaseService;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Http\StatusCode;

class DepositController extends BaseService
{
    /**
     * @param Request  $request
     * @param Response $response
     *
     * @return Response
     * @throws \Exception
     * @throws AmountException
     */
    public function create(Request $request, Response $response): Response
    {
        if($request->getParam('amount') < 0.01){
            throw new AmountException('Amount must be at least 0.01', 102);
        }

        /** @var User $user */
        $user = $this->container->get(UserRepository::class)->findById($request->getParam('userId'));

        if (!$user) {
            return $response->withStatus(StatusCode::HTTP_NOT_FOUND);
        }

        $deposit = new Deposit();
        $deposit->setAmount($request->getParam('amount'));
        $deposit->setUserId($user->getId());
        $deposit->setAdded(time());

        $this->container->get('db')->beginTransaction();

        /** @var TransactionRepository $transactionRepository */
        $transactionRepository = $this->container->get(TransactionRepository::class);

        $transactionRepository->insert($deposit);

        $lastBonus = $transactionRepository->findLastByUserIdAndType(
            $user->getId(),
            Transaction::TYPE_BONUS
        );

        // count how many deposits were after the last bonus
        $depositNum = $transactionRepository->countByUserIdAndTypeAfterId(
            $user->getId(),
            Transaction::TYPE_DEPOSIT,
            $lastBonus ? $lastBonus->getId() : 0
        );

        if($depositNum >= $user->getBonusIncrements()) {
            $bonus = new Bonus();
            $bonus->setAmount(round($deposit->getAmount() * $user->getBonus() / 100, 2));
            $bonus->setUserId($user->getId());
            $bonus->setAdded(time());
            $transactionRepository->insert($bonus);
        }

//        try {
//            $this->container->get(UserRepository::class)->save($user);
//        } catch (ConcurrencyException $e) {
//            $this->container->get('db')->rollBack();
//            throw $e;
//        }

        $this->container->get('db')->commit();

        return $response->withJson($deposit);
    }

}

