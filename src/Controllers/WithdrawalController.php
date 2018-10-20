<?php
/**
 * Created by PhpStorm.
 * User: aurelian
 * Date: 20.10.2018
 * Time: 09:39
 */

namespace App\Controllers;

use App\Exceptions\AmountException;
use App\Exceptions\ConcurrencyException;
use App\Models\User;
use App\Models\Withdrawal;
use App\Repositories\TransactionRepository;
use App\Repositories\UserRepository;
use App\Services\BaseService;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Http\StatusCode;

class WithdrawalController extends BaseService
{
    /**
     * @param Request  $request
     * @param Response $response
     *
     * @return Response
     * @throws \Exception
     */
    public function create(Request $request, Response $response): Response
    {
        if($request->getParam('amount') >= -0.01){
            throw new AmountException('Amount must be negative', 103);
        }

        /** @var User $user */
        $user = $this->container->get(UserRepository::class)->findById($request->getParam('userId'));

        if (!$user) {
            return $response->withStatus(StatusCode::HTTP_NOT_FOUND);
        }

        /** @var TransactionRepository $transactionRepository */
        $transactionRepository = $this->container->get(TransactionRepository::class);

        $available = $transactionRepository->balanceWithoutBonus($user->getId());

        if($available + $request->getParam('amount') < 0){
            throw new AmountException('Maximum amount you can withdraw is ' . $available, 104);
        }

        $withdrawal = new Withdrawal();
        $withdrawal->setAmount($request->getParam('amount'));
        $withdrawal->setUserId($user->getId());
        $withdrawal->setAdded(time());

        $this->container->get('db')->beginTransaction();

        $transactionRepository->insert($withdrawal);

        try {
            $this->container->get(UserRepository::class)->save($user);
        } catch (ConcurrencyException $e) {
            $this->container->get('db')->rollBack();
            throw $e;
        }

        $this->container->get('db')->commit();

        return $response->withJson($withdrawal);
    }

}

