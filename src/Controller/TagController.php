<?php

namespace App\Controller;

use App\Entity\Tag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TagController extends AbstractApiController
{
    #[Route('/tags', methods: ['GET'])]
    public function getAll(Request $request): Response
    {
        $tags = $this->getDoctrine()->getRepository(Tag::class)->findAll();
        return $this->respond($tags);
    }
}
