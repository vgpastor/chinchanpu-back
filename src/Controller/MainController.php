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

    public function generate(Request $request,ResultRepository $resultRepository, GenerateRandomChoice $generateRandomChoice ): JsonResponse
    {
        $enemy = $generateRandomChoice->generate();
        try {
            $result = new Result($request->get('player'), $enemy);
            $resultRepository->save($result);
            return new JsonResponse($result->toArray());
        } catch (ChoiceException | NotDefinedException $e) {
            return new JsonResponse($e->getMessage(),Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function history(ResultRepository $resultRepository): JsonResponse
    {
        return new JsonResponse($resultRepository->getLast());
    }
}