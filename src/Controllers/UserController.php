<?php
/**
 * Created by PhpStorm.
 * User: aurelian
 * Date: 20.10.2018
 * Time: 09:39
 */

namespace App\Controllers;

use App\Models\User;
use App\Repositories\UserRepository;
use App\Services\BaseService;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Http\StatusCode;

class UserController extends BaseService
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
        $user = new User();
        $user->setEmail($request->getParam('email'))
             ->setFirstName($request->getParam('firstName'))
             ->setLastName($request->getParam('lastName'))
             ->setCountry($request->getParam('country'))
             ->setGender($request->getParam('gender'))
             ->setBonus(random_int(5, 20));

        $this->container->get(UserRepository::class)->save($user);

        return $response->withJson($user);
    }


    public function update(Request $request, Response $response, array $args): Response
    {
        /** @var User $user */
        $user = $this->container->get(UserRepository::class)->findById($args['id']);

        if (!$user) {
            return $response->withStatus(StatusCode::HTTP_NOT_FOUND);
        }

        $user->setEmail($request->getParam('email'))
             ->setFirstName($request->getParam('firstName'))
             ->setLastName($request->getParam('lastName'))
             ->setCountry($request->getParam('country'))
             ->setGender($request->getParam('gender'));

        $this->container->get(UserRepository::class)->save($user);

        return $response->withJson($user);
    }

}

