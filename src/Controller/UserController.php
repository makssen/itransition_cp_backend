<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractApiController
{
    #[Route('/users', methods: ['GET'])]
    public function getAll(Request $request): Response
    {
        $users = $this->getDoctrine()->getRepository(User::class)->findAll();
        return $this->respond($users);
    }
}
