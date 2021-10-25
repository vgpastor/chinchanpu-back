<?php

namespace App\Controller;

use App\Application\GenerateRandomChoice;
use App\Repository\ResultRepository;
use App\Entity\Result;
use App\Exceptions\ChoiceException;
use App\Exceptions\NotDefinedException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class MainController extends AbstractController
{

    public function generate(Request $request, ResultRepository $resultRepository, GenerateRandomChoice $generateRandomChoice): JsonResponse
    {
        $enemy = $generateRandomChoice->generate();
        try {
            $result = new Result($request->get('player'), $enemy);
            $resultRepository->save($result);
            return new JsonResponse($result->toArray(),Response::HTTP_CREATED);
        } catch (ChoiceException | NotDefinedException $e) {
            return new JsonResponse(["error"=>$e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    public function history(ResultRepository $resultRepository): JsonResponse
    {
        $results = $resultRepository->getLast();
        $response = [];
        foreach ($results as $result) {
            $response[] = $result->toArray();
        }
        return new JsonResponse($response);
    }

    public function clean(ResultRepository $resultRepository): JsonResponse
    {
        $resultRepository->clean();
        return new JsonResponse(null,Response::HTTP_ACCEPTED);
    }
}
