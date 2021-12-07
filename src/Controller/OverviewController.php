<?php

namespace App\Controller;

use App\Entity\Overview;
use App\Entity\Tag;
use App\Form\OverviewType;
use App\Services\Cloudinary;
use Exception;
use Firebase\JWT\JWT;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class OverviewController extends AbstractApiController
{

    #[Route('/overviews', methods: ['GET'])]
    public function getAll(Request $request): Response
    {
        $overviews = $this->getDoctrine()->getRepository(Overview::class)->findAll();
        return $this->respond($overviews);
    }

    #[Route('/overviews/search', methods: ['GET'])]
    public function search(Request $request): Response
    {
        $overviews = $this->getDoctrine()->getRepository(Overview::class)->findBy(['title' => $request->get('q')]);
        return $this->respond($overviews);
    }

    #[Route('/overviews/{id}', methods: ['GET'])]
    public function getById(Request $request): Response
    {
        $overviewId = $request->get('id');
        $overview = $this->getDoctrine()->getRepository(Overview::class)->findOneBy(['id' => $overviewId]);
        if (!$overview) {
            throw new NotFoundHttpException('Overview not found');
        }
        return $this->respond($overview);
    }

    #[Route('/overviews', methods: ['POST'])]
    public function create(Request $request): Response
    {
        $jwt = $request->headers->get('authorization');

        if ($jwt) {
            try {
                $decoded = JWT::decode($jwt, $this->getParameter('jwt_secret'), array('HS256'));
                $form = $this->buildForm(OverviewType::class);

                $form->handleRequest($request);

                if (!$form->isSubmitted() || !$form->isValid()) {
                    return $this->respond($form, Response::HTTP_BAD_REQUEST);
                }

                $overview = $form->getData();
                $images = Cloudinary::getImage($form['images']->getData());
                $overview->setImages($images);
                $tags = $form['tags']->getData();

                foreach ($tags as $value) {
                    $tag = $this->getDoctrine()->getRepository(Tag::class)->findOneBy(['text' => $value]);
                    if (!$tag) {
                        $entityManager = $this->getDoctrine()->getManager();
                        $newTag = (new Tag())->setText($value);
                        $entityManager->persist($newTag);
                        $entityManager->flush();
                    }
                }

                $this->getDoctrine()->getManager()->persist($overview);
                $this->getDoctrine()->getManager()->flush();

                return $this->respond($overview);
            } catch (Exception $e) {
                return $this->respond(['message' => $e->getMessage()]);
            }
        } else {
            return $this->respond(['message' => 'Access closed']);
        }
    }

    #[Route('/overviews/{id}', methods: ['PUT'])]
    public function update(Request $request): Response
    {
        $overviewId = $request->get('id');
        $overview = $this->getDoctrine()->getRepository(Overview::class)->findOneBy(['id' => $overviewId]);
        if (!$overview) {
            throw new NotFoundHttpException('Overview not found');
        }

        $form = $this->buildForm(OverviewType::class, $overview, [
            'method' => $request->getMethod(),
        ]);

        $form->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid()) {
            return $this->respond($form, Response::HTTP_BAD_REQUEST);
        }

        $overview = $form->getData();

        $this->getDoctrine()->getManager()->persist($overview);
        $this->getDoctrine()->getManager()->flush();

        return $this->respond($overview);
    }

    #[Route('/overviews/{id}', methods: ['DELETE'])]
    public function delete(Request $request): Response
    {
        $overviewId = $request->get('id');
        $overview = $this->getDoctrine()->getRepository(Overview::class)->findOneBy(['id' => $overviewId]);
        if (!$overview) {
            throw new NotFoundHttpException('Overview not found');
        }

        $this->getDoctrine()->getManager()->remove($overview);
        $this->getDoctrine()->getManager()->flush();

        return $this->respond(null);
    }
}
