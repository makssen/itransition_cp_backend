<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractApiController
{
    #[Route('/users', methods: ['GET'])]
    public function getAll(Request $request): Response
    {
        $users = $this->getDoctrine()->getRepository(User::class)->findAll();
        return $this->respond($users);
    }

    #[Route('/users/{id}', methods: ['GET'])]
    public function getById(Request $request): Response
    {
        $userId = $request->get('id');
        $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(['id' => $userId]);
        if (!$user) {
            throw new NotFoundHttpException('User not found');
        }
        return $this->respond($user);
    }
}
