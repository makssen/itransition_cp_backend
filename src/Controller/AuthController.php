<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Exception;
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
        if ($getUser) {
            return $this->respond(['message' => 'User already exists'], Response::HTTP_UNAUTHORIZED);
        };

        $user = $form->getData();
        $password = $user->getPassword();
        $hashedPassword = $passwordHasher->hashPassword($user, $password);
        $user->setPassword($hashedPassword);

        $this->getDoctrine()->getManager()->persist($user);
        $this->getDoctrine()->getManager()->flush();

        $payload = [
            "id" => $user->getId(),
            "email" => $user->getEmail(),
            "username" => $user->getUsername(),
            "role" => $user->getRoles(),
            "exp"  => (new \DateTime())->modify("+7 days")->getTimestamp(),
        ];

        $jwt = JWT::encode($payload, $this->getParameter('jwt_secret'), 'HS256');
        return $this->respond(['token' => $jwt]);

        return $this->respond($user);
    }

    #[Route('/auth/login', methods: ['POST'])]
    public function login(Request $request, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(['email' => $request->get('email')]);

        if (!$user) {
            return $this->respond(['message' => 'auth/email-not-found'], Response::HTTP_UNAUTHORIZED);
        } else {
            $userPassword = $passwordHasher->isPasswordValid($user, $request->get('password'));
            if (!$userPassword) {
                return $this->respond(['message' => 'auth/password-wrong'], Response::HTTP_UNAUTHORIZED);
            }
        }

        $payload = [
            "id" => $user->getId(),
            "email" => $user->getEmail(),
            "username" => $user->getUsername(),
            "role" => $user->getRoles(),
            "exp"  => (new \DateTime())->modify("+7 days")->getTimestamp(),
        ];

        $jwt = JWT::encode($payload, $this->getParameter('jwt_secret'), 'HS256');
        return $this->respond(['token' => $jwt]);
    }

    #[Route('/auth/check', methods: ['GET'])]
    public function check(Request $request): Response
    {
        $jwt = $request->headers->get('authorization');

        if ($jwt) {
            try {
                $decoded = JWT::decode($jwt, $this->getParameter('jwt_secret'), array('HS256'));

                $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(['email' => $decoded->email]);

                if ($user) {
                    $payload = [
                        "id" => $user->getId(),
                        "email" => $user->getEmail(),
                        "username" => $user->getUsername(),
                        "role" => $user->getRoles(),
                        "exp"  => (new \DateTime())->modify("+7 days")->getTimestamp(),
                    ];

                    $jwt = JWT::encode($payload, $this->getParameter('jwt_secret'), 'HS256');
                    return $this->respond(['token' => $jwt]);
                }
            } catch (Exception $e) {
                return $this->respond(['message' => $e->getMessage()]);
            }
        }
    }
}
