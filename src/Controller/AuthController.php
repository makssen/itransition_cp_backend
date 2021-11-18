<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use CustomError;
use Firebase\JWT\JWT;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class AuthController extends AbstractApiController
{
    #[Route('/auth/register', methods: ['POST'])]
    public function register(Request $request, UserPasswordHasherInterface $passwordHasher): Response
    {
        $form = $this->buildForm(UserType::class);

        $form->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid()) {
            return $this->respond($form, Response::HTTP_BAD_REQUEST);
        }

        $getUser = $this->getDoctrine()->getRepository(User::class)->findOneBy(['email' => $request->get('email')]);
        if ($getUser) return $this->respond(CustomError::UnauthorizedError('Such email already exists'));

        $user = $form->getData();
        $password = $user->getPassword();
        $hashedPassword = $passwordHasher->hashPassword($user, $password);
        $user->setPassword($hashedPassword);

        $this->getDoctrine()->getManager()->persist($user);
        $this->getDoctrine()->getManager()->flush();

        return $this->respond($user);
    }

    #[Route('/auth/login', methods: ['POST'])]
    public function login(Request $request, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(['email' => $request->get('email')]);
        $userPassword = $passwordHasher->isPasswordValid($user, $request->get('password'));

        if (!$user || !$userPassword) {
            return $this->respond(CustomError::UnauthorizedError('Email or password is wrong'));
        }

        $payload = [
            "email" => $user->getEmail(),
            "username" => $user->getUsername(),
            "exp"  => (new \DateTime())->modify("+3 minutes")->getTimestamp(),
        ];

        $jwt = JWT::encode($payload, $this->getParameter('jwt_secret'), 'HS256');
        return $this->respond([
            'message' => 'Success!',
            'token' => $jwt
        ]);
    }
}
