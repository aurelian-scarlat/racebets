<?php
/**
 * Created by PhpStorm.
 * User: aurelian
 * Date: 21.10.2018
 * Time: 02:41
 */

namespace App\Controllers;

use App\Repositories\UserRepository;
use App\Services\BaseService;
use Slim\Http\Request;
use Slim\Http\Response;

class ReportController extends BaseService
{

    /**
     * @param Request  $request
     * @param Response $response
     *
     * @return Response
     */
    public function daily(Request $request, Response $response): Response
    {
        $startTime = new \DateTime($request->getParam('start', '7 days ago midnight'));
        $result = $this->container->get(UserRepository::class)
                                  ->transactionReportByDayAndCountry($startTime->getTimestamp());

        return $response->withJson($result);
    }
}